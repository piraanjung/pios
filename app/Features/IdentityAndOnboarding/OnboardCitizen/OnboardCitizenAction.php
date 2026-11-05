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
                'zone_id'       => $dto->zoneId,
                'subzone_id'    => $dto->subzoneId,
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
                $userPref = KpUserWastePreference::create([
                    'org_id_fk'            => $dto->orgId,
                    'user_id'              => $user->id,
                    'is_waste_bank'        => $dto->enableRecycleBank ? 1 : 0,
                    'is_recycle_bank'      => $dto->enableRecycleBank ? 1 : 0,
                    'is_annual_collection' => 0,
                    'address'              => $dto->address,
                    'zone_id'              => $dto->zoneId,
                    'subzone_id'           => $dto->subzoneId,
                ]);

                $accountNo = sprintf('ACC-%04d-%06d', $dto->orgId, $user->id);
                $memberNumber = sprintf('MBR-%04d-%06d', $dto->orgId, $user->id);

                $bankAccount = KPBankAccount::create([
                    'org_id_fk'     => $dto->orgId,
                    'user_id'       => $user->id,
                    'user_pref_id'  => $userPref->id,
                    'account_no'    => $accountNo,
                    'member_number' => $memberNumber,
                    'balance'       => 0.00,
                    'points'        => 0,
                    'status'        => 'active',
                ]);
            }

            // 3. Food Waste Profile
            $foodWastePref = null;
            if ($dto->enableFoodWaste) {
                $foodWastePref = FoodWasteUserPreference::create([
                    'org_id_fk'         => $dto->orgId,
                    'user_id'           => $user->id,
                    'is_foodwaste_bank' => true,
                ]);
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
