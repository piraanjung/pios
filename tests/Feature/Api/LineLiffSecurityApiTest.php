<?php

namespace Tests\Feature\Api;

use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\User;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class LineLiffSecurityApiTest extends TestCase
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

    public function test_non_existent_preference_returns_404(): void
    {
        $response = $this->get('/line/dashboard/999999/1');

        $this->assertSame(404, $response->status());
    }

    public function test_cross_tenant_preference_access_returns_404(): void
    {
        // สร้าง Citizen ใน Org 2
        $citizen = $this->createCitizenForOrg(2);
        $pref = KpUserWastePreference::create([
            'org_id_fk'       => 2,
            'user_id'         => $citizen->id,
            'is_recycle_bank' => true,
        ]);

        // แต่ส่ง URL เรียกดูผ่าน Org 1 (Cross-tenant probing)
        $response = $this->get("/line/dashboard/{$pref->id}/1");

        $this->assertSame(404, $response->status());
    }

    public function test_unverified_line_user_access_is_forbidden_or_denied(): void
    {
        $citizen = $this->createCitizenForOrg(1, [
            'line_user_id' => 'U_ORIGINAL_OWNER',
        ]);
        $pref = KpUserWastePreference::create([
            'org_id_fk'       => 1,
            'user_id'         => $citizen->id,
            'is_recycle_bank' => true,
        ]);

        // ผู้ไม่ประสงค์ดีส่ง X-Line-User-Id อื่นมาเพื่อสวมรอย
        $response = $this->withHeaders([
            'X-Line-User-Id' => 'U_ATTACKER',
        ])->get("/line/dashboard/{$pref->id}/1");

        $this->assertTrue(in_array($response->status(), [403, 404, 302]));
    }
}
