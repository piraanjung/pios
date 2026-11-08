<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ImportSqlData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pios:import-sql {file=database/data.sql} {--force : Force execution without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SQL dump into the current database, drop old tables safely, and run migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = base_path($this->argument('file'));

        if (!file_exists($filePath)) {
            $this->error("SQL file not found at: {$filePath}");
            return 1;
        }

        $this->info("Starting SQL data import from: {$filePath} (" . round(filesize($filePath) / 1024, 2) . " KB)");

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $this->error("Database connection failed: " . $e->getMessage());
            return 1;
        }

        // 1. Disable Foreign Key Checks and Drop Existing Tables
        $this->info("Dropping existing tables...");
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $colName = "Tables_in_{$dbName}";

        $droppedCount = 0;
        foreach ($tables as $table) {
            $tableName = $table->$colName ?? array_values((array)$table)[0];
            DB::statement("DROP TABLE IF EXISTS `{$tableName}`;");
            $droppedCount++;
        }
        $this->info("Dropped {$droppedCount} existing tables.");

        // 2. Read and Execute SQL file
        $this->info("Executing SQL dump...");
        $sql = file_get_contents($filePath);

        // Prefix and suffix with safe SQL session variables
        $wrappedSql = "SET FOREIGN_KEY_CHECKS = 0;\nSET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n"
            . $sql
            . "\nSET FOREIGN_KEY_CHECKS = 1;";

        // Execute via unprepared query
        DB::unprepared($wrappedSql);
        $this->info("SQL dump imported successfully.");

        // Sync baseline migrations for pre-existing schema
        if (Schema::hasTable('migrations')) {
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
            foreach ($existingMigrations as $m) {
                if (!DB::table('migrations')->where('migration', $m)->exists()) {
                    DB::table('migrations')->insert(['migration' => $m, 'batch' => 1]);
                }
            }
            $this->info("Synced baseline migrations table records.");
        }

        // 3. Run pending migrations
        $this->info("Running pending migrations...");
        Artisan::call('migrate', ['--force' => true]);
        $this->line(trim(Artisan::output()));

        // 4. Ensure Super Admin and Admin roles
        if (Schema::hasTable('roles') && Schema::hasTable('users') && Schema::hasTable('model_has_roles')) {
            $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
            $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

            $superadmin = User::where('username', 'superadmin')->first();
            if ($superadmin) {
                if (!$superadmin->hasRole('Super Admin')) {
                    $superadmin->assignRole($superAdminRole);
                }
                if (!$superadmin->hasRole('Admin')) {
                    $superadmin->assignRole($adminRole);
                }
                $this->info("Ensured superadmin roles (Super Admin, Admin).");
            }
        }

        // 5. Clear caches
        $this->info("Clearing cache...");
        Artisan::call('optimize:clear');
        $this->line(trim(Artisan::output()));

        $tableCount = count(DB::select('SHOW TABLES'));
        $this->info("Import completed successfully! Total tables now: {$tableCount}");

        return 0;
    }
}
