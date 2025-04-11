/**
 * Общие утилиты для CLI
 */
import path from "path";
import { spawn, execSync } from "child_process";
import ora from "ora";
import logUpdate from "log-update";
import Listr from "listr";
import { Observable } from "rxjs";
import COLOR from "./colors.js";

// Путь к проекту (по умолчанию текущий каталог)
export const PROJECT_DIR = process.cwd();

/**
 * Копирование файла, если он не существует
 * @param {string} source - Исходный файл
 * @param {string} destination - Файл назначения
 * @returns {Promise<boolean>} - true если файл был скопирован, false если файл уже существовал
 */
export const copyIfNotExists = async (source, destination) => {
  try {
    const fs = await import("fs/promises");
    const { existsSync } = await import("fs");

    if (!existsSync(destination)) {
      await fs.copyFile(source, destination);
      return true; // Файл был скопирован
    } else {
      return false; // Файл уже существовал
    }
  } catch (error) {
    throw new Error(`Ошибка при копировании файла: ${error.message}`);
  }
};

/**
 * Чтение переменных окружения из .env файла
 * @param {string} envPath - Путь к .env файлу
 * @returns {Promise<object>} - Объект с переменными окружения
 */
export const readEnvFile = async (envPath) => {
  const { existsSync, readFileSync } = await import("fs");
  const envVars = {};

  try {
    const filePath = path.resolve(PROJECT_DIR, envPath);
    if (existsSync(filePath)) {
      const envContent = readFileSync(filePath, "utf8");
      const lines = envContent.split("\n");

      for (const line of lines) {
        // Пропускаем комментарии и пустые строки
        if (line.trim() === "" || line.trim().startsWith("#")) continue;

        // Разбиваем строку на ключ и значение
        const match = line.match(/^\s*([\w.-]+)\s*=\s*(.*)?\s*$/);
        if (match) {
          const key = match[1];
          // Убираем кавычки в начале и конце, если они есть
          let value = match[2] || "";
          value = value.trim();
          if (value.startsWith('"') && value.endsWith('"')) {
            value = value.slice(1, -1);
          } else if (value.startsWith("'") && value.endsWith("'")) {
            value = value.slice(1, -1);
          }

          envVars[key] = value;
        }
      }
    } else {
      console.warn(COLOR.WARNING(`Файл ${envPath} не найден`));
    }
  } catch (error) {
    console.warn(
      COLOR.WARNING(`Ошибка при чтении файла ${envPath}: ${error.message}`)
    );
  }

  return envVars;
};

/**
 * Создает спиннер Ora для отображения загрузки
 * @param {string} text - Начальный текст спиннера
 * @returns {Object} Настроенный объект спиннера
 */
export const createSpinner = (text = "Выполнение команды...") => {
  // Конфигурируем спиннер
  const spinner = ora({
    text,
    color: "cyan",
    spinner: "dots", // Более интерактивный спиннер
    interval: 80, // Обновление каждые 80мс
    discardStdin: false,
  });

  return spinner;
};

/**
 * Создает Observable для выполнения команды с интерактивным выводом
 * @param {string} command - Команда для выполнения
 * @param {string[]} args - Аргументы команды
 * @param {object} options - Дополнительные опции
 * @returns {Observable} Observable для выполнения команды
 */
export const createCommandObservable = (command, args = [], options = {}) => {
  return new Observable((observer) => {
    // Информируем о начале выполнения
    observer.next(`Запуск: ${command} ${args.join(" ")}...`);

    // Создаем процесс
    const child = spawn(command, args, {
      cwd: options.cwd || PROJECT_DIR,
      shell: true,
      stdio: ["inherit", "pipe", "pipe"],
      ...options, // Другие опции, например env
    });

    // Буфер для хранения последних строк вывода
    let outputBuffer = "";
    const MAX_BUFFER_LINES = 5; // Максимальное количество последних строк для отображения

    // Добавление строки в буфер
    const addToBuffer = (line) => {
      // Разбиваем буфер на строки, добавляем новую и ограничиваем размер
      const lines = outputBuffer.split("\n");
      lines.push(line);

      // Если строк больше MAX_BUFFER_LINES, удаляем самые старые
      while (lines.length > MAX_BUFFER_LINES) {
        lines.shift();
      }

      outputBuffer = lines.join("\n");

      // Обновляем наблюдателя с новым буфером вывода
      observer.next(outputBuffer);
    };

    // Обработка stdout
    child.stdout.on("data", (data) => {
      const output = data.toString().trim();
      if (output) {
        const lines = output.split("\n");
        for (const line of lines) {
          addToBuffer(line);
        }
      }
    });

    // Обработка stderr
    child.stderr.on("data", (data) => {
      const output = data.toString().trim();
      if (output) {
        const lines = output.split("\n");
        for (const line of lines) {
          addToBuffer(COLOR.ERROR(line));
        }
      }
    });

    // Обработка закрытия
    child.on("close", (code) => {
      if (code === 0) {
        observer.next(`Команда выполнена успешно.`);
        observer.complete();
      } else {
        observer.error(new Error(`Процесс завершился с кодом ${code}`));
      }
    });

    // Обработка ошибок
    child.on("error", (err) => {
      observer.error(err);
    });

    // Функция очистки при отписке
    return () => {
      if (!child.killed) {
        child.kill();
      }
    };
  });
};

/**
 * Выполнить команду интерактивно с отображением статуса и вывода в реальном времени
 * @param {string} command - Команда для выполнения
 * @param {string[]} args - Аргументы команды
 * @param {object} options - Опции для выполнения
 * @param {string} options.title - Заголовок задачи (по умолчанию - команда)
 * @param {string[]} options.statusMessages - Циклические сообщения о статусе (опционально)
 * @returns {Promise} Промис, который разрешается при успешном выполнении
 */
export const runInteractive = (command, args = [], options = {}) => {
  const cmdString = `${command} ${args.join(" ")}`;
  const title = options.title || `Выполнение: ${cmdString}`;

  // Настройка для простого отображения, если терминал не поддерживает интерактивный режим
  const nonTTYRenderer = {
    render: () => {
      console.log(`${title}...`);
    },
    end: (err) => {
      if (err) {
        console.error(COLOR.ERROR(`✗ ${title} - ошибка: ${err.message}`));
      } else {
        console.log(COLOR.SUCCESS(`✓ ${title} - выполнено`));
      }
    },
  };

  // Если терминал не поддерживает TTY, используем упрощенный вывод
  if (!process.stdout.isTTY) {
    return new Promise((resolve, reject) => {
      const child = spawn(command, args, {
        cwd: options.cwd || PROJECT_DIR,
        stdio: "inherit",
        shell: true,
        ...options, // Другие опции, например env
      });

      child.on("close", (code) => {
        if (code === 0) {
          resolve();
        } else {
          reject(new Error(`Процесс завершился с кодом ${code}`));
        }
      });

      child.on("error", (err) => {
        reject(err);
      });
    });
  }

  // Создаем задачу Listr
  const tasks = new Listr(
    [
      {
        title,
        task: () => createCommandObservable(command, args, options),
      },
    ],
    {
      renderer: process.stdout.isTTY ? "default" : "silent",
      nonTTYRenderer: nonTTYRenderer,
    }
  );

  // Запускаем задачу
  return tasks.run();
};

/**
 * Создает интерактивный спиннер для отображения статуса команды с выводом
 * @param {string} initialText - Начальный текст
 * @returns {object} - Объект для управления спиннером и выводом
 */
export const createInteractiveRunner = (initialText) => {
  // Настройка спиннера
  const spinner = ora({
    text: initialText,
    color: "cyan",
    spinner: "dots",
    discardStdin: false,
  });

  // Хранение выходных строк
  let outputLines = [];
  const MAX_OUTPUT_LINES = 10;

  // Функция для обновления вывода
  const updateOutput = () => {
    // Сначала выводим спиннер
    const spinnerText = spinner.isSpinning ? spinner.text : spinner.text;

    // Затем последние строки вывода
    const output = outputLines.slice(-MAX_OUTPUT_LINES).join("\n");

    // Обновляем вывод
    logUpdate(`${spinnerText}\n\n${output}`);
  };

  // Добавляет строку вывода и обновляет дисплей
  const addOutputLine = (line) => {
    outputLines.push(line);
    updateOutput();
  };

  // Очищает вывод и обновляет дисплей
  const clearOutput = () => {
    outputLines = [];
    updateOutput();
  };

  // Запускает спиннер и обновляет вывод
  const start = () => {
    spinner.start();
    updateOutput();
    return runner;
  };

  // Останавливает спиннер и показывает успешное завершение
  const succeed = (text) => {
    spinner.succeed(text || spinner.text);
    const finalText = text || spinner.text;
    logUpdate(
      `${COLOR.SUCCESS(`✓ ${finalText}`)}\n\n${outputLines
        .slice(-MAX_OUTPUT_LINES)
        .join("\n")}`
    );
    logUpdate.done();
    return runner;
  };

  // Останавливает спиннер и показывает ошибку
  const fail = (text) => {
    spinner.fail(text || spinner.text);
    const finalText = text || spinner.text;
    logUpdate(
      `${COLOR.ERROR(`✗ ${finalText}`)}\n\n${outputLines
        .slice(-MAX_OUTPUT_LINES)
        .join("\n")}`
    );
    logUpdate.done();
    return runner;
  };

  // Обновляет текст спиннера
  const setText = (text) => {
    spinner.text = text;
    updateOutput();
    return runner;
  };

  // Объект для управления интерактивным выводом
  const runner = {
    start,
    succeed,
    fail,
    setText,
    addOutputLine,
    clearOutput,
    updateOutput,
    get text() {
      return spinner.text;
    },
  };

  return runner;
};

/**
 * Выполнение команды оболочки (неинтерактивное)
 * @param {string} command - Команда для выполнения
 * @param {object} options - Опции для выполнения
 * @returns {string} Вывод команды
 */
export const execCommand = (command, options = {}) => {
  const spinner = options.silent
    ? null
    : createSpinner(options.text || `Выполнение: ${command}`);

  if (spinner) spinner.start();

  try {
    const result = execSync(command, {
      cwd: PROJECT_DIR,
      stdio: "pipe",
      encoding: "utf8",
      ...options,
    });

    if (spinner) spinner.succeed();

    if (!options.silent && result) {
      console.log(result);
    }

    return result || "";
  } catch (error) {
    if (spinner) spinner.fail();

    if (!options.silent) {
      const errorMessage = error.stderr?.toString() || error.message;
      console.error(COLOR.ERROR(`Ошибка выполнения: ${errorMessage}`));
    }

    if (options.ignoreError) {
      return "";
    }

    throw error;
  }
};

export default {
  PROJECT_DIR,
  copyIfNotExists,
  readEnvFile,
  createSpinner,
  createInteractiveRunner,
  createCommandObservable,
  runInteractive,
  execCommand,
};
