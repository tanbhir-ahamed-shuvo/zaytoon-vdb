FROM php:8.3-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    git \
    nodejs \
    npm \
    sqlite \
    sqlite-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    libxml2-dev \
    && docker-php-ext-install \
    bcmath \
    intl \
    mbstring \
    pdo \
    pdo_sqlite \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && npm run build \
    && php artisan storage:link || true \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 10000

CMD ["sh", "-c", "mkdir -p database && touch database/database.sqlite && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
