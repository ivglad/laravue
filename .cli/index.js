#!/usr/bin/env node

/**
 * LaraVue - CLI утилита для управления проектом
 * Точка входа для CLI
 */

import { Command } from "commander";
import { VERSION } from "./config.js";
import "./utils/utilities.js"; // Предзагрузка модуля утилит

// Импортируем регистраторы команд
import registerCoreCommands from "./commands/core.js";
import registerDockerCommands from "./commands/docker.js";
import registerFrontendCommands from "./commands/frontend.js";
import registerBackendCommands from "./commands/backend.js";
import registerDbCommands from "./commands/db.js";
import registerMaintenanceCommands from "./commands/maintenance.js";

// Создаем экземпляр программы
const program = new Command();

// Настройка программы
program
  .name("laravue")
  .description("CLI утилита для управления проектом LaraVue")
  .version(VERSION);

// Регистрируем команды
registerCoreCommands(program);
registerDockerCommands(program);
registerFrontendCommands(program);
registerBackendCommands(program);
registerDbCommands(program);
registerMaintenanceCommands(program);

// Обработка неизвестных команд
program.on("command:*", function () {
  console.error(`Неизвестная команда: ${program.args.join(" ")}`);
  console.log("Введите --help для получения списка команд");
  process.exit(1);
});

// Парсинг аргументов командной строки
program.parse(process.argv);

// Если нет аргументов, показываем справку
if (!process.argv.slice(2).length) {
  program.outputHelp();
}
