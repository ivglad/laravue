/**
 * Утилита для запуска команд с интерактивным выводом
 * Использует log-update, ora и listr для создания интерактивного вывода
 */
import { spawn } from "child_process";
import logUpdate from "log-update";
import ora from "ora";
import { Observable } from "rxjs";
import Listr from "listr";
import COLOR from "./colors.js";
import { PROJECT_DIR } from "./executor.js";

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

export default {
  runInteractive,
  createInteractiveRunner,
  createCommandObservable,
};
