#!/bin/bash
set -e

echo "Waiting for database to be ready..."
maxTries=60
while [ "$maxTries" -gt 0 ]; do
    if php artisan db:monitor > /dev/null 2>&1; then
        break
    fi
    maxTries=$((maxTries - 1))
    echo "Waiting for database connection... ($maxTries tries left)"
    sleep 1
done

if [ "$maxTries" -le 0 ]; then
    echo "Could not connect to database"
    exit 1
fi

echo "Refreshing migration state..."
php artisan migrate:reset --force || true

echo "Running fresh migrations..."
echo "Listing migration files present in the container (for debug):"
ls -la database/migrations || true

php artisan migrate --force --seed

echo "Clearing and caching configuration..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=8000
