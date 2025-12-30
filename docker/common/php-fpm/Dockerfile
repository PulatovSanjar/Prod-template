# ===============================
# Stage 1 — base
# ===============================
FROM php:8.4-cli AS base

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libicu-dev \
    && docker-php-ext-install intl zip bcmath pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

COPY . .

# ===============================
# Stage 2 — prod deps (NO dev)
# ===============================
FROM base AS prod-deps
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# ===============================
# Stage 3 — ci deps (WITH dev)
# ===============================
FROM base AS ci-deps
RUN composer install \
    --prefer-dist \
    --no-interaction

# ===============================
# Stage 4 — production image
# ===============================
FROM php:8.4-fpm AS production

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev \
    && docker-php-ext-install intl zip bcmath pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=prod-deps /var/www /var/www

RUN chown -R www-data:www-data storage bootstrap/cache
USER www-data

CMD ["php-fpm"]

# ===============================
# Stage 5 — CI image
# ===============================
FROM php:8.4-cli AS ci

WORKDIR /var/www
COPY --from=ci-deps /var/www /var/www

CMD ["php", "-v"]
