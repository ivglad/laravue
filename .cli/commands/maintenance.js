/**
 * Команды CLI для обслуживания проекта
 */
import os from "os";
import path from "path";
import { execSync } from "child_process";
import COLOR from "../utils/colors.js";
import { PROJECT_DIR, createInteractiveRunner } from "../utils/utilities.js";

/**
 * Добавление команд для обслуживания проекта
 * @param {Command} program - Экземпляр программы Commander
 */
export const registerMaintenanceCommands = (program) => {
  program
    .command("clean")
    .description("Очистка временных файлов")
    .action(async () => {
      const runner = createInteractiveRunner("Очистка временных файлов...");
      runner.start();

      try {
        runner.addOutputLine(
          COLOR.INFO_SYMBOL("Начало процесса очистки временных файлов...")
        );

        // Удаление временных файлов
        const findAndDeleteFiles = async (extension) => {
          try {
            runner.setText(COLOR.PROCESS(`Поиск файлов *.${extension}...`));
            const findCmd = `find ${PROJECT_DIR} -name "*.${extension}" -type f`;
            const filesStr = execSync(findCmd, {
              encoding: "utf8",
              stdio: "pipe",
            });

            if (filesStr.trim()) {
              const files = filesStr.trim().split(os.EOL);
              runner.setText(
                COLOR.PROCESS(
                  `Удаление ${COLOR.VALUE(
                    files.length
                  )} файлов *.${extension}...`
                )
              );

              const fs = await import("fs/promises");
              for (const file of files) {
                if (file.trim()) {
                  await fs.unlink(file);
                  const relPath = path.relative(PROJECT_DIR, file);
                  runner.addOutputLine(COLOR.FILE(`Удален файл: ${relPath}`));
                }
              }

              runner.addOutputLine(
                COLOR.DONE(
                  `Удалено ${files.length} файлов с расширением ${extension}`
                )
              );
            } else {
              runner.addOutputLine(
                COLOR.SKIPPED(`Файлы с расширением ${extension} не найдены`)
              );
            }
          } catch (error) {
            runner.addOutputLine(
              COLOR.WARNING_SYMBOL(
                `Ошибка при поиске файлов *.${extension}: ${error.message}`
              )
            );
          }
        };

        await findAndDeleteFiles("tmp");
        await findAndDeleteFiles("log");

        runner.succeed("Временные файлы успешно очищены");
      } catch (error) {
        runner.fail(`Ошибка при очистке временных файлов: ${error.message}`);
        console.error(
          COLOR.ERROR_SYMBOL(
            `Ошибка при очистке временных файлов: ${error.message}`
          )
        );
        process.exit(1);
      }
    });

  program
    .command("info")
    .description("Информация о проекте")
    .action(async () => {
      const runner = createInteractiveRunner("Сбор информации о проекте...");
      runner.start();

      try {
        // Информация о системе
        runner.setText(COLOR.PROCESS("Получение информации о системе..."));
        const platform = os.platform();
        const release = os.release();
        const arch = os.arch();
        runner.addOutputLine(COLOR.HEADER("Системная информация:"));
        runner.addOutputLine(
          `${COLOR.INFO_SYMBOL("Платформа")}: ${COLOR.VALUE(platform)}`
        );
        runner.addOutputLine(
          `${COLOR.INFO_SYMBOL("Версия")}: ${COLOR.VALUE(release)}`
        );
        runner.addOutputLine(
          `${COLOR.INFO_SYMBOL("Архитектура")}: ${COLOR.VALUE(arch)}`
        );

        // Информация о Node.js
        try {
          runner.setText(COLOR.PROCESS("Получение информации о Node.js..."));
          const nodeVersion = execSync("node -v", { encoding: "utf8" }).trim();
          const npmVersion = execSync("npm -v", { encoding: "utf8" }).trim();

          runner.addOutputLine(COLOR.HEADER("Версии Node.js:"));
          runner.addOutputLine(
            `${COLOR.INFO_SYMBOL("Node.js")}: ${COLOR.VALUE(nodeVersion)}`
          );
          runner.addOutputLine(
            `${COLOR.INFO_SYMBOL("npm")}: ${COLOR.VALUE(npmVersion)}`
          );
        } catch (error) {
          runner.addOutputLine(
            COLOR.WARNING_SYMBOL("Не удалось получить информацию о Node.js")
          );
        }

        // Информация о Docker
        try {
          runner.setText(COLOR.PROCESS("Получение информации о Docker..."));
          const dockerVersion = execSync("docker --version", {
            encoding: "utf8",
          }).trim();
          const dockerComposeVersion = execSync("docker compose version", {
            encoding: "utf8",
          }).trim();

          runner.addOutputLine(COLOR.HEADER("Версии Docker:"));
          runner.addOutputLine(
            `${COLOR.INFO_SYMBOL("Docker")}: ${COLOR.VALUE(dockerVersion)}`
          );
          runner.addOutputLine(
            `${COLOR.INFO_SYMBOL("Docker Compose")}: ${COLOR.VALUE(
              dockerComposeVersion
            )}`
          );
        } catch (error) {
          runner.addOutputLine(
            COLOR.WARNING_SYMBOL("Не удалось получить информацию о Docker")
          );
        }

        // Информация о проекте
        runner.setText(COLOR.PROCESS("Получение информации о проекте..."));
        runner.addOutputLine(COLOR.HEADER("Информация о проекте:"));
        runner.addOutputLine(
          `${COLOR.INFO_SYMBOL("Путь к проекту")}: ${COLOR.DIR(PROJECT_DIR)}`
        );

        runner.succeed("Информация о проекте успешно собрана");
      } catch (error) {
        runner.fail(`Ошибка при сборе информации: ${error.message}`);
        console.error(COLOR.ERROR_SYMBOL(`Ошибка: ${error.message}`));
        process.exit(1);
      }
    });
};

export default registerMaintenanceCommands;
