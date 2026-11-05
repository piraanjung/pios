<?php

namespace Tests\Integration\Features\LineLiff;

use App\Features\LineLiff\VerifyLineSession\VerifyLineSessionAction;
use Illuminate\Http\Request;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class VerifyLineSessionActionTest extends TestCase
{
    use BuildsTestSchema, InteractsWithTenancy;

    protected VerifyLineSessionAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buildStandardTestSchema();
        $this->action = new VerifyLineSessionAction();
        $this->resetTenancy();
    }

    protected function tearDown(): void
    {
        $this->resetTenancy();
        parent::tearDown();
    }

    public function test_non_existent_target_user_returns_404(): void
    {
        $request = Request::create('/line/test', 'GET');
        $result = $this->action->execute($request, targetUserId: 999999, orgId: 1);

        $this->assertFalse($result['authorized']);
        $this->assertSame(404, $result['status']);
        $this->assertNull($result['user']);
    }

    public function test_line_user_id_mismatch_returns_403(): void
    {
        $orgId = 1;
        $citizen = $this->createCitizenForOrg($orgId, [
            'line_user_id' => 'U_LEGITIMATE_123',
        ]);

        // Hacker พยายามส่ง line_user_id คนอื่นมา
        $request = Request::create('/line/test', 'GET', ['line_user_id' => 'U_ATTACKER_999']);
        $result = $this->action->execute($request, targetUserId: $citizen->id, orgId: $orgId);

        $this->assertFalse($result['authorized']);
        $this->assertSame(403, $result['status']);
        $this->assertStringContainsString('ไม่ตรงกับข้อมูลเจ้าของบัญชี', $result['message']);
    }

    public function test_verified_line_user_id_returns_200_and_authorized(): void
    {
        $orgId = 1;
        $citizen = $this->createCitizenForOrg($orgId, [
            'line_user_id' => 'U_AUTHENTIC_CITIZEN',
        ]);

        $request = Request::create('/line/test', 'GET', ['line_user_id' => 'U_AUTHENTIC_CITIZEN']);
        $result = $this->action->execute($request, targetUserId: $citizen->id, orgId: $orgId);

        $this->assertTrue($result['authorized']);
        $this->assertSame(200, $result['status']);
        $this->assertSame($citizen->id, $result['user']->id);
    }
}
