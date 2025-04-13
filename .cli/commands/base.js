import inquirer from "inquirer";
import fs from "fs";
import { COLORS, SYMBOLS, AVAILABLE_COMMANDS } from "../config.js";
import { executeCommand, executeSequence } from "../utils/executor.js";
import {
  checkRequirements,
  displayRequirementsErrors,
} from "../utils/validator.js";
import ora from "ora";

/**
 * Регистрирует базовые команды
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerBaseCommands(program) {
  // Команда help
  program
    .command("help")
    .description("Информация о доступных командах")
    .action(() => {
      console.log(`${COLORS.INFO}Доступные команды:${COLORS.RESET}`);

      // Базовые команды
      console.log(`\n${COLORS.INFO}Базовые команды:${COLORS.RESET}`);
      console.log(`  help                 - Информация о доступных командах`);
      console.log(
        `  check-requirements   - Проверка наличия необходимых программ`
      );
      console.log(`  env                  - Установка переменных окружения`);
      console.log(`  install              - Полная установка проекта (Docker)`);

      // Docker команды
      console.log(`\n${COLORS.INFO}Docker команды:${COLORS.RESET}`);
      console.log(
        `  docker build [сервис] - Сборка проекта или указанного сервиса`
      );
      console.log(`  docker init          - Инициализация проекта`);
      console.log(`  docker up            - Создание и запуск контейнеров`);
      console.log(`  docker down          - Остановка и удаление контейнеров`);
      console.log(`  docker start         - Запуск контейнеров`);
      console.log(`  docker stop          - Остановка контейнеров`);
      console.log(`  docker restart       - Перезапуск контейнеров`);
      console.log(`  docker status        - Проверка статуса контейнеров`);
      console.log(
        `  docker prune         - Очистка неиспользуемых Docker ресурсов`
      );

      // Frontend команды
      console.log(
        `\n${COLORS.INFO}Frontend команды (frontend [команда]):${COLORS.RESET}`
      );
      AVAILABLE_COMMANDS.FRONTEND.forEach((cmd) => {
        console.log(`  ${cmd.name.padEnd(20)} - ${cmd.description}`);
      });

      // Backend команды
      console.log(
        `\n${COLORS.INFO}Backend команды (backend [команда]):${COLORS.RESET}`
      );
      AVAILABLE_COMMANDS.BACKEND.forEach((cmd) => {
        console.log(`  ${cmd.name.padEnd(20)} - ${cmd.description}`);
      });

      // DB команды
      console.log(`\n${COLORS.INFO}DB команды (db [команда]):${COLORS.RESET}`);
      AVAILABLE_COMMANDS.DB.forEach((cmd) => {
        console.log(`  ${cmd.name.padEnd(20)} - ${cmd.description}`);
      });

      // Команды очистки
      console.log(`\n${COLORS.INFO}Команды очистки:${COLORS.RESET}`);
      console.log(
        `  clean                - Очистка временных файлов и артефактов`
      );
    });

  // Команда check-requirements
  program
    .command("check-requirements")
    .description("Проверка наличия необходимых программ")
    .action(() => {
      const requirements = checkRequirements();

      if (!displayRequirementsErrors(requirements)) {
        process.exit(1);
      }
    });

  // Команда env
  program
    .command("env")
    .description("Установка переменных окружения")
    .action(async () => {
      // Создаем спиннер для интерактивного вывода
      const envSpinner = ora({
        text: "Установка переменных окружения...",
        color: "cyan",
      }).start();

      // Копирование .env файлов, если они не существуют
      const envFiles = [
        { src: ".env.example", dest: ".env" },
        { src: "backend/.env.example", dest: "backend/.env" },
        { src: "frontend/.env.example", dest: "frontend/.env" },
      ];

      let envMessages = [];

      for (const file of envFiles) {
        if (!fs.existsSync(file.dest) && fs.existsSync(file.src)) {
          fs.copyFileSync(file.src, file.dest);
          envMessages.push(
            `${COLORS.SUCCESS}Создан файл ${file.dest}${COLORS.RESET}`
          );
        } else if (fs.existsSync(file.dest)) {
          envMessages.push(
            `${COLORS.INFO}Файл ${file.dest} уже существует${COLORS.RESET}`
          );
        } else {
          envMessages.push(
            `${COLORS.WARNING}Не найден файл ${file.src}${COLORS.RESET}`
          );
        }

        // Обновляем текст спиннера
        envSpinner.text = `Установка переменных окружения...\n${envMessages.join(
          "\n"
        )}`;
      }

      envSpinner.succeed("Переменные окружения установлены");
    });

  // Команда install
  program
    .command("install")
    .description("Полная установка проекта (Docker)")
    .action(async () => {
      const requirements = checkRequirements();

      if (!displayRequirementsErrors(requirements)) {
        process.exit(1);
      }

      // Сначала выполняем установку переменных окружения
      const envSpinner = ora({
        text: "Установка переменных окружения...",
        color: "cyan",
      }).start();

      // Копирование .env файлов, если они не существуют
      const envFiles = [
        { src: ".env.example", dest: ".env" },
        { src: "backend/.env.example", dest: "backend/.env" },
        { src: "frontend/.env.example", dest: "frontend/.env" },
      ];

      let envMessages = [];

      for (const file of envFiles) {
        if (!fs.existsSync(file.dest) && fs.existsSync(file.src)) {
          fs.copyFileSync(file.src, file.dest);
          envMessages.push(
            `${COLORS.SUCCESS}Создан файл ${file.dest}${COLORS.RESET}`
          );
        } else if (fs.existsSync(file.dest)) {
          envMessages.push(
            `${COLORS.INFO}Файл ${file.dest} уже существует${COLORS.RESET}`
          );
        } else {
          envMessages.push(
            `${COLORS.WARNING}Не найден файл ${file.src}${COLORS.RESET}`
          );
        }

        // Обновляем текст спиннера
        envSpinner.text = `Установка переменных окружения...\n${envMessages.join(
          "\n"
        )}`;
      }

      envSpinner.succeed("Переменные окружения установлены");

      // Последовательное выполнение остальных команд установки
      const commands = [
        {
          command: "docker compose build",
          description: "Сборка Docker образов",
        },
        {
          command: "docker compose up -d",
          description: "Запуск контейнеров",
        },
        {
          command: "docker compose exec backend php artisan migrate",
          description: "Миграция базы данных",
        },
        {
          command: "docker compose exec backend php artisan db:seed",
          description: "Заполнение базы тестовыми данными",
        },
      ];

      await executeSequence(commands);
    });
}
