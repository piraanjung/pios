# Feature Specification: 010 Local Docker Compose Micro-containers Environment

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Draft — Pending Approval  
**Design Document Ref**: `docs/superpowers/specs/2026-11-05-local-docker-compose-design.md`

---

## 1. User Scenarios & Testing *(mandatory)*

### User Story 1 - One-Command Out-of-the-Box Startup (Priority: P1)

As a developer or evaluator running `pios` on a local machine, I want to execute a single command (`docker compose up -d`) and have the complete Micro-container stack (`nginx`, `app` [PHP-FPM], `db` [MySQL 8.0], `redis` [Redis 7], `vite` [Node 20], and `phpmyadmin`) start, initialize dependencies, migrate and seed the database, and serve the web application at `http://localhost:8000` ready for immediate login.

**Why this priority**: Core requirement — the system must be immediately usable without manual environment setup or local PHP/MySQL installations.

**Independent Test**: Run `docker compose up -d --build` on the project; wait for the `app` entrypoint to finish bootstrapping; send an HTTP GET request to `http://localhost:8000/login` and verify HTTP 200 and ability to authenticate as `superadmin` (`123456`).

**Acceptance Scenarios**:

1. **Given** a fresh Docker volume state with no SQL dump files in `docker/mysql/initdb.d/`, **When** `docker compose up -d` is executed, **Then** all 6 containers reach `running`/`healthy` status, `app` runs migrations and seeders automatically, and `http://localhost:8000/login` renders with HTTP 200.
2. **Given** the running stack after initial seed, **When** logging in with username `superadmin` and password `123456`, **Then** authentication succeeds and the dashboard loads without database connection or missing asset errors.
3. **Given** source code mounted from the host into `/var/www/html`, **When** a Blade view or PHP file is edited on the host, **Then** refreshing `http://localhost:8000` reflects the change immediately without rebuilding the container.

---

### User Story 2 - Seamless SQL Dump Import & Multi-Tenant DB Support (Priority: P1)

As a developer who receives a production/staging MySQL dump file (`.sql` or `.sql.gz`), I want to import that database either by placing it in `docker/mysql/initdb.d/` or uploading it via `phpmyadmin` (`http://localhost:8081`) without encountering upload size limits or multi-tenant database permission errors.

**Why this priority**: The user specifically noted that they can supply a database dump later; the system must work before the dump arrives (via seeders) and seamlessly accept the dump when provided.

**Independent Test**: Place a `.sql` dump in `docker/mysql/initdb.d/` or import via `http://localhost:8081`, restart the stack, and verify that `app` detects existing data (skips redundant seeding) and can query both `envsogo_main` and tenant-specific `org_database` schemas.

**Acceptance Scenarios**:

1. **Given** a `.sql` file placed in `docker/mysql/initdb.d/` before initial volume creation, **When** `docker compose up -d` starts `db` and `app`, **Then** MySQL imports the dump automatically and `app` detects existing records in `users` and skips `db:seed` so imported data is preserved intact.
2. **Given** an organization record with a separate `org_database` name, **When** `SetTenantConnection` switches the `tenant` connection to that database, **Then** the MySQL user has full privileges (`GRANT ALL PRIVILEGES ON *.*`) to query it without access denied errors.
3. **Given** a large `.sql` file up to 256MB, **When** importing via `http://localhost:8081` (phpMyAdmin), **Then** the upload succeeds without hitting PHP upload size limits.

---

### User Story 3 - Frontend Asset Bundling & Hot Module Replacement (Priority: P2)

As a frontend/UI developer working with Soft UI PRO, Tailwind, and Kanit tokens, I want the `vite` container to automatically install Node dependencies, generate initial production assets (`public/build/manifest.json`), and run the Vite HMR server on port `5173` so that pages using `@vite` never fail with `Vite manifest not found`.

**Why this priority**: Prevents runtime 500 errors on pages referencing `@vite` and supports live CSS/JS updates.

**Independent Test**: Inspect `public/build/manifest.json` after container startup and verify port `5173` responds to Vite asset requests.

**Acceptance Scenarios**:

1. **Given** the `vite` container starts, **When** `node_modules/` is missing, **Then** it runs `npm install`, builds `public/build/manifest.json`, and starts `npm run dev -- --host 0.0.0.0` on port `5173`.

---

## 2. Functional Requirements

- **FR-001**: `docker-compose.yml` MUST define a Micro-container topology consisting of `nginx`, `app` (PHP 8.3-FPM), `db` (MySQL 8.0), `redis` (Redis 7 Alpine), `vite` (Node 20 Alpine), and `phpmyadmin`.
- **FR-002**: Root `Dockerfile` (`php:8.3-apache` for Cloud Run) MUST remain intact; the PHP-FPM image MUST be built from `docker/php/Dockerfile`.
- **FR-003**: `docker/php/entrypoint.sh` MUST automatically handle `.env` initialization, `composer install` (if `vendor/autoload.php` is absent), `APP_KEY` generation, storage directory permissions, MySQL readiness check, and conditional `migrate --seed` (only seeding when `users` table is empty).
- **FR-004**: `config/database.php` MUST support `envsogo_main`, `tenant`, `qa`, and standard `mysql` connection keys mapped cleanly to Docker environment variables (`DB_HOST_S_ADMIN=db`, `DB_DATABASE_S_ADMIN=pios`).
- **FR-005**: `database/seeders/DatabaseSeeder.php` MUST be updated to seed foundational data (`ProvincesSeeder`, `DistrictsSeeder`, `TambonsSeeder`, `OrganizationsSeeder`, `ZonesSeeder`, `SubzonesSeeder`, `RolesSeeder`, `PermissionsSeeder`, `AdminSeeder`) idempotently so fresh environments have working login accounts (`superadmin` / `swadmin1`).
