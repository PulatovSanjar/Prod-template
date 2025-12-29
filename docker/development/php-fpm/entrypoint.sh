#!/bin/sh
set -e

USER_ID=${UID:-1000}
GROUP_ID=${GID:-1000}

# Ensure writable dirs exist
mkdir -p /var/www/storage /var/www/bootstrap/cache

# Fix permissions only for writable dirs (not whole repo)
if [ "${FIX_PERMS:-false}" = "true" ]; then
  echo "Fixing permissions for storage and cache with UID=${USER_ID} and GID=${GROUP_ID}..."
  chown -R "${USER_ID}:${GROUP_ID}" /var/www/storage /var/www/bootstrap/cache || true
fi

# Do NOT clear caches on every start; run manually when needed.
exec "$@"
