#!/bin/sh
set -e

# Ensure dirs exist
mkdir -p /var/www/storage /var/www/bootstrap/cache

# Initialize storage directory if empty
if [ ! "$(ls -A /var/www/storage 2>/dev/null)" ]; then
  echo "Initializing storage directory..."
  cp -R /var/www/storage-init/. /var/www/storage
fi

# Remove storage-init directory (optional cleanup)
rm -rf /var/www/storage-init || true

# Fix permissions only for writable dirs
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

# IMPORTANT:
# - Do NOT run migrations here
# - Do NOT cache configs/routes here
# These should be executed as part of deployment steps.

exec "$@"
