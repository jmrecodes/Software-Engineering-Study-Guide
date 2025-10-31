#!/usr/bin/env bash
set -e

cd /var/www/html || exit 1

# If composer.json exists and vendor folders is missing, install dependencies
if [ -f composer.json ] && [ ! -d vendor ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Generate app key if artisan exists and .env is present
if [ -f artisan ]; then
  if [ -f .env ] && [ ! -f .env.APP_KEY_GENERATED ]; then
    php artisan key:generate --ansi || true
    touch .env.APP_KEY_GENERATED
  fi
fi

# Exec provided command
exec "$@"
