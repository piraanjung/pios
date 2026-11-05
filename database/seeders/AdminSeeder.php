<?php

namespace Database\Seeders;

use App\Models\Admin\UserProfile;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $superadmin = User::firstOrCreate(
      ['username' => 'superadmin'],
      [
        'org_id_fk' => 1,
        'password' => Hash::make('123456'),
        'prefix' => 'นาย', 
        'firstname' => 'super',
        'lastname' => 'admin',
        'email' => 'superadmin@gmail.com',
        'email_verified_at' => now(),
        'id_card' => '111111111',
        'phone' => '0999999999',
        'gender' => 'm',
        'address' => 'x',
        'zone_id' => '1',
        'subzone_id' => '1',
        'tambon_code' => '350805',
        'district_code' => '3508',
        'province_code' => '35',
      ]
    );
    if (!$superadmin->hasRole('Super Admin')) {
      $superadmin->assignRole(['Super Admin']);
    }

    $swadmin1 = User::firstOrCreate(
      ['username' => 'swadmin1'],
      [
        'org_id_fk' => 1,
        'password' => Hash::make('123456'),
        'prefix' => 'นาย', 
        'firstname' => 'sw',
        'lastname' => 'admin',
        'email' => 'swadmin1@gmail.com',
        'email_verified_at' => now(),
        'id_card' => '111111111',
        'phone' => '0999999999',
        'gender' => 'm',
        'address' => 'x',
        'zone_id' => '1',
        'subzone_id' => '1',
        'tambon_code' => '350805',
        'district_code' => '3508',
        'province_code' => '35',
      ]
    );
    if (!$swadmin1->hasRole('Admin')) {
      $swadmin1->assignRole(['Admin']);
    }
  }
}
