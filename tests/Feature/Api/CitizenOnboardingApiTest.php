<?php

namespace Tests\Feature\Api;

use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class CitizenOnboardingApiTest extends TestCase
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

    public function test_fails_validation_when_required_names_are_missing(): void
    {
        $response = $this->postJson('/features/onboard-citizen', [
            'org_id_fk' => 1,
            // ขาด firstname และ lastname
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['firstname', 'lastname']);
    }

    public function test_successfully_onboards_citizen_via_api(): void
    {
        $response = $this->postJson('/features/onboard-citizen', [
            'org_id_fk'           => 1,
            'firstname'           => 'สุริยา',
            'lastname'            => 'ส่องแสง',
            'prefix'              => 'นาย',
            'id_card'             => '1100500123456',
            'phone'               => '0891234567',
            'enable_recycle_bank' => true,
            'enable_welfare'      => true,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user_id',
                    'username',
                    'account_no',
                    'member_number',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'firstname' => 'สุริยา',
            'lastname'  => 'ส่องแสง',
            'id_card'   => '1100500123456',
        ]);
    }
}
