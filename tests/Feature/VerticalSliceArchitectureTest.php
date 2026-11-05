<?php

namespace Tests\Feature;

use App\Features\LineLiff\VerifyLineSession\VerifyLineSessionAction;
use App\Shared\Tenancy\TenantContext;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VerticalSliceArchitectureTest extends TestCase
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
        }
    }

    public function test_tenant_context_can_set_and_get_org_id(): void
    {
        TenantContext::reset();
        $this->assertNull(TenantContext::currentOrgId());

        TenantContext::setOrgId(42);
        $this->assertEquals(42, TenantContext::currentOrgId());

        TenantContext::reset();
        $this->assertNull(TenantContext::currentOrgId());
    }

    public function test_vertical_slice_route_is_auto_discovered(): void
    {
        $this->assertTrue(Route::has('identity_and_onboarding.onboard-citizen.store'));
    }

    public function test_verify_line_session_action_rejects_missing_user(): void
    {
        $action = new VerifyLineSessionAction();
        $request = Request::create('/test', 'GET');

        $result = $action->execute($request, 999999, 1);

        $this->assertFalse($result['authorized']);
        $this->assertEquals(404, $result['status']);
    }
}
