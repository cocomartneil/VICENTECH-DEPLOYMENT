#!/bin/bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies
npm install

# Build frontend assets
npm run build

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache