import { execSync } from "child_process";
import fs from "fs";
import { COLORS, SYMBOLS } from "../config.js";
import ora from "ora";

/**
 * Проверяет наличие необходимых программ
 * @returns {Object} Результат проверки
 */
export function checkRequirements() {
  const requirements = {
    docker: false,
    dockerCompose: false,
  };

  try {
    execSync("docker --version", { stdio: "ignore" });
    requirements.docker = true;
  } catch (err) {
    // Docker не установлен
  }

  try {
    execSync("docker compose version", { stdio: "ignore" });
    requirements.dockerCompose = true;
  } catch (err) {
    try {
      // Проверяем старый формат docker-compose
      execSync("docker-compose --version", { stdio: "ignore" });
      requirements.dockerCompose = true;
    } catch (err) {
      // Docker Compose не установлен
    }
  }

  return requirements;
}

/**
 * Выводит список ошибок требований
 * @param {Object} requirements Результат проверки требований
 * @returns {boolean} Все ли требования выполнены
 */
export function displayRequirementsErrors(requirements) {
  const spinner = ora({
    text: "Проверка требований...",
    color: "cyan",
  }).start();

  let errorMessages = [];

  if (!requirements.docker) {
    errorMessages.push(
      `${COLORS.ERROR}Docker не установлен! Пожалуйста, установите Docker.${COLORS.RESET}`
    );
  }

  if (!requirements.dockerCompose) {
    errorMessages.push(
      `${COLORS.ERROR}Docker Compose не установлен! Пожалуйста, установите Docker Compose.${COLORS.RESET}`
    );
  }

  const hasErrors = errorMessages.length > 0;

  if (hasErrors) {
    spinner.fail("Не все требования выполнены");
    // Выводим сообщения об ошибках после завершения
    errorMessages.forEach((msg) => console.error(msg));
  } else {
    // Просто останавливаем спиннер без вывода сообщения об успехе
    spinner.stop();
  }

  return !hasErrors;
}

/**
 * Проверяет наличие файла
 * @param {string} filePath Путь к файлу
 * @returns {boolean} Существует ли файл
 */
export function fileExists(filePath) {
  return fs.existsSync(filePath);
}

/**
 * Возвращает команду docker-compose в зависимости от доступных утилит
 * @returns {string} Команда docker-compose
 */
export function getDockerComposeCommand() {
  try {
    execSync("docker compose version", { stdio: "ignore" });
    return "docker compose";
  } catch (err) {
    try {
      execSync("docker-compose --version", { stdio: "ignore" });
      return "docker-compose";
    } catch (err) {
      return "docker compose";
    }
  }
}
