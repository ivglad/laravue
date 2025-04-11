/**
 * Основные команды CLI (install, env)
 */
import path from "path";
import COLOR from "../utils/colors.js";
import {
  copyIfNotExists,
  PROJECT_DIR,
  createInteractiveRunner,
} from "../utils/utilities.js";

/**
 * Добавление команд для основных функций
 * @param {Command} program - Экземпляр программы Commander
 */
export const registerCoreCommands = (program) => {
  program
    .command("install")
    .description("Полная установка проекта (Docker)")
    .action(async () => {
      const runner = createInteractiveRunner(
        "Начало полной установки проекта..."
      );
      runner.start();

      try {
        const envCommand = program.commands.find((cmd) => cmd.name() === "env");
        const dockerBuildCommand = program.commands
          .find((cmd) => cmd.name() === "docker")
          ?.commands.find((cmd) => cmd.name() === "build");
        const dockerUpCommand = program.commands
          .find((cmd) => cmd.name() === "docker")
          ?.commands.find((cmd) => cmd.name() === "up");
        const dbMigrateCommand = program.commands
          .find((cmd) => cmd.name() === "db")
          ?.commands.find((cmd) => cmd.name() === "migrate");
        const dbSeedCommand = program.commands
          .find((cmd) => cmd.name() === "db")
          ?.commands.find((cmd) => cmd.name() === "seed");

        runner.setText("Шаг 1: Настройка переменных окружения...");
        runner.addOutputLine(COLOR.INFO("Настройка переменных окружения..."));
        if (envCommand) {
          await envCommand.action();
        } else {
          runner.addOutputLine(COLOR.WARNING("Команда 'env' не найдена."));
        }

        runner.setText("Шаг 2: Сборка Docker-контейнеров...");
        runner.addOutputLine(COLOR.INFO("\nСборка Docker-контейнеров..."));
        if (dockerBuildCommand) {
          await dockerBuildCommand.action();
        } else {
          runner.addOutputLine(
            COLOR.WARNING("Команда 'docker build' не найдена.")
          );
        }

        runner.setText("Шаг 3: Запуск Docker-контейнеров...");
        runner.addOutputLine(COLOR.INFO("\nЗапуск Docker-контейнеров..."));
        if (dockerUpCommand) {
          await dockerUpCommand.action({ detach: true });
        } else {
          runner.addOutputLine(
            COLOR.WARNING("Команда 'docker up' не найдена.")
          );
        }

        runner.setText("Ожидание запуска контейнеров...");
        runner.addOutputLine(COLOR.INFO("Ожидание запуска контейнеров..."));
        await new Promise((resolve) => setTimeout(resolve, 15000));

        runner.setText("Шаг 4: Миграция базы данных...");
        runner.addOutputLine(COLOR.INFO("\nМиграция базы данных..."));
        if (dbMigrateCommand) {
          await dbMigrateCommand.action();
        } else {
          runner.addOutputLine(
            COLOR.WARNING("Команда 'db migrate' не найдена.")
          );
        }

        runner.setText("Шаг 5: Заполнение базы данных...");
        runner.addOutputLine(COLOR.INFO("\nЗаполнение базы данных..."));
        if (dbSeedCommand) {
          await dbSeedCommand.action();
        } else {
          runner.addOutputLine(COLOR.WARNING("Команда 'db seed' не найдена."));
        }

        runner.succeed("Проект успешно установлен и запущен!");
      } catch (error) {
        runner.fail(`Ошибка при установке проекта: ${error.message}`);
        runner.addOutputLine(
          COLOR.INFO(
            "Попробуйте запустить 'lv docker down' для остановки контейнеров."
          )
        );
      }
    });

  program
    .command("env")
    .description("Установка переменных окружения")
    .action(async () => {
      const runner = createInteractiveRunner(
        "Установка переменных окружения..."
      );
      runner.start();

      let copiedAny = false;
      let errorOccurred = false;

      try {
        runner.setText("Копирование основного .env файла...");
        const copiedMain = await copyIfNotExists(
          path.join(PROJECT_DIR, ".env.example"),
          path.join(PROJECT_DIR, ".env")
        );
        if (copiedMain) {
          runner.addOutputLine(COLOR.SUCCESS("✓ Основной .env скопирован."));
        } else {
          runner.addOutputLine(COLOR.INFO("* Основной .env уже существует."));
        }
        copiedAny = copiedAny || copiedMain;

        runner.setText("Копирование .env файла для бэкенда...");
        const copiedBackend = await copyIfNotExists(
          path.join(PROJECT_DIR, "backend/.env.example"),
          path.join(PROJECT_DIR, "backend/.env")
        );
        if (copiedBackend) {
          runner.addOutputLine(COLOR.SUCCESS("✓ Бэкенд .env скопирован."));
        } else {
          runner.addOutputLine(COLOR.INFO("* Бэкенд .env уже существует."));
        }
        copiedAny = copiedAny || copiedBackend;

        runner.setText("Копирование .env файла для фронтенда...");
        const copiedFrontend = await copyIfNotExists(
          path.join(PROJECT_DIR, "frontend/.env.example"),
          path.join(PROJECT_DIR, "frontend/.env")
        );
        if (copiedFrontend) {
          runner.addOutputLine(COLOR.SUCCESS("✓ Фронтенд .env скопирован."));
        } else {
          runner.addOutputLine(COLOR.INFO("* Фронтенд .env уже существует."));
        }
        copiedAny = copiedAny || copiedFrontend;

        if (copiedAny) {
          runner.succeed("Переменные окружения успешно скопированы.");
        } else {
          runner.succeed("Все файлы .env уже существовали.");
        }

        runner.addOutputLine(
          COLOR.WARNING(
            "\nНе забудьте проверить и настроить скопированные .env файлы!"
          )
        );
      } catch (error) {
        errorOccurred = true;
        runner.fail(
          `Ошибка при копировании файлов окружения: ${error.message}`
        );
      }
    });
};

export default registerCoreCommands;
