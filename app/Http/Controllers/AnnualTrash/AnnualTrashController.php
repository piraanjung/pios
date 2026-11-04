<?php

namespace App\Http\Controllers\AnnualTrash;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FunctionsController;
use App\Models\Admin\Organization;
use App\Models\IssueType;
use App\Models\Tabwater\TwNotifies;
use App\Models\FoodWaste\FoodAnnualTrashStocks;
use App\Models\KeptKaya\KpUserGroup;
use App\Models\Keptkaya\KpUsergroupPayratePerMonth;
use App\Models\AnnualTrash\AnnualTrash;
use App\Models\AnnualTrash\AnnualTrashMember;
use App\Models\AnnualTrash\AnnualTrashPayratePerMonth;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\User;
use App\Services\UserWasteStatusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AnnualTrashController extends Controller
{
    protected $wasteStatusService;

    public function __construct(UserWasteStatusService $wasteStatusService)
    {
        $this->wasteStatusService = $wasteStatusService;
    }


    public function index(User $w_user)
    {
        $AnnualTrashs = $w_user->AnnualTrashs()->paginate(10);
        // view อ่านจากตัวแปร $wasteBins
        $wasteBins = $AnnualTrashs;
        return view('annual_trash.bins.index', compact('w_user', 'AnnualTrashs', 'wasteBins'));
    }

    public function create(User $w_user)
    {
        $user_groups = KpUserGroup::all();
        $func = new FunctionsController();
        $bin_code = $func->wastBinCode();
        $orgInfos = Organization::getOrgName($w_user->org_id_fk);

        $active_bins = FoodAnnualTrashStocks::where('bin_type', 'ab')->get();

        return view('annual_trash.bins.create', compact('w_user', 'user_groups', 'active_bins', 'bin_code'));
    }

    public function store(Request $request, User $w_user)
    {
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        abort_unless((int) $w_user->org_id_fk === $orgId, 403);

        // 1. Validation (เหมือนเดิม)
        $request->validate([
            'bin_code' => 'nullable|string|unique:annual_trashs,bin_code|max:255',
            'bin_type' => 'required|string|max:255',
            'user_group' => 'required',
            'location_description' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', Rule::in(['active', 'inactive', 'damaged', 'removed'])],
            'is_active_for_annual_collection' => 'boolean',
        ]);

        // 2. Create AnnualTrash (เหมือนเดิม)
        $AnnualTrash = $w_user->AnnualTrashs()->create([
            'org_id_fk' => $orgId,
            'bin_code' => $request->bin_code,
            'bin_type' => $request->user_group,
            'location_description' => $request->location_description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
            'is_active_for_annual_collection' => $request->has('is_active_for_annual_collection'),
            'created_at' => now(), // ใช้ now() ให้ได้เวลาปัจจุบันเป๊ะๆ
            'updated_at' => now(),
        ]);

        // 3. Create Subscription (จุดที่ต้องแก้ logic การคำนวณเงิน)
        if ($AnnualTrash->is_active_for_annual_collection) {

            // 3.1 ดึงปีงบประมาณ (สมมติว่าเป็น พ.ศ. 2569)
            $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();

            // แปลงเป็น ค.ศ. เพื่อคำนวณวัน (เช่น 2569 -> 2026)
            $fiscalYearAD = ($fiscalYear > 2500) ? $fiscalYear - 543 : $fiscalYear;

            // 3.2 ดึงเรทราคาต่อเดือน
            $payratePerMonth = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)
                ->where('kp_usergroup_idfk', $request->get('user_group'))
                ->where('status', 'active')
                ->first(); // ใช้ first() ก็พอ ไม่ต้อง get()->first()

            if ($payratePerMonth) {
                $monthlyFee = $payratePerMonth->payrate_permonth;

                // --- LOGIC คำนวณยอดเงินตามจริง (Pro-rate) ---

                // วันที่สมัคร (วันนี้)
                $startDate = Carbon::now();

                // วันสิ้นสุดปีงบประมาณ (30 กันยายน ของปีงบนั้น)
                $endDate = Carbon::create($fiscalYearAD, 9, 30)->endOfDay();

                // กรณีพิเศษ: ถ้าสมัครช่วงคาบเกี่ยว (เช่น สมัคร ต.ค. แต่นับเป็นปีงบหน้า)
                // ให้ตรวจสอบว่า วันนี้ เลยวันสิ้นปีงบไปหรือยัง ถ้าเลยแล้ว แสดงว่าเป็นรอบปีถัดไป
                if ($startDate->gt($endDate)) {
                    $endDate->addYear(); // บวกเพิ่มไปอีกปี
                }

                // นับจำนวนเดือนที่เหลือ (รวมเดือนปัจจุบันด้วย)
                // เช่น สมัคร ม.ค. ถึง ก.ย. = 9 เดือน
                // diffInMonths จะนับจำนวนเดือนเต็ม เราใช้ floatDiff หรือคำนวณเองเพื่อให้ครอบคลุม
                // แต่วิธีที่ง่ายที่สุดสำหรับระบบรอบบิลคือดูที่เดือน

                // วิธีนับแบบบ้านๆ แต่ชัวร์สุดสำหรับราชการ (นับนิ้ว):
                // Loop จากเดือนปัจจุบัน ไปจนถึงเดือน 9 (กันยายน)
                $remainingMonths = 0;
                $checkDate = $startDate->copy()->startOfMonth();
                $targetDate = $endDate->copy()->startOfMonth();

                while ($checkDate->lte($targetDate)) {
                    $remainingMonths++;
                    $checkDate->addMonth();
                }

                // คำนวณยอดรายปีตามจริง (เช่น 50 บาท x 9 เดือน = 450)
                $annualFee = $monthlyFee * $remainingMonths;

                // บันทึกข้อมูล
                AnnualTrashSubscription::firstOrCreate(
                    [
                        'org_id_fk' => $orgId,
                        'waste_bin_id' => $AnnualTrash->id,
                        'fiscal_year' => $fiscalYear, // ระบุปีด้วย เผื่อมีขยะเดิมแต่ปีใหม่
                    ],
                    [
                        'org_id_fk' => $orgId,
                        'payrate_permonth_id_fk' => $payratePerMonth->id,
                        'fiscal_year' => $fiscalYear,
                        'annual_fee' => $annualFee, // ยอดที่คำนวณใหม่
                        'month_fee' => $monthlyFee,
                        'total_paid_amt' => 0,
                        'status' => 'pending',
                        'created_at' => now(), // ใช้ now() ให้ได้เวลาปัจจุบันเป๊ะๆ
                        'updated_at' => now(),
                    ]
                );
            }
        }

        // 4. Update Status (เหมือนเดิม)
        $this->wasteStatusService->updateOverallUserWasteStatus($w_user);

        return redirect()->route('keptkayas.waste_bins.index', $w_user->id)
            ->with('success', 'เพิ่มถังขยะเรียบร้อยแล้ว!');
    }


    public function show(User $w_user, AnnualTrash $waste_bin)
    {
        $orgId = (int) getAuthOrgId();
        abort_unless((int) $waste_bin->user_id === (int) $w_user->id, 404);
        abort_unless((int) $waste_bin->org_id_fk === $orgId, 404);
        $AnnualTrash = $waste_bin->load(['user', 'kpUserGroup']);
        return view('annual_trash.bins.show', compact('AnnualTrash'));
    }

    public function edit(User $w_user, AnnualTrash $waste_bin)
    {
        abort_unless((int) $waste_bin->user_id === (int) $w_user->id, 404);
        $AnnualTrash = $waste_bin;
        return view('annual_trash.bins.edit', compact('AnnualTrash'));
    }


    public function update(Request $request, AnnualTrash $waste_bin)
    {
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        abort_unless((int) ($waste_bin->user?->org_id_fk ?? 0) === $orgId, 403);

        $request->validate([
            'bin_code' => ['nullable', 'string', 'max:255', Rule::unique('annual_trashs')->ignore($waste_bin->id)],
            'bin_type' => 'required|string|max:255',
            'location_description' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', Rule::in(['active', 'inactive', 'damaged', 'removed'])],
            'is_active_for_annual_collection' => 'boolean',
        ]);

        $oldIsActiveForAnnualCollection = $waste_bin->is_active_for_annual_collection;
        $newIsActiveForAnnualCollection = $request->has('is_active_for_annual_collection');

        $payrate = null;
        $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        if (!$oldIsActiveForAnnualCollection && $newIsActiveForAnnualCollection) {
            $payrate = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)
                ->where('kp_usergroup_idfk', $request->input('bin_type'))
                ->where('status', 'active')
                ->whereHas('budgetyear', fn($query) => $query->where('budgetyear_name', $fiscalYear))
                ->first();

            if (!$payrate) {
                return back()->withErrors(['is_active_for_annual_collection' => 'ยังไม่มีอัตราค่าบริการของประเภทนี้ในปีงบประมาณปัจจุบัน'])->withInput();
            }
        }

        $data = $request->only([
            'bin_code', 'bin_type', 'location_description', 'latitude', 'longitude', 'status',
        ]);
        $data['org_id_fk'] = $orgId;
        $data['is_active_for_annual_collection'] = $newIsActiveForAnnualCollection;

        $waste_bin->update($data);

        if (!$oldIsActiveForAnnualCollection && $newIsActiveForAnnualCollection) {
            AnnualTrashSubscription::firstOrCreate(
                [
                    'org_id_fk' => $orgId,
                    'waste_bin_id' => $waste_bin->id,
                    'fiscal_year' => $fiscalYear,
                ],
                [
                    'org_id_fk' => $orgId,
                    'user_id' => $waste_bin->user_id,
                    'payrate_permonth_id_fk' => $payrate->id,
                    'annual_fee' => (float) $payrate->payrate_permonth * 12,
                    'month_fee' => $payrate->payrate_permonth,
                    'total_paid_amt' => 0,
                    'status' => 'pending',
                ]
            );
        }
        // If status changed from active to inactive, you might want to update the subscription status to cancelled/inactive
        // Or handle this logic in a separate process. For now, we only create on activation.

        // Call service to update overall user waste status (waste_preference)
        $this->wasteStatusService->updateOverallUserWasteStatus($waste_bin->user);
        return redirect()->route('keptkayas.waste_bins.index', $waste_bin->user->id)
            ->with('success', 'อัปเดตถังขยะเรียบร้อยแล้ว!');
    }


    public function destroy(AnnualTrash $waste_bin)
    {
        $orgId = (int) (function_exists('getAuthOrgId') ? getAuthOrgId() : Auth::user()->org_id_fk);
        abort_unless((int) ($waste_bin->user?->org_id_fk ?? 0) === $orgId, 404);
        $w_user = $waste_bin->user;

        DB::transaction(function () use ($waste_bin, $w_user) {
            $waste_bin->delete();
            // Call service to update overall user waste status (waste_preference)
            $this->wasteStatusService->updateOverallUserWasteStatus($w_user);
        });

        return redirect()->route('keptkayas.waste_bins.index', $w_user->id)
            ->with('success', 'ลบถังขยะเรียบร้อยแล้ว!');
    }

    public function viewmap()
    {
        $orgId = (int) getAuthOrgId();
        $orgInfos = Organization::getOrgName($orgId);
        $members = AnnualTrashMember::with(['user', 'userGroup'])
            ->where('org_id_fk', $orgId)
            ->where('status', 'active')
            ->orderBy('member_number')
            ->get();

        $mapCfg = [
            'lat' => $orgInfos['lat'] ?? null,
            'lng' => $orgInfos['lng'] ?? ($orgInfos['long'] ?? null),
            'mapUrl' => route('keptkayas.waste_bins.map'),
            'storeUrl' => route('keptkayas.waste_bins.map.store'),
            'optionsUrl' => route('keptkayas.waste_bins.map.options'),
            'optionsNeedMember' => true,
            'csrf' => csrf_token(),
            'members' => $members->map(fn ($m) => [
                'id' => $m->id,
                'text' => trim($m->formatted_member_number . ' · ' . ($m->user->firstname ?? '') . ' ' . ($m->user->lastname ?? '') . ' · ' . ($m->userGroup->usergroup_name ?? 'ไม่ระบุประเภท')),
            ])->values(),
            'issueTypes' => IssueType::whereIn('system_type', self::ISSUE_SYSTEM_TYPES)->where('is_active', 1)->get(['id', 'name', 'color']),
        ];

        return view('annual_trash.map', compact('mapCfg'));
    }

    // เหตุแจ้งที่แสดงสีบนแผนที่ถังรายปี (ระบบแจ้งเหตุกลาง tw_notifies)
    private const ISSUE_SYSTEM_TYPES = ['public_health', 'recycle_trash'];

    public function binOptions(Request $request)
    {
        $orgId = (int) getAuthOrgId();
        $member = AnnualTrashMember::where('org_id_fk', $orgId)->findOrFail($request->query('member_id'));

        $owned = AnnualTrash::where('org_id_fk', $orgId)->where('user_id', $member->user_id)->orderBy('bin_code')->get()
            ->map(fn ($b) => [
                'id' => 'bin:' . $b->id,
                'text' => $b->bin_code . ($b->location_description ? ' · ' . $b->location_description : ''),
                'latitude' => $b->latitude,
                'longitude' => $b->longitude,
            ])->values();
        $new = collect(range(1, 5))->map(fn ($i) => ['id' => 'new:' . $i, 'text' => 'ถังใหม่ #' . $i . ' (สร้างรหัสอัตโนมัติ)']);

        return response()->json([
            ['text' => 'ถังของสมาชิกที่ลงทะเบียนแล้ว (ย้ายตำแหน่ง)', 'children' => $owned],
            ['text' => 'เพิ่มถังใหม่', 'children' => $new],
        ]);
    }

    public function storeFromMap(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|integer',
            'refs' => 'required|array|min:1|max:50',
            'refs.*' => ['required', 'string', 'regex:/^(bin|new):\d+$/'],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_description' => 'nullable|string|max:255',
        ]);

        $orgId = (int) getAuthOrgId();
        $member = AnnualTrashMember::with('user')
            ->where('org_id_fk', $orgId)
            ->where('status', 'active')
            ->findOrFail($validated['member_id']);

        $result = DB::transaction(function () use ($validated, $member, $orgId) {
            $orgInfos = Organization::getOrgName($orgId);
            $prefix = trim((string) ($orgInfos['org_code'] ?? '')) ?: 'ORG' . $orgId;
            $prefix .= '-B';
            $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();
            $payrate = AnnualTrashPayratePerMonth::where('org_id_fk', $orgId)
                ->where('kp_usergroup_idfk', $member->user_group_id_fk)
                ->where('status', 'active')
                ->whereHas('budgetyear', fn($query) => $query->where('budgetyear_name', $fiscalYear))
                ->first();

            $sequence = AnnualTrash::where('org_id_fk', $orgId)
                ->pluck('bin_code')
                ->map(function ($code) use ($prefix) {
                    return preg_match('/^' . preg_quote($prefix, '/') . '(\d+)$/', (string) $code, $matches)
                        ? (int) $matches[1]
                        : 0;
                })
                ->max() + 1;

            $codes = [];
            foreach ($validated['refs'] as $i => $ref) {
                [$kind, $refId] = explode(':', $ref);
                // ถังแรกใช้พิกัดที่ปัก ถังถัดไปเลื่อนเล็กน้อยให้เห็นว่าซ้อนกัน
                $b = [
                    'latitude' => (float) $validated['latitude'] + $i * 0.00002,
                    'longitude' => (float) $validated['longitude'] + $i * 0.00002,
                    'location_description' => $validated['location_description'] ?? null,
                ];

                if ($kind === 'bin') {
                    // ถังที่ลงทะเบียนไว้แล้ว: ย้ายตำแหน่งเท่านั้น
                    $existing = AnnualTrash::where('org_id_fk', $orgId)
                        ->where('user_id', $member->user_id)
                        ->findOrFail($refId);
                    $existing->update([
                        'latitude' => $b['latitude'],
                        'longitude' => $b['longitude'],
                        'location_description' => $b['location_description'] ?? $existing->location_description,
                    ]);
                    $codes[] = $existing->bin_code;
                    continue;
                }

                do {
                    $binCode = $prefix . str_pad((string) $sequence++, 4, '0', STR_PAD_LEFT);
                } while (AnnualTrash::where('bin_code', $binCode)->exists());

                $bin = AnnualTrash::create([
                    'org_id_fk' => $orgId,
                    'user_id' => $member->user_id,
                    'bin_code' => $binCode,
                    'bin_type' => $member->user_group_id_fk,
                    'location_description' => $b['location_description'] ?? null,
                    'latitude' => $b['latitude'],
                    'longitude' => $b['longitude'],
                    'status' => 'active',
                    'is_active_for_annual_collection' => (bool) $payrate,
                ]);
                $codes[] = $bin->bin_code;

                if ($payrate) {
                    AnnualTrashSubscription::firstOrCreate(
                        [
                            'org_id_fk' => $orgId,
                            'waste_bin_id' => $bin->id,
                            'fiscal_year' => $fiscalYear,
                        ],
                        [
                            'user_id' => $member->user_id,
                            'payrate_permonth_id_fk' => $payrate->id,
                            'month_fee' => $payrate->payrate_permonth,
                            'annual_fee' => (float) $payrate->payrate_permonth * 12,
                            'total_paid_amt' => 0,
                            'status' => 'pending',
                        ]
                    );
                }
            }

            return [$codes, (bool) $payrate];
        });

        $this->wasteStatusService->updateOverallUserWasteStatus($member->user);

        [$codes, $hasRate] = $result;
        $message = 'ผูกถัง ' . count($codes) . ' ใบ (' . implode(', ', $codes) . ') กับสมาชิก ' . $member->formatted_member_number . ' เรียบร้อยแล้ว';
        if (!$hasRate) {
            $message .= ' แต่ยังไม่มีอัตราค่าบริการของปีงบประมาณนี้ จึงยังไม่สร้างรายการชำระ';
        }

        return response()->json(['message' => $message, 'count' => count($codes)]);
    }

    public function map()
    {
        $orgId = (int) getAuthOrgId();
        $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();
        $bins = AnnualTrash::where('org_id_fk', $orgId)->with([
            'user' => function ($q) {
                return $q->select('id', 'firstname', 'lastname', 'address', 'zone_id', 'subzone_id', 'org_id_fk');
            },
            'user.user_zone' => function ($q) {
                return $q->select('id', 'zone_name');
            },
            'user.user_subzone' => function ($q) {
                return $q->select('id', 'subzone_name');
            },
            'subscriptions' => function ($q) use ($orgId, $fiscalYear) {
                $q->where('org_id_fk', $orgId)->where('fiscal_year', $fiscalYear)
                    ->select('id', 'waste_bin_id', 'status', 'annual_fee', 'total_paid_amt');
            },
        ])->get(['id', 'org_id_fk', 'user_id', 'bin_code', 'latitude', 'longitude', 'status', 'bin_type']);

        // เหตุแจ้งล่าสุดที่ยังไม่เสร็จของเจ้าของถัง (แจ้งผูกกับผู้ใช้ ไม่ใช่ถัง)
        $notifies = TwNotifies::where('org_id_fk', $orgId)
            ->whereIn('system_type', self::ISSUE_SYSTEM_TYPES)
            ->whereIn('user_id', $bins->pluck('user_id')->filter()->unique())
            ->whereNotIn('status', ['complete', 'completed', 'cancelled'])
            ->orderBy('id')
            ->get()
            ->keyBy('user_id');
        $types = IssueType::whereIn('id', $notifies->pluck('issue_type')->unique())->get()->keyBy('id');

        return response()->json($bins->map(function ($bin) use ($notifies, $types) {
            $row = $bin->toArray();
            $n = $notifies->get($bin->user_id);
            $t = $n ? $types->get($n->issue_type) : null;
            $row['issue'] = $t ? [
                'type_id' => $t->id,
                'name' => $t->name,
                'color' => $t->color,
                'status' => $n->status,
                'description' => $n->description,
            ] : null;
            return $row;
        }));
    }
}
