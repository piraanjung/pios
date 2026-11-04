<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WelfareMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelfareMemberController extends Controller
{
    // 1. แสดงรายชื่อสมาชิกกองทุนปัจจุบัน
    public function index(Request $request)
    {
        $orgId = function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk;
        
        $members = WelfareMember::where('org_id_fk', $orgId)
            ->with('user')
            ->when($request->search, function($query, $search) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->paginate(15);

        return view('admin.welfare.members.index', compact('members'));
    }

    // 2. หน้าจอเลือกสมาชิกกลางจากตารางหลัก (users) ที่ยังไม่ได้เป็นสมาชิกกองทุน เพื่อมาติ๊กเพิ่ม
    public function create(Request $request)
    {
        $orgId = function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk;

        // ดึง User จากตารางหลัก (users) ในองค์กรที่ยังไม่เคยเข้ากองทุนฌาปนกิจ
        $availableUsers = User::where('org_id_fk', $orgId)
            ->whereDoesntHave('welfareMember', function ($q) use ($orgId) {
                $q->where('org_id_fk', $orgId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('id_card', 'like', "%{$search}%");
                });
            })
            ->when($request->zone_id, function ($query, $zoneId) {
                $query->where('zone_id', $zoneId);
            })
            ->paginate(20);

        return view('admin.welfare.members.create', compact('availableUsers'));
    }

    // 3. บันทึกการเพิ่มสมาชิกแบบ Bulk จากตารางหลัก (users) เข้าสู่ตาราง welfare_members ผ่าน UserService
    public function bulkStore(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $orgId = function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk;
        $userService = app(\App\Services\UserService::class);
        $count = 0;

        DB::beginTransaction();
        try {
            foreach ($request->user_ids as $userId) {
                $user = User::where('org_id_fk', $orgId)->find($userId);
                if (!$user) continue;

                $exists = WelfareMember::where('org_id_fk', $orgId)->where('user_id', $userId)->exists();

                if (!$exists) {
                    $membershipType = $request->input("membership_type.{$userId}", 'both');
                    $userService->registerToWelfare($user, [
                        'membership_type' => $membershipType,
                        'status'          => 'active',
                        'registered_at'   => now(),
                    ]);
                    $count++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาดในการลงทะเบียนสมาชิกกองทุน: ' . $e->getMessage());
        }

        return redirect()->route('admin.welfare.members.index')
            ->with('success', "ลงทะเบียนสมาชิกจากตารางผู้ใช้งานหลักเข้ากองทุนฌาปนกิจสำเร็จจำนวน {$count} คน");
    }

    // 4. ลบ/ยกเลิกสิทธิ์สมาชิกกองทุน (ตรวจสอบ org_id_fk ป้องกันการลบข้ามองค์กร)
    public function destroy($id)
    {
        $orgId = function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk;
        $member = WelfareMember::where('org_id_fk', $orgId)->findOrFail($id);
        $member->delete();

        return back()->with('success', 'ยกเลิกสิทธิ์สมาชิกกองทุนเรียบร้อยแล้ว');
    }
}