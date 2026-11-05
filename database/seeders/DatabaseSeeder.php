<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProvincesSeeder::class,
            DistrictsSeeder::class,
            TambonsSeeder::class,
            OrganizationsSeeder::class,
            ZonesSeeder::class,
            SubzonesSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
