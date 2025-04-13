import inquirer from "inquirer";
import { AVAILABLE_COMMANDS, COLORS, SYMBOLS } from "../config.js";
import { executeCommand, executeSequence } from "../utils/executor.js";
import {
  checkRequirements,
  displayRequirementsErrors,
} from "../utils/validator.js";

/**
 * Регистрирует команды для работы с Backend
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerBackendCommands(program) {
  // Команда backend
  program
    .command("backend [command]")
    .description("Выполнение команд для бэкенда")
    .action(async (command) => {
      const requirements = checkRequirements();
      if (!displayRequirementsErrors(requirements)) {
        process.exit(1);
      }

      if (!command) {
        // Если команда не указана, запрашиваем у пользователя
        const choices = AVAILABLE_COMMANDS.BACKEND.map((cmd) => ({
          name: `${cmd.name} - ${cmd.description}`,
          value: cmd.name,
        }));

        const { selectedCommand } = await inquirer.prompt([
          {
            type: "list",
            name: "selectedCommand",
            message: "Выберите команду для бэкенда:",
            choices,
          },
        ]);

        command = selectedCommand;
      }

      // Обработка команды
      switch (command) {
        case "term":
          await executeCommand(
            "docker compose exec backend bash",
            "Открытие консоли бэкенда",
            { interactive: true }
          );
          break;
        case "logs":
          await executeCommand(
            "docker compose logs -f backend",
            "Логи бэкенда",
            { interactive: true }
          );
          break;
        case "routes":
          await executeCommand(
            "docker compose exec backend php artisan route:list",
            "Отображение маршрутов приложения"
          );
          break;
        case "clear":
          // Очистка кэша приложения через несколько команд
          const clearCommands = [
            {
              command: "docker compose exec backend php artisan cache:clear",
              description: "Очистка кэша",
            },
            {
              command: "docker compose exec backend php artisan config:clear",
              description: "Очистка кэша конфигурации",
            },
            {
              command: "docker compose exec backend php artisan route:clear",
              description: "Очистка кэша маршрутов",
            },
            {
              command: "docker compose exec backend php artisan view:clear",
              description: "Очистка кэша представлений",
            },
          ];

          await executeSequence(clearCommands);
          break;
        default:
          console.error(
            `${COLORS.ERROR}${SYMBOLS.ERROR} Неизвестная команда: ${command}${COLORS.RESET}`
          );
          console.log("Доступные команды:");
          AVAILABLE_COMMANDS.BACKEND.forEach((cmd) => {
            console.log(`  ${cmd.name.padEnd(10)} - ${cmd.description}`);
          });
          process.exit(1);
      }
    });
}
