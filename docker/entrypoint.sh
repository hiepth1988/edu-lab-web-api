#!/bin/bash
set -e

if [ "$RUN_MIGRATIONS" = "true" ]; then
  php artisan migrate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
