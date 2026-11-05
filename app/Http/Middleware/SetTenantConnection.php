<?php

namespace App\Http\Middleware;

use App\Models\Admin\Organization;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SetTenantConnection
{
    /**
     * สลับ Database Connection ตามองค์กร (1 องค์กร ต่อ 1 Database)
     * หากองค์กรมีค่า org_database ในตาราง organizations ระบบจะสลับไปใช้ connection 'tenant' อัตโนมัติ
     * หาก org_database ยังว่างอยู่ (NULL) ระบบจะใช้ฐานข้อมูลกลาง (envsogo_main) ตามปกติ
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // ในโหมด Testing ให้ข้ามการสลับ DB แบบไดนามิก เพื่อให้ทำงานบน SQLite In-memory ได้อย่างสมบูรณ์
        if (app()->environment('testing')) {
            return $next($request);
        }

        // 1. โหลด User และสิทธิ์ (Roles/Permissions) จาก Central DB (envsogo_main) เก็บไว้ใน Memory ก่อนสลับ DB
        $user = null;
        if (Auth::check()) {
            $user = Auth::user();
            $user->loadMissing(['roles', 'permissions']);
        }

        // 2. หารหัสองค์กร (org_id) จากลำดับความสำคัญ:
        //    - Session ที่ Super Admin เลือกสลับองค์กร (active_org_id)
        //    - Header 'X-Org-Id' สำหรับ API / IoT / Kiosk
        //    - สังกัดของผู้ใช้ที่ล็อกอินอยู่ ($user->org_id_fk)
        $sessionOrgId = $request->hasSession() ? $request->session()->get('active_org_id') : null;
        $headerOrgId = $request->header('X-Org-Id');
        $orgId = $sessionOrgId ?: ($headerOrgId ?: ($user ? $user->org_id_fk : null));

        if ($orgId) {
            // ถ้า Super Admin สลับองค์กรผ่าน Session ให้จำค่า org_id_fk ใน Memory ของ $user ด้วย
            if ($user && $sessionOrgId) {
                $user->org_id_fk = (int) $sessionOrgId;
            }

            // ดึงชื่อ Database ขององค์กรจาก Central DB (envsogo_main) โดยตรงเสมอ
            // เพื่อป้องกันปัญหาค่าเก่าค้างใน Session เมื่อมีการแก้ค่า org_database ในตาราง organizations
            $org = Organization::on('envsogo_main')
                ->select(['id', 'org_database'])
                ->find($orgId);

            $tenantDbName = $org && is_string($org->org_database) ? trim($org->org_database) : null;
            $centralDbName = Config::get('database.connections.envsogo_main.database');

            // รายชื่อค่าที่หมายถึงให้ใช้ฐานข้อมูลกลาง (Central DB: envsogo_main)
            $centralAliases = array_filter([
                $centralDbName,
                'envsogo_main',
                'envsogo_super_admin',
                'mysql',
                'null',
                'NULL',
            ]);

            $useTenantDb = !empty($tenantDbName) && !in_array($tenantDbName, $centralAliases, true);

            // 3. สลับ Default Database Connection ไปที่ฐานข้อมูลขององค์กร (ถ้ามีการระบุแยก Database ไว้และเชื่อมต่อได้จริง)
            if ($useTenantDb) {
                Config::set('database.connections.tenant.database', $tenantDbName);
                DB::purge('tenant');

                try {
                    DB::connection('tenant')->getPdo();
                    Config::set('database.default', 'tenant');
                } catch (\Throwable $e) {
                    // หากยังไม่ได้สร้าง Database แยกขององค์กรใน MySQL ให้สลับกลับมาใช้ envsogo_main เพื่อป้องกัน Error 500
                    \Illuminate\Support\Facades\Log::warning("SetTenantConnection: Cannot connect to tenant database '{$tenantDbName}' for org_id={$orgId}, falling back to envsogo_main. Error: " . $e->getMessage());
                    Config::set('database.default', 'envsogo_main');
                }
            } else {
                Config::set('database.default', 'envsogo_main');
            }

            if ($request->hasSession()) {
                $request->session()->put('active_org_database', $tenantDbName);
                $request->session()->put('db_conn', Config::get('database.default'));
            }
        } else {
            Config::set('database.default', 'envsogo_main');
            if ($request->hasSession()) {
                $request->session()->put('db_conn', 'envsogo_main');
            }
        }

        return $next($request);
    }
}
