<?php

namespace Tests\Unit\Features\Welfare;

use App\Shared\Domain\Welfare\MembershipDurationRule;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class MembershipDurationRuleTest extends TestCase
{
    protected MembershipDurationRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new MembershipDurationRule();
    }

    public function test_boundary_179_days_is_ineligible(): void
    {
        $joinedDate = Carbon::parse('2026-01-01');
        $asOfDate = $joinedDate->copy()->addDays(179);

        $result = $this->rule->isSatisfied($joinedDate, $asOfDate, requiredDays: 180);

        $this->assertFalse($result['eligible']);
        $this->assertSame(179, (int) $result['membership_days']);
        $this->assertSame(1, (int) $result['remaining_days']);
        $this->assertStringContainsString('ขาดอีก 1 วัน', $result['reason']);
    }

    public function test_boundary_180_days_is_eligible(): void
    {
        $joinedDate = Carbon::parse('2026-01-01');
        $asOfDate = $joinedDate->copy()->addDays(180);

        $result = $this->rule->isSatisfied($joinedDate, $asOfDate, requiredDays: 180);

        $this->assertTrue($result['eligible']);
        $this->assertSame(180, (int) $result['membership_days']);
        $this->assertSame(0, (int) $result['remaining_days']);
        $this->assertStringContainsString('ครบตามเกณฑ์', $result['reason']);
    }

    public function test_181_days_is_eligible(): void
    {
        $joinedDate = Carbon::parse('2026-01-01');
        $asOfDate = $joinedDate->copy()->addDays(365);

        $result = $this->rule->isSatisfied($joinedDate, $asOfDate, requiredDays: 180);

        $this->assertTrue($result['eligible']);
        $this->assertSame(365, (int) $result['membership_days']);
    }
}
