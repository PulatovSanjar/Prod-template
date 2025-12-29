FROM php:8.4-cli

ARG UID=1000
ARG GID=1000
ARG NODE_MAJOR=20

ENV TZ=Europe/Riga

# Xdebug args (as in compose)
ARG XDEBUG_ENABLED=false
ARG XDEBUG_MODE
ARG XDEBUG_HOST
ARG XDEBUG_IDE_KEY
ARG XDEBUG_LOG
ARG XDEBUG_LOG_LEVEL

RUN apt-get update && apt-get install -y --no-install-recommends \
    tzdata \
    curl unzip git ca-certificates gnupg \
    libonig-dev libssl-dev libxml2-dev libcurl4-openssl-dev libicu-dev libzip-dev \
    $PHPIZE_DEPS \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && docker-php-ext-install -j$(nproc) \
      pdo_mysql opcache intl zip bcmath soap \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && if [ "${XDEBUG_ENABLED}" = "true" ]; then \
        pecl install xdebug && docker-php-ext-enable xdebug && \
        echo "xdebug.mode=${XDEBUG_MODE}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.client_host=${XDEBUG_HOST}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.idekey=${XDEBUG_IDE_KEY}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.log=${XDEBUG_LOG}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.log_level=${XDEBUG_LOG_LEVEL}" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
        echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini ; \
      fi \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    # Install Node.js (stable, no NVM)
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_${NODE_MAJOR}.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y --no-install-recommends nodejs \
    && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Create user/group matching host
RUN groupadd -g ${GID} www || true \
    && useradd -m -u ${UID} -g ${GID} -s /bin/bash www || true

USER www
WORKDIR /var/www

ENTRYPOINT []
CMD ["bash"]
