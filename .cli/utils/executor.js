/**
 * Функции для выполнения команд в терминале
 */
import { execSync, spawn } from "child_process";
import path from "path";
import logUpdate from "log-update";
import COLOR from "./colors.js";
import createSpinner from "./spinner.js";

// Путь к проекту (по умолчанию текущий каталог)
export const PROJECT_DIR = process.cwd();

/**
 * Чтение переменных окружения из .env файла
 * @param {string} envPath - Путь к .env файлу
 * @returns {object} - Объект с переменными окружения
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

// --- Новая функция спиннера на основе log-update ---
const spinnerFrames = ["⠋", "⠙", "⠹", "⠸", "⠼", "⠴", "⠦", "⠧", "⠇", "⠏"];

/**
 * Создает спиннер с использованием log-update для интерактивных команд
 * @param {string} initialText - Начальный текст
 * @param {string[]} statusMessages - Массив сообщений для циклического отображения (опционально)
 * @param {number} animationIntervalMs - Интервал анимации кадров спиннера (мс)
 * @param {number} statusUpdateIntervalMs - Интервал смены статусных сообщений (мс)
 * @returns {object} - Объект управления спиннером
 */
const createLogUpdateSpinner = (
  initialText,
  statusMessages = null,
  animationIntervalMs = 80,
  statusUpdateIntervalMs = 2000
) => {
  let frameIndex = 0;
  let statusIndex = 0;
  let currentText = initialText;
  let animationInterval = null;
  let statusInterval = null;
  let isActive = false;

  const getSpinnerText = () => {
    const frame = spinnerFrames[frameIndex % spinnerFrames.length];
    return `${COLOR.INFO(frame)} ${currentText}`;
  };

  const render = () => {
    if (isActive) {
      logUpdate(getSpinnerText());
    }
  };

  const start = () => {
    if (isActive) return controller;
    isActive = true;

    // Обновление текста статуса (если есть)
    if (
      statusMessages &&
      Array.isArray(statusMessages) &&
      statusMessages.length > 0
    ) {
      currentText = `${statusMessages[statusIndex % statusMessages.length]}...`;
      statusInterval = setInterval(() => {
        statusIndex++;
        currentText = `${
          statusMessages[statusIndex % statusMessages.length]
        }...`;
        // Рендер произойдет в animationInterval
      }, statusUpdateIntervalMs);
    } else {
      currentText = initialText; // Убедимся, что текст начальный
    }

    // Анимация кадров
    animationInterval = setInterval(() => {
      render(); // Рендерим текущий текст и кадр
      frameIndex++;
    }, animationIntervalMs);

    render(); // Первый рендер
    return controller;
  };

  const stop = () => {
    if (!isActive) return;
    clearInterval(animationInterval);
    clearInterval(statusInterval);
    animationInterval = null;
    statusInterval = null;
    isActive = false;
  };

  const clear = () => {
    if (isActive) {
      // Очищаем только если были активны
      stop(); // Останавливаем интервалы перед очисткой
    }
    logUpdate.clear(); // Очищаем строку
  };

  const update = () => {
    // Просто перерисовываем текущее состояние спиннера
    // Полезно после clear() и вывода данных команды
    if (isActive) {
      render();
    }
  };

  const succeed = (text) => {
    const finalText =
      text || (statusMessages && statusMessages[0]) || initialText;
    clear(); // Остановит интервалы и очистит строку
    console.log(COLOR.SUCCESS(`✓ ${finalText}`));
  };

  const fail = (text) => {
    const finalText =
      text || (statusMessages && statusMessages[0]) || initialText;
    clear(); // Остановит интервалы и очистит строку
    console.log(COLOR.ERROR(`✗ ${finalText}`));
  };

  // Метод для обновления текста спиннера извне
  // (если не используются statusMessages)
  const setText = (text) => {
    currentText = text;
    if (isActive && (!statusMessages || statusMessages.length === 0)) {
      render(); // Обновляем сразу, если статус не циклический
    }
  };

  const controller = {
    start,
    stop,
    clear,
    update,
    succeed,
    fail,
    setText, // Добавляем setText
    // Добавим геттер для текста, может пригодиться
    get text() {
      return currentText;
    },
  };
  return controller;
};
// --- Конец новой функции спиннера ---

/**
 * Выполнение команды оболочки (неинтерактивное)
 * Использует старый спиннер ora, если нужен (например, для silent)
 * @param {string} command - Команда для выполнения
 * @param {object} options - Опции для выполнения
 * @returns {string} Вывод команды
 */
export const execCommand = (command, options = {}) => {
  // Используем новый log-update спиннер и для execCommand
  let spinnerControl = null;
  const statusMessages = options.statusMessages || [`Выполнение: ${command}`];

  // Запускаем спиннер только если не установлен режим silent
  if (!options.silent) {
    spinnerControl = createLogUpdateSpinner(statusMessages[0], statusMessages);
    spinnerControl.start();
  }

  try {
    const result = execSync(command, {
      cwd: PROJECT_DIR,
      // Для execSync 'pipe' подходит лучше всего для захвата вывода
      stdio: "pipe",
      encoding: "utf8",
      ...options,
    });

    // Выводим результат, если не silent
    if (!options.silent && result) {
      // Если спиннер был активен, очищаем его перед выводом
      spinnerControl?.clear();
      process.stdout.write(result);
    }

    // Показываем успех, если спиннер был активен
    spinnerControl?.succeed();
    return result || ""; // Возвращаем результат или пустую строку
  } catch (error) {
    // Показываем ошибку, если спиннер был активен
    spinnerControl?.fail();

    // Выводим stderr ошибки, если не silent
    if (!options.silent) {
      // Очистка не нужна, fail уже очистил
      const errorMessage = error.stderr?.toString() || error.message;
      // Используем console.error, так как logUpdate уже очищен
      console.error(COLOR.ERROR(`Ошибка выполнения: ${errorMessage}`));
    }

    if (options.ignoreError) {
      return "";
    }
    process.exit(1); // Выход остается
  }
};

/**
 * Интерактивный запуск команды оболочки (использует log-update)
 * @param {string} command - Команда для выполнения
 * @param {string[]} args - Аргументы команды
 * @param {object} options - Дополнительные опции
 * @param {boolean} options.interactiveOutput - Если true, разрешает прямой доступ
 * к stdout/stderr для команд с интерактивным выводом (docker compose и т.д.)
 */
export const spawnInteractive = (command, args = [], options = {}) => {
  return new Promise((resolve, reject) => {
    const commandStr = `${command} ${args.join(" ")}`;
    const statusMessages = options.statusMessages || [
      `Выполнение: ${commandStr}`,
    ];

    // Выбираем режим работы в зависимости от типа команды
    const isInteractiveOutput = options.interactiveOutput === true;
    let spinnerControl = null;

    // Если команда не требует интерактивного вывода, используем log-update спиннер
    if (!isInteractiveOutput) {
      spinnerControl = createLogUpdateSpinner(
        statusMessages[0],
        statusMessages
      );
      spinnerControl.start();
    } else {
      // Для интерактивных команд просто выводим стартовое сообщение
      console.log(COLOR.INFO(`${statusMessages[0]}...`));
    }

    const child = spawn(command, args, {
      cwd: PROJECT_DIR,
      // Для интерактивных команд (docker compose и т.д.) используем inherit,
      // чтобы они могли напрямую управлять выводом и обновлять статус на одной строке
      stdio: isInteractiveOutput ? "inherit" : ["inherit", "pipe", "pipe"],
      shell: true,
      ...options, // Передаем остальные опции, например, env
    });

    // Если команда не интерактивная, перехватываем вывод и показываем спиннер
    if (!isInteractiveOutput && child.stdout && child.stderr) {
      // Обработка вывода stdout
      child.stdout.on("data", (data) => {
        spinnerControl.clear(); // Временно очищаем строку спиннера
        process.stdout.write(data); // Выводим данные команды
        spinnerControl.update(); // Перерисовываем спиннер над новыми данными
      });

      // Обработка вывода stderr
      child.stderr.on("data", (data) => {
        spinnerControl.clear(); // Временно очищаем строку спиннера
        process.stderr.write(data); // Выводим данные ошибки
        spinnerControl.update(); // Перерисовываем спиннер над новыми данными
      });
    }

    child.on("close", (code) => {
      if (code === 0) {
        // Только для не-интерактивных команд показываем спиннер успеха
        if (!isInteractiveOutput) {
          spinnerControl.succeed();
        } else {
          // Для интерактивных команд просто выводим сообщение об успехе
          console.log(COLOR.SUCCESS(`✓ ${statusMessages[0]}`));
        }
        resolve();
      } else {
        // Только для не-интерактивных команд показываем спиннер ошибки
        if (!isInteractiveOutput) {
          spinnerControl.fail();
        } else {
          // Для интерактивных команд просто выводим сообщение об ошибке
          console.log(COLOR.ERROR(`✗ ${statusMessages[0]}`));
        }
        // Не нужно выводить доп. ошибку, stderr уже должен был вывести все
        reject(new Error(`Процесс завершился с кодом ${code}`));
      }
    });

    child.on("error", (err) => {
      // Только для не-интерактивных команд показываем спиннер ошибки
      if (!isInteractiveOutput) {
        spinnerControl.fail();
      } else {
        // Для интерактивных команд просто выводим сообщение об ошибке
        console.log(COLOR.ERROR(`✗ ${statusMessages[0]}`));
      }
      // Добавляем вывод ошибки для большей информативности
      console.error(COLOR.ERROR(` Ошибка запуска процесса: ${err.message}`));
      reject(err);
    });
  });
};

/**
 * Копирование файла, если он не существует
 * @param {string} source - Исходный файл
 * @param {string} destination - Файл назначения
 * @returns {boolean} - true если файл был скопирован, false если файл уже существовал
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
