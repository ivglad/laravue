# Этап сборки
FROM node:22-alpine AS builder

WORKDIR /build

# Обновляем npm до последней версии
RUN npm install -g npm@latest

# Копируем только файлы для установки зависимостей
COPY frontend/package*.json ./

# Устанавливаем зависимости
RUN npm install --no-audit --no-fund && npm cache clean --force

# Финальный этап
FROM node:22-alpine

# Устанавливаем tini для правильной обработки сигналов и curl для healthcheck
RUN apk add --no-cache --virtual .build-deps tini curl

# Обновляем npm до последней версии
RUN npm install -g npm@latest

# Создаем директорию приложения и настраиваем права
WORKDIR /var/www/frontend
RUN mkdir -p /var/www/frontend && \
    chown -R node:node /var/www/frontend

# Копируем установленные модули из этапа сборки
COPY --from=builder --chown=node:node /build/node_modules ./node_modules
COPY --chown=node:node frontend/package*.json ./

# Используем непривилегированного пользователя
USER node

# Добавление проверки работоспособности
HEALTHCHECK --interval=10s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:3000 || exit 1

# Используем tini в качестве точки входа для правильной обработки сигналов
ENTRYPOINT ["/sbin/tini", "--"]

# Команда запуска
CMD ["npm", "run", "dev"]