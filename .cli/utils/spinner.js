/**
 * Утилиты для работы со спиннерами (индикаторами выполнения)
 */
import ora from "ora";

/**
 * Создает и настраивает спиннер для индикации выполнения
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
    // Настройка стилей успеха/ошибки
    successText: (text) => `✓ ${text.replace(/\.*\s*$/, "")}`,
    failText: (text) => `✗ ${text.replace(/\.*\s*$/, "")}`,
  });

  // Переопределяем методы для более чистого вывода
  const originalSucceed = spinner.succeed.bind(spinner);
  spinner.succeed = (text) => {
    text = text || spinner.text;
    return originalSucceed(text.replace(/\.*\s*$/, ""));
  };

  const originalFail = spinner.fail.bind(spinner);
  spinner.fail = (text) => {
    text = text || spinner.text;
    return originalFail(text.replace(/\.*\s*$/, ""));
  };

  return spinner;
};

export default createSpinner;
