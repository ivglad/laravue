#!/usr/bin/env node

/**
 * LaraVue - CLI утилита для управления проектом
 * Главная точка входа, делегирующая выполнение модульной версии
 */

import { program } from "commander";
import { registerCommands } from "./.cli/index.js";

// Импорт информации из package.json
import { readFileSync } from "fs";
const packageJson = JSON.parse(readFileSync("./package.json", "utf8"));

// Настройка командной строки
program
  .name(Object.keys(packageJson.bin)[0])
  .description(packageJson.description)
  .version(packageJson.version);

// Регистрация всех команд
registerCommands(program);

// Обработка ошибок при отсутствии команды
program.on("command:*", () => {
  console.error(
    "\x1b[31mНеизвестная команда. Воспользуйтесь `lv help` для получения списка команд.\x1b[0m"
  );
  process.exit(1);
});

// Запуск CLI
program.parse(process.argv);
