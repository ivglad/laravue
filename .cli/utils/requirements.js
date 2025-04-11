/**
 * Функции для проверки наличия необходимых программ
 */
import { execSync } from "child_process";
import COLOR from "./colors.js";
import createSpinner from "./spinner.js";

/**
 * Проверка наличия установленных программ
 */
export const checkRequirements = () => {
  const checkSpinner = createSpinner(
    "Проверка необходимых программ..."
  ).start();

  try {
    execSync("docker --version", { stdio: "pipe" });
    execSync("docker compose version", { stdio: "pipe" });
    checkSpinner.succeed("Все необходимые программы установлены");
  } catch (error) {
    checkSpinner.fail("Ошибка при проверке необходимых программ");

    if (error.message.includes("docker --version")) {
      console.error(
        COLOR.ERROR("Docker не установлен! Пожалуйста, установите Docker.")
      );
    } else if (error.message.includes("docker compose version")) {
      console.error(
        COLOR.ERROR(
          "Docker Compose не установлен! Пожалуйста, установите Docker Compose."
        )
      );
    } else {
      console.error(COLOR.ERROR(error.message));
    }

    process.exit(1);
  }
};

export default checkRequirements;
