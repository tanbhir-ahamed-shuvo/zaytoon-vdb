#!/bin/bash
set -e

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Installing Node dependencies..."
npm install

echo "Building frontend assets..."
npm run build

echo "Generating app key..."
php artisan key:generate --force

echo "Clearing caches..."
php artisan config:clear
php artisan view:clear
php artisan cache:clear

echo "Running migrations..."
php artisan migrate --force

echo "Building complete!"
