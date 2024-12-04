FROM php:8.3-fpm-alpine

# mix
RUN apk add --no-cache \
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
    && docker-php-ext-install zip \
        exif \
        pdo_mysql \
        pdo_pgsql \
        pgsql
# intl
RUN apk add --no-cache \
    icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl
# gd
RUN apk add --no-cache \
    freetype-dev \
    jpeg-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install gd
# redis
RUN apk add --no-cache autoconf \
    && pecl install redis \
    && docker-php-ext-enable redis
# pcov
RUN pecl install pcov && docker-php-ext-enable pcov

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
RUN composer config --global process-timeout 3600
RUN composer global require "laravel/installer"

RUN mkdir -p /var/www/backend

WORKDIR /var/www/backend

COPY --chown=www-data:www-data backend/composer.* ./

RUN composer install --no-scripts

EXPOSE 9000

CMD ["php", "-S", "0.0.0.0:9000"]