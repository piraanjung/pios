<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
