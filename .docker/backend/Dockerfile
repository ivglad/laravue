# Этап сборки композера
FROM composer:2.7 as composer

WORKDIR /app

COPY backend/composer.* ./

# Устанавливаем зависимости для composer
RUN apk add --no-cache zip libzip-dev
RUN docker-php-ext-install zip

# Установка зависимостей без скриптов
RUN composer install --no-scripts --no-autoloader --no-dev

# Основной образ
FROM php:8.4-fpm-alpine as backend

ARG UID=1000
ARG GID=1000
ENV UID=${UID}
ENV GID=${GID}

# Установка всех необходимых зависимостей в одном слое
RUN apk add --no-cache \
    bash \
    build-base \
    mysql-client \
    curl \
    gnupg \
    procps \
    nano \
    git \
    zip \
    wget \
    unzip \
    libzip-dev \
    libpq-dev \
    icu-dev \
    freetype-dev \
    jpeg-dev \
    libpng-dev \
    autoconf \
    # Установка PHP расширений
    && docker-php-ext-install -j$(nproc) \
        zip \
        exif \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && pecl install pcov \
    && docker-php-ext-enable pcov \
    # Установка Composer
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    # Установка Laravel Installer
    && mkdir -p /composer \
    && composer config --global process-timeout 3600 \
    && composer global require "laravel/installer" \
    # Очистка кеша
    && rm -rf /tmp/* /var/cache/apk/*

# Настройка переменных окружения
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# Создание пользователя docker для работы приложения
RUN addgroup -g ${GID} --system docker && \
    adduser -G docker --system -D -s /bin/sh -u ${UID} docker && \
    mkdir -p /var/www/backend && \
    chown -R docker:docker /var/www/backend

# Настройка рабочей директории
WORKDIR /var/www/backend

# Копирование composer файлов
COPY --chown=docker:docker backend/composer.* ./

# Установка зависимостей
RUN composer install --no-scripts --no-autoloader --no-dev

# Копирование кода приложения
COPY --chown=docker:docker backend ./

# Оптимизация автозагрузчика
RUN composer dump-autoload --optimize

# Переключение на пользователя docker
USER docker

# Добавление проверки работоспособности
HEALTHCHECK --interval=10s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:9000 || exit 1

EXPOSE 9000

CMD ["php-fpm"]