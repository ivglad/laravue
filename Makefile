.ONESHELL: ;
.NOTPARALLEL: ;
default: help;


.PHONY: help
help: ## Информация о доступных командах
	@egrep -h '\s##\s' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'


.PHONY: env
env: ## Установка переменных окружения
	@echo "Установка переменных окружения..."
	cp .env.example .env
	cp backend/.env.example backend/.env
	cp frontend/.env.example frontend/.env

.PHONY: build
build: ## Сборка проекта
	@echo "Сборка проекта..."
	docker compose build

.PHONY: init
init: ## Иницциализация проекта
	@echo "Иницциализация проекта..."
	make env
	make build


.PHONY: up
up: ## Создание и запуск контейнеров
	@echo "Создание и запуск контейнеров..."
	docker compose up -d

.PHONY: down
down: ## Остановка и удаление контейнеров
	@echo "Остановка и удаление контейнеров..."
	docker compose down

.PHONY: start
start: ## Запуск контейнеров
	@echo "Запуск контейнеров..."
	docker compose start

.PHONY: stop
stop: ## Остановка контейнеров
	@echo "Остановка контейнеров..."
	docker compose stop

.PHONY: restart
restart: ## Перезапуск контейнеров
	@echo "Остановка контейнеров..."
	docker compose restart

.PHONY: console-frontend
console-frontend: ## Открыть консоль фронтенда
	@echo "Открытие консоли фронтенда..."
	docker compose exec frontend bash

.PHONY: console-backend
console-backend: ## Открыть консоль бэкенда
	@echo "Открытие консоли бэкенда..."
	docker compose exec backend bash

.PHONY: logs-frontend
logs-frontend: ## Посмотреть логи фронтенда
	@echo "Логи фронтенда..."
	docker compose logs -f frontend

.PHONY: logs-backend
logs-backend: ## Посмотреть логи бэкенда
	@echo "Логи бэкенда..."
	docker compose logs -f backend