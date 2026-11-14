<?php

namespace App\Http\Controllers;

use App\Models\Admin\Organization;
use App\Models\Admin\Province;
use App\Models\AnnualTrash\AnnualTrashPayratePerMonth;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\FoodWaste\CompostBatches;
use App\Models\FoodWaste\FoodWasteAccount;
use App\Models\FoodWaste\FoodWasteIssueReport;
use App\Models\FoodWaste\FoodWasteIssueType;
use App\Models\KeptKaya\KPAccounts;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\FoodWaste\MealLog;
use App\Models\Tabwater\SequenceNumber;
use App\Models\User;
use App\Models\FoodWaste\FoodWasteLog;
use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\KeptKaya\KpPurchaseTransaction;
use App\Models\KeptKaya\KpPurchaseTransactionDetail;
use App\Models\KPBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class   LineLiffController extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        $orgs = Organization::with('provinces', 'districts', 'tambons', 'orgType')
            ->get(['id', 'org_type_id', 'org_name', 'org_tambon_id_fk', 'org_district_id_fk', 'org_province_id_fk']);
        return view('lineliff.index', compact('provinces', 'orgs'));
    }

    public function dashboard(Request $request, $pref_id, $org_id)
    {
        // 1. ดึงข้อมูล User และตรวจสอบความตรงกันขององค์กร (org_id)
        $prefOrgCol = \Illuminate\Support\Facades\Schema::hasColumn('kp_user_waste_preferences', 'org_id') ? 'org_id' : 'org_id_fk';
        $pref = KpUserWastePreference::where('id', $pref_id)
            ->when($org_id, fn($q) => $q->where($prefOrgCol, $org_id))
            ->firstOrFail();

        $userId = $pref->user_id;
        $user = User::where('id', $userId)
            ->when($org_id, fn($q) => $q->where('org_id_fk', $org_id))
            ->firstOrFail();

        // ป้องกันการสวมรอยบัญชี Admin / Super Admin ผ่าน URL สาธารณะ
        if ($user->hasAnyRole(['Super Admin', 'Admin'])) {
            abort(403, 'ไม่อนุญาตให้เข้าถึงบัญชีผู้ดูแลระบบผ่านช่องทาง LIFF URL โดยตรง');
        }

        // ตรวจสอบ LINE User ID หรือ Session ที่ได้รับการยืนยันตัวตนแล้วเท่านั้น (ป้องกัน IDOR Account Takeover ตาม ADR-0003)
        $verifiedLineUserId = $request->input('line_user_id')
            ?? $request->header('X-Line-User-Id')
            ?? $request->session()->get('verified_line_user_id');

        if (Auth::check() && Auth::id() === $user->id) {
            // ผู้ใช้งานล็อกอินตรงกับเจ้าของข้อมูลอยู่แล้ว
        } elseif (!empty($verifiedLineUserId) && (!empty($user->line_user_id) || !empty($user->line_id))) {
            if ($user->line_user_id !== $verifiedLineUserId && $user->line_id !== $verifiedLineUserId) {
                abort(403, 'ข้อมูลบัญชี LINE ไม่ตรงกับผู้ใช้งาน');
            }
            $request->session()->put('verified_line_user_id', $verifiedLineUserId);
        } elseif ($request->session()->get('pref_id_mobile') == $pref_id) {
            // อนุญาตเฉพาะ Session ที่ผ่านการผูกบัญชีในรอบการใช้งานเดียวกัน
        } else {
            abort(403, 'กรุณายืนยันตัวตนผ่าน LINE LIFF ก่อนเข้าใช้งานแดชบอร์ด');
        }

        $request->session()->put('pref_id_mobile', $pref_id);
        // 2. ดึงข้อมูลบัญชี (New Schema)
        $recycleAcc = KpBankAccount::where('user_id', $userId)
            ->where('org_id_fk', $org_id)
            ->where('entity_type', 'recycle_bank')
            ->first();
        $foodWasteAcc = FoodWasteAccount::where('user_id', $userId)
            ->where('org_id_fk', $org_id)
            ->first();
        $currentFiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        $annualTrash = AnnualTrashSubscription::where('user_id', $userId)
            ->where('org_id_fk', $org_id)
            ->where('fiscal_year', $currentFiscalYear)
            ->with('AnnualTrash')
            ->get();
        $annualTrashFee = (float) $annualTrash->sum('annual_fee');
        $annualTrashPaid = (float) $annualTrash->sum('total_paid_amt');
        $annualTrashDebt = max($annualTrashFee - $annualTrashPaid, 0);

        // --- ส่วนที่ 3 (แก้ไขให้มีตัวแปรครบตามที่ compact ต้องการ) ---
        // 1. น้ำหนักขยะเปียกสะสม (ดึงจากบัญชีโดยตรงตาม Logic เดิมที่คุณอยากได้)
        $totalWasteWeight = $foodWasteAcc ? $foodWasteAcc->total_weight_kg : 0;

        // 2. คาร์บอนขยะเปียก
        $totalFoodWasteCarbon = FoodWasteLog::where('user_id', $userId)->sum('carbon_saved_kg');
        $treesByFoodWasteCarbon = number_format($totalFoodWasteCarbon > 0 ? ($totalFoodWasteCarbon / 12) : 0, 2);
        // 3. คาร์บอนขยะรีไซเคิล

        $transactions = KpPurchaseTransaction::where('user_id', $userId)
            ->where('org_id_fk', $org_id)
            ->with('details')
            ->get();
        $totalRecycleCarbon = (float) $transactions->sum(
            fn($transaction) => $transaction->details->sum('carbon_saved')
        );

        // ต้นไม้ 1 ต้น ดูดซับ CO2 ได้ประมาณ 12 kg/ปี
        $treesByRecycleCarbon = number_format($totalRecycleCarbon > 0 ? ($totalRecycleCarbon / 12) : 0, 2);

        // 4. รวมคาร์บอนทั้งหมด (ตัวแปรหลักที่ใช้โชว์ใน Dashboard)
        $totalCo2Saved = $totalFoodWasteCarbon + $totalRecycleCarbon;

        // 5. ตัวแปรสำรอง (ถ้าใน Blade ยังมีการใช้ชื่อ $totalCarbonSaved อยู่)
        $totalCarbonSaved = number_format($totalCo2Saved, 4);

        // 4. จัดการข้อมูล Batch (ล็อตปุ๋ยปัจจุบัน)
        $activeBatch = CompostBatches::where('user_id', $userId)
            ->where('status', 'filling')
            ->latest()
            ->first();
        // return response()->json($transaction);

        if ($activeBatch) {
            $days = (int) now()->diffInDays($activeBatch->start_date);
            $activeBatch->days_passed = ($days == 0) ? 1 : $days;
            $activeBatch->total_weight = FoodWasteLog::where('batch_id', $activeBatch->id)->sum('weight_kg');

            $lastLog = FoodWasteLog::where('batch_id', $activeBatch->id)->latest()->first();
            $activeBatch->temp_status = $lastLog ? $lastLog->temperature_feel : 'ยังไม่มีข้อมูล';
        }

        // 5. ข้อมูลกราฟแคลอรี่ (MealLog)
        $weeklyStats = MealLog::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, SUM(total_calories) as daily_calories')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $chartLabels = $weeklyStats->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d/m'))->toArray();
        $chartData = $weeklyStats->pluck('daily_calories')->toArray();

        // 6. เป้าหมายแคลอรี่ (TDEE) - ใส่ค่า Default เพื่อให้เส้น Red Line ขึ้นเสมอ
        $targetCalories = $user->calculateTDEE() ?: 2000;
        $todayCalories = MealLog::where('user_id', $userId)->whereDate('created_at', now())->sum('total_calories');

        // 7. 🌟 ส่วนสำคัญ: แต้มและเงิน
        // ดึงแต้มจาก FoodWasteAccount (ขยะเปียก)
        $foodWasteTotalPoints = $foodWasteAcc ? $foodWasteAcc->points_balance : 0;

        // ดึงเงินจาก KpBankAccount (เงินจากการขายขยะรีไซเคิล)
        $recycleTotalBalance = $recycleAcc ? $recycleAcc->balance : 0.00;
        $recycleTotalPoints = $recycleAcc ? $recycleAcc->points : 0.00;

        // 8. ข้อมูลอื่นๆ
        $qrcode = QrCode::size(300)->generate("USER-" . $userId);
        $myIssues = FoodWasteIssueReport::where('user_id', $userId)->latest()->get();
        $pendingIssuesCount = FoodWasteIssueReport::where('user_id', $userId)->where('status', '!=', 'resolved')->count();
        $issueTypes = FoodWasteIssueType::where('is_active', 1)->get();
        return view('lineliff.dashboard', compact(
            'user',
            'pref_id',
            'totalWasteWeight',
            'totalCarbonSaved',
            'activeBatch',
            'chartLabels',
            'chartData',
            'targetCalories',
            'todayCalories',
            'foodWasteTotalPoints',    // 🌟 แต้มขยะเปียก
            'recycleTotalBalance',   // 🌟 ยอดเงินคงเหลือ
            'recycleTotalPoints',
            'qrcode',
            'pendingIssuesCount',
            'annualTrash',
            'currentFiscalYear',
            'annualTrashFee',
            'annualTrashPaid',
            'annualTrashDebt',
            'myIssues',
            'issueTypes',
            'totalCo2Saved',
            'treesByRecycleCarbon',
            'totalRecycleCarbon',
            'totalFoodWasteCarbon',
            'treesByFoodWasteCarbon'
        ));
    }


    public function handleLineLogin(Request $request)
    {
        $validatedData = $request->validate([
            'userId' => 'required|string',
            'displayName' => 'required|string',
            'pictureUrl' => 'nullable|string',
        ]);

        $lineUserId = $validatedData['userId'];

        // ค้นหา User จาก line_user_id
        $user = User::where('line_user_id', $lineUserId)->first();

        if ($user) {
            // ถ้าพบ: User ได้ลงทะเบียนไว้แล้ว
            Log::info("User with LINE ID {$lineUserId} logged in.");
            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully.',
                'action' => 'login'
            ]);
        } else {
            // ถ้าไม่พบ: ลงทะเบียน User ใหม่
            $newUser = User::create([
                'name' => $validatedData['displayName'],
                'line_user_id' => $lineUserId,
                'picture_url' => $validatedData['pictureUrl'],
                // สามารถเพิ่มข้อมูลอื่นๆ ได้ตามต้องการ
            ]);

            Log::info("New user with LINE ID {$lineUserId} registered.");
            return response()->json([
                'status' => 'success',
                'message' => 'New user registered successfully.',
                'action' => 'register'
            ], 201);
        }
    }


    public function update_user_by_phone(Request $request)
    {
        $userService = app(\App\Services\UserService::class);
        $_user = User::with('kpUserPreference')
            ->where('phone', $request->phoneNum)
            ->when($request->org_id, fn($q) => $q->where('org_id_fk', $request->org_id))
            ->first();

        $res = 0;
        $user_id = 0;
        $waste_pref_id = 0;

        DB::beginTransaction();
        try {
            if ($_user) {
                $_user->line_id = $request->line_user_id;
                $_user->line_user_id = $request->line_user_id;
                $_user->image = $request->line_user_image;
                $_user->save();

                $userService->registerToRecycleBank($_user);
                $pref = $_user->kpUserPreference()->first();

                $user_id = $_user->id;
                $waste_pref_id = $pref ? $pref->id : 0;
                $res = 1;
            } else {
                // สร้าง Master User ลงตาราง users ก่อน แล้วลงทะเบียนเข้าโมดูลผ่าน UserService
                $orgId = $request->org_id ?? 1;
                $user = User::create([
                    'username'     => $orgId . ($request->phoneNum ?? time()),
                    'password'     => Hash::make($request->phoneNum ?? '12345678'),
                    'firstname'    => $request->displayName ?? 'สมาชิก LINE',
                    'lastname'     => '',
                    'phone'        => $request->phoneNum,
                    'line_id'      => $request->line_user_id,
                    'line_user_id' => $request->line_user_id,
                    'image'        => $request->line_user_image,
                    'org_id_fk'    => $orgId,
                    'status'       => 'active',
                ]);
                $user->assignRole('User');

                $userService->registerToRecycleBank($user);
                $pref = $user->kpUserPreference()->first();

                $user_id = $user->id;
                $waste_pref_id = $pref ? $pref->id : 0;
                $res = 1;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('update_user_by_phone error: ' . $e->getMessage());
        }

        return response()->json([
            'res' => $res,
            'user_id' => $user_id,
            'waste_pref_id' => $waste_pref_id
        ]);
    }

    public function user_line_register(Request $request)
    {
        $payload = $request->get('payload', $request->all());
        $userService = app(\App\Services\UserService::class);

        DB::beginTransaction();
        try {
            // 1. สร้าง User ในตารางหลัก (users) ก่อนเสมอ
            $user = User::create([
                'username'      => ($payload['org_id'] ?? '') . ($payload['phoneNum'] ?? ''),
                'password'      => Hash::make($payload['phoneNum'] ?? '12345678'),
                'firstname'     => $payload['firstname'] ?? '',
                'lastname'      => $payload['lastname'] ?? '',
                'line_id'       => $payload['line_user_id'] ?? null,
                'line_user_id'  => $payload['line_user_id'] ?? null,
                'image'         => $payload['line_user_image'] ?? null,
                'phone'         => $payload['phoneNum'] ?? null,
                'org_id_fk'     => $payload['org_id'] ?? null,
                'province_code' => $payload['province_id'] ?? null,
                'district_code' => $payload['district_id'] ?? null,
                'tambon_code'   => $payload['tambon_id'] ?? null,
                'zone_id'       => $payload['zone_id'] ?? null,
                'subzone_id'    => $payload['subzone_id'] ?? null,
                'address'       => $payload['address'] ?? null,
                'status'        => 'active',
            ]);

            $user->assignRole('User');

            // 2. นำ User จากตารางหลักไปลงทะเบียนเป็น Member ของโมดูลผ่าน UserService มาตรฐานเดียวกัน
            $userService->registerToRecycleBank($user, [
                'zone_id' => $user->zone_id,
            ]);
            $userService->registerToFoodWaste($user);

            $recycleAccount = $user->recycleBankAccount()->first();
            $kpref = $user->kpUserPreference()->first();

            DB::commit();

            return response()->json([
                'res' => 1,
                'user_id' => $user->id,
                'recycle_account_id' => $recycleAccount->id ?? null,
                'kp_ref' => $kpref->id ?? null,
                'msg' => 'ลงทะเบียนผู้ใช้งานในตารางหลักและเปิดบัญชีขยะเรียบร้อยแล้ว'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Register Error: ' . $e->getMessage());

            return response()->json([
                'res' => 0,
                'msg' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function register_bank(Request $request)
    {
        $userId = Auth::id();

        return DB::transaction(function () use ($userId) {
            // 1. สร้าง/อัปเดต Preference (ตัวแม่)
            $pref = FoodWasteUserPreference::updateOrCreate(
                ['user_id' => $userId],
                ['is_foodwaste_bank' => 1]
            );

            // 2. สร้างกระเป๋าเงิน (Account ตัวลูก) สำหรับขยะเปียก
            // ใช้ Model FoodWasteAccount ตามที่คุณส่งมาล่าสุด
            $account = FoodWasteAccount::firstOrCreate(
                ['fw_pref_id_fk' => $pref->id],
                [
                    'points_balance' => 0,
                    'money_balance' => 0,
                    'total_weight_contributed' => 0
                ]
            );

            return back()->with('success', 'ยินดีด้วย! คุณเปิดบัญชีธนาคารขยะเปียกสำเร็จ');
        });
    }
}
