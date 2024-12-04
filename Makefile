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
	chmod -R 755 .docker/db/data
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









# .PHONY: install
# install: ## Локальная установка проекта
# 	@echo "Локальная установка проекта..."
# 	make build
# 	make up
# 	docker-compose exec -T laravel bash -c 'composer install'
# 	docker-compose exec -T laravel bash -c 'php artisan key:generate'
# 	docker-compose exec -T laravel bash -c 'php artisan storage:link'
# 	# docker-compose exec -T laravel bash -c 'php artisan optimize:clear'
# 	docker-compose exec -T laravel bash -c 'php artisan migrate:fresh --seed'
# 	docker-compose exec -T laravel bash -c 'php artisan queue:restart'

# .PHONY: update
# update: ## Обновить проект
# 	@echo "Обновление проекта..."
# 	make build
# 	make up
# 	docker-compose exec -T laravel bash -c 'composer install'
# 	docker-compose exec -T laravel bash -c 'composer dump-autoload'
# 	docker-compose exec -T laravel bash -c 'php artisan optimize:clear'
# 	docker-compose exec -T laravel bash -c 'php artisan migrate'
# 	docker-compose exec -T laravel bash -c 'php artisan queue:restart'

# .PHONY: build_prod
# build_prod: ## Деплой проекта
# 	@echo "Деплой проекта..."
# 	docker-compose -f docker-compose.yml -f docker-compose.production.yml build

# .PHONY: up_prod
# up_prod: ## Деплой проекта
# 	@echo "Запуск в проде..."
# 	docker-compose -f docker-compose.yml -f docker-compose.production.yml up -d

# .PHONY: install_prod
# install_prod: ## Локальная установка проекта
# 	@echo "Установка проекта в продакшене..."
# 	make build_prod
# 	make up_prod
# 	docker-compose exec -T laravel bash -c 'composer install'
# 	docker-compose exec -T laravel bash -c 'php artisan key:generate'
# 	docker-compose exec -T laravel bash -c 'php artisan storage:link'
# 	# docker-compose exec -T laravel bash -c 'php artisan optimize:clear'
# 	# docker-compose exec -T laravel bash -c 'php artisan config:cache'
# 	# docker-compose exec -T laravel bash -c 'php artisan event:cache'
# 	# docker-compose exec -T laravel bash -c 'php artisan route:cache'
# 	docker-compose exec -T laravel bash -c 'php artisan migrate:fresh --seed'
# 	docker-compose exec -T laravel bash -c 'php artisan queue:restart'

# .PHONY: update_prod
# update_prod: ## Обновить проект
# 	@echo "Обновление проекта..."
# 	make build_prod
# 	make up_prod
# 	docker-compose exec -T laravel bash -c 'composer install --no-interaction --no-dev --optimize-autoloader'
# 	docker-compose exec -T laravel bash -c 'php artisan optimize:clear'
# 	docker-compose exec -T laravel bash -c 'php artisan config:cache'
# 	docker-compose exec -T laravel bash -c 'php artisan event:cache'
# 	docker-compose exec -T laravel bash -c 'php artisan route:cache'
# 	docker-compose exec -T laravel bash -c 'php artisan migrate --force'
# 	docker-compose exec -T laravel bash -c 'php artisan queue:restart'

# .PHONY: test
# test: ## Протестировать проект
# 	@echo "Тестирование проекта..."
# 	docker-compose run --rm laravel bash -c 'composer check'

# .PHONY: init
# init: ## Первичная установка проекта
# 	@echo "Первичная установка проекта..."
# 	make build
# 	make up
# 	make init_backend
# 	make init_frontend

# .PHONY: env
# env: ## Установка переменных окружения
# 	@echo "Установка переменных окружения..."
# 	cp .env.example .env
# 	cp backend/.env.example backend/.env
# 	cp frontend/.env.example frontend/.env

# .PHONY: serve
# serve: ## Запуск фронтенд приложения
# 	@echo "Запуск фронтенд приложения..."
# 	docker-compose exec -T laravel bash -c 'cd /frontend && npm install'
# 	docker-compose exec -T laravel bash -c 'cd /frontend && npm run dev'

# .PHONY: build_frontend
# build_frontend: ## Сборка фронтенд приложения
# 	@echo "Frontend build..."
# 	cd frontend && npm run build

# .PHONY: init_frontend
# init_frontend:
# 	@echo "Установка фронтенда..."
# 	docker-compose exec -T laravel bash -c 'cd /frontend && rm .gitignore && npm install vue@next'

# .PHONY: init_backend
# init_backend:
# 	@echo "Установка бэкенда..."
# 	docker-compose exec -T laravel bash -c 'rm .gitignore && composer create-project laravel/laravel .'

