/**
 * Команды CLI для работы с Docker
 */
import COLOR from "../utils/colors.js";
import checkRequirements from "../utils/requirements.js";
import { DOCKER_COMPOSE } from "../config.js";
import {
  runInteractive,
  createInteractiveRunner,
} from "../utils/interactive-runner.js";

/**
 * Добавление команд для работы с Docker
 * @param {Command} program - Экземпляр программы Commander
 */
export const registerDockerCommands = (program) => {
  const docker = program
    .command("docker")
    .description("Команды для управления Docker");

  docker
    .command("build")
    .description("Собрать образы Docker")
    .argument("[service]", "Имя сервиса для сборки (по умолчанию - все)")
    .action(async (service) => {
      try {
        const title = `Сборка ${service || "всех сервисов"}`;
        const statusMessages = [
          `Сборка ${service || "всех сервисов"}`,
          `Подготовка контекста сборки для ${service || "всех сервисов"}`,
          `Загрузка зависимостей для ${service || "всех сервисов"}`,
          `Компиляция приложения для ${service || "всех сервисов"}`,
          `Оптимизация образа для ${service || "всех сервисов"}`,
        ];

        const args = ["build"];
        if (service) args.push(service);

        await runInteractive(DOCKER_COMPOSE, args, {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при сборке Docker образов:\n${error.message}`)
        );
      }
    });

  docker
    .command("up")
    .description("Запустить все контейнеры")
    .option(
      "-d, --detach",
      "Запустить в фоновом режиме (используется по умолчанию)"
    )
    .option("-i, --interactive", "Запустить в интерактивном режиме (без -d)")
    .action(async (options) => {
      try {
        const title = "Запуск контейнеров";
        const statusMessages = [
          "Запуск контейнеров",
          "Подготовка Docker окружения",
          "Инициализация сервисов",
          "Проверка сетевых подключений",
        ];

        // Параметры команды up
        const upArgs = ["up"];

        // По умолчанию используем detach режим, если только не указан --interactive
        const useDetach = !options?.interactive;

        if (useDetach) {
          upArgs.push("-d");
        }

        await runInteractive(DOCKER_COMPOSE, upArgs, {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при запуске контейнеров:\n${error.message}`)
        );
      }
    });

  docker
    .command("down")
    .description("Остановка и удаление контейнеров")
    .action(async () => {
      try {
        const title = "Остановка и удаление контейнеров";
        const statusMessages = [
          "Остановка контейнеров",
          "Удаление контейнеров",
          "Очистка сетевых ресурсов",
        ];

        await runInteractive(DOCKER_COMPOSE, ["down"], {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при остановке контейнеров:\n${error.message}`)
        );
      }
    });

  docker
    .command("start")
    .description("Запуск контейнеров")
    .action(async () => {
      try {
        const title = "Запуск контейнеров";
        const statusMessages = ["Запуск контейнеров", "Инициализация сервисов"];

        await runInteractive(DOCKER_COMPOSE, ["start"], {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при запуске контейнеров:\n${error.message}`)
        );
      }
    });

  docker
    .command("stop")
    .description("Остановка контейнеров")
    .action(async () => {
      try {
        const title = "Остановка контейнеров";
        const statusMessages = [
          "Остановка контейнеров",
          "Завершение работы сервисов",
        ];

        await runInteractive(DOCKER_COMPOSE, ["stop"], {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при остановке контейнеров:\n${error.message}`)
        );
      }
    });

  docker
    .command("restart")
    .description("Перезапуск контейнеров")
    .action(async () => {
      try {
        const title = "Перезапуск контейнеров";
        const statusMessages = [
          "Перезапуск контейнеров",
          "Остановка сервисов",
          "Инициализация сервисов",
        ];

        await runInteractive(DOCKER_COMPOSE, ["restart"], {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при перезапуске контейнеров:\n${error.message}`)
        );
      }
    });

  docker
    .command("status")
    .description("Проверка статуса контейнеров")
    .action(async () => {
      try {
        const title = "Проверка статуса контейнеров";
        const statusMessages = ["Проверка статуса контейнеров"];

        await runInteractive(DOCKER_COMPOSE, ["ps"], {
          title,
          statusMessages,
        });
      } catch (error) {
        console.error(
          COLOR.ERROR(
            `Ошибка при проверке статуса контейнеров:\n${error.message}`
          )
        );
      }
    });

  docker
    .command("prune")
    .description("Очистка неиспользуемых Docker ресурсов")
    .action(async () => {
      try {
        const title = "Очистка неиспользуемых Docker ресурсов";
        const statusMessages = [
          "Очистка неиспользуемых Docker ресурсов",
          "Удаление неиспользуемых образов",
          "Удаление неиспользуемых томов",
          "Удаление неиспользуемых сетей",
        ];

        await runInteractive(
          "docker",
          ["system", "prune", "-af", "--volumes"],
          {
            title,
            statusMessages,
          }
        );
      } catch (error) {
        console.error(
          COLOR.ERROR(`Ошибка при очистке Docker ресурсов:\n${error.message}`)
        );
      }
    });
};

export default registerDockerCommands;
