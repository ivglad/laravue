import inquirer from "inquirer";
import { executeCommand } from "../utils/executor.js";
import { COLORS, SYMBOLS, AVAILABLE_COMMANDS } from "../config.js";
import {
  checkRequirements,
  displayRequirementsErrors,
  getDockerComposeCommand,
} from "../utils/validator.js";

/**
 * Регистрирует команды для работы с Docker
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerDockerCommands(program) {
  const dockerCompose = getDockerComposeCommand();

  // Общая функция для проверки требований перед выполнением команд
  const checkBeforeAction = async () => {
    const requirements = checkRequirements();
    if (!displayRequirementsErrors(requirements)) {
      process.exit(1);
    }
    return true;
  };

  // Основная команда docker с интерактивным режимом
  program
    .command("docker [command]")
    .description("Команды для работы с Docker")
    .action(async (command) => {
      try {
        await checkBeforeAction();

        // Если команда не указана, предлагаем интерактивный выбор
        if (!command) {
          const choices = AVAILABLE_COMMANDS.DOCKER.map((cmd) => ({
            name: `${cmd.name} - ${cmd.description}`,
            value: cmd.name,
          }));

          const { selectedCommand } = await inquirer.prompt([
            {
              type: "list",
              name: "selectedCommand",
              message: "Выберите Docker команду:",
              choices,
            },
          ]);

          command = selectedCommand;
        }

        // Обработка команды
        switch (command) {
          case "build":
            // Для build запрашиваем дополнительную информацию о сервисе
            const { service } = await inquirer.prompt([
              {
                type: "input",
                name: "service",
                message:
                  "Введите имя сервиса для сборки (оставьте пустым для сборки всех сервисов):",
                default: "",
              },
            ]);

            if (service) {
              await executeCommand(
                `${dockerCompose} build ${service}`,
                `Сборка сервиса ${service}`
              );
            } else {
              await executeCommand(
                `${dockerCompose} build`,
                "Сборка всех сервисов"
              );
            }
            break;

          case "init":
            const commands = [
              {
                command: "lv env",
                description: "Установка переменных окружения",
              },
              {
                command: `${dockerCompose} build`,
                description: "Сборка Docker образов",
              },
            ];

            for (const cmd of commands) {
              const success = await executeCommand(
                cmd.command,
                cmd.description
              );
              if (!success) break;
            }
            break;

          case "up":
            await executeCommand(
              `${dockerCompose} up -d`,
              "Создание и запуск контейнеров"
            );
            break;

          case "down":
            await executeCommand(
              `${dockerCompose} down`,
              "Остановка и удаление контейнеров"
            );
            break;

          case "start":
            await executeCommand(
              `${dockerCompose} start`,
              "Запуск контейнеров"
            );
            break;

          case "stop":
            await executeCommand(
              `${dockerCompose} stop`,
              "Остановка контейнеров"
            );
            break;

          case "restart":
            await executeCommand(
              `${dockerCompose} restart`,
              "Перезапуск контейнеров"
            );
            break;

          case "status":
            await executeCommand(
              `${dockerCompose} ps`,
              "Проверка статуса контейнеров"
            );
            break;

          case "prune":
            // Запрос подтверждения перед удалением
            const { confirmPrune } = await inquirer.prompt([
              {
                type: "confirm",
                name: "confirmPrune",
                message:
                  "Это действие удалит все неиспользуемые Docker ресурсы (образы, контейнеры, сети и тома). Продолжить?",
                default: false,
              },
            ]);

            if (confirmPrune) {
              await executeCommand(
                "docker system prune -af --volumes",
                "Очистка неиспользуемых Docker ресурсов"
              );
            } else {
              console.log(
                `${COLORS.INFO}${SYMBOLS.INFO} Операция отменена${COLORS.RESET}`
              );
            }
            break;

          default:
            console.error(
              `${COLORS.ERROR}${SYMBOLS.ERROR} Неизвестная команда: ${command}${COLORS.RESET}`
            );
            console.log("Доступные команды:");
            AVAILABLE_COMMANDS.DOCKER.forEach((cmd) => {
              console.log(`  ${cmd.name.padEnd(10)} - ${cmd.description}`);
            });
            process.exit(1);
        }
      } catch (error) {
        // Убираем обработку ExitPromptError, так как она теперь обрабатывается глобально
        console.error(
          `${COLORS.ERROR}${SYMBOLS.ERROR} Ошибка: ${error.message}${COLORS.RESET}`
        );
        process.exit(1);
      }
    });
}
