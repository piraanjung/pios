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

    # 7. Dual-Mode DB detection & Auto-import from docs/database.sql
    TABLE_COUNT=$(mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" -N -s -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}' AND table_name='users';" 2>/dev/null || echo "0")

    if [ "${TABLE_COUNT}" -eq 0 ] && [ -f "/var/www/html/docs/database.sql" ]; then
        echo "[PIOS Entrypoint] Fresh/empty database detected and docs/database.sql found. Importing dump..."
        mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" << 'EOSQL'
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
EOSQL
        mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" < /var/www/html/docs/database.sql
        mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" << 'EOSQL'
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
EOSQL
        echo "[PIOS Entrypoint] Dump imported. Syncing migration records for existing schema..."
        mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" -e "
            INSERT IGNORE INTO migrations (migration, batch) VALUES
            ('2023_10_30_130210_create_user_merter_infos_table', 1),
            ('2025_07_28_061902_create_staff_table', 1),
            ('2025_09_03_084012_create_organizations_table', 1),
            ('2025_10_12_065735_create_machines_table', 1),
            ('2026_09_25_085045_add_audit_logs_to_inv_transactions_table', 1),
            ('2026_09_25_090011_create_inv_transaction_approvals_table', 1),
            ('2026_09_25_090554_create_approval_workflows_table', 1),
            ('2026_09_25_090555_create_approval_workflow_steps_table', 1),
            ('2026_09_25_103925_add_approvalworkflow_id_column_in_inv_categories_table', 1),
            ('2026_09_25_164846_create_inv_locations_table', 1),
            ('2026_09_25_164847_add_location_id_to_inv_item_details_table', 1),
            ('2026_09_25_171122_add_conversion_rate_to_inv_item_details_table', 1),
            ('2026_09_26_005147_create_suppliers_table', 1),
            ('2026_10_08_110038_create_kp_purchase_routes_table_and_kp_purchase_route_zones_table', 1),
            ('2026_10_09_001549_create_kp_settings_table', 1),
            ('2026_10_09_042443_add_modulename_column_in_inv_transaction_approvals_table', 1),
            ('2026_10_13_025520_create_welfare_members_table', 1),
            ('2026_10_15_135128_create_tabwater_stations_table', 1),
            ('2026_10_15_135129_create_tabwater_station_staffs_table', 1),
            ('2026_10_15_135130_create_tabwater_devices_table', 1),
            ('2026_10_15_135943_create_tabwater_device_logs_table', 1),
            ('2026_10_15_160030_create_tabwater_pipe_nodes_table', 1),
            ('2026_10_15_160058_create_tabwater_pipelines_table', 1);
        "
        echo "[PIOS Entrypoint] Running pending migrations on top of imported dump..."
        php artisan migrate --force || echo "[PIOS Entrypoint] Notice: some migrations reported notices."
    elif [ "${TABLE_COUNT}" -gt 0 ]; then
        echo "[PIOS Entrypoint] Existing 'users' table detected in '${DB_NAME}'. Syncing migration records if needed and running pending migrations..."
        mysql --skip-ssl -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" -e "
            INSERT IGNORE INTO migrations (migration, batch) VALUES
            ('2023_10_30_130210_create_user_merter_infos_table', 1),
            ('2025_07_28_061902_create_staff_table', 1),
            ('2025_09_03_084012_create_organizations_table', 1),
            ('2025_10_12_065735_create_machines_table', 1),
            ('2026_09_25_085045_add_audit_logs_to_inv_transactions_table', 1),
            ('2026_09_25_090011_create_inv_transaction_approvals_table', 1),
            ('2026_09_25_090554_create_approval_workflows_table', 1),
            ('2026_09_25_090555_create_approval_workflow_steps_table', 1),
            ('2026_09_25_103925_add_approvalworkflow_id_column_in_inv_categories_table', 1),
            ('2026_09_25_164846_create_inv_locations_table', 1),
            ('2026_09_25_164847_add_location_id_to_inv_item_details_table', 1),
            ('2026_09_25_171122_add_conversion_rate_to_inv_item_details_table', 1),
            ('2026_09_26_005147_create_suppliers_table', 1),
            ('2026_10_08_110038_create_kp_purchase_routes_table_and_kp_purchase_route_zones_table', 1),
            ('2026_10_09_001549_create_kp_settings_table', 1),
            ('2026_10_09_042443_add_modulename_column_in_inv_transaction_approvals_table', 1),
            ('2026_10_13_025520_create_welfare_members_table', 1),
            ('2026_10_15_135128_create_tabwater_stations_table', 1),
            ('2026_10_15_135129_create_tabwater_station_staffs_table', 1),
            ('2026_10_15_135130_create_tabwater_devices_table', 1),
            ('2026_10_15_135943_create_tabwater_device_logs_table', 1),
            ('2026_10_15_160030_create_tabwater_pipe_nodes_table', 1),
            ('2026_10_15_160058_create_tabwater_pipelines_table', 1);
        " 2>/dev/null || true
        php artisan migrate --force || echo "[PIOS Entrypoint] Notice: some migrations reported notices."
    else
        echo "[PIOS Entrypoint] Fresh database detected in '${DB_NAME}'. Running migrations and foundational seeders..."
        php artisan migrate --force || echo "[PIOS Entrypoint] Notice: some migrations reported notices."
        php artisan db:seed --force || echo "[PIOS Entrypoint] Notice: some seeders reported notices."
    fi
fi

echo "=== [PIOS Entrypoint] Bootstrap complete. Executing command: $@ ==="
exec "$@"
