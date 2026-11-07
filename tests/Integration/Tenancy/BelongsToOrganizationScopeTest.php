<?php

namespace Tests\Integration\Tenancy;

use App\Models\KPBankAccount;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Shared\Tenancy\TenantContext;
use Tests\Support\Concerns\BuildsTestSchema;
use Tests\Support\Concerns\InteractsWithTenancy;
use Tests\TestCase;

class BelongsToOrganizationScopeTest extends TestCase
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

    public function test_creating_model_auto_assigns_current_org_id(): void
    {
        TenantContext::setOrgId(10);

        $citizen = $this->createCitizenForOrg(10);
        $pref = KpUserWastePreference::create([
            'user_id' => $citizen->id,
            'is_recycle_bank' => true,
        ]);

        $this->assertSame(10, (int) $pref->org_id_fk);
    }

    public function test_global_scope_isolates_records_between_organizations(): void
    {
        // สร้างข้อมูลใน Org 1
        TenantContext::setOrgId(1);
        $user1 = $this->createCitizenForOrg(1);
        $account1 = KPBankAccount::create([
            'user_id'    => $user1->id,
            'account_no' => 'ACC-ORG1',
            'balance'    => 500.0,
        ]);

        // สร้างข้อมูลใน Org 2
        TenantContext::setOrgId(2);
        $user2 = $this->createCitizenForOrg(2);
        $account2 = KPBankAccount::create([
            'user_id'    => $user2->id,
            'account_no' => 'ACC-ORG2',
            'balance'    => 1200.0,
        ]);

        // เมื่อ Context คือ Org 2 ต้องมองเห็นเฉพาะ Org 2
        $accountsOrg2 = KPBankAccount::all();
        $this->assertCount(1, $accountsOrg2);
        $this->assertSame('ACC-ORG2', $accountsOrg2->first()->account_no);

        // สลับกลับเป็น Org 1 ต้องมองเห็นเฉพาะ Org 1
        TenantContext::setOrgId(1);
        $accountsOrg1 = KPBankAccount::all();
        $this->assertCount(1, $accountsOrg1);
        $this->assertSame('ACC-ORG1', $accountsOrg1->first()->account_no);
    }

    public function test_can_explicitly_query_specific_organization(): void
    {
        TenantContext::setOrgId(1);
        $user1 = $this->createCitizenForOrg(1);
        KPBankAccount::create(['user_id' => $user1->id, 'account_no' => 'ACC-ORG1', 'org_id_fk' => 1]);

        TenantContext::setOrgId(2);
        $user2 = $this->createCitizenForOrg(2);
        KPBankAccount::create(['user_id' => $user2->id, 'account_no' => 'ACC-ORG2', 'org_id_fk' => 2]);

        // คิวรีข้าม Scope แบบระบุ Org เจาะจง
        $specificAccounts = KPBankAccount::forOrg(1)->get();
        $this->assertCount(1, $specificAccounts);
        $this->assertSame('ACC-ORG1', $specificAccounts->first()->account_no);
    }
}
