FROM php:8.1-fpm-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
        unzip git libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libxml2-dev libonig-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql bcmath gd zip intl soap opcache exif \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/conf.d/app.ini

WORKDIR /var/www/html
