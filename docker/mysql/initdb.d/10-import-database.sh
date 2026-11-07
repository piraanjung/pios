#!/bin/bash
# docker/mysql/initdb.d/10-import-database.sh
# Automatically imports docs/database.sql dump into the main database if mounted.
set -e

DUMP_FILE="/docker-entrypoint-initdb.d/database.sql.dump"
TARGET_DB="${MYSQL_DATABASE:-pios}"

if [ -f "$DUMP_FILE" ]; then
    echo "=== [MySQL Init] Importing $DUMP_FILE into database '$TARGET_DB' ==="
    mysql -u root -p"${MYSQL_ROOT_PASSWORD}" "${TARGET_DB}" << 'EOSQL'
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
EOSQL

    mysql -u root -p"${MYSQL_ROOT_PASSWORD}" "${TARGET_DB}" < "$DUMP_FILE"

    mysql -u root -p"${MYSQL_ROOT_PASSWORD}" "${TARGET_DB}" << 'EOSQL'
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
EOSQL
    echo "=== [MySQL Init] Database import completed successfully! ==="
else
    echo "=== [MySQL Init] No database dump found at $DUMP_FILE. Skipping initial dump import. ==="
fi
