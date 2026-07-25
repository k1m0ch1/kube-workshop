#!/bin/sh
set -e

echo "==> Fixing storage permissions..."
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Running database migrations..."
php artisan migrate --force --no-interaction

echo "==> Linking storage..."
php artisan storage:link --force

echo "==> Starting PHP-FPM..."
exec "$@"
