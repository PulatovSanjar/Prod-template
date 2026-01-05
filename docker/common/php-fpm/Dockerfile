# syntax=docker/dockerfile:1.4

############################################
# BUILDER STAGE
############################################
FROM php:8.4-fpm-alpine AS builder

# Composer arguments
ARG COMPOSER_INSTALL_ARGS="--no-dev --optimize-autoloader --no-interaction"
ARG COMPOSER_DUMP_ARGS="--optimize --no-interaction"

# System dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    postgresql-dev \
    linux-headers \
    $PHPIZE_DEPS

# PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install dependencies (cached layer)
COPY composer.json composer.lock ./
RUN composer install ${COMPOSER_INSTALL_ARGS} --no-scripts --no-autoloader

# Copy application
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload ${COMPOSER_DUMP_ARGS}

############################################
# PRODUCTION STAGE
############################################
FROM php:8.4-fpm-alpine AS production

# Runtime dependencies only
RUN apk add --no-cache \
    libpng \
    libxml2 \
    libzip \
    oniguruma \
    icu-libs \
    postgresql-libs

# Copy PHP extensions & config
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.memory_consumption=256" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.max_accelerated_files=20000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

WORKDIR /var/www/html

# Copy app from builder
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]

############################################
# DEVELOPMENT STAGE
############################################
FROM builder AS development

# Xdebug (optional)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug || true

# PHP dev config
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
    && echo "error_reporting=E_ALL" >> "$PHP_INI_DIR/php.ini" \
    && echo "display_errors=On" >> "$PHP_INI_DIR/php.ini" \
    && echo "xdebug.mode=debug,coverage" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo "xdebug.client_host=host.docker.internal" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini" \
    && echo "xdebug.start_with_request=yes" >> "$PHP_INI_DIR/conf.d/docker-php-ext-xdebug.ini"

WORKDIR /var/www/html

# Dev dependencies
RUN composer install --no-interaction

# Writable dirs
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
