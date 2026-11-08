<?php
/**
 * One-Time Secure Fast Extractor & Optimizer for PIOS cPanel Deployment
 */
@set_time_limit(300);
@ini_set('memory_limit', '512M');
@ini_set('display_errors', '0');
error_reporting(E_ALL);

$startTime = microtime(true);
$log = [];

$expectedToken = 'TOKEN_PLACEHOLDER';
$providedToken = $_GET['token'] ?? ($_POST['token'] ?? '');

// 1. Verify Token
if (empty($expectedToken) || !hash_equals($expectedToken, (string)$providedToken)) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'Unauthorized token'], JSON_PRETTY_PRINT);
    exit;
}

// 2. Locate deploy.zip
$targetDir = file_exists(__DIR__ . '/deploy.zip') ? __DIR__ : dirname(__DIR__);
$zipFile = $targetDir . '/deploy.zip';

if (!file_exists($zipFile)) {
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => 'deploy.zip not found in ' . $targetDir,
        'current_dir' => __DIR__,
    ], JSON_PRETTY_PRINT);
    exit;
}

$zipSizeMb = round(filesize($zipFile) / (1024 * 1024), 2);
$log[] = "Found deploy.zip ($zipSizeMb MB) in $targetDir";

// 3. Fast Extract: System unzip first (100x faster), fallback to ZipArchive
$extracted = false;
$extractMethod = 'none';

if (function_exists('exec')) {
    $out = [];
    $ret = -1;
    @exec('unzip -o -q ' . escapeshellarg($zipFile) . ' -d ' . escapeshellarg($targetDir) . ' 2>&1', $out, $ret);
    if ($ret === 0 && file_exists($targetDir . '/artisan')) {
        $extracted = true;
        $extractMethod = 'exec(unzip)';
        $log[] = "Extracted via exec(unzip) successfully";
    } else {
        $log[] = "exec(unzip) returned code $ret: " . implode(" ", $out);
    }
}

if (!$extracted && function_exists('shell_exec')) {
    $out = @shell_exec('unzip -o -q ' . escapeshellarg($zipFile) . ' -d ' . escapeshellarg($targetDir) . ' 2>&1');
    if (file_exists($targetDir . '/artisan')) {
        $extracted = true;
        $extractMethod = 'shell_exec(unzip)';
        $log[] = "Extracted via shell_exec(unzip) successfully";
    } else {
        $log[] = "shell_exec(unzip) output: " . trim((string)$out);
    }
}

if (!$extracted && class_exists('ZipArchive')) {
    $zip = new ZipArchive();
    $res = $zip->open($zipFile);
    if ($res === true) {
        $zip->extractTo($targetDir);
        $zip->close();
        if (file_exists($targetDir . '/artisan')) {
            $extracted = true;
            $extractMethod = 'ZipArchive';
            $log[] = "Extracted via ZipArchive successfully";
        }
    } else {
        $log[] = "ZipArchive::open failed with error code $res";
    }
}

if (!$extracted) {
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => 'Failed to extract deploy.zip with any available method',
        'log' => $log,
    ], JSON_PRETTY_PRINT);
    exit;
}

$extractDuration = round(microtime(true) - $startTime, 2);
$log[] = "Extraction completed in {$extractDuration}s using $extractMethod";

// 4. Cleanup zip and extractor files immediately
@unlink($zipFile);
@unlink($targetDir . '/extract.php');
@unlink($targetDir . '/public/extract.php');
if (file_exists(__FILE__)) {
    @unlink(__FILE__);
}
$log[] = "Cleaned up deploy.zip and extract.php";

// 5. Setup directories & symlink
@mkdir($targetDir . '/storage/framework/cache/data', 0775, true);
@mkdir($targetDir . '/storage/framework/sessions', 0775, true);
@mkdir($targetDir . '/storage/framework/views', 0775, true);
@mkdir($targetDir . '/storage/logs', 0775, true);
@mkdir($targetDir . '/bootstrap/cache', 0775, true);
if (!file_exists($targetDir . '/public/storage') && file_exists($targetDir . '/storage/app/public')) {
    @symlink($targetDir . '/storage/app/public', $targetDir . '/public/storage');
}

// 6. Bootstrap Laravel and run Artisan commands
$artisanResults = [];
try {
    if (file_exists($targetDir . '/vendor/autoload.php') && file_exists($targetDir . '/bootstrap/app.php')) {
        require $targetDir . '/vendor/autoload.php';
        $app = require_once $targetDir . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Baseline migration sync
        try {
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
        } catch (\Throwable $mEx) {
            $artisanResults['migration_sync_notice'] = $mEx->getMessage();
        }

        // Run migrations
        try {
            Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $artisanResults['migrate_output'] = trim(Illuminate\Support\Facades\Artisan::output());
        } catch (\Throwable $mEx) {
            $artisanResults['migrate_error'] = $mEx->getMessage();
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

$totalDuration = round(microtime(true) - $startTime, 2);

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => true,
    'method' => $extractMethod,
    'extract_duration' => $extractDuration,
    'total_duration' => $totalDuration,
    'artisan' => $artisanResults,
    'log' => $log,
    'timestamp' => date('c'),
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
