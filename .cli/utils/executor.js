import { spawn } from "child_process";
import ora from "ora";
import { COLORS, SYMBOLS } from "../config.js";

/**
 * Выполняет команду с отображением спиннера и форматированным выводом
 * @param {string} command - Команда для выполнения
 * @param {string} description - Описание команды для спиннера
 * @param {Object} options - Дополнительные опции
 * @param {boolean} options.shell - Выполнять в оболочке shell (по умолчанию true)
 * @param {boolean} options.interactive - Интерактивная команда (по умолчанию false)
 * @returns {Promise<boolean>} - Успешность выполнения команды
 */
export function executeCommand(command, description, options = {}) {
  const { shell = true, interactive = false } = options;

  // Создаем спиннер для отображения прогресса
  const spinner = ora({
    text: `${description}...`,
    color: "cyan",
  }).start();

  return new Promise((resolve) => {
    // Разделяем команду на части
    const [cmd, ...args] = interactive
      ? [command]
      : command.split(" ").filter(Boolean);

    // Создаем процесс с наследованием stdio для интерактивного вывода
    const proc = spawn(cmd, args, {
      shell,
      stdio: "pipe", // Всегда используем pipe для контроля вывода
      env: process.env,
    });

    let stdout = "";
    let stderr = "";
    // Максимальное количество строк для отображения в интерактивном режиме
    const maxLines = 10;

    // Это Docker команда?
    const isDockerCommand = command.includes("docker");

    // Для обоих потоков (stdout и stderr) обновляем текст спиннера
    proc.stdout.on("data", (data) => {
      const output = data.toString();
      stdout += output;

      // Обновляем интерактивный вывод, показывая последние N строк
      const lines = stdout.split("\n");
      const lastLines = lines.slice(-maxLines).join("\n");

      // Останавливаем спиннер и создаем новый для имитации обновления экрана
      spinner.stop();
      spinner.start(`${description}...\n${lastLines}`);
    });

    proc.stderr.on("data", (data) => {
      const output = data.toString();
      stderr += output;

      // Обновляем интерактивный вывод, показывая последние N строк
      const lines = stderr.split("\n");
      const lastLines = lines.slice(-maxLines).join("\n");

      // Останавливаем спиннер и создаем новый для имитации обновления экрана
      spinner.stop();
      spinner.start(`${description}...\n${lastLines}`);

      // Для Docker команд добавляем stderr в stdout для полноты лога
      if (isDockerCommand) {
        stdout += output;
      }
    });

    // Обработка завершения процесса
    proc.on("close", (code) => {
      if (code === 0) {
        spinner.succeed(
          `${description} ${COLORS.SUCCESS}${SYMBOLS.SUCCESS} Успешно${COLORS.RESET}`
        );
        resolve(true);
      } else {
        // Выводим полный лог ошибки и stdout
        let errorOutput = "";

        // Логика обработки для Docker-команд и других
        if (isDockerCommand) {
          // Для Docker команд ищем ошибки как в stderr, так и в stdout
          // Соединяем оба потока, но пытаемся извлечь осмысленные части ошибок
          let combinedOutput = "";

          // Сначала проверяем stderr
          if (stderr) {
            combinedOutput += stderr;
          }

          // Затем добавляем stdout, если он содержит информацию не из stderr
          if (stdout) {
            combinedOutput += (combinedOutput ? "\n" : "") + stdout;
          }

          // Извлекаем осмысленные строки
          const cleanLines = combinedOutput
            .split("\n")
            .filter(
              (line) =>
                line.trim() &&
                !line.includes("⠋") &&
                !line.includes("⠙") &&
                !line.includes("⠹") &&
                !line.includes("⠸") &&
                !line.includes("⠼") &&
                !line.includes("⠴") &&
                !line.includes("⠦") &&
                !line.includes("⠧") &&
                !line.includes("⠇") &&
                !line.includes("⠏") &&
                !line.includes("#") &&
                !line.includes("DONE") &&
                !line.includes("CACHED")
            );

          if (cleanLines.length > 0) {
            errorOutput = cleanLines.join("\n");
          }
        } else {
          // Стандартная обработка для не-Docker команд
          // Проверяем stderr
          if (stderr) {
            const stderrLines = stderr
              .split("\n")
              .filter((line) => line.trim());
            if (stderrLines.length > 0) {
              errorOutput += stderrLines.join("\n");
            }
          }

          // Проверяем stdout, если stderr пуст
          if (stdout && (!errorOutput || errorOutput.length === 0)) {
            const stdoutLines = stdout
              .split("\n")
              .filter((line) => line.trim());
            if (stdoutLines.length > 0) {
              errorOutput += (errorOutput ? "\n" : "") + stdoutLines.join("\n");
            }
          }
        }

        spinner.fail(
          `${COLORS.ERROR}Ошибка команды: ${command}${COLORS.RESET}`
        );

        if (errorOutput) {
          console.error(`${COLORS.ERROR}${errorOutput}${COLORS.RESET}`);
        } else {
          // Если нет детальной информации, пробуем показать хоть что-то
          console.error(
            `${COLORS.ERROR}Выполнение команды завершилось с ошибкой. Код ошибки: ${code}${COLORS.RESET}`
          );
        }

        resolve(false);
      }
    });

    // Обработка ошибок запуска процесса
    proc.on("error", (err) => {
      spinner.fail(
        `${COLORS.ERROR}Ошибка запуска команды: ${command}${COLORS.RESET}`
      );
      console.error(`${COLORS.ERROR}${err.message}${COLORS.RESET}`);
      resolve(false);
    });
  });
}

/**
 * Выполняет последовательность команд
 * @param {Array<{command: string, description: string, options?: Object}>} commands - Массив команд
 * @returns {Promise<boolean>} - Успешность выполнения всех команд
 */
export async function executeSequence(commands) {
  for (const cmd of commands) {
    const success = await executeCommand(
      cmd.command,
      cmd.description,
      cmd.options
    );

    if (!success) {
      return false;
    }
  }

  return true;
}
