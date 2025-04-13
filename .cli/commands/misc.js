import inquirer from "inquirer";
import fs from "fs";
import path from "path";
import { execSync } from "child_process";
import { COLORS, SYMBOLS } from "../config.js";
import { executeCommand } from "../utils/executor.js";
import {
  checkRequirements,
  displayRequirementsErrors,
} from "../utils/validator.js";

/**
 * Регистрирует прочие вспомогательные команды
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerMiscCommands(program) {
  // Команда clean
  program
    .command("clean")
    .description("Очистка временных файлов и артефактов")
    .action(async () => {
      console.log(`${COLORS.INFO}Очистка временных файлов...${COLORS.RESET}`);

      // Поиск и удаление временных файлов
      try {
        // Поиск и удаление .tmp файлов
        execSync('find . -name "*.tmp" -type f -delete');
        console.log(
          `${COLORS.SUCCESS}${SYMBOLS.SUCCESS} Удалены временные файлы .tmp${COLORS.RESET}`
        );

        // Поиск и удаление .log файлов
        execSync('find . -name "*.log" -type f -delete');
        console.log(
          `${COLORS.SUCCESS}${SYMBOLS.SUCCESS} Удалены лог файлы .log${COLORS.RESET}`
        );

        console.log(
          `${COLORS.SUCCESS}${SYMBOLS.SUCCESS} Очистка временных файлов завершена${COLORS.RESET}`
        );
      } catch (error) {
        console.error(
          `${COLORS.ERROR}${SYMBOLS.ERROR} Ошибка при удалении временных файлов: ${error.message}${COLORS.RESET}`
        );
        process.exit(1);
      }
    });
}
