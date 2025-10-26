#!/bin/bash
set -e

echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed, but continuing..."

echo "Clearing and caching configuration..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
