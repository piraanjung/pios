<?php

namespace Tests\Feature;

use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SecurityAndRuntimeHotfixesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'sqlite') {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('line_user_id')->nullable();
                $table->unsignedBigInteger('org_id_fk')->nullable();
                $table->timestamps();
            });

            Schema::create('kp_user_waste_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('org_id_fk');
                $table->timestamps();
            });
        }
    }

    public function test_user_model_has_waste_preference_and_org_aliases(): void
    {
        $user = new User();

        $this->assertInstanceOf(HasOne::class, $user->wastePreference());
        $this->assertInstanceOf(BelongsTo::class, $user->org());
        $this->assertEquals(0.0, $user->wallet_balance);
    }

    public function test_food_waste_user_preference_has_proper_casts_array(): void
    {
        $pref = new FoodWasteUserPreference();
        $casts = $pref->getCasts();

        $this->assertArrayHasKey('user_id', $casts);
        $this->assertEquals('integer', $casts['user_id']);
    }

    public function test_kiosk_submit_transaction_rejects_missing_user_id(): void
    {
        $response = $this->postJson('/api/kiosk/submit-transaction', [
            'totalPoints' => 10,
            'totalPrice' => 5,
            'machineId' => 'KIOSK-01',
            'items' => json_encode([['rateId' => 1, 'price' => 5, 'point' => 10, 'confidence' => 95]]),
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_line_liff_dashboard_denies_unverified_access(): void
    {
        $response = $this->get('/line/dashboard/999999/1');

        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    public function test_routes_compile_cleanly_without_empty_parameter_names(): void
    {
        $this->assertTrue(Route::has('admin.super_users.index'));
        $this->assertTrue(Route::has('keptkayas.kp_usergroup.index'));
        $this->assertTrue(Route::has('usermeter_infos.store_edited_invoice'));
    }

    public function test_keptkaya_models_use_strict_psr4_case_sensitive_namespaces(): void
    {
        $this->assertTrue(class_exists(\App\Models\KeptKaya\KpRecycleBankMember::class));
        $this->assertTrue(class_exists(\App\Models\KPBankAccount::class));

        $reflection = new \ReflectionClass(\App\Models\KeptKaya\KpRecycleBankMember::class);
        $this->assertSame('App\\Models\\KeptKaya\\KpRecycleBankMember', $reflection->getName());
    }

    public function test_plan_first_hard_gate_rules_are_enforced(): void
    {
        $planFirstRulePath = base_path('.agents/rules/plan-first-gate.md');
        $this->assertFileExists($planFirstRulePath);

        $geminiMd = file_get_contents(base_path('GEMINI.md'));
        $this->assertStringContainsString('Universal Plan-First Hard Gate', $geminiMd);
        $this->assertStringContainsString('.agents/rules/plan-first-gate.md', $geminiMd);

        $autonomousEditingMd = file_get_contents(base_path('.agents/rules/autonomous-editing.md'));
        $this->assertStringContainsString('ONLY AFTER the user has explicitly approved the plan', $autonomousEditingMd);
    }
}

