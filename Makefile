.ONESHELL: ;
.NOTPARALLEL: ;
default: help;

# Переменные для повторяющихся команд
DOCKER_COMPOSE = docker compose
DOCKER_PROJECT = $(shell grep DOCKER_PROJECT_NAME .env | cut -d '=' -f2 2>/dev/null || echo "laravue")

# Проверка наличия необходимых программ
DOCKER := $(shell command -v docker 2> /dev/null)
DOCKER_COMPOSE_CMD := $(shell command -v docker-compose 2> /dev/null || command -v docker 2> /dev/null)

# Цвета для вывода
COLOR_RESET = \033[0m
COLOR_INFO = \033[36m
COLOR_SUCCESS = \033[32m
COLOR_WARNING = \033[33m
COLOR_ERROR = \033[31m

# Определение списков доступных команд
define FRONTEND_COMMANDS
  term    - Открыть консоль фронтенда
  logs    - Посмотреть логи фронтенда
  dev     - Запуск фронтенда в режиме разработки
  build   - Сборка фронтенда
endef
export FRONTEND_COMMANDS

define BACKEND_COMMANDS
  term    - Открыть консоль бэкенда
  logs    - Посмотреть логи бэкенда
  clear   - Очистка кэша приложения
  routes  - Отображение маршрутов приложения
endef
export BACKEND_COMMANDS

define DB_COMMANDS
  migrate - Миграция базы данных
  seed    - Заполнение базы тестовыми данными
  reset   - Сброс базы данных и миграция
  fresh   - Пересоздание таблиц с миграцией
  dump    - Создание дампа базы данных (DB_TYPE=mysql|postgres DUMP_PATH=./path)
endef
export DB_COMMANDS

# Получение аргументов командной строки
MAKECMDGOALS_LIST = $(MAKECMDGOALS)
ifeq (d-build,$(firstword $(MAKECMDGOALS)))
  # Для d-build берем первый аргумент после d-build как имя сервиса
  BUILD_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # Убираем эти цели из обработки, чтобы избежать ошибок "No rule to make target"
  ifneq ($(BUILD_ARGS),)
    $(eval $(BUILD_ARGS):;@:)
  endif
endif

# Обработка аргументов для команд frontend, backend и db
ifeq (frontend,$(firstword $(MAKECMDGOALS)))
  # Для frontend берем первый аргумент после frontend как команду
  FRONTEND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # Убираем эти цели из обработки
  ifneq ($(FRONTEND_ARGS),)
    $(eval $(FRONTEND_ARGS):;@:)
  endif
endif

ifeq (backend,$(firstword $(MAKECMDGOALS)))
  # Для backend берем первый аргумент после backend как команду
  BACKEND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # Убираем эти цели из обработки
  ifneq ($(BACKEND_ARGS),)
    $(eval $(BACKEND_ARGS):;@:)
  endif
endif

ifeq (db,$(firstword $(MAKECMDGOALS)))
  # Для db берем первый аргумент после db как команду
  DB_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  # Убираем эти цели из обработки
  ifneq ($(DB_ARGS),)
    $(eval $(DB_ARGS):;@:)
  endif
endif

# Обработчик для любых неизвестных аргументов - просто вернуть успешный код
%:
	@:

##############################################################################
# БАЗОВЫЕ КОМАНДЫ
##############################################################################

.PHONY: help
help: ## Информация о доступных командах
	@egrep -h '\s##\s' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(COLOR_INFO)%-20s$(COLOR_RESET) %s\n", $$1, $$2}'
	@echo ""
	@echo "$(COLOR_INFO)Доступные подкоманды:$(COLOR_RESET)"
	@echo ""
	@echo "$(COLOR_INFO)frontend [команда]:$(COLOR_RESET)"
	@echo "$$FRONTEND_COMMANDS"
	@echo ""
	@echo "$(COLOR_INFO)backend [команда]:$(COLOR_RESET)"
	@echo "$$BACKEND_COMMANDS"
	@echo ""
	@echo "$(COLOR_INFO)db [команда]:$(COLOR_RESET)"
	@echo "$$DB_COMMANDS"

.PHONY: check-requirements
check-requirements: ## Проверка наличия необходимых программ
ifndef DOCKER
	@echo "$(COLOR_ERROR)Docker не установлен! Пожалуйста, установите Docker.$(COLOR_RESET)"
	@exit 1
endif
ifndef DOCKER_COMPOSE_CMD
	@echo "$(COLOR_ERROR)Docker Compose не установлен! Пожалуйста, установите Docker Compose.$(COLOR_RESET)"
	@exit 1
endif

.PHONY: env
env: ## Установка переменных окружения
	@echo "Установка переменных окружения..."
	cp -n .env.example .env || echo ".env уже существует"
	cp -n backend/.env.example backend/.env || echo "backend/.env уже существует"
	cp -n frontend/.env.example frontend/.env || echo "frontend/.env уже существует"

.PHONY: install
install: env d-build db-migrate db-seed ## Полная установка проекта (Docker)
	@echo "$(COLOR_SUCCESS)Проект установлен и готов к использованию!$(COLOR_RESET)"

##############################################################################
# DOCKER
##############################################################################

.PHONY: d-build
d-build: check-requirements ## Сборка проекта или указанного сервиса, использование: make d-build [сервис]
	@if [ -n "$(BUILD_ARGS)" ]; then \
		echo "Сборка сервиса $(BUILD_ARGS)..."; \
		$(DOCKER_COMPOSE) build $(BUILD_ARGS); \
	else \
		echo "Сборка всех сервисов..."; \
		$(DOCKER_COMPOSE) build; \
	fi

.PHONY: d-init
d-init: ## Иницциализация проекта
	@echo "Иницциализация проекта..."
	make env
	make d-build

.PHONY: d-up
d-up: check-requirements ## Создание и запуск контейнеров
	@echo "Создание и запуск контейнеров..."
	$(DOCKER_COMPOSE) up -d

.PHONY: d-down
d-down: check-requirements ## Остановка и удаление контейнеров
	@echo "Остановка и удаление контейнеров..."
	$(DOCKER_COMPOSE) down

.PHONY: d-start
d-start: check-requirements ## Запуск контейнеров
	@echo "Запуск контейнеров..."
	$(DOCKER_COMPOSE) start

.PHONY: d-stop
d-stop: check-requirements ## Остановка контейнеров
	@echo "Остановка контейнеров..."
	$(DOCKER_COMPOSE) stop

.PHONY: d-restart
d-restart: check-requirements ## Перезапуск контейнеров
	@echo "Перезапуск контейнеров..."
	$(DOCKER_COMPOSE) restart

.PHONY: d-status
d-status: check-requirements ## Проверка статуса контейнеров
	@echo "Проверка статуса контейнеров..."
	$(DOCKER_COMPOSE) ps

##############################################################################
# ФРОНТЕНД
##############################################################################

.PHONY: frontend
frontend: check-requirements ## Выполнение команд для фронтенда, использование: make frontend [команда]
	@if [ -z "$(FRONTEND_ARGS)" ]; then \
		echo "$(COLOR_ERROR)Требуется указать команду. Использование: make frontend [команда]$(COLOR_RESET)"; \
		echo "Доступные команды:"; \
		echo "$$FRONTEND_COMMANDS"; \
		exit 1; \
	elif [ "$(FRONTEND_ARGS)" = "term" ]; then \
		echo "Открытие консоли фронтенда..."; \
		$(DOCKER_COMPOSE) exec frontend bash; \
	elif [ "$(FRONTEND_ARGS)" = "logs" ]; then \
		echo "Логи фронтенда..."; \
		$(DOCKER_COMPOSE) logs -f frontend; \
	elif [ "$(FRONTEND_ARGS)" = "dev" ]; then \
		echo "Запуск фронтенда в режиме разработки..."; \
		$(DOCKER_COMPOSE) exec frontend npm run dev; \
	elif [ "$(FRONTEND_ARGS)" = "build" ]; then \
		echo "Сборка фронтенда..."; \
		$(DOCKER_COMPOSE) exec frontend npm run build; \
	else \
		echo "$(COLOR_ERROR)Неизвестная команда: $(FRONTEND_ARGS)$(COLOR_RESET)"; \
		echo "Доступные команды:"; \
		echo "$$FRONTEND_COMMANDS"; \
		exit 1; \
	fi

##############################################################################
# БЭКЕНД
##############################################################################

.PHONY: backend
backend: check-requirements ## Выполнение команд для бэкенда, использование: make backend [команда]
	@if [ -z "$(BACKEND_ARGS)" ]; then \
		echo "$(COLOR_ERROR)Требуется указать команду. Использование: make backend [команда]$(COLOR_RESET)"; \
		echo "Доступные команды:"; \
		echo "$$BACKEND_COMMANDS"; \
		exit 1; \
	elif [ "$(BACKEND_ARGS)" = "term" ]; then \
		echo "Открытие консоли бэкенда..."; \
		$(DOCKER_COMPOSE) exec backend bash; \
	elif [ "$(BACKEND_ARGS)" = "logs" ]; then \
		echo "Логи бэкенда..."; \
		$(DOCKER_COMPOSE) logs -f backend; \
	elif [ "$(BACKEND_ARGS)" = "routes" ]; then \
		echo "Отображение маршрутов приложения..."; \
		$(DOCKER_COMPOSE) exec backend php artisan route:list; \
	elif [ "$(BACKEND_ARGS)" = "clear" ]; then \
		echo "Очистка кэша приложения..."; \
		$(DOCKER_COMPOSE) exec backend php artisan cache:clear; \
		$(DOCKER_COMPOSE) exec backend php artisan config:clear; \
		$(DOCKER_COMPOSE) exec backend php artisan route:clear; \
		$(DOCKER_COMPOSE) exec backend php artisan view:clear; \
	else \
		echo "$(COLOR_ERROR)Неизвестная команда: $(BACKEND_ARGS)$(COLOR_RESET)"; \
		echo "Доступные команды:"; \
		echo "$$BACKEND_COMMANDS"; \
		exit 1; \
	fi

##############################################################################
# БАЗА ДАННЫХ
##############################################################################

.PHONY: db
db: check-requirements ## Выполнение команд для базы данных, использование: make db [команда]
	@if [ -z "$(DB_ARGS)" ]; then \
		echo "$(COLOR_ERROR)Требуется указать команду. Использование: make db [команда]$(COLOR_RESET)"; \
		echo "Доступные команды:"; \
		echo "$$DB_COMMANDS"; \
		exit 1; \
	elif [ "$(DB_ARGS)" = "migrate" ]; then \
		echo "Миграция базы данных..."; \
		$(DOCKER_COMPOSE) exec backend php artisan migrate; \
	elif [ "$(DB_ARGS)" = "seed" ]; then \
		echo "Заполнение базы тестовыми данными..."; \
		$(DOCKER_COMPOSE) exec backend php artisan db:seed; \
	elif [ "$(DB_ARGS)" = "reset" ]; then \
		echo "Сброс базы данных и миграция..."; \
		$(DOCKER_COMPOSE) exec backend php artisan migrate:reset; \
	elif [ "$(DB_ARGS)" = "fresh" ]; then \
		echo "Пересоздание таблиц с миграцией..."; \
		$(DOCKER_COMPOSE) exec backend php artisan migrate:fresh; \
	elif [ "$(DB_ARGS)" = "dump" ]; then \
		echo "Создание дампа базы данных..."; \
		DB_TYPE=$$(echo $$DB_TYPE | tr '[:upper:]' '[:lower:]' || echo "mysql"); \
		DUMP_PATH=$$(echo $$DUMP_PATH || echo "."); \
		if [ "$$DB_TYPE" = "mysql" ]; then \
			echo "Создание дампа MySQL в $$DUMP_PATH..."; \
			$(DOCKER_COMPOSE) exec db mysqldump -u$${MYSQL_USER:-root} -p$${MYSQL_PASSWORD:-password} $${MYSQL_DATABASE:-laravel} > "$$DUMP_PATH/mysql_dump_$$(date +%Y%m%d_%H%M%S).sql"; \
		elif [ "$$DB_TYPE" = "postgres" ]; then \
			echo "Создание дампа PostgreSQL в $$DUMP_PATH..."; \
			$(DOCKER_COMPOSE) exec db pg_dump -U $${POSTGRES_USER:-postgres} $${POSTGRES_DB:-laravel} > "$$DUMP_PATH/postgres_dump_$$(date +%Y%m%d_%H%M%S).sql"; \
		else \
			echo "$(COLOR_ERROR)Неизвестный тип базы данных: $$DB_TYPE. Используйте mysql или postgres.$(COLOR_RESET)"; \
			exit 1; \
		fi; \
		echo "Дамп базы данных создан в $$DUMP_PATH"; \
	else \
		echo "$(COLOR_ERROR)Неизвестная команда: $(DB_ARGS)$(COLOR_RESET)"; \
		echo "Доступные команды:"; \
		echo "$$DB_COMMANDS"; \
		exit 1; \
	fi

##############################################################################
# ОЧИСТКА И ОБСЛУЖИВАНИЕ
##############################################################################

.PHONY: clean
clean: ## Очистка временных файлов и артефактов
	@echo "Очистка временных файлов..."
	find . -name "*.tmp" -type f -delete
	find . -name "*.log" -type f -delete

.PHONY: distclean
distclean: clean ## Удаление всех файлов, созданных при конфигурации или сборке
	@echo "Удаление всех файлов, созданных при конфигурации или сборке..."
	find . -name "*.cache" -type f -delete
	find frontend -name "node_modules" -type d -exec rm -rf {} +
	find backend -name "vendor" -type d -exec rm -rf {} +

.PHONY: d-prune
d-prune: check-requirements ## Очистка неиспользуемых Docker ресурсов
	@echo "Очистка неиспользуемых Docker ресурсов..."
	docker system prune -af --volumes