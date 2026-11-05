<?php

namespace App\Features\LineLiff\VerifyLineSession;

use App\Models\User;
use Illuminate\Http\Request;

class VerifyLineSessionAction
{
    /**
     * Verify LINE user identity against requested user account.
     */
    public function execute(Request $request, int $targetUserId, int $orgId): array
    {
        $verifiedLineUserId = $request->input('line_user_id')
            ?? $request->header('X-Line-User-Id')
            ?? ($request->hasSession() ? $request->session()->get('verified_line_user_id') : null);

        $user = User::where('id', $targetUserId)
            ->where('org_id_fk', $orgId)
            ->first();

        if (!$user) {
            return [
                'authorized' => false,
                'status'     => 404,
                'message'    => 'ไม่พบข้อมูลบัญชีผู้ใช้งาน',
                'user'       => null,
            ];
        }

        // Prevent unauthorized direct access to Admin accounts via public LIFF URL
        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['Super Admin', 'Admin'])) {
            return [
                'authorized' => false,
                'status'     => 403,
                'message'    => 'ไม่อนุญาตให้เข้าถึงบัญชีผู้ดูแลระบบผ่านช่องทาง LIFF URL โดยตรง',
                'user'       => null,
            ];
        }

        // Validate LINE identity match if configured
        if (!empty($user->line_user_id) && !empty($verifiedLineUserId)) {
            if ($user->line_user_id !== $verifiedLineUserId) {
                return [
                    'authorized' => false,
                    'status'     => 403,
                    'message'    => 'บัญชี LINE ไม่ตรงกับข้อมูลเจ้าของบัญชีในระบบ',
                    'user'       => null,
                ];
            }
        }

        return [
            'authorized' => true,
            'status'     => 200,
            'message'    => 'ยืนยันตัวตนสำเร็จ',
            'user'       => $user,
        ];
    }
}
