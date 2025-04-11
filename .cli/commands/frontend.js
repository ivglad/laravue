/**
 * Команды CLI для работы с фронтендом
 */
import COLOR from "../utils/colors.js";
import checkRequirements from "../utils/requirements.js";
import { DOCKER_COMPOSE } from "../config.js";
import { runInteractive } from "../utils/utilities.js";

/**
 * Добавление команд для работы с фронтендом
 * @param {Command} program - Экземпляр программы Commander
 */
export const registerFrontendCommands = (program) => {
  const frontend = program
    .command("frontend")
    .description("Команды для управления фронтендом");

  frontend
    .command("term")
    .description("Открыть консоль фронтенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(DOCKER_COMPOSE, ["exec", "frontend", "bash"], {
          title: COLOR.HEADER("Консоль фронтенда"),
          statusMessages: [
            "Открытие консоли фронтенда...",
            "Подключение к контейнеру frontend...",
            "Запуск bash в контейнере frontend...",
          ],
        });
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  frontend
    .command("logs")
    .description("Посмотреть логи фронтенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(DOCKER_COMPOSE, ["logs", "-f", "frontend"], {
          title: COLOR.HEADER("Логи фронтенда"),
          statusMessages: [
            "Просмотр логов фронтенда...",
            "Получение информации из контейнера frontend...",
            "Отслеживание логов в реальном времени...",
          ],
        });
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  frontend
    .command("dev")
    .description("Запуск фронтенда в режиме разработки")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "frontend", "npm", "run", "dev"],
          {
            title: COLOR.HEADER("Фронтенд в режиме разработки"),
            statusMessages: [
              "Запуск фронтенда в режиме разработки...",
              "Инициализация dev сервера...",
              "Компиляция исходных файлов...",
              "Запуск HMR (Hot Module Replacement)...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  frontend
    .command("build")
    .description("Сборка фронтенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "frontend", "npm", "run", "build"],
          {
            title: COLOR.HEADER("Сборка фронтенда"),
            statusMessages: [
              "Сборка фронтенда...",
              "Компиляция исходных файлов...",
              "Оптимизация зависимостей...",
              "Минификация кода...",
              "Создание производственных артефактов...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  frontend
    .command("lint")
    .description("Проверка кода фронтенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "frontend", "npm", "run", "lint"],
          {
            title: COLOR.HEADER("Проверка кода фронтенда"),
            statusMessages: [
              "Запуск проверки кода...",
              "Анализ исходных файлов...",
              "Поиск проблем и ошибок...",
              "Проверка соответствия стилю кода...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  frontend
    .command("install")
    .description("Установка зависимостей для фронтенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "frontend", "npm", "install"],
          {
            title: COLOR.HEADER("Установка зависимостей фронтенда"),
            statusMessages: [
              "Установка зависимостей фронтенда...",
              "Анализ package.json...",
              "Разрешение зависимостей...",
              "Загрузка пакетов...",
              "Создание дерева зависимостей...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });
};

export default registerFrontendCommands;
