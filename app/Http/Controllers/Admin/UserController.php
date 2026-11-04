<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\Admin\BudgetYear;
use App\Models\Admin\Organization;
use App\Models\Admin\Subzone;
use App\Models\AnnualTrash\AnnualTrashSubscription;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\TwInvoiceHistory;
use App\Models\User;
use App\Models\Admin\Zone;
use App\Models\FoodWaste\FoodWasteAccount;
use App\Models\AnnualTrash\AnnualTrashPayratePerMonth;
use App\Models\FoodWaste\FoodWasteUserPreference;
use App\Models\KeptKaya\KpUserWastePreference;
use App\Models\KpBankAccount;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\Tabwater\TwUsersInfos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\UserService;
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);

        // ใช้ with() เพื่อป้องกัน N+1 Query (โหลดข้อมูลบัญชีมาพร้อมกันเลย)

        $orgId = $this->userService->resolveOrgId();

        $query = User::with([
            'kpUserPreference',
            'kpRecycleBankMember',
            'recycleBankAccount',
            'foodWasteBankAccount',
            'foodWasteAccount',
            'annualTrashMember',
            'annualTrashSubscription',
            'usermeterinfos',
            'welfareMember',
        ])
            ->where('org_id_fk', $orgId)
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('id_card', 'like', "%$search%")
                    ->orWhere('id', $search)
                    ->orWhere('address', 'like', "%$search%");
            });
        }


        // --- 🌟 Filter ตามโซน (Zone / Subzone) ---
        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }
        if ($request->filled('subzone_id')) {
            $query->where('subzone_id', $request->subzone_id);
        }

        // --- 🌟 Filter ตามสถานะบริการ (Service Status) ---
        if ($request->filled('service_filter')) {
            $filter = $request->service_filter;
            if ($filter == 'recycle') {
                $query->where(function ($q) {
                    $q->has('recycleBankAccount')->orHas('kpRecycleBankMember');
                });
            }
            if ($filter == 'food_waste') $query->has('foodWasteAccount');
            if ($filter == 'annual_trash') {
                $query->where(function ($q) {
                    $q->has('annualTrashSubscription')->orHas('annualTrashMember');
                });
            }
            if ($filter == 'tabwater') $query->has('usermeterinfos');
            if ($filter == 'welfare') $query->has('welfareMember');
        }
        $users = ($perPage == 'all') ? $query->get() : $query->paginate($perPage);

        // ดึงข้อมูล Zone สำหรับตัวเลือก Filter (กรองตามองค์กร)
        $zones = Zone::where('org_id_fk', $orgId)->orWhereNull('org_id_fk')->get();

        return view('admin.users.index', compact('users', 'perPage', 'zones'));
    }

    public function users_search(Request $request) 
    { 
        $users = User::role("user")->whereIn("zone_id", $request->input("zone"))->get(); 
        $usertype = "user"; 
        $zones = Zone::all(); 
        return view('admin.users.index', compact('users', 'usertype', 'zones')); 
    } 

    public function staff() 
    { 
        return redirect()->route('keptkayas.staffs.index'); 
    } 

    public function create() 
    { 
        $org = Organization::find(Auth::user()->org_id_fk); 
        $zones = Zone::all(); 
        $defaultAddress = [ 
            'province' => $org->provinces->province_name ?? '-', 
            'district' => $org->districts->district_name ?? '-', 
            'tambon' => $org->tambons->tambon_name ?? '-' 
        ]; 
        return view('admin.users.create', compact('zones', 'defaultAddress')); 
    }


    /**
     * บันทึกข้อมูลผู้ใช้ใหม่และเปิดสิทธิ์บริการ (Store)
     */
    public function store(Request $request) 
    { 
        $messages = [ 
            'required' => 'กรุณากรอกข้อมูลในช่อง :attribute', 
            'unique'   => ':attribute นี้มีอยู่ในระบบแล้ว', 
            'min'      => ':attribute ต้องมีความยาวอย่างน้อย :min ตัวอักษร', 
        ]; 

        $attributes = [ 
            'username'   => 'ชื่อผู้ใช้งาน', 
            'password'   => 'รหัสผ่าน', 
            'firstname'  => 'ชื่อจริง', 
            'lastname'   => 'นามสกุล', 
            'phone'      => 'เบอร์โทรศัพท์', 
            'id_card'    => 'เลขบัตรประชาชน', 
            'zone_id'    => 'โซน', 
            'subzone_id' => 'ซอย/ชุมชนย่อย', 
        ]; 

        $request->validate([ 
            'username'   => 'required|string|max:255|unique:users,username', 
            'password'   => 'required|string|min:6', 
            'firstname'  => 'required|string|max:255', 
            'lastname'   => 'required|string|max:255', 
            'phone'      => 'nullable|string|max:20', 
            'id_card'    => 'nullable|string|max:13|unique:users,id_card', 
            'zone_id'    => 'required', 
            'subzone_id' => 'required', 
        ], $messages,$attributes); 

        DB::beginTransaction(); 
        try { 
            $org = Organization::find(Auth::user()->org_id_fk); 

            // 1. สร้าง Master User Record
            $user = User::create([ 
                'username'      => $request->username, 
                'password'      => Hash::make($request->password), 
                'prefix'        => $request->prefix ?? 'นาย', 
                'firstname'     => $request->firstname, 
                'lastname'      => $request->lastname, 
                'phone'         => $request->phone, 
                'id_card'       => $request->id_card, 
                'address'       => $request->address, 
                'zone_id'       => $request->zone_id, 
                'subzone_id'    => $request->subzone_id, 
                'tambon_code'   => $org->org_tambon_id_fk ?? null, 
                'district_code' => $org->org_district_id_fk ?? null, 
                'province_code' => $org->org_province_id_fk ?? null, 
                'org_id_fk'     => $org->id ?? Auth::user()->org_id_fk, 
                'status'        => 'active', 
            ]); 

            $user->assignRole('User'); 

            // 2. แมปค่าบริการจาก Blade Switch (`svc_*`) ไปยัง UserService
            $serviceData = [
                'recycle'      => $request->has('svc_recycle') ? '1' : '0',
                'food_waste'   => $request->has('svc_food_waste') ? '1' : '0',                 'annual_trash' =>$request->has('svc_annual_trash') ? '1' : '0',
            ];

            // 3. เรียกใช้งาน UserService เพื่อเปิดบัญชีบริการและอัปเดตสิทธิ์
            $this->userService->syncUserServices($user,$serviceData);

            // 4. บันทึก/ซิงค์ KpUserWastePreference
            KpUserWastePreference::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'org_id_fk'             => $org->id ?? Auth::user()->org_id_fk,
                    'zone_id'               => $user->zone_id,
                    'subzone_id'            => $user->subzone_id,
                    'is_annual_collection'  => $request->has('svc_annual_trash') ? 1 : 0,                     'is_recycle_bank'       =>$request->has('svc_recycle') ? 1 : 0,
                ]
            );

            DB::commit(); 
            return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้งานและเปิดสิทธิ์บริการเรียบร้อยแล้ว'); 

        } catch (\Exception $e) { 
            DB::rollBack(); 
            return back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage()); 
        } 
    } 
    

    /**
     * อัปเดตข้อมูลผู้ใช้งานและสิทธิ์บริการ (Update)
     */
    public function update(Request $request,$id) 
    { 
        $messages = [ 
            'required' => 'กรุณากรอกข้อมูลในช่อง :attribute', 
            'unique'   => ':attribute นี้ถูกใช้งานแล้ว', 
        ]; 

        $request->validate([ 
            'username'   => 'required|string|max:255|unique:users,username,' . $id, 
            'firstname'  => 'required|string|max:255', 
            'lastname'   => 'required|string|max:255', 
            'phone'      => 'nullable|string|max:20|unique:users,phone,' . $id, 
            'id_card'    => 'nullable|string|max:13|unique:users,id_card,' . $id, 
            'password'   => 'nullable|min:6', 
            'zone_id'    => 'required', 
            'subzone_id' => 'required', 
        ], $messages); 

        DB::beginTransaction(); 
        try { 
            $user = User::findOrFail($id); 
            
            // 1. อัปเดตข้อมูลบุคคล
            $user->username   =$request->username; 
            $user->prefix     =$request->prefix; 
            $user->firstname  =$request->firstname; 
            $user->lastname   =$request->lastname; 
            $user->phone      =$request->phone; 
            $user->id_card    =$request->id_card; 
            $user->address    =$request->address; 
            $user->zone_id    =$request->zone_id; 
            $user->subzone_id =$request->subzone_id; 

            if ($request->filled('password')) { 
                $user->password = Hash::make($request->password); 
            } 
            $user->save(); 

            // 2. แมปค่าสวิตช์บริการจาก Blade (`svc_*`) ส่งเข้า UserService
            $serviceData = [
                'recycle'      => $request->has('svc_recycle') ? '1' : '0',
                'food_waste'   => $request->has('svc_food_waste') ? '1' : '0',                 'annual_trash' =>$request->has('svc_annual_trash') ? '1' : '0',
            ];

            // 3. ซิงค์สิทธิ์เปิด/ปิดบัญชีบริการ
            $this->userService->syncUserServices($user,$serviceData);

            // 4. อัปเดต KpUserWastePreference
            KpUserWastePreference::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'zone_id'              => $user->zone_id,
                    'subzone_id'           => $user->subzone_id,
                    'is_annual_collection' => $request->has('svc_annual_trash') ? 1 : 0,                     'is_recycle_bank'      =>$request->has('svc_recycle') ? 1 : 0,
                ]
            );

            DB::commit(); 
            return redirect()->route('admin.users.index')->with('success', 'อัปเดตข้อมูลและสิทธิ์บริการเรียบร้อยแล้ว'); 

        } catch (\Exception $e) { 
            DB::rollBack(); 
            return back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage()); 
        } 
    }

    public function edit($user_id, $addmeter = "") 
{ 
    $user = User::where('id', $user_id) 
        ->with([ 
            'user_zone', 
            'user_subzone', 
            'recycleBankAccount', 
            'foodWasteBankAccount', 
            'annualTrashSubscription',
            // ดึงความสัมพันธ์มิเตอร์น้ำประปาจาก User Model
            'usermeterinfos.undertake_zone',
            'usermeterinfos.undertake_subzone',
            'usermeterinfos.meter_type'
        ]) 
        ->firstOrFail(); 

    $org = Organization::find(Auth::user()->org_id_fk); 
    $defaultAddress = [ 
        'province' => $org->provinces->province_name ?? '-', 
        'amphure'  => $org->districts->district_name ?? '-', 
        'district' => $org->tambons->tambon_name ?? '-' 
    ]; 
    
    $zones = Zone::where('org_id_fk', Auth::user()->org_id_fk)->get(); 
    $subzones   = Subzone::where('zone_id', $user->zone_id)->get(); 
    $meterTypes = \App\Models\Tabwater\TwMeterType::all();
    
    $nextMeterCount   = collect($user->usermeterinfos)->count() + 1;
    $generatedMeterId = 'MTR-' . substr('000', strlen($org->id)).$org->id . '-' . substr('0000',strlen($user->id)).$user->id.'-'.$nextMeterCount;

    return view('admin.users.edit', compact( 
        'user', 'zones', 'subzones', 'defaultAddress', 'addmeter', 'meterTypes', 'generatedMeterId'
    )); 
}

 public function edit_invoices($meter_id)
    {
        $inv_period = InvoicePeriod::where('status', 'active')->get('id')->first();
        $usermeter_infos = TwMeterInfos::with([
            'invoice' => function ($q) use ($inv_period) {
                $budget_year = BudgetYear::with('invoicePeriod:id,budgetyear_id')->where('status', 'active')
                    ->get()->first();
                $inv_period_lists = collect($budget_year->invoicePeriod)->pluck('id');
                return $q->select('*')->whereIn('inv_period_id_fk', $inv_period_lists);
            }
        ])->where('meter_id', $meter_id)->get()->first();
        return view('tabwater.usermeter_infos.index', compact('usermeter_infos'));
    }

/**
 * ผูกมิเตอร์น้ำประปาใหม่เข้ากับผู้ใช้งาน (Master User)
 */
public function attachMeter(Request $request, $id)
{
    $request->validate([
        'meter_address'        => 'required|string',
        'undertake_zone_id'    => 'required',
        'undertake_subzone_id' => 'required',
        'metertype_id'         => 'required',
    ], [
        'required' => 'กรุณากรอกข้อมูลในช่อง :attribute ให้ครบถ้วน'
    ]);

    try {
        $user = User::findOrFail($id);
        $this->userService->registerToTabwater($user, [
            'factory_no'           => $request->factory_no,
            'submeter_name'        => $request->submeter_name,
            'meter_address'        => $request->meter_address,
            'undertake_zone_id'    => $request->undertake_zone_id,
            'undertake_subzone_id' => $request->undertake_subzone_id,
            'metertype_id'         => $request->metertype_id,
            'status'               => 'active',
            'acceptance_date'      => date('Y-m-d'),
            'last_meter_recording' => 0,
        ]);

        return back()->with('success', 'ผูกมิเตอร์น้ำประปาใหม่เรียบร้อยแล้ว');
    } catch (\Exception $e) {
        return back()->with('error', 'เกิดข้อผิดพลาดในการผูกมิเตอร์: ' . $e->getMessage());
    }
}


    // กำหนดค่าเริ่มต้นให้ $action เป็น null เพื่อป้องกัน Error เวลาส่งมาตัวเดียว
    public function show($function, $action = null)
    {
        if ($function == 'store') {
            return $action . "Error";
        }

        // Logic ปกติสำหรับแสดง User ถ้าไม่ใช่เคส store
        $user = User::find($function);
        // ยังไม่มีหน้ารายละเอียดแยก ใช้หน้าแก้ไขซึ่งแสดงข้อมูลครบแล้ว
        return redirect()->route('admin.users.edit', $user->id ?? $function);
    }

    public function history(User $user)
    {
        $user = User::with('usermeterinfos', 'usermeterinfos.invoice')->where('id', $user->id)->get();
        return view('admin.users_tabwater.history', compact('user'));
    }
    public function assignRole(Request $request, User $user)
    {
        if ($user->hasRole($request->role)) {
            return back()->with('message', 'Role exists.');
        }

        $user->assignRole($request->role);
        return back()->with('message', 'Role assigned.');
    }

    public function cancel($user_id)
    {
        //check ว่ามีค้างจ่ายไหม
        $user = User::where('id', $user_id)
            ->with([
                'usermeterinfos',
                'usermeterinfos.invoice' => function ($query) {
                    return $query->select('meter_id_fk', 'inv_id', 'status')
                        ->whereIn('status', ['init', 'tw_invoices', 'owe']);
                }
            ])
            ->get();
        return view('admin.users_tabwater.cancel', compact('user'));
    }

    public function removeRole(User $user, Role $role)
    {
        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return back()->with('message', 'Role removed.');
        }

        return back()->with('message', 'Role not exists.');
    }
    public function givePermission(Request $request, User $user)
    {
        return $user;
        if ($user->hasPermissionTo($request->permission)) {
            return back()->with('message', 'Permission exists.');
        }
        $user->givePermissionTo($request->permission);
        return back()->with('message', 'Permission added.');
    }

    public function revokePermission(User $user, Permission $permission)
    {
        if ($user->hasPermissionTo($permission)) {
            $user->revokePermissionTo($permission);
            return back()->with('message', 'Permission revoked.');
        }
        return back()->with('message', 'Permission does not exists.');
    }
    public function destroy($meter_id)
    {
        $usermeterinfos = TwUsersInfos::where('meter_id', $meter_id)->get(['user_id', 'meter_id'])->first();

        $user = User::find($usermeterinfos->user_id);
        if ($user->hasRole('admin')) {
            return back()->with('message', 'you are admin.');
        }

        $invoices = TwInvoice::where('meter_id_fk', $usermeterinfos->meter_id)->get();
        $invoicesHistory = TwInvoiceHistory::where('meter_id_fk', $usermeterinfos->meter_id)->get();

        foreach ($invoices as $invoice) {
            if ($invoice->status == 'init') {
                TwInvoice::where('inv_id', $invoice->inv_id)->delete();
            } else if ($invoice->status == 'tw_invoices') {
                TwInvoice::where('inv_id', $invoice->inv_id)->update([
                    'status'        => 'owe',
                    'updated_at'    => date('Y-m-d H:i:s')

                ]);
            }
        }
        TwInvoice::where('meter_id_fk', $usermeterinfos->meter_id)->update([
            'deleted' => '1',
        ]);
        TwInvoiceHistory::where('meter_id_fk', $usermeterinfos->meter_id)->update([
            'deleted' => '1',
        ]);
        $checkInvoiceHasHistoryInfos = collect($invoices)->filter(function ($v) {
            return $v->status == 'paid' || $v->status == 'owe';
        })->count();
        $checkInvoiceHistoryHasHistoryInfos = collect($invoicesHistory)->filter(function ($v) {
            return $v->status == 'paid';
        })->count();
        TwUsersInfos::where('meter_id', $meter_id)->update([
            'status'        => $checkInvoiceHasHistoryInfos > 0 && $checkInvoiceHistoryHasHistoryInfos > 0  ? 'deleted' : 'inactive',
            'deleted'       => '1',
            'comment'       => $checkInvoiceHasHistoryInfos > 0 && $checkInvoiceHistoryHasHistoryInfos > 0 ? 'ยกเลิกการใช้งาน' :  'ยกเลิกการใช้งานแต่มีข้อมูลเก่า',
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        $checkHaveMeternumber = $usermeterinfos::where([
            'status' => 'active',
            'user_id' => $usermeterinfos->user_id
        ])->count();

        if ($checkHaveMeternumber == 0) {
            $user->update([
                'status'        => 'deleted',
                'comment'       => 'ยกเลิกการใช้งาน',
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }



        // $user->delete();
        // FunctionsController::reset_auto_increment_when_deleted('users');
        return redirect()->route('admin.users.index')->with(['message' => 'ทำการลบข้อมูลผู้ใช้งานระบบเรียบร้อยแล้ว', 'color' => 'success']);
    }

    public function showRegistrationForm()
    {
        $organizations = Organization::all();

        return view('auth.register', compact('organizations'));
    }

    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'prefix' => 'nullable|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'id_card' => 'nullable|string|max:13|unique:users,id_card',
            'line_id' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'organization_id' => 'nullable|exists:organizations,id',
            'zone_id' => 'nullable|exists:zones,id',
            'subzone_id' => 'nullable|exists:subzones,id', // Assuming subzones is the table name
            'tambon_code' => 'nullable|string|max:10',
            'district_code' => 'nullable|string|max:10',
            'province_code' => 'nullable|string|max:10',
            'status' => 'nullable|string|in:active,inactive,pending',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'prefix' => $request->prefix,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'id_card' => $request->id_card,
            'line_id' => $request->line_id,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
            'org_id_fk' => $request->organization_id ?? $request->org_id_fk ?? (Auth::check() ? Auth::user()->org_id_fk : null),
            'zone_id' => $request->zone_id,
            'subzone_id' => $request->subzone_id,
            'tambon_code' => $request->tambon_code,
            'district_code' => $request->district_code,
            'province_code' => $request->province_code,
            'status' => $request->status ?? 'pending', // Default to 'pending'
        ]);
        $user->assignRole('User');

        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }

    /**
     * ค้นหารายชื่อผู้ใช้งานจากตารางหลัก (users) เพื่อให้แต่ละโมดูลดึงไปลงทะเบียนเป็น Member
     */
    public function lookupModuleCandidates(Request $request, $module = null)
    {
        $orgId = $this->userService->resolveOrgId();
        $query = User::where('org_id_fk', $orgId)
            ->with(['user_zone', 'user_subzone']);

        if ($module === 'tabwater') {
            $query->whereDoesntHave('usermeterinfos', fn($q) => $q->where('org_id_fk', $orgId));
        } elseif ($module === 'recycle_bank') {
            $query->whereDoesntHave('kpRecycleBankMember', fn($q) => $q->where('org_id_fk', $orgId))
                  ->whereDoesntHave('recycleBankAccount', fn($q) => $q->where('org_id_fk', $orgId))
                  ->whereDoesntHave('kpUserPreference', fn($q) => $q->where('is_recycle_bank', 1));
        } elseif ($module === 'annual_trash') {
            $query->whereDoesntHave('annualTrashMember', fn($q) => $q->where('org_id_fk', $orgId));
        } elseif ($module === 'foodwaste') {
            $query->whereDoesntHave('foodWasteAccount', fn($q) => $q->where('org_id_fk', $orgId))
                  ->whereDoesntHave('foodWasteBankAccount', fn($q) => $q->where('org_id_fk', $orgId))
                  ->whereDoesntHave('foodwastePreference', fn($q) => $q->where('is_foodwaste_bank', 1));
        } elseif ($module === 'welfare') {
            $query->whereDoesntHave('welfareMember', fn($q) => $q->where('org_id_fk', $orgId));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('id_card', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('zone_id')) {
            $query->where('zone_id', $request->zone_id);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->orderBy('id', 'desc')->limit(50)->get(),
        ]);
    }

    /**
     * สร้างผู้ใช้งานใหม่ลงในตารางหลัก (users) แบบด่วน (Quick Register) เพื่อนำไปผูกเข้าโมดูลต่อได้ทันที
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'firstname'  => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'id_card'    => 'nullable|string|max:13|unique:users,id_card',
            'zone_id'    => 'nullable|integer',
            'subzone_id' => 'nullable|integer',
            'address'    => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $orgId = $this->userService->resolveOrgId();
            $org = Organization::find($orgId);
            $username = $request->username ?: ('U' . $orgId . ($request->phone ?: time()));

            $user = User::create([
                'username'      => $username,
                'password'      => Hash::make($request->password ?: ($request->phone ?: '12345678')),
                'prefix'        => $request->prefix ?? 'นาย',
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'phone'         => $request->phone,
                'id_card'       => $request->id_card,
                'address'       => $request->address,
                'zone_id'       => $request->zone_id,
                'subzone_id'    => $request->subzone_id,
                'tambon_code'   => $org->org_tambon_id_fk ?? null,
                'district_code' => $org->org_district_id_fk ?? null,
                'province_code' => $org->org_province_id_fk ?? null,
                'org_id_fk'     => $orgId,
                'status'        => 'active',
            ]);

            $user->assignRole('User');

            KpUserWastePreference::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'org_id_fk'            => $orgId,
                    'zone_id'              => $user->zone_id,
                    'subzone_id'           => $user->subzone_id,
                    'address'              => $user->address,
                    'is_annual_collection' => 0,
                    'is_recycle_bank'      => 0,
                ]
            );

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'success',
                    'message' => 'ลงทะเบียนผู้ใช้ในตารางหลัก (users) เรียบร้อยแล้ว',
                    'user'    => $user,
                ]);
            }

            return back()->with('success', "ลงทะเบียนคุณ {$user->firstname} {$user->lastname} ในตารางหลัก (users) เรียบร้อยแล้ว สามารถเลือกผูกเข้าโมดูลได้ทันที");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                ], 422);
            }
            return back()->withInput()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function users_by_subzone($subzone_id)
    {
        $users = User::where('subzone_id', $subzone_id)
            ->get(['id', 'firstname', 'lastname', 'subzone_id']);
        return response()->json($users);
    }

    public function updateMetrics(Request $request)
    {
        $request->validate([
            'age'    => 'required|integer|min:1|max:120',
            'weight' => 'required|numeric|min:10|max:300',
            'height' => 'required|integer|min:50|max:250',
            'gender' => 'required|in:male,female',
        ]);

        $user = User::find(Auth::id());
        $user->update([
            'age'    => $request->age,
            'weight' => $request->weight,
            'height' => $request->height,
            'gender' => $request->gender == 'male' ? 'm' : 'f',
        ]);

        return back()->with('success', 'บันทึกข้อมูลร่างกายเรียบร้อยแล้ว');
    }


    public function updateService(Request $request) 
    { 
        $services = $request->input('services', []); 

        try { 
            $this->userService->updateServices($services);
            return back()->with('success', 'บันทึกการตั้งค่าสิทธิ์ผู้ใช้งานเรียบร้อยแล้ว'); 
        } catch (\Exception $e) { 
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage()); 
        } 
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new UserImport, $request->file('file'));
            return back()->with('success', 'นำเข้าข้อมูลผู้ใช้งานเรียบร้อยแล้ว');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $messages = collect($e->failures())->flatMap(function ($failure) {
                return collect($failure->errors())->map(function ($message) use ($failure) {
                    return "แถว {$failure->row()} คอลัมน์ {$failure->attribute()}: {$message}";
                });
            });

            return back()->withErrors($messages);
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }


    public function downloadUserTemplate()
    {
        // ตั้งชื่อไฟล์ให้ชัดเจน
        return Excel::download(new UserTemplateExport, 'template_import_users.xlsx');
    }
}
