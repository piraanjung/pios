<?php

namespace App\Features\IdentityAndOnboarding\OnboardCitizen;

use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\KPBankAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OnboardCitizenAction
{
    public function execute(OnboardCitizenDTO $dto): array
    {
        return DB::transaction(function () use ($dto) {
            // 1. Create User
            $username = $dto->idCard ?: ($dto->phone ?: 'citizen_' . Str::lower(Str::random(8)));

            $user = User::create([
                'org_id_fk'     => $dto->orgId,
                'username'      => $username,
                'prefix'        => $dto->prefix,
                'firstname'     => $dto->firstname,
                'lastname'      => $dto->lastname,
                'id_card'       => $dto->idCard,
                'phone'         => $dto->phone,
                'email'         => $dto->email,
                'address'       => $dto->address,
                'zone_id'       => $dto->zoneId ?: 1,
                'subzone_id'    => $dto->subzoneId ?: 1,
                'tambon_code'   => '350805',
                'district_code' => '3508',
                'province_code' => '35',
                'line_user_id'  => $dto->lineUserId,
                'password'      => Hash::make(Str::random(16)),
                'status'        => 1,
            ]);

            // Assign default Citizen role if Spatie is active
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('Citizen');
                } catch (\Throwable $e) {
                    // Ignore missing role in testing/non-seeded DB
                }
            }

            $userPref = null;
            $bankAccount = null;

            // 2. KeptKaya / Recycle Bank Profile & Unified Master Ledger (ADR-0004)
            if ($dto->enableRecycleBank || $dto->enableWelfare) {
                $prefData = [
                    'user_id'              => $user->id,
                    'is_waste_bank'        => $dto->enableRecycleBank ? 1 : 0,
                    'is_annual_collection' => 0,
                    'address'              => $dto->address,
                    'zone_id'              => $dto->zoneId ?: 1,
                    'subzone_id'           => $dto->subzoneId ?: 1,
                ];
                if (\Illuminate\Support\Facades\Schema::hasColumn('kp_user_waste_preferences', 'org_id')) {
                    $prefData['org_id'] = $dto->orgId;
                } elseif (\Illuminate\Support\Facades\Schema::hasColumn('kp_user_waste_preferences', 'org_id_fk')) {
                    $prefData['org_id_fk'] = $dto->orgId;
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('kp_user_waste_preferences', 'is_recycle_bank')) {
                    $prefData['is_recycle_bank'] = $dto->enableRecycleBank ? 1 : 0;
                }
                $userPref = KpUserWastePreference::create($prefData);

                $accountNo = sprintf('ACC-%04d-%06d', $dto->orgId, $user->id);
                $memberNumber = sprintf('MBR-%04d-%06d', $dto->orgId, $user->id);

                $accountData = [
                    'user_id'       => $user->id,
                    'account_no'    => $accountNo,
                    'member_number' => $memberNumber,
                    'balance'       => 0.00,
                    'points'        => 0,
                    'status'        => 'active',
                ];
                if (\Illuminate\Support\Facades\Schema::hasColumn('kp_bank_accounts', 'org_id_fk')) {
                    $accountData['org_id_fk'] = $dto->orgId;
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('kp_bank_accounts', 'user_pref_id')) {
                    $accountData['user_pref_id'] = $userPref->id;
                }
                $bankAccount = KPBankAccount::create($accountData);
            }

            // 3. Food Waste Profile
            $foodWastePref = null;
            if ($dto->enableFoodWaste) {
                $fwData = ['user_id' => $user->id];
                if (\Illuminate\Support\Facades\Schema::hasColumn('foodwaste_user_preferences', 'org_id_fk')) {
                    $fwData['org_id_fk'] = $dto->orgId;
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('foodwaste_user_preferences', 'is_foodwaste_bank')) {
                    $fwData['is_foodwaste_bank'] = true;
                }
                $foodWastePref = FoodWasteUserPreference::create($fwData);
            }

            return [
                'user'             => $user,
                'waste_preference' => $userPref,
                'bank_account'     => $bankAccount,
                'food_waste_pref'  => $foodWastePref,
                'success'          => true,
                'message'          => 'ลงทะเบียนข้อมูลประชาชนเรียบร้อยแล้ว',
            ];
        });
    }
}
