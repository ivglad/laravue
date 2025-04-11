/**
 * Команды CLI для работы с бэкендом
 */
import COLOR from "../utils/colors.js";
import checkRequirements from "../utils/requirements.js";
import {
  runInteractive,
  readEnvFile,
  createInteractiveRunner,
} from "../utils/utilities.js";
import { DOCKER_COMPOSE } from "../config.js";

/**
 * Добавление команд для работы с бэкендом
 * @param {Command} program - Экземпляр программы Commander
 */
export const registerBackendCommands = (program) => {
  const backend = program
    .command("backend")
    .description("Команды для бэкенда (Laravel)");

  backend
    .command("artisan")
    .description("Запуск команды Laravel Artisan")
    .argument("<args...>", "Аргументы для Artisan")
    .action(async (args) => {
      try {
        checkRequirements();

        const artisanCmd = args.join(" ");
        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", ...args],
          {
            title: COLOR.HEADER(`Выполнение Artisan: ${artisanCmd}`),
            statusMessages: [
              `Выполнение команды Artisan: ${artisanCmd}...`,
              "Подготовка среды Laravel...",
              "Обработка запроса Artisan...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  backend
    .command("composer")
    .description("Запуск команды Composer")
    .argument("<args...>", "Аргументы для Composer")
    .action(async (args) => {
      try {
        checkRequirements();

        const composerCmd = args.join(" ");
        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "composer", ...args],
          {
            title: COLOR.HEADER(`Выполнение Composer: ${composerCmd}`),
            statusMessages: [
              `Выполнение команды Composer: ${composerCmd}...`,
              "Обработка зависимостей...",
              "Выполнение операции Composer...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  backend
    .command("install")
    .description("Установка зависимостей для бэкенда")
    .action(async () => {
      try {
        checkRequirements();

        const runner = createInteractiveRunner(
          "Установка зависимостей для бэкенда..."
        );
        runner.start();

        try {
          runner.setText(COLOR.PROCESS("Установка зависимостей Composer..."));

          await runInteractive(
            DOCKER_COMPOSE,
            ["exec", "backend", "composer", "install"],
            {
              title: COLOR.HEADER("Установка зависимостей Composer"),
              statusMessages: [
                "Установка зависимостей Composer...",
                "Анализ composer.json...",
                "Разрешение зависимостей...",
                "Загрузка пакетов...",
              ],
            }
          );

          // Получаем переменные окружения из backend/.env
          runner.setText(COLOR.PROCESS("Проверка конфигурации Laravel..."));
          runner.addOutputLine(COLOR.INFO_SYMBOL("Чтение файла .env..."));

          const backendEnv = await readEnvFile("backend/.env");

          // Генерация ключа приложения, если APP_KEY не установлен
          if (!backendEnv.APP_KEY || backendEnv.APP_KEY === "") {
            runner.setText(COLOR.PROCESS("Генерация ключа приложения..."));
            runner.addOutputLine(
              COLOR.WARNING_SYMBOL("APP_KEY не найден, генерирую новый ключ...")
            );

            await runInteractive(
              DOCKER_COMPOSE,
              ["exec", "backend", "php", "artisan", "key:generate"],
              {
                title: COLOR.HEADER("Генерация ключа приложения"),
                statusMessages: [
                  "Генерация ключа приложения...",
                  "Создание нового ключа шифрования...",
                  "Обновление .env файла...",
                ],
              }
            );
          } else {
            runner.addOutputLine(
              COLOR.INFO_SYMBOL(
                "APP_KEY уже установлен, пропускаю генерацию ключа."
              )
            );
          }

          runner.succeed("Зависимости бэкенда установлены");
        } catch (error) {
          runner.fail(`Ошибка при установке зависимостей: ${error.message}`);
          throw error;
        }
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  backend
    .command("term")
    .description("Открыть консоль бэкенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(DOCKER_COMPOSE, ["exec", "backend", "bash"], {
          title: COLOR.HEADER("Консоль бэкенда"),
          statusMessages: [
            "Открытие консоли бэкенда...",
            "Подключение к контейнеру backend...",
            "Запуск bash в контейнере backend...",
          ],
        });
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  backend
    .command("logs")
    .description("Посмотреть логи бэкенда")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(DOCKER_COMPOSE, ["logs", "-f", "backend"], {
          title: COLOR.HEADER("Логи бэкенда"),
          statusMessages: [
            "Просмотр логов бэкенда...",
            "Получение информации из контейнера backend...",
            "Отслеживание логов в реальном времени...",
          ],
        });
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  backend
    .command("routes")
    .description("Отображение маршрутов приложения")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", "route:list"],
          {
            title: COLOR.HEADER("Маршруты приложения"),
            statusMessages: [
              "Отображение маршрутов приложения...",
              "Анализ файлов маршрутизации...",
              "Сбор информации о маршрутах...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  backend
    .command("clear")
    .description("Очистка кэша приложения")
    .action(async () => {
      try {
        checkRequirements();

        const runner = createInteractiveRunner("Очистка кэша приложения...");
        runner.start();

        try {
          // Очистка кэша конфигурации
          runner.setText(COLOR.PROCESS("Очистка кэша конфигурации..."));
          await runInteractive(
            DOCKER_COMPOSE,
            ["exec", "backend", "php", "artisan", "config:clear"],
            {
              title: COLOR.HEADER("Очистка кэша конфигурации"),
              statusMessages: ["Очистка кэша конфигурации..."],
            }
          );
          runner.addOutputLine(COLOR.DONE("Кэш конфигурации очищен"));

          // Очистка кэша маршрутов
          runner.setText(COLOR.PROCESS("Очистка кэша маршрутов..."));
          await runInteractive(
            DOCKER_COMPOSE,
            ["exec", "backend", "php", "artisan", "route:clear"],
            {
              title: COLOR.HEADER("Очистка кэша маршрутов"),
              statusMessages: ["Очистка кэша маршрутов..."],
            }
          );
          runner.addOutputLine(COLOR.DONE("Кэш маршрутов очищен"));

          // Очистка кэша представлений
          runner.setText(COLOR.PROCESS("Очистка кэша представлений..."));
          await runInteractive(
            DOCKER_COMPOSE,
            ["exec", "backend", "php", "artisan", "view:clear"],
            {
              title: COLOR.HEADER("Очистка кэша представлений"),
              statusMessages: ["Очистка кэша представлений..."],
            }
          );
          runner.addOutputLine(COLOR.DONE("Кэш представлений очищен"));

          // Очистка кэша приложения
          runner.setText(COLOR.PROCESS("Очистка кэша приложения..."));
          await runInteractive(
            DOCKER_COMPOSE,
            ["exec", "backend", "php", "artisan", "cache:clear"],
            {
              title: COLOR.HEADER("Очистка кэша приложения"),
              statusMessages: ["Очистка кэша приложения..."],
            }
          );
          runner.addOutputLine(COLOR.DONE("Кэш приложения очищен"));

          runner.succeed("Все типы кэша успешно очищены");
        } catch (error) {
          runner.fail(`Ошибка при очистке кэша: ${error.message}`);
          throw error;
        }
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });
};

export default registerBackendCommands;
