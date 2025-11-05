# --- stage: vendor (только зависимости, без scripts) ---
FROM composer:2.7 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Отключаем скрипты, чтобы не вызывался artisan
RUN composer install --no-dev --no-interaction --no-progress --prefer-dist --no-scripts

# --- stage: app (php-fpm) ---
FROM php:8.3-fpm-alpine
# системные зависимости и расширения PHP
RUN apk add --no-cache bash git curl icu-dev libzip-dev oniguruma-dev libpng-dev libjpeg-turbo-dev freetype-dev mysql-client \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo pdo_mysql intl zip gd opcache

# таймзона (опционально)
ENV TZ=Asia/Tashkent
RUN apk add --no-cache tzdata && cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /var/www/html

# копируем код и vendor из предыдущей стадии
COPY . /var/www/html
COPY --from=vendor /app/vendor /var/www/html/vendor

# php.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# права
RUN chown -R www-data:www-data storage bootstrap/cache \
    && find storage -type d -exec chmod 775 {} \; \
    && find storage -type f -exec chmod 664 {} \; \
    && chmod -R 775 bootstrap/cache

# На этапе сборки НЕ обязателен прогон artisan (env ещё может быть не подхвачен).
# По желанию можно оптимизировать автолоад:
# RUN php -d detect_unicode=0 /usr/local/bin/php -r "echo 'ok';" && composer dump-autoload -o || true

HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD php -v || exit 1

CMD ["php-fpm", "-F"]
