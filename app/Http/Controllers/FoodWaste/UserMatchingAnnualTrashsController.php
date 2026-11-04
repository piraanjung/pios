<?php

namespace App\Http\Controllers\FoodWaste;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organization;
use App\Models\FoodWaste\FoodAnnualTrash;
use App\Models\FoodWaste\FoodAnnualTrashStocks;
use App\Models\FoodWaste\FoodwastIotbox;
use App\Models\FoodWaste\FoodWasteIssueReport;
use App\Models\FoodWaste\FoodWasteIssueType;
use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\User;
use App\Models\AnnualTrash\AnnualTrashSubscription; // Import AnnualTrashSubscription model
use App\Models\AnnualTrash\AnnualTrash;
use App\Services\UserWasteStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserMatchingAnnualTrashsController extends Controller
{
    protected $wasteStatusService;

    public function __construct(UserWasteStatusService $wasteStatusService)
    {
        $this->wasteStatusService = $wasteStatusService;
    }


    public function index($w_user)
    {
        $w_user = User::find($w_user);
        $AnnualTrashs = $w_user->foodAnnualTrashs()->paginate(10);
        // view อ่านจากตัวแปร $wasteBins
        $wasteBins = $AnnualTrashs;
        return view('foodwaste.w.waste_bins.index', compact('w_user', 'AnnualTrashs', 'wasteBins'));
    }


    public function create($w_user)
    {
        $w_user = User::find($w_user);
        $foodwaste_bins = FoodAnnualTrash::all();
        $bins_pending   = FoodAnnualTrashStocks::where('status', 'pending')->get();
        $iotboxes       = FoodwastIotbox::where('status', 'pending')->get();
        $latLong        = Organization::where('id', Auth::user()->org_id_fk)->get(['lat', 'lng']);
        $bin_type       = 'fb';
        return view('foodwaste.w.waste_bins.create', compact('w_user', 'foodwaste_bins', 'bins_pending', 'bin_type', 'iotboxes', 'latLong'));
    }

    public function store(Request $request, User $w_user)
    {
        $request->validate([
            'bin_code' => 'required',
            'bin_type' => 'required|string|max:255',
            'user_group' => 'required',
            'iotboxes_id' => 'required',
            'location_description' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', Rule::in(['active', 'inactive', 'damaged', 'removed'])],
        ]);

        $w_user->foodAnnualTrashs()->create([
            'bin_code_fk' => $request->bin_code,
            'user_id' => $w_user->id,
            'bin_type' => $request->bin_type,
            'iotbox_id_fk' => $request->iotboxes_id != 0 ? $request->iotboxes_id : 0,
            'location_description' => $request->location_description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
        ]);

        FoodAnnualTrashStocks::where('id', $request->bin_code)->update([
            'status' => 'active',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($request->iotboxes_id != 0) {
            $iotbox = FoodwastIotbox::find($request->iotboxes_id);
            $iotbox->status = 'active';
            $iotbox->updated_at = date('Y-m-d H:i:s');
            $iotbox->save();
        }

        return redirect()->route('foodwaste.waste_bins.index', $w_user->id)
            ->with('success', 'เพิ่มถังขยะเรียบร้อยแล้ว!');
    }


    public function show(AnnualTrash $AnnualTrash)
    {
        return view('foodwaste.waste_bins.show', compact('AnnualTrash'));
    }

    public function edit(FoodAnnualTrash $bin)
    {
        return $bin;
        return view('foodwaste.w.waste_bins.edit', compact('AnnualTrash'));
    }


    public function update(Request $request, FoodAnnualTrash $foodAnnualTrash)
    {
        $request->validate([
            'bin_code' => ['nullable', 'string', 'max:255', Rule::unique('waste_bins')->ignore($foodAnnualTrash->id)],
            'bin_type' => 'required|string|max:255',
            'location_description' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => ['required', Rule::in(['active', 'inactive', 'damaged', 'removed'])],
            'is_active_for_annual_collection' => 'boolean',
        ]);

        // DB::transaction(function () use ($request, $foodAnnualTrash) {
        $oldIsActiveForAnnualCollection = $foodAnnualTrash->is_active_for_annual_collection;
        $newIsActiveForAnnualCollection = $request->has('is_active_for_annual_collection');

        $data = $request->all();
        $data['is_active_for_annual_collection'] = $newIsActiveForAnnualCollection;

        $foodAnnualTrash->update($data); // Update waste bin data

        // If status changed to active for annual collection, create/ensure subscription
        if (!$oldIsActiveForAnnualCollection && $newIsActiveForAnnualCollection) {
            $fiscalYear = AnnualTrashSubscription::calculateFiscalYear();
            $annualFee = 1200.00; // Default annual fee
            $monthlyFee = $annualFee / 12;

            AnnualTrashSubscription::firstOrCreate(
                [
                    'waste_bin_id' => $foodAnnualTrash->id,
                    'fiscal_year' => $fiscalYear,
                ],
                [
                    'annual_fee' => $annualFee,
                    'monthly_fee' => $monthlyFee,
                    'total_paid_amount' => 0,
                    'status' => 'pending',
                ]
            );
        }
        // If status changed from active to inactive, you might want to update the subscription status to cancelled/inactive
        // Or handle this logic in a separate process. For now, we only create on activation.

        // Call service to update overall user waste status (waste_preference)
        $this->wasteStatusService->updateAnnualTrashAndUserStatus($foodAnnualTrash, $data);
        // });

        return redirect()->route('foodwaste.waste_bins.index', $foodAnnualTrash->user->id)
            ->with('success', 'อัปเดตถังขยะเรียบร้อยแล้ว!');
    }


    public function destroy(FoodAnnualTrash $AnnualTrash)
    {
        $w_user = optional($AnnualTrash->fw_user_preference)->user; // Get user before deleting bin

        DB::transaction(function () use ($AnnualTrash, $w_user) {
            $AnnualTrash->delete();
            // Call service to update overall user waste status (waste_preference)
            $this->wasteStatusService->updateOverallUserWasteStatus($w_user);
        });

        return redirect()->route('foodwaste.waste_bins.index', $w_user->id)
            ->with('success', 'ลบถังขยะเรียบร้อยแล้ว!');
    }

    public function viewmap()
    {
        $lat_long = Organization::where('id', Auth::user()->org_id_fk)->get(['lat', 'lng']);
        $members = FoodWasteUserPreference::with('user:id,firstname,lastname,address')
            ->where('org_id_fk', Auth::user()->org_id_fk)
            ->get()
            ->filter(fn ($p) => $p->user)
            ->map(fn ($p) => [
                'id' => $p->id,
                'text' => trim($p->user->firstname . ' ' . $p->user->lastname) . ($p->user->address ? ' (' . $p->user->address . ')' : ''),
            ])->values();
        $issueTypes = FoodWasteIssueType::where('is_active', 1)->get(['id', 'name', 'color']);
        $mapCfg = [
            'lat' => $lat_long[0]->lat ?? null,
            'lng' => $lat_long[0]->lng ?? null,
            'mapUrl' => route('foodwaste.waste_bins.map'),
            'storeUrl' => route('foodwaste.waste_bins.map.store'),
            'optionsUrl' => route('foodwaste.waste_bins.map.options'),
            'optionsNeedMember' => false,
            'csrf' => csrf_token(),
            'members' => $members,
            'issueTypes' => $issueTypes,
        ];
        return view('foodwaste.dashboard_map', compact('mapCfg'));
    }

    public function binOptions(Request $request)
    {
        // ถังที่ลงทะเบียนไว้ใน stock แต่ยังไม่ได้ผูกกับสมาชิก
        $stocks = FoodAnnualTrashStocks::where('org_id_fk', Auth::user()->org_id_fk)
            ->where('status', 'pending')->orderBy('bin_code')->get(['id', 'bin_code', 'description']);

        return response()->json($stocks->map(fn ($s) => [
            'id' => 'stock:' . $s->id,
            'text' => $s->bin_code . ($s->description ? ' · ' . $s->description : ''),
        ])->values());
    }

    public function storeFromMap(Request $request)
    {
        $data = $request->validate([
            'member_id' => 'required|integer',
            'refs' => 'required|array|min:1|max:50',
            'refs.*' => ['required', 'string', 'regex:/^stock:\d+$/'],
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_description' => 'nullable|string|max:255',
        ]);

        $orgId = Auth::user()->org_id_fk;
        $pref = FoodWasteUserPreference::where('org_id_fk', $orgId)->findOrFail($data['member_id']);

        $codes = DB::transaction(function () use ($data, $pref, $orgId) {
            $codes = [];
            foreach ($data['refs'] as $i => $ref) {
                $stock = FoodAnnualTrashStocks::where('org_id_fk', $orgId)
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->findOrFail((int) substr($ref, 6));

                FoodAnnualTrash::create([
                    'org_id_fk' => $orgId,
                    'u_pref_id_fk' => $pref->id,
                    'bin_code_fk' => $stock->id,
                    'bin_code' => $stock->bin_code,
                    'bin_type' => $stock->bin_type ?: 'fb',
                    'location_description' => $data['location_description'] ?? null,
                    // ถังแรกใช้พิกัดที่ปัก ถังถัดไปเลื่อนเล็กน้อยให้เห็นว่าซ้อนกัน
                    'latitude' => (float) $data['latitude'] + $i * 0.00002,
                    'longitude' => (float) $data['longitude'] + $i * 0.00002,
                    'status' => 'active',
                ]);
                $stock->update(['status' => 'active']);
                $codes[] = $stock->bin_code;
            }
            return $codes;
        });

        return response()->json(['message' => 'ผูกถัง ' . count($codes) . ' ใบ (' . implode(', ', $codes) . ') เรียบร้อย', 'count' => count($codes)]);
    }

    public function map()
    {
        // foodwaste_bins ผูกกับสมาชิกผ่าน u_pref_id_fk -> FoodWasteUserPreference -> User
        $bins = FoodAnnualTrash::where('org_id_fk', Auth::user()->org_id_fk)->with([
            'fw_user_preference.user' => function ($q) {
                return $q->select('id', 'firstname', 'lastname', 'address', 'zone_id', 'subzone_id');
            },
            'fw_user_preference.user.user_zone' => function ($q) {
                return $q->select('id', 'zone_name');
            },
            'fw_user_preference.user.user_subzone' => function ($q) {
                return $q->select('id', 'subzone_name');
            },
        ])->get(['id', 'u_pref_id_fk', 'bin_code', 'latitude', 'longitude', 'status', 'bin_type']);

        // ปัญหาล่าสุดที่ยังไม่แก้ไขของสมาชิกแต่ละราย (รายงานผูกกับ user ไม่ได้ผูกกับถัง)
        $userIds = $bins->map(fn ($b) => optional($b->fw_user_preference)->user_id)->filter()->unique();
        $issues = FoodWasteIssueReport::with('issueType:id,name,color')
            ->whereIn('user_id', $userIds)
            ->where('status', '!=', 'resolved')
            ->orderBy('id')
            ->get()
            ->keyBy('user_id'); // รายการล่าสุดทับรายการก่อนหน้า

        // view dashboard_map อ่าน bin.user.* จึงคงรูปแบบ JSON เดิมไว้
        return response()->json($bins->map(function ($bin) use ($issues) {
            $user = optional($bin->fw_user_preference)->user;
            $issue = $user ? $issues->get($user->id) : null;
            return [
                'id' => $bin->id,
                'bin_code' => $bin->bin_code,
                'latitude' => $bin->latitude,
                'longitude' => $bin->longitude,
                'status' => $bin->status,
                'bin_type' => $bin->bin_type,
                'user' => $user,
                'issue' => $issue && $issue->issueType ? [
                    'type_id' => $issue->issueType->id,
                    'name' => $issue->issueType->name,
                    'color' => $issue->issueType->color,
                    'status' => $issue->status,
                    'description' => $issue->description,
                ] : null,
            ];
        }));
    }
}
