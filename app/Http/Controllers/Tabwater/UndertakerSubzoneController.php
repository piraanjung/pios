<?php

namespace App\Http\Controllers\Tabwater;

use App\Http\Controllers\Controller;
use App\Models\Admin\Staff;
use App\Models\Admin\Subzone;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\Tabwater\UndertakerSubzone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UndertakerSubzoneController extends Controller
{
    public function index()
    {
        $undertakerSubzone = UndertakerSubzone::with('twman_info', 'subzone', 'subzone.zone')
            ->get();
        $undertakerSubzones = collect($undertakerSubzone)->groupBy('twman_id')->values();
        return view('undertaker_subzone.index', compact('undertakerSubzones'));
    }

    public function create()
    {
        $undertakerSubzone = DB::table('undertaker_subzones as us')
            ->select('us.subzone_id')
            ->orderBy('us.subzone_id')
            ->get();
        $undertakerSubzoneArray = collect([]);
        foreach ($undertakerSubzone as $uz) {
            $undertakerSubzoneArray->push($uz->subzone_id);
        }
        $subzone = DB::table('subzones as sz')
            ->select('sz.id as subzone_id',)
            ->orderBy('sz.zone_id')
            ->get();

        $subzoneArr = collect([]);
        foreach ($subzone as $uz) {
            $subzoneArr->push($uz->subzone_id);
        }
        $remain_subzone = collect($subzoneArr)->diff($undertakerSubzoneArray)->values();

        $subzoneCollection = collect([]);
        foreach ($remain_subzone as $remain) {
            $sz = DB::table('subzones as sz')
                ->join('zones as z', 'z.id', 'sz.zone_id')
                ->where('sz.id', '=', $remain)
                ->select('sz.id as subzone_id', 'sz.subzone_name', 'z.zone_name', 'z.id as zone_id')
                ->orderBy('z.id')
                ->get();
            $subzoneCollection->push($sz);
        }
        $subzone = collect($subzoneCollection)->flatten()->sortBy('zone_id');

   return     $tw_mans = User::role('Tabwater Staff')
            ->where('status', '=', 'active')
            ->with(
                'undertaker_subzone',
                'undertaker_subzone.subzone',
                'undertaker_subzone.subzone.zone'
            )
            ->get();

        // view undertaker_subzone.create ไม่มีแล้ว กลับไปหน้ารายการ
        return redirect()->route('admin.undertaker_subzone');
    }

    public function store(REQUEST $request)
    {
        $validated = $request->validate([
            'twman_id' => ['required', 'integer'],
            'on' => ['required', 'array', 'min:1'],
            'on.*' => ['accepted'],
        ]);

        $orgId = Auth::user()->org_id_fk;
        $staff = Staff::where('user_id', $validated['twman_id'])
            ->where('org_id_fk', $orgId)
            ->first();

        if (!$staff) {
            return back()->withInput()->withErrors([
                'twman_id' => 'ไม่พบข้อมูลเจ้าหน้าที่ในองค์กรนี้ กรุณาเลือกเจ้าหน้าที่ใหม่',
            ]);
        }

        $selections = collect(array_keys($validated['on']))->map(function ($key) {
            $parts = explode('-', (string) $key, 2);
            if (count($parts) !== 2 || !ctype_digit($parts[0]) || !ctype_digit($parts[1])) {
                return null;
            }

            return ['zone_id' => (int) $parts[0], 'subzone_id' => (int) $parts[1]];
        });

        if ($selections->contains(null)) {
            return back()->withInput()->withErrors([
                'on' => 'ข้อมูลเส้นทางไม่ถูกต้อง กรุณาเลือกเส้นทางใหม่',
            ]);
        }

        $subzones = Subzone::where('org_id_fk', $orgId)
            ->whereIn('id', $selections->pluck('subzone_id')->unique())
            ->get(['id', 'zone_id'])
            ->keyBy('id');

        foreach ($selections as $selection) {
            if (!$subzones->has($selection['subzone_id'])
                || (int) $subzones[$selection['subzone_id']]->zone_id !== $selection['zone_id']) {
                return back()->withInput()->withErrors([
                    'on' => 'พบเส้นทางที่ไม่อยู่ในหมู่ที่เลือก กรุณาเลือกใหม่',
                ]);
            }
        }

        DB::transaction(function () use ($selections, $subzones, $staff, $orgId) {
            foreach ($selections as $selection) {
                UndertakerSubzone::firstOrCreate(
                    [
                        'org_id_fk' => $orgId,
                        'twman_id' => $staff->id,
                        'subzone_id' => $selection['subzone_id'],
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        });

        return redirect()->route('admin.undertaker_subzone')
            ->with('success', 'ทำการบันทึกข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * Summary of edit
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $undertakerSubzone = DB::table('undertaker_subzones as us')
            ->join('subzones as sz', 'sz.id', 'us.subzone_id')
            ->join('zones as z', 'z.id', 'sz.zone_id')
            ->select('us.subzone_id', 'sz.subzone_name', 'z.zone_name', 'z.id as zone_id')
            ->orderBy('z.id')
            ->where('twman_id', '=', $id)
            ->get();
        $undertakerSubzone_subzone_id = collect($undertakerSubzone)->pluck('subzone_id')->toArray();
        $subzones = DB::table('subzones as sz')
            ->join('zones as z', 'z.id', 'sz.zone_id')
            ->select('sz.id as subzone_id', 'sz.subzone_name', 'z.zone_name', 'z.id as zone_id')
            ->orderBy('z.id')
            ->get();

        $zone = [];
        foreach ($subzones as $subzone) {
            if (!in_array($subzone->subzone_id, $undertakerSubzone_subzone_id)) {
                array_push($zone, $subzone);
            }
        }

        $tw_mans = User::role('User')
            ->where('id', $id)
            ->with(
                'undertaker_subzone',
                'undertaker_subzone.subzone',
                'undertaker_subzone.subzone.zone'
            )
            ->get();

        return view('undertaker_subzone.edit', compact('zone', 'tw_mans'));
    }

    public function update(REQUEST $request, $id)
    {
        // date_default_timezone_set('Asia/Bangkok');

        // $tabwaterman_per_areas = TabWaterManPerArea::find($id);
        // $tabwaterman_per_areas->zone_name = $request->get('zone_name');
        // $tabwaterman_per_areas->location = $request->get('location');
        // $tabwaterman_per_areas->updated_at = date('Y-m-d H:i:s');
        // $tabwaterman_per_areas->save();
        // return redirect('tabwaterman_per_areas')->with(['massage' => 'ทำการบันทึกข้อมูลเรียบร้อยแล้ว']);
    }

    /**
     * Summary of delete
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $undertakerSubzone = UndertakerSubzone::where('id', $id);
        $undertakerSubzone->delete();
        return redirect('undertaker_subzone')->with(['success' => 'ทำการลบข้อมูลเรียบร้อยแล้ว']);
    }

    public function get_service_dashboard_data(Request $request)
    {
        $staffId = $request->input('staff_id');
        $orgId = $request->input('org_id_fk');
        $serviceType = $request->input('service_type'); // 'water', 'recycle_trash', 'wet_trash'
        
        if (empty($staffId) || empty($orgId) || empty($serviceType)) {
            return response()->json([
                'code' => 400,
                'message' => 'ข้อมูลไม่ครบถ้วน (ต้องการ user_id, org_id_fk, service_type)'
            ], 400);
        }

        // 1. ตรวจสอบสิทธิ์ Staff ตาม Org ID
        $staff = Staff::where('id', $staffId)
            ->where('org_id_fk', $orgId)
            ->where('deleted', '0')
            ->first();

        if (!$staff) {
            return response()->json([
                'code' => 404,
                'message' => 'ไม่พบข้อมูลสิทธิ์เจ้าหน้าที่ในองค์กรนี้'
            ], 404);
        }

        $currentInvPeriod = InvoicePeriod::where('status', 'active')
            ->where('org_id_fk', $orgId)
            ->get()->first();

        // 2. ดึง UndertakerSubzone ของ Staff คนนี้ พร้อม Relations
        $undertakerSubzones = UndertakerSubzone::where('twman_id', $staff->id)
            ->with(['subzone.zone'])
            ->get();

        // 3. วนลูปแยก Query นับจำนวนสมาชิกตาม Table ของแต่ละ Service Type
        foreach ($undertakerSubzones as $item) {
            $subzoneId = $item->subzone_id;

            if ($serviceType === 'water') {
                // 💧 1. Query นับจำนวนสมาชิก/มิเตอร์จาก TwMeterInfos
                $item->members = TwMeterInfos::where('undertake_subzone_id', $subzoneId)->count();

                // 💧 2. Query นับสถานะใบแจ้งหนี้จาก TwInvoice
                // กรอง subzone ผ่านความสัมพันธ์ tw_meter_infos (หรือ undertake_subzone_id)
                $item->members_status_init = TwInvoice::whereHas('tw_meter_infos', function ($q) use ($subzoneId) {
                    $q->where('undertake_subzone_id', $subzoneId);
                })->where('status', 'init')->count();

                $item->members_status_invoice = TwInvoice::whereHas('tw_meter_infos', function ($q) use ($subzoneId) {
                    $q->where('undertake_subzone_id', $subzoneId);
                })->where('status', 'invoice')->count();

                $item->members_status_paid = TwInvoice::whereHas('tw_meter_infos', function ($q) use ($subzoneId) {
                    $q->where('undertake_subzone_id', $subzoneId);
                })->where('status', 'paid')->count();
            } else if ($serviceType === 'recycle_trash') {
                // ♻️ Query สมาชิกจากตารางธนาคารขยะรีไซเคิล
                $item->members = User::where('org_id_fk', $orgId)
                    ->where('subzone_id', $subzoneId)
                    ->whereHas('wastePreference.kpBankAccount')
                    ->count();
                $item->members_status_init = 0;
                $item->members_status_invoice = 0;
                $item->members_status_paid = 0;
            } else if ($serviceType === 'wet_trash') {
                // 🍃 Query สมาชิกจากตารางธนาคารขยะเปียก
                // เติม Query ตารางขยะเปียกตามโครงสร้าง DB
            }
        }

        return response()->json([
            'code' => 200,
            'data' => [
                'service_type' => $serviceType,
                'undertaker_subzone' => $undertakerSubzones,
                'invoice_period' => $currentInvPeriod
            ]
        ], 200);
    }
}
