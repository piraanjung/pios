<?php

namespace App\Services;

use App\Models\User;
use App\Models\KpBankAccount;
use App\Models\Keptkaya\KpRecycleBankMember;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\KeptKaya\KpUserGroup;
use App\Models\FoodWaste\FoodWasteAccount;
use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\AnnualTrash\AnnualTrashMember;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\AnnualTrash\AnnualTrashPayratePerMonth;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\WelfareMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $wasteStatusService;

    /**
     * Dependency Injection สำหรับ UserWasteStatusService
     */
    public function __construct(UserWasteStatusService $wasteStatusService)
    {
        $this->wasteStatusService = $wasteStatusService;
    }

    /**
     * ดึง org_id_fk ที่ถูกต้องของผู้ใช้หรือแอดมินปัจจุบัน
     */
    public function resolveOrgId(?User $user = null): int
    {
        if ($user && !empty($user->org_id_fk)) {
            return (int) $user->org_id_fk;
        }
        if (function_exists('getAuthOrgId')) {
            $orgId = getAuthOrgId();
            if ($orgId) {
                return (int) $orgId;
            }
        }
        return (int) (session('active_org_id') ?? (Auth::check() ? Auth::user()->org_id_fk : 1));
    }

    /**
     * สร้างรหัสบัญชีมาตรฐานสำหรับ User
     */
    protected function buildAccountNumberSuffix(User $user): string
    {
        $org = $user->organization ?? (Auth::check() ? Auth::user()->organization : null);
        $orgId = $org->id ?? $this->resolveOrgId($user);
        $orgIdStr = sprintf('%03d', (int) $orgId);
        $userIdStr = sprintf('%04d', (int) $user->id);
        return ($org->org_code ?? 'ORG') . '-' . $orgIdStr . $userIdStr;
    }

    /**
     * 1. ลงทะเบียน User จากตารางหลัก (users) เข้าสู่โมดูลน้ำประปา (tw_meter_infos)
     */
    public function registerToTabwater(User $user, array $meterData = []): TwMeterInfos
    {
        $orgId = $this->resolveOrgId($user);
        $maxMeterId = (int) (TwMeterInfos::where('org_id_fk', $orgId)->max('meter_id') ?? 0);
        $meterId = $meterData['meter_id'] ?? ($maxMeterId + 1);

        $meter = TwMeterInfos::create([
            'meter_id'             => $meterId,
            'org_id_fk'            => $orgId,
            'user_id'              => $user->id,
            'factory_no'           => $meterData['factory_no'] ?? null,
            'submeter_name'        => $meterData['submeter_name'] ?? null,
            'last_meter_recording' => $meterData['last_meter_recording'] ?? 0,
            'meter_address'        => $meterData['meter_address'] ?? $user->address,
            'undertake_zone_id'    => $meterData['undertake_zone_id'] ?? $user->zone_id,
            'undertake_subzone_id' => $meterData['undertake_subzone_id'] ?? $user->subzone_id,
            'metertype_id'         => $meterData['metertype_id'] ?? 1,
            'acceptance_date'      => $meterData['acceptance_date'] ?? now(),
            'status'               => $meterData['status'] ?? 'active',
            'recorder_id'          => Auth::id() ?? $user->id,
        ]);

        if (method_exists($user, 'assignRole') && !$user->hasRole('Tabwater User')) {
            try {
                $user->assignRole('Tabwater User');
            } catch (\Throwable $e) {
                // Role might not exist in some seed environments
            }
        }

        return $meter;
    }

    /**
     * 2. ลงทะเบียน User จากตารางหลัก (users) เข้าสู่โมดูลธนาคารขยะรีไซเคิล (KeptKaya)
     * ซิงค์ข้อมูลให้ตรงกันทั้ง kp_recycle_bank_members, kp_bank_accounts และ kp_user_waste_preferences
     */
    public function registerToRecycleBank(User $user, array $options = []): KpRecycleBankMember
    {
        $orgId = $this->resolveOrgId($user);
        $accNumberText = $this->buildAccountNumberSuffix($user);

        // 2.1 ซิงค์ตารางสมาชิกธนาคารขยะ (kp_recycle_bank_members)
        $member = KpRecycleBankMember::where('org_id_fk', $orgId)
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            $maxMemberNo = KpRecycleBankMember::where('org_id_fk', $orgId)->max('member_number') ?? 0;
            $member = KpRecycleBankMember::create([
                'org_id_fk'     => $orgId,
                'user_id'       => $user->id,
                'member_number' => $options['member_number'] ?? ($maxMemberNo + 1),
                'zone_id'       => $options['zone_id'] ?? $user->zone_id,
                'total_points'  => 0,
                'balance'       => 0,
                'status'        => 'active',
                'recorder_id'   => Auth::id() ?? $user->id,
            ]);
        } else {
            $member->update([
                'zone_id' => $options['zone_id'] ?? $member->zone_id ?? $user->zone_id,
                'status'  => 'active',
            ]);
        }

        // 2.2 ซิงค์ตารางกระเป๋าเงินธนาคารขยะ (kp_bank_accounts)
        KpBankAccount::updateOrCreate(
            [
                'user_id'     => $user->id,
                'entity_type' => 'recycle_bank',
            ],
            [
                'account_no' => 'RC-' . $accNumberText,
                'status'     => 'active',
                'org_id_fk'  => $orgId,
            ]
        );

        // 2.3 ซิงค์ตาราง Preference (kp_user_waste_preferences)
        KpUserWastePreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'org_id_fk'       => $orgId,
                'zone_id'         => $options['zone_id'] ?? $user->zone_id,
                'subzone_id'      => $user->subzone_id,
                'address'         => $user->address,
                'tambon_code'     => $user->tambon_code,
                'district_code'   => $user->district_code,
                'province_code'   => $user->province_code,
                'is_recycle_bank' => 1,
            ]
        );

        if (method_exists($user, 'assignRole') && !$user->hasRole('Recycle Bank User')) {
            try {
                $user->assignRole('Recycle Bank User');
            } catch (\Throwable $e) {
            }
        }

        return $member;
    }

    /**
     * 3. ลงทะเบียน User จากตารางหลัก (users) เข้าสู่โมดูลขยะเปียก (Foodwaste)
     */
    public function registerToFoodWaste(User $user, array $options = []): FoodWasteAccount
    {
        $orgId = $this->resolveOrgId($user);
        $accNumberText = $this->buildAccountNumberSuffix($user);

        // 3.1 ซิงค์ FoodWasteUserPreference
        $fwPref = FoodWasteUserPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'org_id_fk'         => $orgId,
                'is_foodwaste_bank' => 1,
                'status'            => 1,
            ]
        );

        // 3.2 ซิงค์ KpBankAccount (foodwaste_bank)
        KpBankAccount::updateOrCreate(
            [
                'user_id'     => $user->id,
                'entity_type' => 'foodwaste_bank',
            ],
            [
                'account_no' => 'FW-' . $accNumberText,
                'status'     => 'active',
                'org_id_fk'  => $orgId,
            ]
        );

        // 3.3 ซิงค์ FoodWasteAccount
        return FoodWasteAccount::updateOrCreate(
            ['user_id' => $user->id],
            [
                'org_id_fk'           => $orgId,
                'fw_pref_id_fk'       => $fwPref->id,
                'last_contributed_at' => now(),
            ]
        );
    }

    /**
     * 4. ลงทะเบียน User จากตารางหลัก (users) เข้าสู่โมดูลขยะรายปี (Annual Trash)
     * ซิงค์ทั้ง annual_trash_members, annual_trash_subscriptions และ kp_user_waste_preferences
     */
    public function registerToAnnualTrash(User $user, array $options = []): AnnualTrashMember
    {
        $orgId = $this->resolveOrgId($user);

        // 4.1 ซิงค์ตารางสมาชิกขยะรายปี (annual_trash_members)
        $defaultGroup = KpUserGroup::where('status', 'active')->first();
        $userGroupId = $options['user_group_id_fk'] ?? ($defaultGroup->id ?? 1);

        $member = AnnualTrashMember::where('org_id_fk', $orgId)
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            $maxMemberNo = AnnualTrashMember::where('org_id_fk', $orgId)->max('member_number') ?? 0;
            $member = AnnualTrashMember::create([
                'org_id_fk'        => $orgId,
                'user_id'          => $user->id,
                'member_number'    => $options['member_number'] ?? ($maxMemberNo + 1),
                'user_group_id_fk' => $userGroupId,
                'zone_id'          => $options['zone_id'] ?? $user->zone_id,
                'subzone_id'       => $options['subzone_id'] ?? $user->subzone_id,
                'status'           => 'active',
                'recorder_id'      => Auth::id() ?? $user->id,
            ]);
        } else {
            $member->update([
                'user_group_id_fk' => $options['user_group_id_fk'] ?? $member->user_group_id_fk ?? $userGroupId,
                'zone_id'          => $options['zone_id'] ?? $member->zone_id ?? $user->zone_id,
                'status'           => 'active',
            ]);
        }

        // 4.2 ซิงค์ตารางสิทธิ์และค่าธรรมเนียมรายปี (annual_trash_subscriptions)
        $payRate = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)
            ->where('status', 'active')
            ->latest()
            ->first();
        $monthFee = $payRate ? $payRate->payrate_permonth : 20.00;
        $isRecycleMember = $options['is_recycle_member']
            ?? $user->recycleBankAccount()->where('status', 'active')->exists();
        $billingStatus = $isRecycleMember ? 'waived' : 'active';

        $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();

        AnnualTrashSubscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'org_id_fk' => $orgId,
                'fiscal_year' => $fiscalYear,
            ],
            [
                'waste_bin_id' => $options['waste_bin_id'] ?? null,
                'payrate_permonth_id_fk' => $payRate->id ?? null,
                'month_fee' => $monthFee,
                'annual_fee' => $monthFee * 12,
                'status' => 'pending',
            ]
        );


        // 4.3 ซิงค์ตาราง Preference (kp_user_waste_preferences)
        KpUserWastePreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'org_id_fk'            => $orgId,
                'zone_id'              => $options['zone_id'] ?? $user->zone_id,
                'subzone_id'           => $user->subzone_id,
                'address'              => $user->address,
                'is_annual_collection' => 1,
            ]
        );

        if (method_exists($user, 'assignRole') && !$user->hasRole('Annual Trash User')) {
            try {
                $user->assignRole('Annual Trash User');
            } catch (\Throwable $e) {
            }
        }

        return $member;
    }

    /**
     * 5. ลงทะเบียน User จากตารางหลัก (users) เข้าสู่โมดูลสวัสดิการชุมชน (Welfare)
     */
    public function registerToWelfare(User $user, array $options = []): WelfareMember
    {
        $orgId = $this->resolveOrgId($user);

        return WelfareMember::updateOrCreate(
            [
                'org_id_fk' => $orgId,
                'user_id'   => $user->id,
            ],
            [
                'membership_type' => $options['membership_type'] ?? 'both',
                'status'          => $options['status'] ?? 'active',
                'registered_at'   => $options['registered_at'] ?? now(),
            ]
        );
    }

    /**
     * อัปเดตสิทธิ์บริการของผู้ใช้งานหลายคนพร้อมกัน (Batch Update)
     */
    public function updateServices(array $services)
    {
        DB::beginTransaction();
        try {
            foreach ($services as $userId => $data) {
                $user = User::find($userId);
                if (!$user) continue;

                // 1. ซิงค์สิทธิ์บริการในตารางของแต่ละโมดูล
                $this->syncUserServices($user, $data);

                // 2. เรียกใช้ UserWasteStatusService เพื่ออัปเดตสิทธิ์ในตาราง Preference ให้ตรงกัน
                $this->wasteStatusService->updateOverallUserWasteStatus($user);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * จัดการเปิด/ปิด สิทธิ์บริการรายบุคคลตามโมดูล
     */
    public function syncUserServices(User $user, array $data)
    {
        // 1. ธนาคารขยะรีไซเคิล (Recycle Bank)
        if (isset($data['recycle']) && $data['recycle'] == "1") {
            $this->registerToRecycleBank($user);
        } else {
            if ($user->recycleBankAccount) {
                $user->recycleBankAccount->update(['status' => 'inactive']);
            }
            if ($user->kpRecycleBankMember) {
                $user->kpRecycleBankMember->update(['status' => 'inactive']);
            }
        }

        // 2. ธนาคารขยะเปียก / เศษอาหาร (Food Waste Bank)
        if (isset($data['food_waste']) && $data['food_waste'] == "1") {
            $this->registerToFoodWaste($user);
        } else {
            if ($user->foodWasteBankAccount) {
                $user->foodWasteBankAccount->update(['status' => 'inactive']);
            }
            if ($user->foodwastePreference) {
                $user->foodwastePreference->update(['is_foodwaste_bank' => 0, 'status' => 0]);
            }
        }

        // 3. บริการจัดเก็บขยะรายปี (Annual Trash Subscription)
        if (isset($data['annual_trash']) && $data['annual_trash'] == "1") {
            $this->registerToAnnualTrash($user, [
                'is_recycle_member' => isset($data['recycle']) && $data['recycle'] == "1",
            ]);
        } else {
            if ($user->annualTrashMember) {
                $user->annualTrashMember->update(['status' => 'inactive']);
            }
            if ($user->annualTrashSubscription) {
                $user->annualTrashSubscription->update(['status' => 'inactive']);
            }
        }
    }
}
