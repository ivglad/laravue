import fs from "fs";
import path from "path";

// Определение цветов для вывода
export const COLORS = {
  RESET: "\x1b[0m",
  INFO: "\x1b[36m", // Синий
  SUCCESS: "\x1b[32m", // Зеленый
  WARNING: "\x1b[33m", // Желтый
  ERROR: "\x1b[31m", // Красный
};

// Символы для статусов
export const SYMBOLS = {
  SUCCESS: "✓",
  ERROR: "✗",
  INFO: "ℹ",
  WARNING: "⚠",
};

// Конфигурация для Docker
export const DOCKER = {
  COMPOSE: "docker compose",
  GET_PROJECT_NAME: () => {
    try {
      return fs.existsSync(".env")
        ? fs
            .readFileSync(".env", "utf8")
            .split("\n")
            .find((line) => line.startsWith("DOCKER_PROJECT_NAME="))
            ?.split("=")[1] || "laravue"
        : "laravue";
    } catch (error) {
      return "laravue";
    }
  },
};

// Описание доступных команд
export const AVAILABLE_COMMANDS = {
  DOCKER: [
    { name: "build", description: "Сборка проекта или указанного сервиса" },
    { name: "init", description: "Инициализация проекта" },
    { name: "up", description: "Создание и запуск контейнеров" },
    { name: "down", description: "Остановка и удаление контейнеров" },
    { name: "start", description: "Запуск контейнеров" },
    { name: "stop", description: "Остановка контейнеров" },
    { name: "restart", description: "Перезапуск контейнеров" },
    { name: "status", description: "Проверка статуса контейнеров" },
    { name: "prune", description: "Очистка неиспользуемых Docker ресурсов" },
  ],
  FRONTEND: [
    { name: "term", description: "Открыть консоль фронтенда" },
    { name: "logs", description: "Посмотреть логи фронтенда" },
    { name: "dev", description: "Запуск фронтенда в режиме разработки" },
    { name: "build", description: "Сборка фронтенда" },
  ],
  BACKEND: [
    { name: "term", description: "Открыть консоль бэкенда" },
    { name: "logs", description: "Посмотреть логи бэкенда" },
    { name: "clear", description: "Очистка кэша приложения" },
    { name: "routes", description: "Отображение маршрутов приложения" },
  ],
  DB: [
    { name: "migrate", description: "Миграция базы данных" },
    { name: "seed", description: "Заполнение базы тестовыми данными" },
    { name: "reset", description: "Сброс базы данных и миграция" },
    { name: "fresh", description: "Пересоздание таблиц с миграцией" },
    { name: "dump", description: "Создание дампа базы данных" },
  ],
};
