/**
 * Утилиты для цветного вывода в консоль
 */
import chalk from "chalk";

// Цвета для вывода
export const COLOR = {
  INFO: chalk.cyan,
  SUCCESS: chalk.green,
  WARNING: chalk.yellow,
  ERROR: chalk.red,
  DIM: chalk.dim,
  BLUE: chalk.blue,
  MAGENTA: chalk.magenta,
  GRAY: chalk.gray,
  BOLD: chalk.bold,
  UNDERLINE: chalk.underline,

  // Цветные индикаторы состояния
  RUNNING: (text) => chalk.cyan(`⟳ ${text}`),
  DONE: (text) => chalk.green(`✓ ${text}`),
  FAILED: (text) => chalk.red(`✗ ${text}`),
  WAITING: (text) => chalk.yellow(`⏱ ${text}`),
  SKIPPED: (text) => chalk.gray(`⏭ ${text}`),
  PROCESS: (text) => chalk.blue(`⚙ ${text}`),
  INFO_SYMBOL: (text) => chalk.cyan(`ℹ ${text}`),
  WARNING_SYMBOL: (text) => chalk.yellow(`⚠ ${text}`),
  ERROR_SYMBOL: (text) => chalk.red(`✖ ${text}`),

  // Форматированный вывод для различных типов содержимого
  HEADER: (text) => chalk.bold.underline(text),
  COMMAND: (text) => chalk.cyan(`$ ${text}`),
  FILE: (text) => chalk.magenta(`📄 ${text}`),
  DIR: (text) => chalk.blue(`📁 ${text}`),
  URL: (text) => chalk.underline.cyan(text),
  CODE: (text) => chalk.gray(`\`${text}\``),
  VALUE: (text) => chalk.green(`"${text}"`),
};

export default COLOR;
