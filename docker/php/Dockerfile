FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    postgresql-dev \
    && docker-php-ext-install intl pdo pdo_pgsql opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/symfony

COPY ./backend/composer.json ./backend/composer.lock ./
RUN composer install --no-scripts --no-autoloader

COPY ./backend ./
RUN composer dump-autoload --optimize
