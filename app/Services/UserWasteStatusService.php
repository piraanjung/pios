<?php

namespace App\Services;

use App\Models\FoodWaste\FoodAnnualTrash;
use App\Models\User;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\AnnualTrash\AnnualTrash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserWasteStatusService
{
    /**
     * อัปเดตสถานะ is_annual_collection และ is_recycle_bank ของ User
     * โดยอิงจากสถานะถังขยะและ Logic ทางธุรกิจ
     *
     * @param User $user
     * @return void
     */
    public function updateOverallUserWasteStatus(User $user)
    {
        DB::transaction(function () use ($user) {
            // 1. ดึงหรือสร้าง KpUserWastePreference สำหรับ User นี้
            $preference = $user->kpUserPreference()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'org_id_fk'     => $user->org_id_fk,
                    'zone_id'       => $user->zone_id,
                    'subzone_id'    => $user->subzone_id,
                    'address'       => $user->address,
                    'tambon_code'   => $user->tambon_code,
                    'district_code' => $user->district_code,
                    'province_code' => $user->province_code,
                ]
            );

            $oldIsAnnualCollection = (bool) $preference->is_annual_collection;
            $oldIsRecycleBank = (bool) $preference->is_waste_bank;

            // 2. คำนวณสถานะ is_annual_collection ใหม่:
            $newIsAnnualCollection = $user->AnnualTrashs()
                ->where('is_active_for_annual_collection', true)
                ->exists()
                || $user->annualTrashMember()->where('status', 'active')->exists()
                || $user->annualTrashSubscription()->where('status', 'active')->exists();

            // 3. คำนวณสถานะ is_recycle_bank ใหม่:
            $newIsRecycleBank = $oldIsRecycleBank
                || $user->kpRecycleBankMember()->where('status', 'active')->exists()
                || $user->recycleBankAccount()->where('status', 'active')->exists();

            if (!$newIsAnnualCollection && $oldIsAnnualCollection && !$oldIsRecycleBank) {
                $newIsRecycleBank = true;
                Log::info("User {$user->id}: Annual Collection changed from TRUE to FALSE. Forcing Recycle Bank to TRUE.");
            }

            // 4. บันทึกการเปลี่ยนแปลงใน KpUserWastePreference (ถ้ามีอะไรเปลี่ยน)
            if (
                (bool) $preference->is_annual_collection !== $newIsAnnualCollection ||
                (bool) $preference->is_waste_bank !== $newIsRecycleBank
            ) {
                $preference->update([
                    'is_annual_collection' => $newIsAnnualCollection ? 1 : 0,
                    'is_waste_bank'        => $newIsRecycleBank ? 1 : 0,
                ]);
                Log::info("User {$user->id} Waste Preference Updated: Annual({$oldIsAnnualCollection} -> {$newIsAnnualCollection}), RecycleBank({$oldIsRecycleBank} -> {$newIsRecycleBank})");
            }
        });
    }

    public function updateAnnualTrashAndUserStatus(FoodAnnualTrash $foodAnnualTrash, array $data)
    {
        DB::transaction(function () use ($foodAnnualTrash, $data) {
            $foodAnnualTrash->update($data);

            if ($foodAnnualTrash->user) {
                $this->updateOverallUserWasteStatus($foodAnnualTrash->user);
            }
        });
    }
}
