/**
 * Команды CLI для работы с базой данных
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
 * Добавление команд для работы с базой данных
 * @param {Command} program - Экземпляр программы Commander
 */
export const registerDbCommands = (program) => {
  const db = program
    .command("db")
    .description("Команды для управления базой данных");

  db.command("migrate")
    .description("Миграция базы данных")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", "migrate"],
          {
            title: COLOR.HEADER("Миграция базы данных"),
            statusMessages: [
              "Миграция базы данных...",
              "Анализ файлов миграций...",
              "Применение изменений к схеме...",
              "Обновление структуры базы данных...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  db.command("seed")
    .description("Заполнение базы тестовыми данными")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", "db:seed"],
          {
            title: COLOR.HEADER("Заполнение базы тестовыми данными"),
            statusMessages: [
              "Заполнение базы тестовыми данными...",
              "Выполнение классов Seeder...",
              "Генерация тестовых данных...",
              "Запись данных в базу...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  db.command("reset")
    .description("Сброс базы данных и миграция")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", "migrate:reset"],
          {
            title: COLOR.HEADER("Сброс базы данных"),
            statusMessages: [
              "Сброс базы данных...",
              "Откат всех миграций...",
              "Удаление таблиц...",
              "Возврат к исходному состоянию...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  db.command("fresh")
    .description("Пересоздание таблиц с миграцией")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", "migrate:fresh"],
          {
            title: COLOR.HEADER("Пересоздание таблиц с миграцией"),
            statusMessages: [
              "Пересоздание таблиц с миграцией...",
              "Удаление всех таблиц...",
              "Запуск всех миграций заново...",
              "Создание новой структуры базы данных...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  db.command("dump")
    .description("Создание дампа базы данных")
    .option(
      "-t, --type <type>",
      "Тип базы данных (mysql или postgres)",
      "postgres"
    )
    .option("-p, --path <path>", "Путь для сохранения дампа", ".")
    .action(async (options) => {
      try {
        checkRequirements();

        const runner = createInteractiveRunner("Создание дампа базы данных...");
        runner.start();

        try {
          const dbType = options.type.toLowerCase();
          const dumpPath = options.path;
          const timestamp = new Date().toISOString().replace(/[:.]/g, "_");

          // Получаем переменные окружения из backend/.env
          runner.setText(COLOR.PROCESS("Чтение конфигурации базы данных..."));
          runner.addOutputLine(
            COLOR.INFO_SYMBOL("Получение переменных окружения из .env...")
          );

          const backendEnv = await readEnvFile("backend/.env");

          if (dbType === "mysql") {
            runner.setText(COLOR.PROCESS("Создание дампа MySQL..."));

            // Используем переменные из backend/.env или значения по умолчанию
            const mysqlHost = backendEnv.DB_HOST || "db";
            const mysqlPort = backendEnv.DB_PORT || "3306";
            const mysqlUser = backendEnv.DB_USERNAME || "root";
            const mysqlPassword = backendEnv.DB_PASSWORD || "password";
            const mysqlDatabase = backendEnv.DB_DATABASE || "laravel";
            const dumpFilePath = `${dumpPath}/mysql_dump_${timestamp}.sql`;

            runner.addOutputLine(
              COLOR.INFO_SYMBOL(
                `Соединение: ${COLOR.VALUE(mysqlHost)}:${COLOR.VALUE(
                  mysqlPort
                )}, ` +
                  `пользователь: ${COLOR.VALUE(mysqlUser)}, БД: ${COLOR.VALUE(
                    mysqlDatabase
                  )}`
              )
            );

            runner.setText(
              COLOR.PROCESS(
                `Создание дампа MySQL в ${COLOR.FILE(dumpFilePath)}...`
              )
            );

            await runInteractive(
              "bash",
              [
                "-c",
                `${DOCKER_COMPOSE} exec -T db mysqldump -u${mysqlUser} -p${mysqlPassword} ${mysqlDatabase} > ${dumpFilePath}`,
              ],
              {
                title: COLOR.HEADER("Экспорт дампа MySQL"),
                statusMessages: [
                  "Выполнение mysqldump...",
                  "Экспорт структуры базы данных...",
                  "Экспорт данных таблиц...",
                  "Сохранение дампа в файл...",
                ],
              }
            );

            runner.succeed(
              `Дамп MySQL успешно создан: ${COLOR.FILE(dumpFilePath)}`
            );
          } else if (dbType === "postgres") {
            runner.setText(COLOR.PROCESS("Создание дампа PostgreSQL..."));

            // Используем переменные из backend/.env или значения по умолчанию
            const pgHost = backendEnv.DB_HOST || "db";
            const pgPort = backendEnv.DB_PORT || "5432";
            const pgUser = backendEnv.DB_USERNAME || "postgres";
            const pgPassword = backendEnv.DB_PASSWORD || "postgres";
            const pgDb = backendEnv.DB_DATABASE || "laravel";
            const dumpFilePath = `${dumpPath}/postgres_dump_${timestamp}.sql`;

            runner.addOutputLine(
              COLOR.INFO_SYMBOL(
                `Соединение: ${COLOR.VALUE(pgHost)}:${COLOR.VALUE(pgPort)}, ` +
                  `пользователь: ${COLOR.VALUE(pgUser)}, БД: ${COLOR.VALUE(
                    pgDb
                  )}`
              )
            );

            runner.setText(
              COLOR.PROCESS(
                `Создание дампа PostgreSQL в ${COLOR.FILE(dumpFilePath)}...`
              )
            );

            // Установка переменной окружения PGPASSWORD для pg_dump
            await runInteractive(
              "bash",
              [
                "-c",
                `PGPASSWORD=${pgPassword} ${DOCKER_COMPOSE} exec -T db pg_dump -h ${pgHost} -p ${pgPort} -U ${pgUser} -d ${pgDb} > ${dumpFilePath}`,
              ],
              {
                title: COLOR.HEADER("Экспорт дампа PostgreSQL"),
                statusMessages: [
                  "Выполнение pg_dump...",
                  "Экспорт структуры базы данных...",
                  "Экспорт данных таблиц...",
                  "Сохранение дампа в файл...",
                ],
              }
            );

            runner.succeed(
              `Дамп PostgreSQL успешно создан: ${COLOR.FILE(dumpFilePath)}`
            );
          } else {
            runner.fail(`Неизвестный тип базы данных: ${dbType}`);
            console.error(
              COLOR.ERROR_SYMBOL(
                `Неизвестный тип базы данных: ${COLOR.CODE(
                  dbType
                )}. Используйте mysql или postgres.`
              )
            );
            process.exit(1);
          }
        } catch (error) {
          runner.fail(
            `Ошибка при создании дампа базы данных: ${error.message}`
          );
          throw error;
        }
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  db.command("status")
    .description("Статус миграций")
    .action(async () => {
      try {
        checkRequirements();

        await runInteractive(
          DOCKER_COMPOSE,
          ["exec", "backend", "php", "artisan", "migrate:status"],
          {
            title: COLOR.HEADER("Статус миграций"),
            statusMessages: [
              "Получение статуса миграций...",
              "Анализ файлов миграций...",
              "Сравнение с базой данных...",
              "Формирование отчета...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });

  db.command("rollback")
    .description("Откат последней миграции")
    .option("-s, --steps <steps>", "Количество шагов для отката", "1")
    .action(async (options) => {
      try {
        checkRequirements();

        const steps = options.steps || "1";

        await runInteractive(
          DOCKER_COMPOSE,
          [
            "exec",
            "backend",
            "php",
            "artisan",
            "migrate:rollback",
            "--step=" + steps,
          ],
          {
            title: COLOR.HEADER(
              `Откат миграций (${steps} ${steps === "1" ? "шаг" : "шагов"})`
            ),
            statusMessages: [
              `Откат миграций (${steps} ${steps === "1" ? "шаг" : "шагов"})...`,
              "Определение последних миграций...",
              "Откат изменений...",
              "Обновление структуры базы данных...",
            ],
          }
        );
      } catch (error) {
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });
};

export default registerDbCommands;
