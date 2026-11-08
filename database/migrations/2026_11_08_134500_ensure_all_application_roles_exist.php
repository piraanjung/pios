<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('roles')) {
            $roles = [
                'Super Admin', 'super admin', 'Admin', 'admin', 'Staff', 'staff', 'User', 'user',
                'Tabwater Header', 'Tabwater Staff', 'tabwater staff', 'Tabwater User',
                'Finance Header', 'Finance Staff', 'finance staff', 'finance',
                'Recycle Bank Staff', 'Recycle Bank User',
                'Food Waste Staff', 'FoodWaste Staff', 'Food Waste User',
                'Annual Trash Staff', 'Annual Staff', 'Annual Bin User', 'Annual Trash User',
            ];
            foreach ($roles as $role) {
                Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to preserve role mappings
    }
};
