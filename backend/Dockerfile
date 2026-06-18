# Base image
FROM php:8.4-fpm-alpine

# Install runtime deps, build deps, then compile PHP extensions and cleanup
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        libzip-dev \
        postgresql-dev \
        zlib-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
    && apk add --no-cache \
        git \
        unzip \
        curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \ 
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html