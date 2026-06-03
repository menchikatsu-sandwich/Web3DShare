#!/usr/bin/env sh
set -eu

php artisan config:clear

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

php artisan config:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
