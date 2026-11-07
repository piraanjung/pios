<?php

namespace Tests\Integration\Features\IdentityAndOnboarding;

use App\Features\IdentityAndOnboarding\OnboardCitizen\OnboardCitizenAction;
use App\Features\IdentityAndOnboarding\OnboardCitizen\OnboardCitizenDTO;
use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\KPBankAccount;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\User;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class OnboardCitizenActionTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected OnboardCitizenAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildStandardTestSchema();
        $this->action = new OnboardCitizenAction();
        $this->resetTenancy();
    }

    protected function tearDown(): void
    {
        $this->resetTenancy();
        parent::tearDown();
    }

    public function test_successfully_onboards_citizen_with_all_services(): void
    {
        $dto = OnboardCitizenDTO::fromArray([
            'org_id_fk'           => 10,
            'firstname'           => 'สมชาย',
            'lastname'            => 'รักสงบ',
            'prefix'              => 'นาย',
            'id_card'             => '1234567890123',
            'phone'               => '0812345678',
            'email'               => 'somchai@example.com',
            'address'             => '123 หมู่ 4',
            'enable_recycle_bank' => true,
            'enable_welfare'      => true,
            'enable_food_waste'   => true,
        ]);

        $result = $this->action->execute($dto);

        $this->assertTrue($result['success']);
        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertSame('สมชาย', $result['user']->firstname);
        $this->assertSame(10, (int) $result['user']->org_id_fk);

        // ตรวจสอบข้อมูลในตาราง users
        $this->assertDatabaseHas('users', [
            'id'        => $result['user']->id,
            'org_id_fk' => 10,
            'id_card'   => '1234567890123',
            'phone'     => '0812345678',
        ]);

        // ตรวจสอบข้อมูลธนาคารขยะและบัญชีกลาง
        $this->assertInstanceOf(KpUserWastePreference::class, $result['waste_preference']);
        $this->assertInstanceOf(KPBankAccount::class, $result['bank_account']);
        $this->assertStringStartsWith('ACC-0010-', $result['bank_account']->account_no);
        $this->assertStringStartsWith('MBR-0010-', $result['bank_account']->member_number);
        $this->assertEquals(0.00, $result['bank_account']->balance);
        $this->assertSame(0, $result['bank_account']->points);

        // ตรวจสอบถังขยะเปียก
        $this->assertInstanceOf(FoodWasteUserPreference::class, $result['food_waste_pref']);
        $this->assertDatabaseHas('foodwaste_user_preferences', [
            'user_id'   => $result['user']->id,
            'org_id_fk' => 10,
        ]);
    }

    public function test_onboards_citizen_without_waste_services_when_disabled(): void
    {
        $dto = OnboardCitizenDTO::fromArray([
            'org_id_fk'           => 5,
            'firstname'           => 'สายใจ',
            'lastname'            => 'มั่นคง',
            'enable_recycle_bank' => false,
            'enable_welfare'      => false,
            'enable_food_waste'   => false,
        ]);

        $result = $this->action->execute($dto);

        $this->assertTrue($result['success']);
        $this->assertNull($result['waste_preference']);
        $this->assertNull($result['bank_account']);
        $this->assertNull($result['food_waste_pref']);

        $this->assertDatabaseHas('users', [
            'id'        => $result['user']->id,
            'firstname' => 'สายใจ',
        ]);
    }
}
