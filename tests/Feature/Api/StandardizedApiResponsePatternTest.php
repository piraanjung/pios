<?php

namespace Tests\Feature\Api;

use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class StandardizedApiResponsePatternTest extends TestCase
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

    public function test_health_check_follows_standard_envelope(): void
    {
        $response = $this->getJson('/api/health-check');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'errors',
                'meta' => [
                    'timestamp',
                    'org_id',
                    'version',
                ],
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertNull($response->json('errors'));
        $this->assertSame('v1', $response->json('meta.version'));
    }

    public function test_validation_error_follows_standard_envelope(): void
    {
        // ยิง request โดยขาด firstname, lastname
        $response = $this->postJson('/features/onboard-citizen', [
            'org_id_fk' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'errors' => [
                    'firstname',
                    'lastname',
                ],
                'meta' => [
                    'timestamp',
                    'org_id',
                    'version',
                ],
            ]);

        $this->assertFalse($response->json('success'));
        $this->assertNull($response->json('data'));
        $this->assertNotNull($response->json('errors.firstname'));
    }

    public function test_kiosk_error_responses_follow_standard_envelope(): void
    {
        // 1. Missing userId -> 422
        $res422 = $this->postJson('/api/kiosk/submit-transaction', [
            'totalPoints' => 10,
            'totalPrice'  => 5,
            'machineId'   => 'KIOSK-01',
            'items'       => [['rateId' => 1]],
        ]);

        $res422->assertStatus(422);
        $this->assertFalse($res422->json('success'));
        $this->assertArrayHasKey('meta', $res422->json());

        // 2. Empty items -> 400
        $citizen = $this->createCitizenForOrg(1);
        $res400 = $this->postJson('/api/kiosk/submit-transaction', [
            'userId'      => $citizen->id,
            'totalPoints' => 0,
            'totalPrice'  => 0,
            'machineId'   => 'KIOSK-01',
            'items'       => [],
        ]);

        $res400->assertStatus(400);
        $this->assertFalse($res400->json('success'));
        $this->assertArrayHasKey('meta', $res400->json());

        // 3. User not found -> 404
        $res404 = $this->postJson('/api/kiosk/submit-transaction', [
            'userId'      => 999999,
            'totalPoints' => 10,
            'totalPrice'  => 5,
            'machineId'   => 'KIOSK-01',
            'items'       => [['rateId' => 1]],
        ]);

        $res404->assertStatus(404);
        $this->assertFalse($res404->json('success'));
        $this->assertArrayHasKey('meta', $res404->json());
    }

    public function test_successful_onboarding_api_follows_standard_envelope(): void
    {
        $response = $this->postJson('/features/onboard-citizen', [
            'org_id_fk'           => 1,
            'firstname'           => 'เกรียงไกร',
            'lastname'            => 'มั่นคง',
            'enable_recycle_bank' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user_id',
                    'username',
                    'account_no',
                    'member_number',
                ],
                'errors',
                'meta' => [
                    'timestamp',
                    'org_id',
                    'version',
                ],
            ]);

        $this->assertTrue($response->json('success'));
        $this->assertNull($response->json('errors'));
    }
}
