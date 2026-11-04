<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getAuthOrgId')) {
    /**
     * ดึง ID ขององค์กร (อปท.) ปัจจุบันของผู้ใช้งาน
     *
     * @return int|string|null
     */
    function getAuthOrgId()
    {
        if (session()->has('active_org_id') && !empty(session('active_org_id'))) {
            return session('active_org_id');
        }

        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return $user->org_id_fk ?? null;
    }
}
