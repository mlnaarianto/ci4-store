FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev

RUN docker-php-ext-install pdo pdo_mysql mysqli zip mbstring exif pcntl bcmath gd intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 🔥 FIX PERMISSION
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# kasih ownership ke project
RUN chown -R www-data:www-data /var/www

# pakai user non-root
USER www-data