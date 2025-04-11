/**
 * Конфигурационные параметры для CLI
 */
import path from "path";
import { existsSync, readFileSync } from "fs";
import { PROJECT_DIR } from "./utils/executor.js";

// Версия приложения
export const VERSION = "1.0.0";

// Конфигурация Docker Compose
export const DOCKER_COMPOSE = "docker compose";

// Получение имени проекта из .env файла или значение по умолчанию
const getDockerProjectName = () => {
  let projectName = "laravue";

  try {
    const envFilePath = path.join(PROJECT_DIR, ".env");
    if (existsSync(envFilePath)) {
      const envContent = readFileSync(envFilePath, "utf8");
      const projectNameMatch = envContent.match(/DOCKER_PROJECT_NAME=(.+)/);
      if (projectNameMatch && projectNameMatch[1]) {
        projectName = projectNameMatch[1].trim();
      }
    }
  } catch (error) {
    // В случае ошибки используем значение по умолчанию
  }

  return projectName;
};

export default {
  VERSION,
  DOCKER_COMPOSE,
  PROJECT_DIR,
};
