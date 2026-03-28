FROM php:8.3-cli-alpine AS base

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    git \
    sqlite \
    sqlite-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    bcmath \
    dom \
    gd \
    intl \
    mbstring \
    pdo \
    pdo_sqlite \
    simplexml \
    xml \
    xmlreader \
    xmlwriter \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM base AS app
COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts
COPY --from=frontend /app/public/build ./public/build

EXPOSE 10000

CMD ["sh", "-c", "mkdir -p database storage/framework/{cache,sessions,views} storage/logs && touch database/database.sqlite && php artisan storage:link || true && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
