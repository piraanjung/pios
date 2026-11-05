<?php

namespace Tests\Feature\Api;

use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class KioskTransactionApiTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildStandardTestSchema();
        $this->resetTenancy();
    }

    protected function tearDown(): void
    {
        $this->resetTenancy();
        parent::tearDown();
    }

    public function test_health_check_returns_online_status(): void
    {
        $response = $this->getJson('/api/health-check');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data'    => [
                    'status' => 'online',
                ],
            ])
            ->assertJsonStructure(['success', 'message', 'data', 'errors', 'meta']);
    }

    public function test_kiosk_transaction_rejects_missing_user_id(): void
    {
        $response = $this->postJson('/api/kiosk/submit-transaction', [
            'totalPoints' => 10,
            'totalPrice'  => 5,
            'machineId'   => 'KIOSK-01',
            'items'       => json_encode([['rateId' => 1, 'price' => 5, 'point' => 10, 'confidence' => 95]]),
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_kiosk_transaction_rejects_empty_items(): void
    {
        $citizen = $this->createCitizenForOrg(1);

        $response = $this->postJson('/api/kiosk/submit-transaction', [
            'userId'      => $citizen->id,
            'totalPoints' => 0,
            'totalPrice'  => 0,
            'machineId'   => 'KIOSK-01',
            'items'       => [],
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'ไม่พบข้อมูลรายการขยะในตระกร้า หรือโครงสร้างข้อมูลผิดพลาด',
            ]);
    }

    public function test_kiosk_transaction_rejects_non_existent_user(): void
    {
        $response = $this->postJson('/api/kiosk/submit-transaction', [
            'userId'      => 999999,
            'totalPoints' => 10,
            'totalPrice'  => 5,
            'machineId'   => 'KIOSK-01',
            'items'       => [['rateId' => 1, 'price' => 5, 'point' => 10]],
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'ไม่พบบัญชีผู้ใช้งานในระบบ',
            ]);
    }
}
