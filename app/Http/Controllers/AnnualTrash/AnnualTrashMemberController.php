<?php

namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller;
use App\Models\AnnualTrash\AnnualTrashMember;
use App\Models\KeptKaya\KpUserGroup;
use App\Models\Admin\Zone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnualTrashMemberController extends Controller
{
    /**
     * แสดงรายการสมาชิกขยะรายปี
     */
    public function index(Request $request)
    {
        $orgId = getAuthOrgId();
        $query = AnnualTrashMember::with(['user', 'zone', 'userGroup'])
            ->where('org_id_fk', $orgId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('id_card', 'like', "%{$search}%");
                })->orWhere('member_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(15);

        return view('annual_trash.members.index', compact('members'));
    }

    /**
     * หน้าเลือก Master User เพื่อเพิ่มเป็นสมาชิกขยะรายปี
     */
    public function create(Request $request)
    {
        $orgId = getAuthOrgId();
        $zones = Zone::where('org_id_fk', $orgId)->orWhereNull('org_id_fk')->get();
        $userGroups = KpUserGroup::where('status', 'active')->get();

        // ดึง Master User ที่ยังไม่ได้เป็นสมาชิกขยะรายปีใน อปท. นี้
        $query = User::where('org_id_fk', $orgId)
            ->whereDoesntHave('annualTrashMember', function ($q) use ($orgId) {
                $q->where('org_id_fk', $orgId);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id_card', 'like', "%{$search}%");
            });
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        $availableUsers = $query->paginate(20);

        return view('annual_trash.members.create', compact('availableUsers', 'zones', 'userGroups'));
    }

    /**
     * บันทึกการเพิ่มสมาชิกขยะรายปีแบบหลายคน (Bulk Store) จากรายชื่อในตารางหลัก (users) พร้อม Auto-run เลข AT00001
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_ids'          => 'required|array|min:1',
            'user_group_id_fk'  => 'required|exists:kp_usergroups,id',
            'zone_id'           => 'nullable|array',
        ], [
            'user_ids.required'         => 'กรุณาเลือกสมาชิกอย่างน้อย 1 คน',
            'user_group_id_fk.required' => 'กรุณาเลือกประเภทผู้เสียค่าธรรมเนียม',
        ]);
      
        $orgId = getAuthOrgId();
        $userService = app(\App\Services\UserService::class);

        DB::beginTransaction();
        try {
            $maxMemberNo = AnnualTrashMember::where('org_id_fk', $orgId)->max('member_number') ?? 0;

            foreach ($request->user_ids as $userId) {
                $user = User::where('org_id_fk', $orgId)->find($userId);
                if (!$user) continue;

                $maxMemberNo++;

                $userService->registerToAnnualTrash($user, [
                    'member_number'    => $maxMemberNo,
                    'user_group_id_fk' => $request->user_group_id_fk,
                    'zone_id'          => $request->zone_id[$userId] ?? $user->zone_id,
                    'subzone_id'       => $user->subzone_id,
                ]);
            }

            DB::commit();
            return redirect()->route('keptkayas.annual-members.index')
                ->with('success', 'ลงทะเบียนสมาชิกขยะรายปีจากตารางผู้ใช้งานหลักเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * หน้าแก้ไขข้อมูลสมาชิก
     */
    public function edit($id)
    {
        $orgId = getAuthOrgId();
        $member = AnnualTrashMember::where('org_id_fk', $orgId)->with('user')->findOrFail($id);
        $zones = Zone::where('org_id_fk', $orgId)->orWhereNull('org_id_fk')->get();
        $userGroups = KpUserGroup::where('status', 'active')->get();

        return view('annual_trash.members.edit', compact('member', 'zones', 'userGroups'));
    }

    /**
     * อัปเดตข้อมูลสมาชิก
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_group_id_fk' => 'required|exists:kp_usergroups,id',
            'zone_id'          => 'nullable|exists:zones,id',
            'status'           => 'required|in:active,inactive',
        ]);

        $orgId = getAuthOrgId();
        $member = AnnualTrashMember::where('org_id_fk', $orgId)->findOrFail($id);

        $member->update([
            'user_group_id_fk' => $request->user_group_id_fk,
            'zone_id'          => $request->zone_id,
            'status'           => $request->status,
        ]);

        return redirect()->route('keptkayas.annual-members.index')
            ->with('success', 'อัปเดตข้อมูลสมาชิกขยะรายปีเรียบร้อยแล้ว');
    }

    /**
     * ลบ/ยกเลิกสมาชิกภาพ
     */
    public function destroy($id)
    {
        $orgId = getAuthOrgId();
        $member = AnnualTrashMember::where('org_id_fk', $orgId)->findOrFail($id);

        DB::transaction(function () use ($member) {
            $user = $member->user;
            $member->delete();

            // ถ้าไม่มีบัญชีขยะรายปีเหลืออยู่อีก ให้ถอน Role ออก
            if ($user && !AnnualTrashMember::where('user_id', $user->id)->exists()) {
                $user->removeRole('Annual Trash User');
            }
        });

        return redirect()->route('keptkayas.annual-members.index')
            ->with('success', 'ลบสมาชิกขยะรายปีเรียบร้อยแล้ว');
    }
}