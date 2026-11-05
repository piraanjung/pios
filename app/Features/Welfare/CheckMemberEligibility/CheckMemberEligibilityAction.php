<?php

namespace App\Features\Welfare\CheckMemberEligibility;

use App\Models\KPBankAccount;
use App\Models\User;
use Carbon\Carbon;

class CheckMemberEligibilityAction
{
    /**
     * Check if a citizen/member is eligible for welfare support or benefit claim.
     */
    public function execute(int $userId, int $orgId, int $requiredDays = 180): array
    {
        $user = User::forOrg($orgId)->find($userId);
        if (!$user) {
            return [
                'eligible' => false,
                'reason'   => 'ไม่พบข้อมูลประชาชนในองค์กรที่ระบุ',
            ];
        }

        $bankAccount = KPBankAccount::where('user_id', $userId)
            ->where('org_id_fk', $orgId)
            ->first();

        if (!$bankAccount) {
            return [
                'eligible' => false,
                'reason'   => 'ยังไม่ได้เปิดบัญชีธนาคารขยะ/กองทุนสวัสดิการชุมชน',
            ];
        }

        $registeredAt = $bankAccount->created_at ?? $user->created_at ?? now();
        $membershipDays = Carbon::parse($registeredAt)->diffInDays(now());

        if ($membershipDays < $requiredDays) {
            return [
                'eligible'        => false,
                'membership_days' => $membershipDays,
                'required_days'   => $requiredDays,
                'reason'          => "อายุการเป็นสมาชิกไม่ครบกำหนด (ปัจจุบัน {$membershipDays} วัน จากเกณฑ์ขั้นต่ำ {$requiredDays} วัน)",
            ];
        }

        return [
            'eligible'        => true,
            'membership_days' => $membershipDays,
            'balance'         => (float) $bankAccount->balance,
            'points'          => (int) $bankAccount->points,
            'member_number'   => $bankAccount->member_number ?? $bankAccount->account_no,
            'reason'          => 'มีสิทธิรับสวัสดิการชุมชนตามเกณฑ์',
        ];
    }
}
