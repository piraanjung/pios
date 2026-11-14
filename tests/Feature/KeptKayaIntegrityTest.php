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

            if (\Illuminate\Support\Facades\Schema::hasTable('kp_bank_accounts')) {
                \Illuminate\Support\Facades\Schema::table('kp_bank_accounts', function (\Illuminate\Database\Schema\Blueprint $table) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('kp_bank_accounts', 'org_id_fk')) {
                        $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                    }
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('kp_bank_accounts', 'entity_type')) {
                        $table->string('entity_type', 50)->nullable();
                    }
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

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_tbank_items')) {
                \Illuminate\Support\Facades\Schema::create('kp_tbank_items', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('kp_itemscode', 191)->nullable();
                    $table->string('kp_itemsname', 191);
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_tbank_items_pricepoint')) {
                \Illuminate\Support\Facades\Schema::create('kp_tbank_items_pricepoint', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->unsignedBigInteger('kp_items_idfk')->index();
                    $table->unsignedBigInteger('kp_units_idfk')->nullable();
                    $table->decimal('price_for_member', 10, 2)->default(0);
                    $table->decimal('price_from_dealer', 10, 2)->default(0);
                    $table->decimal('point', 10, 2)->default(0);
                    $table->dateTime('start_date')->nullable();
                    $table->dateTime('end_date')->nullable();
                    $table->string('status', 30)->default('active');
                    $table->string('deleted', 1)->default('0');
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_purchase_shops')) {
                \Illuminate\Support\Facades\Schema::create('kp_purchase_shops', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('shop_name', 191);
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->string('status', 30)->default('active');
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_tbank_items_units')) {
                \Illuminate\Support\Facades\Schema::create('kp_tbank_items_units', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('unitname', 191);
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->string('status', 30)->default('active');
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_sell_transactions')) {
                \Illuminate\Support\Facades\Schema::create('kp_sell_transactions', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->unsignedBigInteger('user_id_fk')->nullable();
                    $table->string('kp_u_trans_no', 191);
                    $table->unsignedBigInteger('shop_id_fk');
                    $table->date('transaction_date');
                    $table->decimal('total_weight', 10, 2);
                    $table->decimal('total_amount', 10, 2);
                    $table->unsignedBigInteger('recorder_id');
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('kp_sell_details')) {
                \Illuminate\Support\Facades\Schema::create('kp_sell_details', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('kp_sell_trans_id');
                    $table->unsignedBigInteger('kp_recycle_item_id');
                    $table->unsignedBigInteger('org_id_fk')->index();
                    $table->decimal('weight', 10, 2);
                    $table->decimal('price_per_unit', 10, 2);
                    $table->decimal('amount', 10, 2);
                    $table->string('comment', 191)->nullable();
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('staffs')) {
                \Illuminate\Support\Facades\Schema::create('staffs', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id')->nullable();
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
        \App\Models\Admin\Staff::firstOrCreate([
            'user_id' => $this->adminUser->id,
        ], [
            'org_id_fk' => $this->org->id,
        ]);

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

    public function test_sell_form_blocks_and_warns_when_org_bank_account_is_missing(): void
    {
        // No KPBankAccount for org
        $response = $this->get(route('keptkayas.sell.form'));

        $response->assertStatus(200);
        $response->assertViewHas('hasOrgBankAccount', false);
        $response->assertSee('ยังไม่ได้ตั้งค่าบัญชีกลางธนาคารขยะ', false);
    }

    public function test_sell_form_submits_cleanly_when_org_bank_account_exists(): void
    {
        $orgAccount = KPBankAccount::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $this->adminUser->id,
            'account_no' => 'ORG-ACC-01',
            'balance' => 0,
            'entity_type' => 'org_recycle_bank',
            'status' => 'active',
        ]);

        $shop = \App\Models\KeptKaya\KpPurchaseShop::create([
            'shop_name' => 'ร้านรับซื้อทดสอบ',
            'org_id_fk' => $this->org->id,
            'status' => 'active',
        ]);

        $staff = \App\Models\Admin\Staff::create([
            'user_id' => $this->adminUser->id,
            'org_id_fk' => $this->org->id,
        ]);

        $item = \App\Models\KeptKaya\KpTbankItems::create([
            'kp_itemsname' => 'ขวดพลาสติกใส',
            'org_id_fk' => $this->org->id,
        ]);

        $unit = \App\Models\KeptKaya\KpTbankUnits::create([
            'unitname' => 'กิโลกรัม',
            'org_id_fk' => $this->org->id,
            'status' => 'active',
        ]);

        \App\Models\KeptKaya\KpTbankItemsPriceAndPoint::create([
            'kp_items_idfk' => $item->id,
            'kp_units_idfk' => $unit->id,
            'org_id_fk' => $this->org->id,
            'price_for_member' => 10,
            'price_from_dealer' => 12,
            'status' => 'active',
        ]);

        $response = $this->post(route('keptkayas.sell.store'), [
            'shop_name' => $shop->id,
            'sell_date' => now()->toDateString(),
            'recorder_id' => $this->adminUser->id,
            'details' => [
                [
                    'kp_recycle_item_id' => $item->id,
                    'weight' => 20,
                    'price_per_unit' => 12,
                    'amount' => 240,
                    'comment' => 'รอบเช้า',
                ]
            ]
        ]);

        $response->assertRedirect(route('keptkayas.sell.history'));
        $this->assertEquals(240, $orgAccount->fresh()->balance);
    }
}
