#!/bin/sh
set -e

echo "=== [PIOS Entrypoint] Starting Container Bootstrap ==="

cd /var/www/html

# Avoid git dubious ownership warning in mounted workspace
git config --global --add safe.directory /var/www/html 2>/dev/null || true

# 1. Initialize .env if missing
if [ ! -f ".env" ]; then
    if [ -f ".env.docker" ]; then
        echo "[PIOS Entrypoint] .env not found. Copying .env.docker -> .env"
        cp .env.docker .env
    elif [ -f ".env.example" ]; then
        echo "[PIOS Entrypoint] .env not found. Copying .env.example -> .env"
        cp .env.example .env
    fi
fi

# 2. Install composer dependencies if missing
if [ ! -f "vendor/autoload.php" ]; then
    echo "[PIOS Entrypoint] vendor/autoload.php not found. Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Generate APP_KEY if empty
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    echo "[PIOS Entrypoint] APP_KEY is empty. Generating new key..."
    php artisan key:generate --force
fi

# 4. Ensure storage directories and permissions exist
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache \
         public/uploads

chown -R www-data:www-data storage bootstrap/cache public/uploads 2>/dev/null || true
chmod -R 775 storage bootstrap/cache public/uploads 2>/dev/null || true

# 5. Link storage
php artisan storage:link --force 2>/dev/null || true

# 6. Wait for Database readiness
DB_HOST="${DB_HOST_S_ADMIN:-${DB_HOST:-db}}"
DB_PORT="${DB_PORT_S_ADMIN:-${DB_PORT:-3306}}"
DB_USER="${DB_USERNAME_S_ADMIN:-${DB_USERNAME:-pios}}"
DB_PASS="${DB_PASSWORD_S_ADMIN:-${DB_PASSWORD:-secret}}"
DB_NAME="${DB_DATABASE_S_ADMIN:-${DB_DATABASE:-pios}}"

echo "[PIOS Entrypoint] Waiting for MySQL database at ${DB_HOST}:${DB_PORT}..."
RETRIES=30
until mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" -e "SELECT 1;" >/dev/null 2>&1 || [ $RETRIES -eq 0 ]; do
    echo "[PIOS Entrypoint] MySQL not ready yet. Retrying in 2 seconds... ($RETRIES left)"
    sleep 2
    RETRIES=$((RETRIES - 1))
done

if [ $RETRIES -eq 0 ]; then
    echo "[PIOS Entrypoint] WARNING: Could not connect to MySQL after timeout. Proceeding anyway..."
else
    echo "[PIOS Entrypoint] MySQL connection successful!"

    # 7. Dual-Mode DB detection
    TABLE_COUNT=$(mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" -N -s -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}' AND table_name='users';" 2>/dev/null || echo "0")

    if [ "${TABLE_COUNT}" -gt 0 ]; then
        echo "[PIOS Entrypoint] Existing 'users' table detected in '${DB_NAME}'. Skipping initial seeding to preserve data."
        php artisan migrate --force || echo "[PIOS Entrypoint] Notice: some migrations reported notices."
    else
        echo "[PIOS Entrypoint] Fresh database detected in '${DB_NAME}'. Running migrations and foundational seeders..."
        php artisan migrate --force || echo "[PIOS Entrypoint] Notice: some migrations reported notices."
        php artisan db:seed --force || echo "[PIOS Entrypoint] Notice: some seeders reported notices."
    fi
fi

echo "=== [PIOS Entrypoint] Bootstrap complete. Executing command: $@ ==="
exec "$@"
