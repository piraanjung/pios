<?php

namespace Tests\Feature;

use App\Models\Admin\BudgetYear;
use App\Models\Admin\Organization;
use App\Models\Admin\Staff;
use App\Models\Admin\Subzone;
use App\Models\Admin\Zone;
use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\KeptKaya\KpRecycleBankMember;
use App\Models\KeptKaya\KpTbankItems;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\KPBankAccount;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Tabwater\MeterTypeRateConfig;
use App\Models\Tabwater\TwAccTransactions;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\Tabwater\TwMeterType;
use App\Models\Tabwater\UndertakerSubzone;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class AccModuleIntegrityTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected Organization $org;
    protected User $staffUser;
    protected Staff $staff;
    protected Zone $zone;
    protected Subzone $subzone;
    protected BudgetYear $budgetYear;
    protected InvoicePeriod $invPeriod;
    protected TwMeterType $meterType;
    protected MeterTypeRateConfig $rateConfig;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.connections.envsogo_main' => config('database.connections.sqlite')]);
        $this->buildStandardTestSchema();

        if (config('database.default') === 'sqlite') {
            if (!Schema::connection('envsogo_main')->hasTable('organizations')) {
                Schema::connection('envsogo_main')->create('organizations', function (Blueprint $table) {
                    $table->id();
                    $table->string('org_code', 50)->nullable();
                    $table->string('org_name', 150);
                    $table->string('org_type_name', 50)->nullable();
                    $table->string('org_logo_img', 150)->nullable();
                    $table->string('org_tambon', 50)->nullable();
                    $table->string('org_district', 50)->nullable();
                    $table->string('org_province', 50)->nullable();
                    $table->timestamps();
                });
            }
            $this->ensureAdditionalTables();
        }

        $this->org = Organization::create([
            'org_code' => 'TEST_ORG',
            'org_name' => 'เทศบาลเมืองทดสอบ',
            'org_type_name' => 'เทศบาลเมือง',
            'org_logo_img' => 'test_logo.png',
        ]);

        $this->staffUser = User::create([
            'org_id_fk' => $this->org->id,
            'username' => 'field_staff_1',
            'prefix' => 'นาย',
            'firstname' => 'สมศักดิ์',
            'lastname' => 'รักบริการ',
            'phone' => '0891234567',
            'password' => bcrypt('password123'),
        ]);

        if (class_exists(Role::class) && Schema::hasTable('roles')) {
            $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
            $this->staffUser->assignRole($role);
        }

        $this->staff = Staff::create([
            'user_id' => $this->staffUser->id,
            'org_id_fk' => $this->org->id,
            'status' => 'active',
            'deleted' => '0',
        ]);

        $this->zone = Zone::create([
            'org_id_fk' => $this->org->id,
            'zone_name' => 'หมู่ 1 โซนเหนือ',
        ]);

        $this->subzone = Subzone::create([
            'org_id_fk' => $this->org->id,
            'zone_id' => $this->zone->id,
            'subzone_name' => 'สายจดมิเตอร์ 1-A',
        ]);

        UndertakerSubzone::create([
            'org_id_fk' => $this->org->id,
            'twman_id' => $this->staff->id,
            'subzone_id' => $this->subzone->id,
        ]);

        $this->budgetYear = BudgetYear::create([
            'org_id_fk' => $this->org->id,
            'budgetyear_name' => '2569',
            'status' => 'active',
        ]);

        $this->invPeriod = InvoicePeriod::create([
            'org_id_fk' => $this->org->id,
            'budgetyear_id' => $this->budgetYear->id,
            'inv_p_name' => '10/2569',
            'inv_p_name_int' => '10-69',
            'status' => 'active',
        ]);

        $this->meterType = TwMeterType::create([
            'org_id_fk' => $this->org->id,
            'meter_type_name' => 'ประเภทบ้านพักอาศัย',
        ]);

        $this->rateConfig = MeterTypeRateConfig::create([
            'org_id_fk' => $this->org->id,
            'meter_type_id_fk' => $this->meterType->id,
            'is_active' => 1,
            'pricing_type_id' => 1,
            'fixed_rate_per_unit' => 10.00,
            'min_usage_charge' => 20.00,
            'vat' => 7.00,
        ]);
    }

    protected function ensureAdditionalTables(): void
    {
        if (!Schema::hasTable('staffs')) {
            Schema::create('staffs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->string('status', 30)->default('active');
                $table->string('deleted', 1)->default('0');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('zones')) {
            Schema::create('zones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->string('zone_name', 191);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subzones')) {
            Schema::create('subzones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('zone_id')->nullable()->index();
                $table->string('subzone_name', 191);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('undertaker_subzones')) {
            Schema::create('undertaker_subzones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('twman_id')->index();
                $table->unsignedBigInteger('subzone_id')->index();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('budget_year')) {
            Schema::create('budget_year', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->string('budgetyear_name', 50);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('invoice_period')) {
            Schema::create('invoice_period', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('budgetyear_id')->nullable()->index();
                $table->string('inv_p_name', 50);
                $table->string('inv_p_name_int', 50)->nullable();
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tw_meter_types')) {
            Schema::create('tw_meter_types', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->string('meter_type_name', 100);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tw_meter_type_rate_configs')) {
            Schema::create('tw_meter_type_rate_configs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('meter_type_id_fk')->index();
                $table->boolean('is_active')->default(true);
                $table->integer('pricing_type_id')->default(1);
                $table->decimal('fixed_rate_per_unit', 10, 2)->default(0);
                $table->decimal('min_usage_charge', 10, 2)->default(0);
                $table->decimal('vat', 5, 2)->default(0);
                $table->boolean('charge_min_only_zero_use')->default(false);
                $table->integer('tier_calculation_type')->default(1);
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('tw_meter_type_rate_configs', 'org_id_fk')) {
            Schema::table('tw_meter_type_rate_configs', function (Blueprint $table) {
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
            });
        }

        if (Schema::hasTable('kp_bank_accounts') && !Schema::hasColumn('kp_bank_accounts', 'entity_type')) {
            Schema::table('kp_bank_accounts', function (Blueprint $table) {
                $table->string('entity_type', 50)->nullable()->default('recycle_bank');
            });
        }

        if (Schema::hasTable('kp_purchase_transactions')) {
            if (!Schema::hasColumn('kp_purchase_transactions', 'total_carbon_saved')) {
                Schema::table('kp_purchase_transactions', function (Blueprint $table) {
                    $table->decimal('total_carbon_saved', 10, 4)->default(0);
                });
            }
            if (!Schema::hasColumn('kp_purchase_transactions', 'cash_back')) {
                Schema::table('kp_purchase_transactions', function (Blueprint $table) {
                    $table->boolean('cash_back')->default(false);
                });
            }
        }

        if (!Schema::hasTable('tw_invoice_history')) {
            Schema::create('tw_invoice_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('inv_period_id_fk')->index();
                $table->unsignedBigInteger('meter_id_fk')->index();
                $table->decimal('water_used', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tw_meter_infos')) {
            Schema::create('tw_meter_infos', function (Blueprint $table) {
                $table->id('meter_id');
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('meternumber', 50)->nullable();
                $table->string('factory_no', 50)->nullable();
                $table->string('submeter_name', 100)->nullable();
                $table->string('meter_address', 191)->nullable();
                $table->unsignedBigInteger('undertake_zone_id')->nullable()->index();
                $table->unsignedBigInteger('undertake_subzone_id')->nullable()->index();
                $table->unsignedBigInteger('metertype_id')->nullable()->index();
                $table->decimal('last_meter_recording', 10, 2)->default(0);
                $table->integer('owe_count')->default(0);
                $table->integer('cutmeter')->default(0);
                $table->string('inv_no_index', 50)->nullable();
                $table->unsignedBigInteger('recorder_id')->nullable();
                $table->string('status', 30)->default('active');
                $table->timestamp('acceptance_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tw_invoice')) {
            Schema::create('tw_invoice', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('inv_period_id_fk')->index();
                $table->unsignedBigInteger('meter_id_fk')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->decimal('lastmeter', 10, 2)->default(0);
                $table->decimal('currentmeter', 10, 2)->default(0);
                $table->decimal('water_used', 10, 2)->default(0);
                $table->decimal('paid', 10, 2)->default(0);
                $table->decimal('reserve_meter', 10, 2)->default(0);
                $table->decimal('vat', 10, 2)->default(0);
                $table->decimal('totalpaid', 10, 2)->default(0);
                $table->unsignedBigInteger('acc_trans_id_fk')->nullable()->index();
                $table->unsignedBigInteger('recorder_id')->nullable();
                $table->string('status', 30)->default('init');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('tw_acc_transactions')) {
            Schema::create('tw_acc_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('meter_id_fk')->nullable()->index();
                $table->decimal('paidsum', 10, 2)->default(0);
                $table->decimal('vatsum', 10, 2)->default(0);
                $table->decimal('reserve_meter_sum', 10, 2)->default(0);
                $table->decimal('totalpaidsum', 10, 2)->default(0);
                $table->string('status', 30)->default('1');
                $table->unsignedBigInteger('cashier')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_tbank_items_groups')) {
            Schema::create('kp_tbank_items_groups', function (Blueprint $table) {
                $table->id();
                $table->string('kp_items_groupname', 191);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_tbank_items_units')) {
            Schema::create('kp_tbank_items_units', function (Blueprint $table) {
                $table->id();
                $table->string('unitname', 50);
                $table->string('unit_short_name', 20)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_tbank_items_pricepoint')) {
            Schema::create('kp_tbank_items_pricepoint', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('kp_items_idfk')->index();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->decimal('price_for_member', 10, 2)->default(0);
                $table->decimal('price_from_dealer', 10, 2)->default(0);
                $table->integer('point')->default(0);
                $table->string('type', 20)->default('tbank');
                $table->date('effective_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_purchase_transactions_details')) {
            Schema::create('kp_purchase_transactions_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('kp_purchase_trans_id')->index();
                $table->unsignedBigInteger('kp_recycle_item_id')->nullable();
                $table->unsignedBigInteger('kp_tbank_items_pricepoint_id')->nullable();
                $table->unsignedBigInteger('recorder_id')->nullable();
                $table->decimal('amount_in_units', 10, 2)->default(0);
                $table->unsignedBigInteger('kp_units_idfk')->nullable();
                $table->decimal('price_per_unit', 10, 2)->default(0);
                $table->decimal('amount', 10, 2)->default(0);
                $table->integer('points')->default(0);
                $table->decimal('carbon_saved', 10, 4)->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('kp_purchase_transactions_details')) {
            if (!Schema::hasColumn('kp_purchase_transactions_details', 'recorder_id')) {
                Schema::table('kp_purchase_transactions_details', function (Blueprint $table) {
                    $table->unsignedBigInteger('recorder_id')->nullable();
                });
            }
            if (!Schema::hasColumn('kp_purchase_transactions_details', 'carbon_saved')) {
                Schema::table('kp_purchase_transactions_details', function (Blueprint $table) {
                    $table->decimal('carbon_saved', 10, 4)->default(0);
                });
            }
        }

        if (!Schema::hasTable('tw_cutmeter')) {
            Schema::create('tw_cutmeter', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('meter_id_fk')->index();
                $table->string('status', 30)->default('pending');
                $table->integer('owe_count')->default(0);
                $table->integer('warning_print')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_recycle_bank_members')) {
            Schema::create('kp_recycle_bank_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->decimal('balance', 12, 2)->default(0.00);
                $table->integer('points')->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('kp_tbank_items')) {
            Schema::create('kp_tbank_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->nullable()->index();
                $table->unsignedBigInteger('kp_items_group_idfk')->index();
                $table->unsignedBigInteger('unit_bank_idfk')->nullable();
                $table->unsignedBigInteger('unit_kiosk_idfk')->nullable();
                $table->unsignedBigInteger('ef_id_fk')->nullable();
                $table->string('kp_itemscode', 50)->nullable();
                $table->string('kp_itemsname', 191);
                $table->string('status', 30)->default('active');
                $table->softDeletes();
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('kp_tbank_items', 'deleted_at')) {
            Schema::table('kp_tbank_items', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('emission_factors')) {
            Schema::create('emission_factors', function (Blueprint $table) {
                $table->id();
                $table->decimal('ef_value', 10, 4)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('org_invoice_sequences')) {
            Schema::create('org_invoice_sequences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('org_id_fk')->index();
                $table->integer('year');
                $table->integer('last_running_no')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);
            });
        }
        if (!Schema::hasTable('model_has_permissions')) {
            Schema::create('model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->index(['model_id', 'model_type']);
            });
        }
    }

    public function test_meter_records_does_not_contain_hardcoded_fake_owes(): void
    {
        $citizen = User::create([
            'org_id_fk' => $this->org->id,
            'username' => 'citizen_water_1',
            'prefix' => 'นาย',
            'firstname' => 'ประสิทธิ์',
            'lastname' => 'มั่นคง',
            'address' => '99/1',
        ]);

        $meter = TwMeterInfos::create([
            'meter_id' => 101,
            'org_id_fk' => $this->org->id,
            'user_id' => $citizen->id,
            'meternumber' => 'TW-001-0101',
            'meter_address' => '99/1 หมู่ 1',
            'undertake_zone_id' => $this->zone->id,
            'undertake_subzone_id' => $this->subzone->id,
            'metertype_id' => $this->meterType->id,
            'last_meter_recording' => 50,
            'status' => 'active',
        ]);

        $invoice = TwInvoice::create([
            'org_id_fk' => $this->org->id,
            'inv_period_id_fk' => $this->invPeriod->id,
            'meter_id_fk' => $meter->id,
            'user_id' => $citizen->id,
            'lastmeter' => 50,
            'status' => 'init',
        ]);

        Auth::login($this->staffUser);

        $response = $this->postJson('/api/staff/tabwater/meter-records', [
            'invoice_id' => $invoice->id,
            'current_reading' => 60, // 10 units
            'meter_record_token' => 'token-test-123',
        ]);

        $response->assertStatus(200);
        $data = $response->json('data');

        $this->assertEquals(0, $data['owe_paid_sum'], 'Owe paid sum must not be hardcoded to 300.');
        $this->assertEmpty($data['owes'], 'Owes list must not contain hardcoded fake 09-69 and 08-69 periods.');
        $this->assertEquals('128.40', $data['net_paid'], '10 units * 10 + 20 reserve = 120 + 7% vat (8.40) = 128.40 net');
    }

    public function test_meter_records_validates_required_meter_and_user_with_error_and_workaround(): void
    {
        // An invoice with non-existent meter_id_fk
        $orphanInvoice = TwInvoice::create([
            'org_id_fk' => $this->org->id,
            'inv_period_id_fk' => $this->invPeriod->id,
            'meter_id_fk' => 999999, // non existent meter
            'status' => 'init',
        ]);

        Auth::login($this->staffUser);

        $response = $this->postJson('/api/staff/tabwater/meter-records', [
            'invoice_id' => $orphanInvoice->id,
            'current_reading' => 20,
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('ไม่พบข้อมูลมิเตอร์', $response->json('message'));
    }

    public function test_staff_accessmenu_syncs_session_when_authenticated(): void
    {
        Auth::login($this->staffUser);

        $response = $this->get(route('staff_accessmenu'));
        $response->assertStatus(200);
        $response->assertSee('window.INITIAL_STAFF_SESSION', false);
        $response->assertSee($this->staffUser->firstname);
    }

    public function test_api_keptkaya_store_purchase_has_idempotency_and_syncs_both_ledgers(): void
    {
        $citizen = User::create([
            'org_id_fk' => $this->org->id,
            'firstname' => 'สมปอง',
            'lastname' => 'รักษ์โลก',
        ]);

        $pref = KpUserWastePreference::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $citizen->id,
            'is_recycle_bank' => true,
        ]);

        $bankAcc = KPBankAccount::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $citizen->id,
            'account_no' => 'ACC-000001',
            'entity_type' => 'recycle_bank',
            'balance' => 0,
            'points' => 0,
        ]);

        $member = KpRecycleBankMember::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $citizen->id,
            'balance' => 0,
            'points' => 0,
        ]);

        $orgBankAcc = KPBankAccount::create([
            'org_id_fk' => $this->org->id,
            'user_id' => $this->staffUser->id,
            'account_no' => 'ORG-BANK-001',
            'entity_type' => 'org_recycle_bank',
            'balance' => 5000,
            'points' => 0,
        ]);

        $group = DB::table('kp_tbank_items_groups')->insertGetId([
            'kp_items_groupname' => 'พลาสติก',
            'status' => 'active',
        ]);

        $unit = DB::table('kp_tbank_items_units')->insertGetId([
            'unitname' => 'กิโลกรัม',
            'unit_short_name' => 'กก.',
        ]);

        $item = KpTbankItems::create([
            'kp_items_group_idfk' => $group,
            'unit_bank_idfk' => $unit,
            'kp_itemscode' => 'P001',
            'kp_itemsname' => 'ขวดพลาสติกใส',
            'status' => 'active',
        ]);

        $pricePoint = DB::table('kp_tbank_items_pricepoint')->insertGetId([
            'kp_items_idfk' => $item->id,
            'org_id_fk' => $this->org->id,
            'price_for_member' => 10.00,
            'price_from_dealer' => 12.00,
            'point' => 5,
            'type' => 'tbank',
            'status' => 'active',
        ]);

        Auth::login($this->staffUser);

        $payload = [
            'org_id_fk' => $this->org->id,
            'kp_user_w_pref_id_fk' => $pref->id,
            'user_id' => $citizen->id,
            'recorder_id' => $this->staff->id,
            'transaction_token' => 'trans-token-unique-123',
            'total_weight' => 2.0,
            'cashback' => false,
            'total_amount' => 20.0,
            'total_points' => 10,
            'cart_items' => [
                [
                    'kp_recycle_item_id' => $item->id,
                    'kp_tbank_items_pricepoint_id' => $pricePoint,
                    'amount_in_units' => 2.0,
                    'kp_units_idfk' => $unit,
                    'price_per_unit' => 10.0,
                    'amount' => 20.0,
                    'points' => 10,
                    'carbon_saved' => 0,
                ],
            ],
        ];

        $response = $this->postJson('/api/keptkaya/store_purchase', $payload);
        $response->assertStatus(200);

        // Assert balance and points in both KPBankAccount and KpRecycleBankMember
        $bankAcc->refresh();
        $member->refresh();
        $this->assertEquals(20.00, (float) $bankAcc->balance);
        $this->assertEquals(10, $bankAcc->points);
        $this->assertEquals(20.00, (float) $member->balance);
        $this->assertEquals(10, $member->points);

        // Org bank account deducted
        $orgBankAcc->refresh();
        $this->assertEquals(4980.00, (float) $orgBankAcc->balance);

        // Idempotency test: Re-submitting same transaction_token does NOT double-credit
        $resReplay = $this->postJson('/api/keptkaya/store_purchase', $payload);
        $resReplay->assertStatus(200);

        $bankAcc->refresh();
        $member->refresh();
        $this->assertEquals(20.00, (float) $bankAcc->balance, 'Balance must not double on idempotent replay.');
        $this->assertEquals(10, $bankAcc->points, 'Points must not double on idempotent replay.');
    }

    public function test_access_menu_dashboard_scoped_to_auth_org(): void
    {
        $otherOrg = Organization::create([
            'org_code' => 'OTHER_ORG',
            'org_name' => 'อบต. อื่น',
        ]);

        $otherZone = Zone::create([
            'org_id_fk' => $otherOrg->id,
            'zone_name' => 'โซน อบต. อื่น',
        ]);

        $otherCitizen = User::create([
            'org_id_fk' => $otherOrg->id,
            'firstname' => 'คนนอก',
            'lastname' => 'สังกัด',
        ]);

        $otherMeter = TwMeterInfos::create([
            'meter_id' => 999,
            'org_id_fk' => $otherOrg->id,
            'user_id' => $otherCitizen->id,
            'undertake_zone_id' => $otherZone->id,
            'status' => 'active',
        ]);

        $otherInvoice = TwInvoice::create([
            'org_id_fk' => $otherOrg->id,
            'inv_period_id_fk' => $this->invPeriod->id,
            'meter_id_fk' => $otherMeter->id,
            'user_id' => $otherCitizen->id,
            'water_used' => 500,
            'totalpaid' => 5000,
            'status' => 'paid',
        ]);

        Auth::login($this->staffUser);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        // View must receive subzone_current_chart or subzone_payment_chart_data without leaking otherOrg's data
        $response->assertViewHas('subzone_current_chart');
        $paidTotal = $response->viewData('paid_total');
        $this->assertEquals(0, $paidTotal, 'Other org paid total must not leak into current org dashboard.');
    }

    public function test_payment_controller_store_saves_org_id_and_has_idempotency(): void
    {
        $citizen = User::create([
            'org_id_fk' => $this->org->id,
            'firstname' => 'ธนา',
            'lastname' => 'จ่ายเงิน',
        ]);

        $meter = TwMeterInfos::create([
            'meter_id' => 201,
            'org_id_fk' => $this->org->id,
            'user_id' => $citizen->id,
            'undertake_zone_id' => $this->zone->id,
            'undertake_subzone_id' => $this->subzone->id,
            'meternumber' => 'TW-001-0201',
            'status' => 'active',
        ]);

        $invoice = TwInvoice::create([
            'org_id_fk' => $this->org->id,
            'inv_period_id_fk' => $this->invPeriod->id,
            'meter_id_fk' => $meter->meter_id,
            'user_id' => $citizen->id,
            'paid' => 100,
            'vat' => 7,
            'reserve_meter' => 20,
            'totalpaid' => 127,
            'status' => 'invoice',
        ]);

        Auth::login($this->staffUser);

        $payload = [
            'meter_id' => $meter->meter_id,
            'payment_token' => 'pay-token-unique-999',
            'payments' => [
                ['on' => '1', 'iv_id' => $invoice->id],
            ],
        ];

        $response = $this->post(route('payment.store'), $payload);
        $response->assertStatus(200);

        $trans = TwAccTransactions::where('meter_id_fk', $meter->meter_id)->first();
        $this->assertNotNull($trans);
        $this->assertEquals($this->org->id, $trans->org_id_fk, 'org_id_fk must be set on TwAccTransactions.');

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
        $this->assertEquals($trans->id, $invoice->acc_trans_id_fk);

        // Double cancel test
        $this->delete(route('payment.destroy', $trans->id));
        $trans->refresh();
        $this->assertEquals('2', $trans->status);

        // Deleting again must not double increment owe_count
        $meter->refresh();
        $firstOweCount = $meter->owe_count;
        $this->delete(route('payment.destroy', $trans->id));
        $meter->refresh();
        $this->assertEquals($firstOweCount, $meter->owe_count, 'Owe count must not double increment on repeated cancellation.');
    }
}
