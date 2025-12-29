# ===============================
# Stage 1 — builder
# ===============================
FROM php:8.4-cli AS builder

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libicu-dev \
    && docker-php-ext-install intl zip bcmath pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# 👉 КРИТИЧНО: копируем ВЕСЬ проект
COPY . .

# 👉 теперь composer видит helpers.php
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# ===============================
# Stage 2 — production php-fpm
# ===============================
FROM php:8.4-fpm AS production

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev \
    && docker-php-ext-install intl zip bcmath pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=builder /var/www /var/www

RUN chown -R www-data:www-data storage bootstrap/cache

USER www-data

CMD ["php-fpm"]

# ===============================
# Stage 3 — dev (xdebug optional)
# ===============================
FROM production AS development
USER root
RUN pecl install xdebug && docker-php-ext-enable xdebug
USER www-data
