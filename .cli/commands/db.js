import inquirer from "inquirer";
import fs from "fs";
import path from "path";
import { AVAILABLE_COMMANDS, COLORS, SYMBOLS } from "../config.js";
import { executeCommand } from "../utils/executor.js";
import {
  checkRequirements,
  displayRequirementsErrors,
  fileExists,
} from "../utils/validator.js";

/**
 * Регистрирует команды для работы с базой данных
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerDbCommands(program) {
  // Команда db
  program
    .command("db [command]")
    .description("Выполнение команд для базы данных")
    .action(async (command) => {
      const requirements = checkRequirements();
      if (!displayRequirementsErrors(requirements)) {
        process.exit(1);
      }

      if (!command) {
        // Если команда не указана, запрашиваем у пользователя
        const choices = AVAILABLE_COMMANDS.DB.map((cmd) => ({
          name: `${cmd.name} - ${cmd.description}`,
          value: cmd.name,
        }));

        const { selectedCommand } = await inquirer.prompt([
          {
            type: "list",
            name: "selectedCommand",
            message: "Выберите команду для базы данных:",
            choices,
          },
        ]);

        command = selectedCommand;
      }

      // Обработка команды
      switch (command) {
        case "migrate":
          await executeCommand(
            "docker compose exec backend php artisan migrate",
            "Миграция базы данных"
          );
          break;
        case "seed":
          await executeCommand(
            "docker compose exec backend php artisan db:seed",
            "Заполнение базы тестовыми данными"
          );
          break;
        case "reset":
          await executeCommand(
            "docker compose exec backend php artisan migrate:reset",
            "Сброс базы данных и миграция"
          );
          break;
        case "fresh":
          await executeCommand(
            "docker compose exec backend php artisan migrate:fresh",
            "Пересоздание таблиц с миграцией"
          );
          break;
        case "dump":
          await createDatabaseDump();
          break;
        default:
          console.error(
            `${COLORS.ERROR}${SYMBOLS.ERROR} Неизвестная команда: ${command}${COLORS.RESET}`
          );
          console.log("Доступные команды:");
          AVAILABLE_COMMANDS.DB.forEach((cmd) => {
            console.log(`  ${cmd.name.padEnd(10)} - ${cmd.description}`);
          });
          process.exit(1);
      }
    });
}

/**
 * Создает дамп базы данных
 */
async function createDatabaseDump() {
  // Определяем тип базы данных из .env файла
  let dbType = process.env.DB_TYPE;
  let dumpPath = process.env.DUMP_PATH || ".";

  if (!dbType && fileExists("backend/.env")) {
    try {
      const envContent = fs.readFileSync("backend/.env", "utf8");
      const connectionType =
        envContent
          .split("\n")
          .find((line) => line.startsWith("DB_CONNECTION="))
          ?.split("=")[1] || null;

      if (connectionType) {
        dbType = connectionType.toLowerCase();
      }
    } catch (error) {
      // Не удалось прочитать файл .env
    }
  }

  // Если тип базы данных все еще не определен, спрашиваем у пользователя
  if (!dbType) {
    const { selectedDbType } = await inquirer.prompt([
      {
        type: "list",
        name: "selectedDbType",
        message: "Выберите тип базы данных:",
        choices: [
          { name: "MySQL", value: "mysql" },
          { name: "PostgreSQL", value: "postgres" },
        ],
      },
    ]);

    dbType = selectedDbType;
  }

  // Запрашиваем путь для сохранения дампа, если не указан
  if (!dumpPath || dumpPath === ".") {
    const { selectedPath } = await inquirer.prompt([
      {
        type: "input",
        name: "selectedPath",
        message:
          "Укажите путь для сохранения дампа (по умолчанию текущая директория):",
        default: ".",
      },
    ]);

    dumpPath = selectedPath || ".";
  }

  // Убедимся, что директория существует
  try {
    if (!fs.existsSync(dumpPath)) {
      fs.mkdirSync(dumpPath, { recursive: true });
    }
  } catch (error) {
    console.error(
      `${COLORS.ERROR}${SYMBOLS.ERROR} Не удалось создать директорию: ${dumpPath}${COLORS.RESET}`
    );
    process.exit(1);
  }

  // Формируем имя файла с датой и временем
  const timestamp = new Date()
    .toISOString()
    .replace(/[-:]/g, "")
    .replace("T", "_")
    .split(".")[0];

  const dumpFileName = path.join(dumpPath, `${dbType}_dump_${timestamp}.sql`);

  // Создаем дамп в зависимости от типа базы данных
  if (dbType === "mysql") {
    await executeCommand(
      `docker compose exec db mysqldump -u\${MYSQL_USER:-root} -p\${MYSQL_PASSWORD:-password} \${MYSQL_DATABASE:-laravel} > ${dumpFileName}`,
      "Создание дампа MySQL"
    );
  } else if (dbType === "postgres") {
    await executeCommand(
      `docker compose exec db pg_dump -U \${POSTGRES_USER:-postgres} \${POSTGRES_DB:-laravel} > ${dumpFileName}`,
      "Создание дампа PostgreSQL"
    );
  } else {
    console.error(
      `${COLORS.ERROR}${SYMBOLS.ERROR} Неизвестный тип базы данных: ${dbType}. Используйте mysql или postgres.${COLORS.RESET}`
    );
    process.exit(1);
  }

  console.log(
    `${COLORS.SUCCESS}${SYMBOLS.SUCCESS} Дамп базы данных создан: ${dumpFileName}${COLORS.RESET}`
  );
}
