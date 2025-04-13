#!/usr/bin/env node

import { program } from "commander";
import { registerCommands } from "./.cli/index.js";
import ora from "ora";
import { COLORS, SYMBOLS } from "./.cli/config.js";

// Импорт информации из package.json
import { readFileSync } from "fs";
const packageJson = JSON.parse(readFileSync("./package.json", "utf8"));

// Глобальный обработчик сигнала SIGINT (Ctrl+C)
process.on("SIGINT", () => {
  console.log(
    `\n${COLORS.INFO}${SYMBOLS.INFO} Выполнение команды прервано${COLORS.RESET}`
  );
  process.exit(0);
});

// Настройка командной строки
program
  .name(Object.keys(packageJson.bin)[0])
  .description(packageJson.description)
  .version(packageJson.version);

// Регистрация всех команд
registerCommands(program);

// Обработка ошибок при отсутствии команды
program.on("command:*", () => {
  const spinner = ora("Выполнение команды").fail();
  console.error(
    "\x1b[31mНеизвестная команда. Воспользуйтесь `lv help` для получения списка команд.\x1b[0m"
  );
  process.exit(1);
});

// Запуск CLI
program.parse(process.argv);
