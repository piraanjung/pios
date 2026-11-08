<?php
/**
 * Fast Extractor & Post-Deploy Migration Runner for PIOS cPanel Deployment
 */
@set_time_limit(600);
@ini_set('memory_limit', '512M');
@ini_set('display_errors', '1');
error_reporting(E_ALL);

if (function_exists('apache_setenv')) {
    @apache_setenv('no-gzip', '1');
}
@ini_set('zlib.output_compression', 'Off');
@ini_set('output_buffering', 'Off');
@ini_set('implicit_flush', '1');
ob_implicit_flush(true);
while (ob_get_level()) {
    @ob_end_flush();
}

header('X-Accel-Buffering: no');
header('Content-Type: text/plain; charset=utf-8');

$startTime = microtime(true);
$log = [];

$expectedToken = 'TOKEN_PLACEHOLDER';
$providedToken = $_GET['token'] ?? ($_POST['token'] ?? '');

// 1. Verify Authentication Token
if (empty($expectedToken) || !hash_equals($expectedToken, (string)$providedToken)) {
    http_response_code(403);
    echo "---DEPLOY_JSON_START---\n";
    echo json_encode(['success' => false, 'error' => 'Unauthorized token'], JSON_PRETTY_PRINT);
    echo "\n---DEPLOY_JSON_END---\n";
    exit;
}

// 2. Locate targetDir and deploy.zip
$targetDir = file_exists(__DIR__ . '/deploy.zip') ? __DIR__ : dirname(__DIR__);
$zipFile = $targetDir . '/deploy.zip';

if (!file_exists($zipFile)) {
    http_response_code(200);
    echo "---DEPLOY_JSON_START---\n";
    echo json_encode([
        'success' => false,
        'error' => 'deploy.zip not found in ' . $targetDir,
        'current_dir' => __DIR__,
    ], JSON_PRETTY_PRINT);
    echo "\n---DEPLOY_JSON_END---\n";
    exit;
}

$zipSizeMb = round(filesize($zipFile) / (1024 * 1024), 2);
$log[] = "Found deploy.zip ($zipSizeMb MB) in $targetDir";
echo "HEARTBEAT: Found deploy.zip ($zipSizeMb MB). Extracting...\n";
echo str_repeat(" ", 4096) . "\n";
@flush();

// 3. Fast Extract using ZipArchive
$extracted = false;
$extractMethod = 'none';

if (class_exists('ZipArchive')) {
    $zip = new ZipArchive();
    $res = $zip->open($zipFile);
    if ($res === true) {
        $numFiles = $zip->numFiles;
        $log[] = "ZipArchive opened archive with $numFiles files";
        echo "HEARTBEAT: Uncompressing $numFiles files...\n";
        @flush();
        
        $extractResult = $zip->extractTo($targetDir);
        $zip->close();
        
        $log[] = "ZipArchive::extractTo result: " . ($extractResult ? "true" : "false");
        if ($extractResult && file_exists($targetDir . '/artisan')) {
            $extracted = true;
            $extractMethod = 'ZipArchive';
        }
    } else {
        $log[] = "ZipArchive::open failed with code $res";
    }
}

// Fallback to exec/shell_exec unzip if needed
if (!$extracted) {
    $disabledFunctions = explode(',', (string)ini_get('disable_functions'));
    $disabledFunctions = array_map('trim', $disabledFunctions);
    if (function_exists('exec') && !in_array('exec', $disabledFunctions, true)) {
        $out = [];
        $ret = -1;
        @exec('unzip -o -q ' . escapeshellarg($zipFile) . ' -d ' . escapeshellarg($targetDir) . ' 2>&1', $out, $ret);
        if ($ret === 0 && file_exists($targetDir . '/artisan')) {
            $extracted = true;
            $extractMethod = 'exec(unzip)';
        }
    }
}

if (!$extracted) {
    echo "---DEPLOY_JSON_START---\n";
    echo json_encode([
        'success' => false,
        'error' => 'Failed to extract deploy.zip',
        'log' => $log,
    ], JSON_PRETTY_PRINT);
    echo "\n---DEPLOY_JSON_END---\n";
    exit;
}

$extractDuration = round(microtime(true) - $startTime, 2);
$log[] = "Extraction finished in {$extractDuration}s using $extractMethod";
echo "HEARTBEAT: Extraction complete in {$extractDuration}s\n";
@flush();

// 4. Ensure Storage Directories & Symlinks
@mkdir($targetDir . '/storage/framework/cache/data', 0775, true);
@mkdir($targetDir . '/storage/framework/sessions', 0775, true);
@mkdir($targetDir . '/storage/framework/views', 0775, true);
@mkdir($targetDir . '/storage/logs', 0775, true);
@mkdir($targetDir . '/bootstrap/cache', 0775, true);
@mkdir($targetDir . '/public/uploads', 0775, true);
if (!file_exists($targetDir . '/public/storage') && file_exists($targetDir . '/storage/app/public')) {
    @symlink($targetDir . '/storage/app/public', $targetDir . '/public/storage');
}

// 5. Ensure .env exists with APP_KEY
if (!file_exists($targetDir . '/.env') && file_exists($targetDir . '/.env.example')) {
    @copy($targetDir . '/.env.example', $targetDir . '/.env');
    $log[] = "Created .env from .env.example";
}

if (file_exists($targetDir . '/.env')) {
    $envContent = file_get_contents($targetDir . '/.env');
    if (!str_contains($envContent, 'APP_KEY=base64:')) {
        $randomKey = 'base64:' . base64_encode(random_bytes(32));
        $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY={$randomKey}", $envContent);
        @file_put_contents($targetDir . '/.env', $envContent);
        $log[] = "Generated APP_KEY in .env";
    }
}

// 6. Clear APCu / OPcache if present
if (function_exists('opcache_reset')) {
    @opcache_reset();
    $log[] = "OPcache reset";
}

// 7. Bootstrap Laravel and Run Artisan
$artisanResults = [];
try {
    if (file_exists($targetDir . '/vendor/autoload.php') && file_exists($targetDir . '/bootstrap/app.php')) {
        require $targetDir . '/vendor/autoload.php';
        $app = require_once $targetDir . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Check DB connection & run migrations
        try {
            Illuminate\Support\Facades\DB::connection()->getPdo();
            $log[] = "Database connected successfully";
            echo "HEARTBEAT: Database connected successfully\n";
            @flush();

            // Baseline migration sync for pre-existing tables
            if (Illuminate\Support\Facades\Schema::hasTable('migrations')) {
                $existingMigrations = [
                    '2023_10_30_130210_create_user_merter_infos_table',
                    '2025_07_28_061902_create_staff_table',
                    '2025_09_03_084012_create_organizations_table',
                    '2025_10_12_065735_create_machines_table',
                    '2026_09_25_085045_add_audit_logs_to_inv_transactions_table',
                    '2026_09_25_090011_create_inv_transaction_approvals_table',
                    '2026_09_25_090554_create_approval_workflows_table',
                    '2026_09_25_090555_create_approval_workflow_steps_table',
                    '2026_09_25_103925_add_approvalworkflow_id_column_in_inv_categories_table',
                    '2026_09_25_164846_create_inv_locations_table',
                    '2026_09_25_164847_add_location_id_to_inv_item_details_table',
                    '2026_09_25_171122_add_conversion_rate_to_inv_item_details_table',
                    '2026_09_26_005147_create_suppliers_table',
                    '2026_10_08_110038_create_kp_purchase_routes_table_and_kp_purchase_route_zones_table',
                    '2026_10_09_001549_create_kp_settings_table',
                    '2026_10_09_042443_add_modulename_column_in_inv_transaction_approvals_table',
                    '2026_10_13_025520_create_welfare_members_table',
                    '2026_10_15_135128_create_tabwater_stations_table',
                    '2026_10_15_135129_create_tabwater_station_staffs_table',
                    '2026_10_15_135130_create_tabwater_devices_table',
                    '2026_10_15_135943_create_tabwater_device_logs_table',
                    '2026_10_15_160030_create_tabwater_pipe_nodes_table',
                    '2026_10_15_160058_create_tabwater_pipelines_table',
                ];
                $existingInDb = Illuminate\Support\Facades\DB::table('migrations')->pluck('migration')->toArray();
                $toInsert = [];
                foreach ($existingMigrations as $m) {
                    if (!in_array($m, $existingInDb, true)) {
                        $toInsert[] = ['migration' => $m, 'batch' => 1];
                    }
                }
                if (!empty($toInsert)) {
                    Illuminate\Support\Facades\DB::table('migrations')->insert($toInsert);
                    $artisanResults['baseline_synced'] = count($toInsert);
                }
            }

            // Run migrations
            echo "HEARTBEAT: Running artisan migrate --force...\n";
            @flush();
            Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $artisanResults['migrate_output'] = trim(Illuminate\Support\Facades\Artisan::output());

            // Run seeders for essential roles and admin
            if (Illuminate\Support\Facades\Schema::hasTable('roles')) {
                echo "HEARTBEAT: Running artisan db:seed RolesSeeder --force...\n";
                @flush();
                Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\RolesSeeder', '--force' => true]);
                $artisanResults['roles_output'] = trim(Illuminate\Support\Facades\Artisan::output());
            }

            if (Illuminate\Support\Facades\Schema::hasTable('users')) {
                echo "HEARTBEAT: Running artisan db:seed AdminSeeder --force...\n";
                @flush();
                Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\AdminSeeder', '--force' => true]);
                $artisanResults['seed_output'] = trim(Illuminate\Support\Facades\Artisan::output());
            }
        } catch (\Throwable $mEx) {
            $artisanResults['database_notice'] = $mEx->getMessage();
        }

        // Cache optimization
        try {
            Illuminate\Support\Facades\Artisan::call('optimize:clear');
            Illuminate\Support\Facades\Artisan::call('config:cache');
            Illuminate\Support\Facades\Artisan::call('view:cache');
            $artisanResults['cache_optimized'] = true;
        } catch (\Throwable $cEx) {
            $artisanResults['cache_error'] = $cEx->getMessage();
        }
    }
} catch (\Throwable $e) {
    $artisanResults['bootstrap_error'] = $e->getMessage();
}

// 8. Self-cleanup temporary deploy files
@unlink($zipFile);
@unlink($targetDir . '/extract.php');
@unlink($targetDir . '/public/extract.php');
if (file_exists(__FILE__)) {
    @unlink(__FILE__);
}
$log[] = "Self-cleaned deploy.zip and extract.php";

$totalDuration = round(microtime(true) - $startTime, 2);
$log[] = "Deployment finished in {$totalDuration}s";

echo "\n---DEPLOY_JSON_START---\n";
echo json_encode([
    'success' => true,
    'method' => $extractMethod,
    'extract_duration' => $extractDuration,
    'total_duration' => $totalDuration,
    'artisan' => $artisanResults,
    'log' => $log,
    'timestamp' => date('c'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
echo "\n---DEPLOY_JSON_END---\n";
