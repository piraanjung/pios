<?php

namespace Tests\Unit\Shared;

use App\Shared\Tenancy\TenantContext;
use PHPUnit\Framework\TestCase;

class TenantContextUnitTest extends TestCase
{
    protected function tearDown(): void
    {
        TenantContext::reset();
        parent::tearDown();
    }

    public function test_tenant_context_defaults_to_null_when_empty(): void
    {
        TenantContext::reset();
        $this->assertNull(TenantContext::currentOrgId());
    }

    public function test_tenant_context_stores_and_returns_org_id(): void
    {
        TenantContext::setOrgId(101);
        $this->assertSame(101, TenantContext::currentOrgId());
    }

    public function test_tenant_context_can_be_reset(): void
    {
        TenantContext::setOrgId(999);
        $this->assertSame(999, TenantContext::currentOrgId());

        TenantContext::reset();
        $this->assertNull(TenantContext::currentOrgId());
    }
}
