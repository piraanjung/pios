<?php
/**
 * One-Time Secure Extractor & Post-Deploy Optimizer for PIOS cPanel Deployment
 * This file is automatically deployed and deleted upon successful execution.
 */
@set_time_limit(300);
@ini_set('memory_limit', '512M');

$expectedToken = 'TOKEN_PLACEHOLDER';
$providedToken = $_GET['token'] ?? ($_POST['token'] ?? '');

// 1. Verify Authentication Token
if (empty($expectedToken) || !hash_equals($expectedToken, (string)$providedToken)) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'Unauthorized access'], JSON_PRETTY_PRINT);
    exit;
}

// 2. Locate deploy.zip (either current dir or parent dir if running inside public/)
$targetDir = file_exists(__DIR__ . '/deploy.zip') ? __DIR__ : dirname(__DIR__);
$zipFile = $targetDir . '/deploy.zip';

if (!file_exists($zipFile)) {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => 'deploy.zip not found in ' . $targetDir,
        'current_dir' => __DIR__,
    ], JSON_PRETTY_PRINT);
    exit;
}

// 3. Extract Archive
$zip = new ZipArchive();
if ($zip->open($zipFile) !== true) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => 'Failed to open deploy.zip'], JSON_PRETTY_PRINT);
    exit;
}

$zip->extractTo($targetDir);
$zip->close();

// 4. Immediate Self-Cleanup of deploy.zip and extract.php
@unlink($zipFile);
@unlink($targetDir . '/extract.php');
@unlink($targetDir . '/public/extract.php');
if (file_exists(__FILE__)) {
    @unlink(__FILE__);
}

// 5. Ensure Storage Directories & Symlinks
@mkdir($targetDir . '/storage/framework/cache/data', 0775, true);
@mkdir($targetDir . '/storage/framework/sessions', 0775, true);
@mkdir($targetDir . '/storage/framework/views', 0775, true);
@mkdir($targetDir . '/storage/logs', 0775, true);
@mkdir($targetDir . '/bootstrap/cache', 0775, true);
if (!file_exists($targetDir . '/public/storage') && file_exists($targetDir . '/storage/app/public')) {
    @symlink($targetDir . '/storage/app/public', $targetDir . '/public/storage');
}

$results = [
    'success' => true,
    'message' => 'Archive extracted and self-cleaned successfully',
    'timestamp' => date('c'),
];

// 6. Bootstrap Laravel and Run Artisan Migration & Cache Optimization
try {
    if (file_exists($targetDir . '/vendor/autoload.php') && file_exists($targetDir . '/bootstrap/app.php')) {
        require $targetDir . '/vendor/autoload.php';
        $app = require_once $targetDir . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Baseline migration sync for pre-existing tables if needed
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
                }
            }
        } catch (\Throwable $mEx) {
            $results['migration_sync_notice'] = $mEx->getMessage();
        }

        // Run migrations
        try {
            Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            $results['migrate_output'] = trim(Illuminate\Support\Facades\Artisan::output());
        } catch (\Throwable $mEx) {
            $results['migrate_error'] = $mEx->getMessage();
        }

        // Clear and rebuild caches
        try {
            Illuminate\Support\Facades\Artisan::call('optimize:clear');
            Illuminate\Support\Facades\Artisan::call('config:cache');
            Illuminate\Support\Facades\Artisan::call('view:cache');
            $results['cache_optimized'] = true;
        } catch (\Throwable $cEx) {
            $results['cache_error'] = $cEx->getMessage();
        }
    }
} catch (\Throwable $e) {
    $results['artisan_bootstrap_error'] = $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
