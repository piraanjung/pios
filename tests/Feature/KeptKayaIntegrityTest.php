<?php

namespace Tests\Feature;

use App\Models\Admin\Organization;
use App\Models\Admin\Zone;
use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\KeptKaya\KpRecycleBankMember;
use App\Models\KPBankAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class KeptKayaIntegrityTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected User $adminUser;
    protected Organization $org;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.connections.envsogo_main' => config('database.connections.sqlite')]);
        $this->buildStandardTestSchema();

        if (config('database.default') === 'sqlite') {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'email_verified_at')) {
                \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->timestamp('email_verified_at')->nullable();
                });
            }

            if (\Illuminate\Support\Facades\Schema::hasColumn('kp_user_waste_preferences', 'is_recycle_bank')) {
                \Illuminate\Support\Facades\Schema::table('kp_user_waste_preferences', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->dropColumn('is_recycle_bank');
                });
            }
            if (!\Illuminate\Support\Facades\Schema::connection('envsogo_main')->hasTable('organizations')) {
                \Illuminate\Support\Facades\Schema::connection('envsogo_main')->create('organizations', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('org_code', 50)->nullable();
                    $table->string('org_name', 150);
                    $table->string('org_logo_img', 150)->nullable();
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_recycle_bank_members')) {
                \Illuminate\Support\Facades\Schema::create('kp_recycle_bank_members', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->unsignedBigInteger('user_id')->index();
                    $table->integer('member_number')->nullable();
                    $table->unsignedBigInteger('zone_id')->nullable();
                    $table->decimal('total_points', 12, 2)->default(0);
                    $table->decimal('balance', 12, 2)->default(0);
                    $table->string('status', 30)->default('active');
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('zones')) {
                \Illuminate\Support\Facades\Schema::create('zones', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('zone_name', 191);
                    $table->unsignedBigInteger('org_id_fk')->nullable();
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('subzones')) {
                \Illuminate\Support\Facades\Schema::create('subzones', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('subzone_name', 191);
                    $table->unsignedBigInteger('zone_id')->nullable();
                    $table->unsignedBigInteger('org_id_fk')->nullable();
                    $table->timestamps();
                });
            }
            if (!\Illuminate\Support\Facades\Schema::hasTable('roles')) {
                $this->artisan('migrate');
            }
        }

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);

        $this->org = Organization::firstOrCreate(
            ['id' => 1],
            ['org_code' => 'TEST_ORG', 'org_name' => 'เทศบาลตำบลทดสอบ']
        );

        $this->adminUser = User::firstOrCreate(
            ['username' => 'test_admin_keptkaya'],
            [
                'firstname' => 'ผู้ดูแล',
                'lastname' => 'ระบบทดสอบ',
                'password' => bcrypt('password'),
                'org_id_fk' => $this->org->id,
            ]
        );
        $this->adminUser->syncRoles(['Admin', 'Super Admin']);
        $this->actingAs($this->adminUser);
    }

    public function test_recycle_bank_members_create_page_renders_without_sql_column_error(): void
    {
        $response = $this->get(route('keptkayas.recycle-bank.members.create'));

        $response->assertStatus(200);
        $response->assertViewIs('keptkayas.recycle_bank.members.create');
    }

    public function test_recycle_bank_history_requires_valid_user_and_isolates_transactions(): void
    {
        $memberA = User::factory()->create(['org_id_fk' => $this->org->id]);
        $memberB = User::factory()->create(['org_id_fk' => $this->org->id]);

        KpRecycleBankMember::create([
            'user_id' => $memberA->id,
            'org_id_fk' => $this->org->id,
            'member_number' => 1001,
            'status' => 'active',
            'balance' => 500,
            'total_points' => 50,
        ]);

        KpRecycleBankMember::create([
            'user_id' => $memberB->id,
            'org_id_fk' => $this->org->id,
            'member_number' => 1002,
            'status' => 'active',
            'balance' => 300,
            'total_points' => 30,
        ]);

        $transA = KpPurchaseTransaction::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $memberA->id,
            'kp_u_trans_no' => 'TRA-' . uniqid(),
            'transaction_date' => now()->toDateString(),
            'total_weight' => 10,
            'total_amount' => 150,
            'total_points' => 15,
            'status' => 1,
            'recorder_id' => $this->adminUser->id,
        ]);

        $transB = KpPurchaseTransaction::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $memberB->id,
            'kp_u_trans_no' => 'TRB-' . uniqid(),
            'transaction_date' => now()->toDateString(),
            'total_weight' => 5,
            'total_amount' => 75,
            'total_points' => 7,
            'status' => 1,
            'recorder_id' => $this->adminUser->id,
        ]);

        $response = $this->get(route('keptkayas.recycle-bank.history', $memberA->id));

        $response->assertStatus(200);
        $transactions = $response->viewData('transactions');
        $this->assertTrue($transactions->contains('id', $transA->id));
        $this->assertFalse($transactions->contains('id', $transB->id), 'History must strictly isolate user transactions');
    }
}
