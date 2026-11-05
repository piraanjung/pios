<?php

namespace Tests\Unit\Features\IdentityAndOnboarding;

use App\Features\IdentityAndOnboarding\OnboardCitizen\OnboardCitizenDTO;
use PHPUnit\Framework\TestCase;

class OnboardCitizenDTOTest extends TestCase
{
    public function test_can_instantiate_and_read_dto_properties(): void
    {
        $dto = new OnboardCitizenDTO(
            orgId: 10,
            firstname: 'สมชาย',
            lastname: 'ใจดี',
            prefix: 'นาย',
            idCard: '1234567890123',
            phone: '0812345678',
            enableRecycleBank: true,
            enableWelfare: true,
        );

        $this->assertEquals(10, $dto->orgId);
        $this->assertEquals('สมชาย', $dto->firstname);
        $this->assertEquals('ใจดี', $dto->lastname);
        $this->assertEquals('1234567890123', $dto->idCard);
        $this->assertTrue($dto->enableRecycleBank);
        $this->assertTrue($dto->enableWelfare);
    }

    public function test_can_instantiate_from_array(): void
    {
        $dto = OnboardCitizenDTO::fromArray([
            'org_id_fk'           => 5,
            'firstname'           => 'สมหญิง',
            'lastname'            => 'รักสงบ',
            'phone'               => '0899999999',
            'enable_recycle_bank' => false,
            'enable_welfare'      => true,
            'enable_food_waste'   => true,
        ]);

        $this->assertEquals(5, $dto->orgId);
        $this->assertEquals('สมหญิง', $dto->firstname);
        $this->assertFalse($dto->enableRecycleBank);
        $this->assertTrue($dto->enableWelfare);
        $this->assertTrue($dto->enableFoodWaste);
    }
}
