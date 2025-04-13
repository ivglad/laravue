import { registerDockerCommands } from "./commands/docker.js";
import { registerFrontendCommands } from "./commands/frontend.js";
import { registerBackendCommands } from "./commands/backend.js";
import { registerDbCommands } from "./commands/db.js";
import { registerBaseCommands } from "./commands/base.js";
import { registerMiscCommands } from "./commands/misc.js";

/**
 * Регистрирует все доступные команды
 * @param {import('commander').Command} program - Экземпляр commander
 */
export function registerCommands(program) {
  // Регистрация групп команд
  registerBaseCommands(program);
  registerDockerCommands(program);
  registerFrontendCommands(program);
  registerBackendCommands(program);
  registerDbCommands(program);
  registerMiscCommands(program);
}
