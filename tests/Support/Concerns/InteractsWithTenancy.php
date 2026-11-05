<?php

namespace Tests\Support\Concerns;

use App\Models\KPBankAccount;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\Organization;
use App\Models\User;
use App\Shared\Tenancy\TenantContext;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait InteractsWithTenancy
{
    /**
     * Switch current execution context to the given organization.
     */
    protected function actingAsOrg(int $orgId): self
    {
        TenantContext::setOrgId($orgId);

        return $this;
    }

    /**
     * Create an Organization record for testing.
     */
    protected function createOrg(array $attributes = []): Organization
    {
        return Organization::create(array_merge([
            'org_code'            => 'ORG-' . rand(100, 999),
            'org_name'            => 'เทศบาลตำบลทดสอบ ' . rand(1, 99),
            'org_short_type_name' => 'ทต.',
            'org_type_name'       => 'เทศบาลตำบล',
            'org_address'         => '123 หมู่ 1',
        ], $attributes));
    }

    /**
     * Create a Citizen User bound to a specific organization.
     */
    protected function createCitizenForOrg(int $orgId, array $attributes = []): User
    {
        return User::create(array_merge([
            'org_id_fk' => $orgId,
            'username'  => 'citizen_' . Str::lower(Str::random(8)),
            'firstname' => 'ทดสอบ',
            'lastname'  => 'พลเมือง',
            'id_card'   => rand(1000000000000, 9999999999999),
            'phone'     => '08' . rand(10000000, 99999999),
            'email'     => 'citizen_' . Str::random(5) . '@example.com',
            'password'  => Hash::make('password'),
            'status'    => 1,
        ], $attributes));
    }

    /**
     * Create a citizen with active Recycle Bank and master KPBankAccount.
     */
    protected function createCitizenWithRecycleAccount(int $orgId, float $initialBalance = 0.0, int $initialPoints = 0, array $userAttributes = []): array
    {
        $user = $this->createCitizenForOrg($orgId, $userAttributes);

        $pref = KpUserWastePreference::create([
            'org_id_fk'            => $orgId,
            'user_id'              => $user->id,
            'is_waste_bank'        => true,
            'is_recycle_bank'      => true,
            'is_annual_collection' => false,
        ]);

        $account = KPBankAccount::create([
            'org_id_fk'     => $orgId,
            'user_id'       => $user->id,
            'user_pref_id'  => $pref->id,
            'account_no'    => sprintf('ACC-%04d-%06d', $orgId, $user->id),
            'member_number' => sprintf('MBR-%04d-%06d', $orgId, $user->id),
            'balance'       => $initialBalance,
            'points'        => $initialPoints,
            'status'        => 'active',
        ]);

        return [
            'user'    => $user,
            'pref'    => $pref,
            'account' => $account,
        ];
    }

    /**
     * Reset tenant state after test.
     */
    protected function resetTenancy(): void
    {
        TenantContext::reset();
    }
}
