# syntax=docker/dockerfile:1.4

# ============================================
# BUILDER STAGE - Base for all other stages
# ============================================
FROM php:8.4-fpm-alpine AS builder

# Install system dependencies
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

# Install PHP extensions
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

# Install Redis extension via PECL
RUN pecl install redis-6.1.0 && \
    docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for layer caching)
COPY composer.json composer.lock ./

# Install production dependencies only (no autoload yet)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Copy application source
COPY . .

# Generate optimized autoloader
RUN composer dump-autoload \
    --no-dev \
    --optimize \
    --classmap-authoritative

# ============================================
# PRODUCTION STAGE - Runtime only
# ============================================
FROM php:8.4-fpm-alpine AS production

# Install runtime dependencies only
RUN apk add --no-cache \
    libpng \
    libxml2 \
    libzip \
    oniguruma \
    icu-libs \
    postgresql-libs

# Copy PHP extensions from builder
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && \
    { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=256'; \
        echo 'opcache.interned_strings_buffer=16'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.validate_timestamps=0'; \
        echo 'opcache.save_comments=1'; \
        echo 'opcache.fast_shutdown=1'; \
    } > "$PHP_INI_DIR/conf.d/opcache.ini"

WORKDIR /var/www/html

# Copy application from builder (with vendor and optimized autoloader)
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache && \
    chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]

# ============================================
# CI STAGE - Optimized for testing
# ============================================
FROM builder AS ci

WORKDIR /var/www/html

# The builder stage already has production dependencies
# We only need to ADD dev dependencies, not reinstall everything

# Install dev dependencies on top of existing vendor
RUN composer install \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

# Copy application source
COPY --chown=www-data:www-data . .

# Run Composer scripts and generate optimized autoloader
RUN composer dump-autoload --optimize

# Create necessary directories for tests with proper permissions
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

# Configure PHP for CI
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    { \
        echo 'memory_limit=1G'; \
        echo 'error_reporting=E_ALL'; \
        echo 'display_errors=On'; \
        echo 'log_errors=On'; \
        echo 'max_execution_time=300'; \
    } > "$PHP_INI_DIR/conf.d/ci.ini"

# Create empty .env for cases where it's needed
RUN touch .env.testing && \
    echo "APP_ENV=testing" > .env.testing && \
    echo "APP_KEY=" >> .env.testing

USER root

CMD ["php", "-v"]
# ============================================
# DEVELOPMENT STAGE - Local development
# ============================================
FROM builder AS development

# Install Xdebug for local debugging
RUN pecl install xdebug-3.4.0 && \
    docker-php-ext-enable xdebug

# Configure PHP for development
RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    { \
        echo 'memory_limit=512M'; \
        echo 'error_reporting=E_ALL'; \
        echo 'display_errors=On'; \
        echo 'log_errors=On'; \
    } > "$PHP_INI_DIR/conf.d/dev.ini" && \
    { \
        echo 'xdebug.mode=debug,coverage'; \
        echo 'xdebug.client_host=host.docker.internal'; \
        echo 'xdebug.client_port=9003'; \
        echo 'xdebug.start_with_request=yes'; \
        echo 'xdebug.log=/tmp/xdebug.log'; \
    } > "$PHP_INI_DIR/conf.d/xdebug.ini"

WORKDIR /var/www/html

# Install dev dependencies
RUN composer install \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Create writable directories
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache && \
    chmod -R 777 storage bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
