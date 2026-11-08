<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure `staffs` has `user_id` column
        if (Schema::hasTable('staffs')) {
            if (!Schema::hasColumn('staffs', 'user_id')) {
                Schema::table('staffs', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('org_id_fk');
                    $table->index('user_id');
                });

                // In legacy schema where staffs.id was equal to users.id, backfill user_id = id
                DB::statement('UPDATE staffs SET user_id = id WHERE user_id IS NULL OR user_id = 0');
            }
        }

        // 2. Ensure `kp_user_waste_preferences` has `org_id_fk` column
        if (Schema::hasTable('kp_user_waste_preferences')) {
            if (!Schema::hasColumn('kp_user_waste_preferences', 'org_id_fk')) {
                Schema::table('kp_user_waste_preferences', function (Blueprint $table) {
                    $table->unsignedBigInteger('org_id_fk')->default(1)->after('user_id');
                    $table->index('org_id_fk');
                });

                if (Schema::hasColumn('kp_user_waste_preferences', 'org_id')) {
                    DB::statement('UPDATE kp_user_waste_preferences SET org_id_fk = org_id WHERE org_id IS NOT NULL');
                }
            }
        }

        // 3. Ensure other multi-tenant tables have `org_id_fk`
        $tenantTables = [
            'budget_year' => 'id',
            'annual_trash_members' => 'id',
            'foodwaste_bin_stocks' => 'id',
            'kp_recycle_bank_members' => 'id',
            'tw_invoice_history' => 'id',
        ];

        foreach ($tenantTables as $table => $afterCol) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'org_id_fk')) {
                Schema::table($table, function (Blueprint $table) use ($afterCol) {
                    $table->unsignedBigInteger('org_id_fk')->default(1);
                    $table->index('org_id_fk');
                });
            }
        }

        // 4. Ensure Super Admin and Admin roles exist and are assigned to superadmin user
        if (Schema::hasTable('roles') && Schema::hasTable('users') && Schema::hasTable('model_has_roles')) {
            $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
            $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

            $admins = User::whereIn('username', ['superadmin', 'swadmin1'])->get();
            foreach ($admins as $adminUser) {
                if (!$adminUser->hasRole('Super Admin')) {
                    $adminUser->assignRole($superAdminRole);
                }
                if (!$adminUser->hasRole('Admin')) {
                    $adminUser->assignRole($adminRole);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe no-op for backward compatibility
    }
};
