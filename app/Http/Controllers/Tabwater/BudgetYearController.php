<?php

namespace App\Http\Controllers\Tabwater;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FunctionsController;
use App\Models\Admin\BudgetYear;
use App\Models\Admin\Organization;
use App\Models\Tabwater\InvoicePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BudgetYearController extends Controller
{
    public function index()
    {
        $funcCtrl = new FunctionsController();
        $budgetyears = BudgetYear::orderBy('budgetyear_name', 'desc')
            ->get();
        $orgInfos = Organization::getOrgName(Auth::user()->org_id_fk);

        foreach ($budgetyears as $budgetyear) {
            $budgetyear->startdate = $funcCtrl->engDateToThaiDateFormat($budgetyear->startdate);
            $budgetyear->enddate = $funcCtrl->engDateToThaiDateFormat($budgetyear->enddate);
            $budgetyear->have_inv_peroid = InvoicePeriod::where('budgetyear_id', $budgetyear->id)
                ->exists();
        }

        return view('admin.budgetyear.index', compact('budgetyears', 'orgInfos'));
    }

    public function create()
    {
        $orgInfos = Organization::getOrgName(Auth::user()->org_id_fk);
        return view('admin.budgetyear.create', compact('orgInfos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'budgetyear' => 'required|integer|digits:4|min:2566',
            'start' => 'required',
            'end' => 'required',
        ], [
            'required' => 'กรุณากรอกข้อมูล',
            'integer' => 'ต้องเป็นตัวเลข',
            'digits' => 'ปีต้องมี 4 หลัก (พ.ศ.)',
            'min' => 'ปีงบประมาณต้องมากกว่า 2566',
        ]);

        try {
            $funcCtrl = new FunctionsController();
            $org_id = session('active_org_id') ?? Auth::user()->org_id_fk;

            $startDateEng = $funcCtrl->thaiDateToEngDateFormat($request->start);
            $endDateEng = $funcCtrl->thaiDateToEngDateFormat($request->end);

            DB::transaction(function () use ($request, $org_id, $startDateEng, $endDateEng) {
                // 1. ปิดสถานะปีงบประมาณเก่าให้เป็น inactive
                BudgetYear::query()
                    ->where('status', 'active')
                    ->update(['status' => 'inactive']);

                // 2. สร้างปีงบประมาณใหม่ (หรืออัปเดตหากปีงบประมาณนี้เคยถูกสร้างค้างไว้แต่ยังไม่มีรอบบิล)
                $newBudgetYear = BudgetYear::updateOrCreate(
                    [
                        'org_id_fk'       => $org_id,
                        'budgetyear_name' => $request->budgetyear,
                    ],
                    [
                        'startdate' => $startDateEng,
                        'enddate'   => $endDateEng,
                        'status'    => 'active',
                    ]
                );

                // หากปีงบประมาณนี้ยังไม่มีรอบบิล ให้สร้างรอบบิลทั้ง 12 เดือน
                $alreadyHasPeriods = InvoicePeriod::where('budgetyear_id', $newBudgetYear->id)->exists();
                if ($alreadyHasPeriods) {
                    return;
                }

                // =====================================================================
                // 3. สร้างรอบบิลกลาง 12 เดือน (รองรับไว้ใช้งานร่วมกันทุกโมดูล)
                // =====================================================================
                $thaiMonths = [
                    1 => 'มกราคม',
                    2 => 'กุมภาพันธ์',
                    3 => 'มีนาคม',
                    4 => 'เมษายน',
                    5 => 'พฤษภาคม',
                    6 => 'มิถุนายน',
                    7 => 'กรกฎาคม',
                    8 => 'สิงหาคม',
                    9 => 'กันยายน',
                    10 => 'ตุลาคม',
                    11 => 'พฤศจิกายน',
                    12 => 'ธันวาคม',
                ];

                // ตรวจสอบคอลัมน์จริงในตาราง invoice_period เพื่อป้องกัน Error คอลัมน์ไม่มีอยู่ หรือไม่มีค่า Default (เช่น old_inv_p_id)
                $columnsInfo = DB::select('SHOW COLUMNS FROM `invoice_period`');
                $existingColumns = [];
                $requiredNoDefaultColumns = [];
                foreach ($columnsInfo as $col) {
                    $colArr = (array) $col;
                    $field = $colArr['Field'];
                    $existingColumns[$field] = $colArr;
                    if (($colArr['Null'] ?? '') === 'NO' && ($colArr['Default'] ?? null) === null && ($colArr['Extra'] ?? '') !== 'auto_increment') {
                        $requiredNoDefaultColumns[$field] = $colArr['Type'] ?? '';
                    }
                }

                // แปลงปี พ.ศ. เป็น ค.ศ. สำหรับคำนวณ Carbon (พ.ศ. - 543 = ค.ศ.)
                $bhYearAD = intval($request->budgetyear) - 543;
                $startLoopDate = Carbon::create($bhYearAD - 1, 10, 1); // เริ่ม 1 ตุลาคม ปีก่อนหน้า

                for ($i = 0; $i < 12; $i++) {
                    $currentMonthDate = (clone $startLoopDate)->addMonths($i);
                    $monthNum = $currentMonthDate->month;
                    $yearTh = $currentMonthDate->year + 543;

                    $periodName = $thaiMonths[$monthNum] . ' ' . $yearTh;
                    $pStart = (clone $currentMonthDate)->startOfMonth()->toDateString();
                    $pEnd = (clone $currentMonthDate)->endOfMonth()->toDateString();
                    $pStatus = ($i === 0) ? 'active' : 'inactive';

                    $periodData = [
                        'org_id_fk'     => $org_id,
                        'budgetyear_id' => $newBudgetYear->id,
                        'inv_p_name'    => $periodName,
                        'startdate'     => $pStart,
                        'enddate'       => $pEnd,
                        'status'        => $pStatus,
                    ];

                    if (isset($existingColumns['inv_p_name_int'])) {
                        $periodData['inv_p_name_int'] = str_pad((string) $monthNum, 2, '0', STR_PAD_LEFT) . '-' . substr((string) $yearTh, 2);
                    }

                    if (isset($existingColumns['old_inv_p_id'])) {
                        $periodData['old_inv_p_id'] = 0;
                    }

                    // เผื่อมีคอลัมน์ NOT NULL อื่นในตารางที่ไม่มีค่า Default
                    foreach ($requiredNoDefaultColumns as $reqField => $reqType) {
                        if (!array_key_exists($reqField, $periodData) && !in_array($reqField, ['created_at', 'updated_at'], true)) {
                            $periodData[$reqField] = preg_match('/int|decimal|float|double/i', $reqType) ? 0 : '';
                        }
                    }

                    InvoicePeriod::create($periodData);
                }
            });

            return redirect()->route('admin.budgetyear.index')
                ->with('success', 'บันทึกปีงบประมาณและสร้างรอบบิล 12 เดือนเรียบร้อย');
        } catch (\Throwable $e) {
            Log::error('BudgetYearController@store Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถบันทึกปีงบประมาณ/รอบบิลได้ สาเหตุ: ' . $e->getMessage() . ' (ไฟล์ ' . basename($e->getFile()) . ' บรรทัดที่ ' . $e->getLine() . ')');
        }
    }

    public function edit($id)
    {
        $budgetyear = BudgetYear::findOrFail($id);
        $funcCtrl = new FunctionsController();
        $budgetyear->startdate = $funcCtrl->engDateToThaiDateFormat($budgetyear->startdate);
        $budgetyear->enddate = $funcCtrl->engDateToThaiDateFormat($budgetyear->enddate);
        return view('admin.budgetyear.edit', compact('budgetyear'));
    }

    public function update(Request $request, $id)
    {
        try {
            $funcCtrl = new FunctionsController();
            $budgetyear = BudgetYear::findOrFail($id);

            if ($request->has('budgetyear')) {
                $budgetyear->budgetyear_name = $request->budgetyear;
            }

            $budgetyear->startdate = $funcCtrl->thaiDateToEngDateFormat($request->startdate);
            $budgetyear->enddate = $funcCtrl->thaiDateToEngDateFormat($request->enddate);
            $budgetyear->save();

            return redirect()->route('admin.budgetyear.index')->with('success', 'บันทึกการแก้ไขแล้ว');
        } catch (\Throwable $e) {
            Log::error('BudgetYearController@update Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'ไม่สามารถแก้ไขปีงบประมาณได้ สาเหตุ: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $hasInvoice = InvoicePeriod::query()
                ->where('budgetyear_id', $id)
                ->exists();

            if ($hasInvoice) {
                return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีรายการรอบบิลใช้งานอยู่');
            }

            $budgetyear = BudgetYear::findOrFail($id);
            $budgetyear->delete();

            return redirect()->route('admin.budgetyear.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        } catch (\Throwable $e) {
            Log::error('BudgetYearController@delete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาดในการลบ: ' . $e->getMessage());
        }
    }

    public function invoice_period_list($budgetyear_id)
    {
        return InvoicePeriod::query()
            ->where('budgetyear_id', $budgetyear_id)
            ->orderBy('id', 'asc')
            ->get(['id', 'inv_p_name']);
    }
}
