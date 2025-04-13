import inquirer from "inquirer";
import { AVAILABLE_COMMANDS, COLORS, SYMBOLS } from "../config.js";
import { executeCommand } from "../utils/executor.js";
import {
  checkRequirements,
  displayRequirementsErrors,
} from "../utils/validator.js";

/**
 * Регистрирует команды для работы с Frontend
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerFrontendCommands(program) {
  // Команда frontend
  program
    .command("frontend [command]")
    .description("Выполнение команд для фронтенда")
    .action(async (command) => {
      const requirements = checkRequirements();
      if (!displayRequirementsErrors(requirements)) {
        process.exit(1);
      }

      if (!command) {
        // Если команда не указана, запрашиваем у пользователя
        const choices = AVAILABLE_COMMANDS.FRONTEND.map((cmd) => ({
          name: `${cmd.name} - ${cmd.description}`,
          value: cmd.name,
        }));

        const { selectedCommand } = await inquirer.prompt([
          {
            type: "list",
            name: "selectedCommand",
            message: "Выберите команду для фронтенда:",
            choices,
          },
        ]);

        command = selectedCommand;
      }

      // Обработка команды
      switch (command) {
        case "term":
          await executeCommand(
            "docker compose exec frontend bash",
            "Открытие консоли фронтенда",
            { interactive: true }
          );
          break;
        case "logs":
          await executeCommand(
            "docker compose logs -f frontend",
            "Логи фронтенда",
            { interactive: true }
          );
          break;
        case "dev":
          await executeCommand(
            "docker compose exec frontend npm run dev",
            "Запуск фронтенда в режиме разработки",
            { interactive: true }
          );
          break;
        case "build":
          await executeCommand(
            "docker compose exec frontend npm run build",
            "Сборка фронтенда"
          );
          break;
        default:
          console.error(
            `${COLORS.ERROR}${SYMBOLS.ERROR} Неизвестная команда: ${command}${COLORS.RESET}`
          );
          console.log("Доступные команды:");
          AVAILABLE_COMMANDS.FRONTEND.forEach((cmd) => {
            console.log(`  ${cmd.name.padEnd(10)} - ${cmd.description}`);
          });
          process.exit(1);
      }
    });
}
