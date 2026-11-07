<?php

namespace Tests\Integration\Features\Welfare;

use App\Features\Welfare\CheckMemberEligibility\CheckMemberEligibilityAction;
use Carbon\Carbon;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class CheckMemberEligibilityActionTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected CheckMemberEligibilityAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildStandardTestSchema();
        $this->action = new CheckMemberEligibilityAction();
        $this->resetTenancy();
    }

    protected function tearDown(): void
    {
        $this->resetTenancy();
        parent::tearDown();
    }

    public function test_non_existent_citizen_returns_ineligible(): void
    {
        $result = $this->action->execute(userId: 9999, orgId: 1);

        $this->assertFalse($result['eligible']);
        $this->assertStringContainsString('ไม่พบข้อมูลประชาชน', $result['reason']);
    }

    public function test_cross_tenant_citizen_returns_ineligible(): void
    {
        // สร้างผู้ใช้อยู่ Org 2
        $citizen = $this->createCitizenForOrg(2);

        // แต่ค้นหาด้วย Org 1
        $result = $this->action->execute(userId: $citizen->id, orgId: 1);

        $this->assertFalse($result['eligible']);
    }

    public function test_new_member_under_180_days_returns_ineligible(): void
    {
        $orgId = 1;
        $citizenData = $this->createCitizenWithRecycleAccount($orgId, initialBalance: 100.0, initialPoints: 50);
        $user = $citizenData['user'];
        $account = $citizenData['account'];

        // ตั้งเวลาสร้างบัญชีเป็น 30 วันที่แล้ว
        $account->created_at = Carbon::now()->subDays(30);
        $account->save();

        $result = $this->action->execute(userId: $user->id, orgId: $orgId, requiredDays: 180);

        $this->assertFalse($result['eligible']);
        $this->assertLessThan(180, $result['membership_days']);
        $this->assertStringContainsString('อายุการเป็นสมาชิกไม่ครบกำหนด', $result['reason']);
    }

    public function test_mature_member_over_180_days_returns_eligible_with_details(): void
    {
        $orgId = 1;
        $citizenData = $this->createCitizenWithRecycleAccount($orgId, initialBalance: 3500.0, initialPoints: 720);
        $user = $citizenData['user'];
        $account = $citizenData['account'];

        // ตั้งเวลาสร้างบัญชีเป็น 200 วันที่แล้ว
        $account->created_at = Carbon::now()->subDays(200);
        $account->save();

        $result = $this->action->execute(userId: $user->id, orgId: $orgId, requiredDays: 180);

        $this->assertTrue($result['eligible']);
        $this->assertGreaterThanOrEqual(180, $result['membership_days']);
        $this->assertSame(3500.0, $result['balance']);
        $this->assertSame(720, $result['points']);
        $this->assertSame($account->member_number, $result['member_number']);
    }
}
