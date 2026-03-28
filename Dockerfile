FROM php:8.3-cli-alpine AS base

WORKDIR /var/www/html

COPY --from=ghcr.io/mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
    bash \
    curl \
    git \
    sqlite \
    sqlite-dev

RUN install-php-extensions \
    bcmath \
    dom \
    gd \
    intl \
    mbstring \
    pdo_pgsql \
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

CMD ["sh", "-c", "mkdir -p database storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache && if [ \"${DB_CONNECTION:-sqlite}\" = \"sqlite\" ]; then touch database/database.sqlite; fi && chmod -R 775 storage bootstrap/cache && if [ -z \"$APP_URL\" ] || ! echo \"$APP_URL\" | grep -Eq '^https?://[A-Za-z0-9.-]+(:[0-9]+)?$'; then export APP_URL=https://${RENDER_EXTERNAL_HOSTNAME:-localhost}; fi && php artisan package:discover --ansi && (php artisan storage:link || true) && php artisan migrate --force && php -S 0.0.0.0:${PORT:-10000} -t public public/index.php"]
