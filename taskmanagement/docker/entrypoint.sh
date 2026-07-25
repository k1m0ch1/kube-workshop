#!/bin/sh
set -e

echo "==> Fixing storage permissions..."
mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Running database migrations..."
php artisan migrate --force --no-interaction

echo "==> Starting PHP-FPM..."
exec "$@"
