# LaraVue CLI

CLI утилита для управления проектом LaraVue.

## Описание

LaraVue CLI - утилита командной строки, которая упрощает работу с проектом LaraVue.

## Особенности

- Модульная архитектура с разделением ответственности
- Интерактивные команды
- Асинхронное выполнение и обработка ошибок
- Все функции проекта доступны через единый интерфейс
- Поддержка сокращенной команды `lv` как альтернативы `laravue`

## Установка

### Локальная установка

1. Клонируйте репозиторий проекта:

   ```bash
   git clone [URL репозитория]
   cd laravue
   ```

2. Установите зависимости:

   ```bash
   npm install
   ```

3. Сделайте CLI утилиту исполняемой:

   ```bash
   chmod +x cli.js
   ```

4. Запускайте команды локально:
   ```bash
   ./cli.js [команда]
   # или используя сокращение
   ./lv [команда]
   ```

### Глобальная установка

1. Выполните глобальную установку из каталога проекта:

   ```bash
   npm install -g .
   ```

2. Теперь вы можете использовать команду `laravue` или её сокращение `lv` из любого места:
   ```bash
   laravue [команда]
   # или
   lv [команда]
   ```

## Использование

### Основные команды

```bash
# Показать справку
laravue --help
lv --help

# Установка проекта
laravue install
lv install

# Установка переменных окружения
laravue env
lv env
```

### Docker команды

```bash
# Сборка контейнеров
laravue docker build [сервис]
lv docker build [сервис]

# Запуск контейнеров
laravue docker up
lv docker up

# Остановка контейнеров
laravue docker down
lv docker down

# Просмотр статуса контейнеров
laravue docker status
lv docker status

# Очистка неиспользуемых Docker ресурсов
laravue docker prune
lv docker prune
```

### Фронтенд команды

```bash
# Открыть консоль фронтенда
laravue frontend term
lv frontend term

# Запуск в режиме разработки
laravue frontend dev
lv frontend dev

# Сборка фронтенда
laravue frontend build
lv frontend build
```

### Бэкенд команды

```bash
# Открыть консоль бэкенда
laravue backend term
lv backend term

# Очистка кэша
laravue backend clear
lv backend clear

# Просмотр маршрутов
laravue backend routes
lv backend routes
```

### База данных

```bash
# Выполнить миграции
laravue db migrate
lv db migrate

# Заполнить тестовыми данными
laravue db seed
lv db seed

# Сброс базы данных
laravue db reset
lv db reset

# Пересоздание таблиц
laravue db fresh
lv db fresh

# Создание дампа базы данных
laravue db dump
lv db dump
# или с параметрами
laravue db dump --type mysql --path ./backups
lv db dump --type mysql --path ./backups
laravue db dump -t postgres -p ./backups
lv db dump -t postgres -p ./backups
```

### Обслуживание

```bash
# Очистка временных файлов
laravue clean
lv clean

# Полная очистка
laravue distclean
lv distclean
```

## Сравнение с прямым использованием Make

Эта CLI утилита является независимой альтернативой использованию Makefile. Оба способа делают одно и то же, но CLI предлагает более удобный интерфейс.

| Действие         | Makefile             | CLI (laravue/lv)   |
| ---------------- | -------------------- | ------------------ |
| Сборка Docker    | `make d-build`       | `lv docker build`  |
| Запуск фронтенда | `make frontend dev`  | `lv frontend dev`  |
| Миграция БД      | `make db migrate`    | `lv db migrate`    |
| Очистка кэша     | `make backend clear` | `lv backend clear` |
| Дамп базы данных | `make db dump`       | `lv db dump`       |

## Особенности команды db dump

Команда для создания дампа базы данных поддерживает два типа баз данных и настраиваемый путь для сохранения:

- **Тип базы данных**: MySQL (по умолчанию) или PostgreSQL
- **Путь для сохранения**: Можно указать произвольный путь (по умолчанию текущий каталог)

### Примеры использования:

```bash
# Дамп MySQL (по умолчанию) в текущий каталог
laravue db dump
lv db dump

# Дамп MySQL в указанный каталог
laravue db dump --path ./backups
lv db dump --path ./backups
laravue db dump -p ./backups
lv db dump -p ./backups

# Дамп PostgreSQL в текущий каталог
laravue db dump --type postgres
lv db dump --type postgres
laravue db dump -t postgres
lv db dump -t postgres

# Дамп PostgreSQL в указанный каталог
laravue db dump --type postgres --path ./backups
lv db dump --type postgres --path ./backups
laravue db dump -t postgres -p ./backups
lv db dump -t postgres -p ./backups
```

При использовании Makefile:

```bash
# Дамп MySQL (по умолчанию) в текущий каталог
make db dump

# Дамп MySQL в указанный каталог
DUMP_PATH=./backups make db dump

# Дамп PostgreSQL в текущий каталог
DB_TYPE=postgres make db dump

# Дамп PostgreSQL в указанный каталог
DB_TYPE=postgres DUMP_PATH=./backups make db dump
```

## Архитектура CLI

CLI-утилита имеет модульную архитектуру, которая разделяет функциональность по группам команд и общим утилитам:

```
laravue/
├── cli/                    # Основная директория CLI
│   ├── index.js            # Точка входа для CLI
│   ├── config.js           # Конфигурационные параметры
│   ├── commands/           # Команды CLI
│   │   ├── core.js         # Основные команды (install, env)
│   │   ├── docker.js       # Команды для управления Docker
│   │   ├── frontend.js     # Команды для фронтенда
│   │   ├── backend.js      # Команды для бэкенда
│   │   ├── db.js           # Команды для работы с БД
│   │   └── maintenance.js  # Команды для обслуживания
│   └── utils/              # Утилиты
│       ├── colors.js       # Цветной вывод в консоль
│       ├── executor.js     # Выполнение команд
│       ├── requirements.js # Проверка требований
│       └── spinner.js      # Спиннеры для индикации выполнения
└── cli.js                  # Главная точка входа
```

### Преимущества модульной архитектуры

1. **Разделение ответственности** - каждый модуль отвечает за свою функциональность
2. **Лучшая поддерживаемость** - легче находить и исправлять ошибки
3. **Расширяемость** - простое добавление новых команд через создание/изменение модулей
4. **Повторное использование кода** - утилиты доступны для всех модулей
5. **Тестируемость** - модули можно тестировать отдельно

## Разработка и расширение

### Добавление новых команд

Для добавления новых команд нужно:

1. Выбрать существующий модуль команд в директории `cli/commands/` или создать новый
2. Добавить новую команду по шаблону:

```javascript
module
  .command("имя-команды")
  .description("Описание команды")
  .action(async () => {
    // Реализация команды
    try {
      console.log(COLOR.INFO("Выполнение команды..."));
      // Логика команды
    } catch (error) {
      console.error(COLOR.ERROR(error.message));
      process.exit(1);
    }
  });
```

3. Если был создан новый модуль, его нужно импортировать и зарегистрировать в `cli/index.js`

### Пример регистрации нового модуля

```javascript
// cli/index.js
import registerNewModule from "./commands/newmodule.js";

// Регистрируем команды
registerCoreCommands(program);
// ... другие модули
registerNewModule(program);
```
