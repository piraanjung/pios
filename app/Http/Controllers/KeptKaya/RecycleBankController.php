<?php

namespace App\Http\Controllers\KeptKaya;

use App\Http\Controllers\Controller;
use App\Models\Admin\Zone;
use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\KeptKaya\KpRecycleBankMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecycleBankController extends Controller
{
    /**
     * แสดงรายชื่อสมาชิกธนาคารขยะ
     */
    public function index(Request $request)
    {
        $orgId = getAuthOrgId();

        // ดึงรายชื่อ Master Users จากตาราง users ที่ลงทะเบียนเป็นสมาชิกธนาคารขยะของ อปท. นี้
        $members = User::where('org_id_fk', $orgId)
            ->with([
                'user_zone',
                'user_subzone',
                'kpRecycleBankMember',
                'recycleBankAccount',
            ])
            ->where(function ($query) use ($orgId) {
                $query->whereHas('kpRecycleBankMember', function ($q) use ($orgId) {
                    $q->where('org_id_fk', $orgId)->where('status', 'active');
                })->orWhereHas('recycleBankAccount', function ($q) use ($orgId) {
                    $q->where('org_id_fk', $orgId)->where('status', 'active');
                });
            })
            ->latest()
            ->paginate(10);

        return view('keptkayas.recycle_bank.members.index', compact('members'));
    }

    public function create(Request $request)
    {
        $orgId = getAuthOrgId();
        $zones = Zone::where('org_id_fk', $orgId)->orWhereNull('org_id_fk')->get();

        // ดึง Master User ในตาราง users ของ อปท. นี้ที่ยังไม่ได้เป็นสมาชิกธนาคารขยะ
        $query = User::where('org_id_fk', $orgId)
            ->whereDoesntHave('kpRecycleBankMember', function ($q) use ($orgId) {
                $q->where('org_id_fk', $orgId);
            })
            ->whereDoesntHave('recycleBankAccount', function ($q) use ($orgId) {
                $q->where('org_id_fk', $orgId);
            })
            ->whereDoesntHave('kpUserPreference', function ($q) {
                $q->where('is_recycle_bank', 1);
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

        return view('keptkayas.recycle_bank.members.create', compact('availableUsers', 'zones'));
    }

    // 2. บันทึกการลงทะเบียนสมาชิกแบบ Bulk จากตารางหลัก (users) เข้าสู่ตารางสมาชิกธนาคารขยะผ่าน UserService
    public function bulkStore(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'zone_id'  => 'nullable|array',
        ]);

        $orgId = getAuthOrgId();
        $userService = app(\App\Services\UserService::class);

        DB::beginTransaction();
        try {
            $maxMemberNo = KpRecycleBankMember::where('org_id_fk', $orgId)->max('member_number') ?? 0;

            foreach ($request->user_ids as $userId) {
                $user = User::where('org_id_fk', $orgId)->find($userId);
                if (!$user) continue;

                $maxMemberNo++;

                $userService->registerToRecycleBank($user, [
                    'member_number' => $maxMemberNo,
                    'zone_id'       => $request->zone_id[$userId] ?? $user->zone_id,
                ]);
            }

            DB::commit();
            return redirect()->route('keptkayas.recycle-bank.members.index')
                ->with('success', 'ลงทะเบียนสมาชิกธนาคารขยะจากตารางผู้ใช้งานหลักเรียบร้อยแล้ว');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }
    /**
     * แสดงประวัติการขายขยะ แยกตามปีงบประมาณ
     */
    public function history(Request $request, $userId)
    {
        $user = User::with(['user_zone', 'user_subzone', 'recycleBankAccount'])
            ->findOrFail($userId);

        // ปีงบประมาณปัจจุบัน (ถ้าเดือน >= 10 ถือเป็นปีงบประมาณถัดไป)
        $currentMonth = now()->month;
        $currentFiscalYear = $currentMonth >= 10 ? now()->year + 1 : now()->year;

        // รับค่าปีงบประมาณจาก Query String (ถ้าไม่มีให้ใช้ปีปัจจุบัน)
        $selectedFiscalYear = (int) $request->get('fiscal_year', $currentFiscalYear);

        // คำนวณ ช่วงวันที่ของปีงบประมาณที่เลือก (1 ต.ค. ปีก่อน - 30 ก.ย. ปีที่เลือก)
        $startDate = Carbon::create($selectedFiscalYear - 1, 10, 1)->startOfDay();
        $endDate   = Carbon::create($selectedFiscalYear, 9, 30)->endOfDay();

        // ดึงรายการบิลประวัติการขายขยะ
        $transactions = KpPurchaseTransaction::with(['details.item', 'details.unit', 'recorder'])
            ->whereHas('user.recycleBankAccount', function ($q) {
                $q->where('status', 'active');
            })
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderBy('transaction_date', 'desc')
            ->get();

        // รายการปีงบประมาณย้อนหลังให้เลือกใน Dropdown (เช่น ย้อนหลัง 5 ปี)
        $fiscalYears = range($currentFiscalYear, $currentFiscalYear - 4);

        return view('keptkayas.recycle_bank.history', compact('user', 'transactions', 'selectedFiscalYear', 'fiscalYears'));
    }
}
