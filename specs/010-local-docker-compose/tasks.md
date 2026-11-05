# Tasks: Local Docker Compose Micro-containers Environment (Approach 3)

**Input**: Design documents from `/specs/010-local-docker-compose/` and `docs/superpowers/specs/2026-11-05-local-docker-compose-design.md`
**Prerequisites**: [plan.md](file:///Users/poppy.geidtiphong/Documents/workspace/homework/pios/specs/010-local-docker-compose/plan.md) (required), [spec.md](file:///Users/poppy.geidtiphong/Documents/workspace/homework/pios/specs/010-local-docker-compose/spec.md) (required for user stories)

---

## Phase 1: Docker Infrastructure Files (Micro-containers Setup)

**Purpose**: Create the 6-service Docker Compose stack (`nginx`, `app`, `db`, `redis`, `vite`, `phpmyadmin`) and supporting container configuration files without breaking the root Cloud Run `Dockerfile`.

- [ ] T001 Create PHP 8.3-FPM Dockerfile in `docker/php/Dockerfile` with `pdo_mysql`, `mbstring`, `exif`, `pcntl`, `bcmath`, `gd` (`freetype`, `jpeg`, `webp`), `zip`, `xml`, `intl`, PECL `redis`, `tesseract-ocr`, `tesseract-ocr-tha`, and Composer 2
- [ ] T002 [P] Create PHP runtime limits configuration in `docker/php/local.ini` (`upload_max_filesize=64M`, `post_max_size=64M`, `memory_limit=512M`, `max_execution_time=120`)
- [ ] T003 [P] Create Nginx virtual host configuration in `docker/nginx/default.conf` pointing `/var/www/html/public` to `fastcgi_pass app:9000`
- [ ] T004 [P] Create MySQL multi-tenant grant initialization script in `docker/mysql/initdb.d/00-init-grants.sql` (`GRANT ALL PRIVILEGES ON *.* TO 'pios'@'%' WITH GRANT OPTION`) and add `docker/mysql/initdb.d/README.md` explaining SQL dump drop-in usage
- [ ] T005 Create zero-touch environment template `.env.docker` configured for `DB_HOST=db`, `DB_HOST_S_ADMIN=db`, `DB_DATABASE=pios`, `DB_DATABASE_S_ADMIN=pios`, `REDIS_HOST=redis`, `APP_URL=http://localhost:8080`, and `VITE_PORT=5173`
- [ ] T006 Create automated container entrypoint script `docker/php/entrypoint.sh` (executable `chmod +x`) handling `.env` bootstrap, `APP_KEY` check, `composer install`, MySQL readiness wait, Dual-Mode DB detection (`SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='pios' AND table_name='users'`), conditional `php artisan migrate --force --seed`, `storage:link`, and permissions
- [ ] T007 Replace `docker-compose.yml` with the 6-service Micro-containers definition (`nginx`, `app`, `db`, `redis`, `vite`, `phpmyadmin`), named volumes (`pios_mysql_data`, `pios_redis_data`, `pios_vendor`, `pios_node_modules`), and bridge network `pios_net`

---

## Phase 2: Laravel Multi-Tenant Config, Idempotent Seeders & Vite HMR

**Purpose**: Ensure the Laravel application boots cleanly inside the containers, supports both `.sql` dumps and empty-database seeding, and serves Vite HMR assets across Docker boundaries.

- [ ] T008 Update `config/database.php` to include a `'mysql'` connection block aliasing the primary connection environment variables alongside `envsogo_main` and `tenant`
- [ ] T009 Update `database/seeders/DatabaseSeeder.php` and foundational seeders (`ProvincesSeeder`, `DistrictsSeeder`, `TambonsSeeder`, `OrganizationsSeeder`, `ZonesSeeder`, `SubzonesSeeder`, `RolesSeeder`, `PermissionsSeeder`, `AdminSeeder`) to be enabled and idempotent (`updateOrCreate` / `firstOrCreate`) so `superadmin` and `swadmin1` (`123456`) work out-of-the-box
- [ ] T010 Update `vite.config.js` with `server: { host: '0.0.0.0', port: 5173, hmr: { host: 'localhost' }, watch: { usePolling: true } }` so the `vite` container serves hot reload to the host browser

---

## Phase 3: Verification & Smoke Testing

**Purpose**: Verify the Docker Compose configuration via automated tests and container boot verification.

- [ ] T011 Write PHPUnit structural verification test in `tests/Feature/DockerComposeConfigTest.php` asserting all 6 required services (`nginx`, `app`, `db`, `redis`, `vite`, `phpmyadmin`), required `docker/` files, `.env.docker`, and `config/database.php` connections exist
- [ ] T012 Run `docker compose config` and PHPUnit test suite (`php artisan test`) to verify configuration syntax and zero regressions
- [ ] T013 Execute end-to-end container smoke test (`docker compose up -d --build`) verifying HTTP `200` on `http://localhost:8080/login`, `http://localhost:8081` (phpMyAdmin), and login capability with `superadmin` / `123456`
