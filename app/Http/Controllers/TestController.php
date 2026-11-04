<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\FunctionsController;
use App\Models\Admin\BudgetYear;
use App\Models\Admin\Organization;
use App\Models\Admin\Staff;
use App\Models\Admin\UserProfile;
use App\Models\QaAccTrans;
use App\Models\QaInvoice;
use App\Models\QaUser;
use App\Models\QaUsermeterInfos;
use App\Models\KpBankAccount;
use App\Models\Tabwater\SequenceNumber;
use App\Models\Tabwater\TwAccTransactions;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\User;
use App\Models\UserMerterInfo;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestController extends Controller
{
    public function textSendLineMessage()
    {
        $headUserId = "U35f2218b721c112bbc16538248b88e6b";

        $channelToken = config('services.line_staff.channel_token');
        $triggerText = "งานเข้า " . 1234;

        $messageText = "🚨 **มีแจ้งเหตุใหม่ (#{1234})**\n"
            . "องค์กร: เทพวงศา'-') \n"
            . "ประเภท: งานขยะเศษอาหาร \n"
            . "---------------------------\n"
            . "📌 **กรุณาก๊อปปี้ข้อความด้านล่างนี้ วางลงในกลุ่มงาน:**\n\n"
            . $triggerText;

        // 3. ส่ง 1-on-1 หาหัวหน้า
        Http::withHeaders([
            'Authorization' => "Bearer {$channelToken}",
            'Content-Type'  => 'application/json',
        ])->post('https://api.line.me/v2/bot/message/push', [
            'to'       => $headUserId,
            'messages' => [['type' => 'text', 'text' => $messageText]],
        ]);
    }
    // Helper function ตัวอย่างสำหรับแปลงงวดเป็นปีงบประมาณ
    function getBudgetYearFromPeriod($periodId)
    {
        // สมมติว่าดึงจาก DB หรือคำนวณช่วงงวด เช่น งวด 52-63 = ปี 2567, งวด 64-75 = ปี 2568
        // สามารถปรับ logic ตามโครงสร้างจริงของตาราง invoice_period ได้เลยครับ
        return DB::connection('qa')->table('invoice_period')
            ->where('id', $periodId)
            ->value('budgetyear_id') ?? 1;
    }
    public function index(Request $request)
    {


        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $transactionGroups = DB::connection('qa')->table('users')
        ->whereIn('role_id', [2,5])
        ->where('status', '1')
        ->get();
        


// 1. ดึงข้อมูล Transaction จากใบแจ้งหนี้เดิม จัดกลุ่มตาม acc_trans_id_fk พร้อม SUM ค่าต่างๆ แยกคอลัมน์
// $transactionGroups = DB::connection('qa')->table('invoice')
//     ->select(
//         'acc_trans_id_fk',
//         'meter_id_fk',
//         'recorder_id', // หรือคอลัมน์แคชเชียร์จาก DB เก่า
//         DB::raw('SUM(vat) as vatsum'),
//         DB::raw('SUM(reserve_meter) as reserve_meter_sum'),
//         DB::raw('SUM(paid) as paidsum'),
//         DB::raw('SUM(totalpaid) as totalpaidsum'),
//         DB::raw('MIN(created_at) as first_created_at'),
//         DB::raw('MAX(updated_at) as last_updated_at')
//     )
//     ->where('status', '!=', 'deleted')
//     ->where('inv_period_id_fk', '>=', 52)
//     ->whereNotNull('acc_trans_id_fk')
//     ->where('acc_trans_id_fk', '>', 0)
//     ->groupBy('acc_trans_id_fk', 'meter_id_fk', 'recorder_id')
//     ->get();

// // 2. Loop Insert เข้า tw_acc_transactions และอัปเดต acc_trans_id_fk คืนให้ tw_invoice
// foreach ($transactionGroups as $group) {

//     $transId = $group->acc_trans_id_fk;

//     // A. ดึงข้อมูล Transaction เดิม (เพื่อเอาข้อมูลอื่นๆ เช่น cashier ถ้ามี)
//     $oldTrans = DB::connection('qa')->table('acc_transactions')
//         ->where('id', $transId)
//         ->first();

//     // Determine cashier ID (ดึงจาก account_transaction เดิม หรือใช้ recorder_id จากกลุ่ม invoice)
//     $cashierId = $oldTrans->cashier ?? $oldTrans->user_id ?? $group->recorder_id ?? 1;

//     // B. Insert ข้อมูลสร้าง Transaction ใหม่ลงตาราง tw_acc_transactions (คง id เดิมไว้)
//     DB::connection('envsogo_main')->table('tw_acc_transactions')->insertOrIgnore([
//         'id'                => $transId,                   // 🟢 คง Primary Key ID เดิมไว้
//         'org_id_fk'         => 1,
//         'meter_id_fk'       => $group->meter_id_fk,
//         'vatsum'            => $group->vatsum,             // ผลรวม VAT คำนวณใหม่
//         'reserve_meter_sum' => $group->reserve_meter_sum,  // ผลรวมค่ารักษามิเตอร์ คำนวณใหม่
//         'paidsum'           => $group->paidsum,            // ผลรวมค่าน้ำ คำนวณใหม่
//         'totalpaidsum'      => $group->totalpaidsum,       // ผลรวมสุทธิ คำนวณใหม่
//         'cashier'           => $cashierId,                 // ID ผู้รับชำระเงิน
//         'created_at'        => $oldTrans->created_at ?? $group->first_created_at,
//         'updated_at'        => $oldTrans->updated_at ?? $group->last_updated_at,
//     ]);

//     // C. อัปเดต acc_trans_id_fk กลับไปยัง tw_invoice ใน DB ใหม่
//     // อัปเดตผ่านคู่ (meter_id_fk + inv_period_id_fk) ของกลุ่ม Invoice เดิมที่อยู่ใน Transaction นี้
//     $relatedInvoices = DB::connection('qa')->table('invoice')
//         ->where('acc_trans_id_fk', $transId)
//         ->where('status', '!=', 'deleted')
//         ->where('inv_period_id_fk', '>=', 52)
//         ->select('meter_id_fk', 'inv_period_id_fk')
//         ->get();

//     foreach ($relatedInvoices as $rel) {
//         DB::connection('envsogo_main')->table('tw_invoice')
//             ->where('meter_id_fk', $rel->meter_id_fk)
//             ->where('inv_period_id_fk', $rel->inv_period_id_fk)
//             ->update([
//                 'acc_trans_id_fk' => $transId // 🟢 ผูก Transaction ID กลับคืนให้ใบแจ้งหนี้ใน tw_invoice
//             ]);
//     }

//       }     
//        return response()->json(['message' => 'Test']);

//         $targetOrgId = 1;

//         // 1. ดึง Pre-fetch ยอดค้างชำระสะสม (previous_balance) เข้า Memory เพื่อความเร็ว
//         $previousBalances = DB::connection('qa')->table('invoice')
//             ->select('meter_id_fk', 'inv_period_id_fk', DB::raw('SUM(totalpaid) as total_owe'))
//             ->where('status', 'owe')
//             ->groupBy('meter_id_fk', 'inv_period_id_fk')
//             ->get()
//             ->groupBy('meter_id_fk');

//         // 2. ตัวแปรเก็บเลข inv_no ล่าสุด แยกตาม "ปีงบประมาณ"
//         $runningInvNoByYear = [];

//         // 3. ดึงรายการ Invoice เก่าเรียงตามงวดและ ID เพื่อให้ลำดับเวลาถูกต้อง
//         DB::connection('qa')->table('invoice')
//             ->where('status', '!=', 'deleted')
//             ->where('inv_period_id_fk', '>=', 52)
//             ->orderBy('inv_period_id_fk', 'asc') // เรียงตามงวดจากอดีตไปปัจจุบัน
//             ->orderBy('inv_id', 'asc')           //
//             ->chunkById(1000, function ($oldInvoices) use ($targetOrgId, &$runningInvNoByYear, $previousBalances) {

//                 $dataToInsert = [];

//                 foreach ($oldInvoices as $inv) {

//                     // -----------------------------------------------------------------
//                     // A. หาปีงบประมาณจาก inv_period_id_fk
//                     // (สมมติว่ามีฟังก์ชัน หรือสูตรแปลง inv_period_id_fk เป็น budget_year_id หรือปี พ.ศ.)
//                     // -----------------------------------------------------------------
//                     $budgetYear = $this->getBudgetYearFromPeriod($inv->inv_period_id_fk); // เช่น คืนค่าเป็น 2567, 2568 หรือ budget_year_id

//                     // ถ้าเป็นปีงบประมาณใหม่ที่ยังไม่เคยรัน ให้เริ่มนับ 1 ใหม่
//                     if (!isset($runningInvNoByYear[$budgetYear])) {
//                         $runningInvNoByYear[$budgetYear] = 1;
//                     } else {
//                         $runningInvNoByYear[$budgetYear]++; // ถ้าเป็นปีเดิม รันบวก 1 ต่อไปเรื่อยๆ
//                     }

//                     $newInvNoInteger = $runningInvNoByYear[$budgetYear];

//                     // -----------------------------------------------------------------
//                     // B. ดึง previous_balance จาก Memory
//                     // -----------------------------------------------------------------
//                     $previousBalance = 0;
//                     if (isset($previousBalances[$inv->meter_id_fk])) {
//                         $previousBalance = $previousBalances[$inv->meter_id_fk]
//                             ->where('inv_period_id_fk', '<', $inv->inv_period_id_fk)
//                             ->sum('total_owe');
//                     }

//                     // -----------------------------------------------------------------
//                     // C. เตรียมข้อมูล Bulk Insert
//                     // -----------------------------------------------------------------
//                     $dataToInsert[] = [
//                         'org_id_fk'        => $targetOrgId,
//                         'inv_no'           => $newInvNoInteger,        // 🟢 เลข Integer ที่รันยาวจนจบปี แล้วรีเซ็ตเป็น 1 เมื่อขึ้นปีใหม่
//                         'inv_period_id_fk' => $inv->inv_period_id_fk,  //
//                         'meter_id_fk'      => $inv->meter_id_fk,       //
//                         'lastmeter'        => $inv->lastmeter,         //
//                         'currentmeter'     => $inv->currentmeter,      //
//                         'water_used'       => $inv->water_used,        //
//                         'inv_type'         => $inv->inv_type,          //
//                         'paid'             => $inv->paid,              //
//                         'vat'              => $inv->vat,               //
//                         'reserve_meter'    => $inv->reserve_meter,     //
//                         'totalpaid'        => $inv->totalpaid,         //
//                         'previous_balance' => $previousBalance,
//                         'acc_trans_id_fk'  => 0,                       // ตั้ง 0 ไว้ชั่วคราวตามแผน Step 1
//                         'recorder_id'      => $inv->recorder_id,       //
//                         'status'           => $inv->status,            //
//                         'created_at'       => $inv->created_at,        //
//                         'updated_at'       => $inv->updated_at,        //
//                     ];
//                 }

//                 // D. Bulk Insert ทีละ 1,000 รายการ
//                 DB::connection('envsogo_main')->table('tw_invoice')->insert($dataToInsert);
//             }, 'inv_id'); //




//         return response()->json(['message' => 'Indesx method executed successfully']);

//         // 1. ดึง Unique meter_id ทั้งหมดที่มี Invoice ผ่านเกณฑ์
//         $validMeterIds = DB::connection('qa')->table('invoice')
//             ->where('status', '!=', 'deleted')
//             ->where('inv_period_id_fk', '>=', 52)
//             ->pluck('meter_id_fk')
//             ->unique();

//         // 2. ดึง ข้อมูล user_meter_infos จาก DB เก่าตาม meter_id ที่คัดกรองได้
//         $oldMeters = DB::connection('qa')->table('user_meter_infos')
//             ->whereIn('meter_id', $validMeterIds)
//             ->get();

//         // 3. Loop Insert เข้าตาราง tw_meter_infos ใน DB ใหม่
//         foreach ($oldMeters as $meter) {
//             DB::connection('envsogo_main')->table('tw_meter_infos')->insert([
//                 'meter_id'             => $meter->meter_id,             // คงค่า Primary Key เดิม
//                 'org_id_fk'            => 1,                           // ID องค์กรของระบบใหม่
//                 'meter_address'        => $meter->meter_address,        // ที่อยู่น้ำประปา
//                 'submeter_name'        => "",                        // ฟิลด์ใหม่ใน DB ใหม่
//                 'user_id'              => $meter->user_id,              // FK ไปยังตาราง user
//                 'metertype_id'         => $meter->metertype_id,         // ประเภทมิเตอร์
//                 'undertake_zone_id'    => $meter->undertake_zone_id,    // โซน
//                 'undertake_subzone_id' => $meter->undertake_subzone_id, // โซนย่อย
//                 'acceptance_date'      => $meter->acceptance_date,      // วันที่ติดตั้ง/รับเรื่อง
//                 'status'               => $meter->status ?? 'active',   // สถานะมิเตอร์
//                 'payment_id'           => $meter->payment_id,           // ช่องทางการชำระเงิน
//                 'recorder_id'          => $meter->recorder_id,          // ผู้จดค่าน้ำ
//                 'cutmeter'             => '0',             // สถานะงดจ่ายน้ำ/ตัดมิเตอร์
//                 'factory_no'           => "",                        // ฟิลด์ใหม่
//                 'inv_no_index'         => $meter->inv_no ?? "",       // Map inv_no เดิมเข้า inv_no_index (ถ้ามี)
//                 'last_meter_recording' => 0,                        // ฟิลด์ใหม่
//                 'created_at'           => $meter->created_at,
//                 'updated_at'           => $meter->updated_at,
//             ]);
//         }
//         return response()->json(['message' => 'Migration completed successfully']);

//         $validUserIds = DB::connection('qa')->table('users')
//             ->whereIn('id', function ($q) {
//                 $q->select('user_id')
//                     ->from('user_meter_infos')
//                     ->whereIn('meter_id', function ($q2) {
//                         $q2->select('meter_id_fk')
//                             ->from('invoice')
//                             ->where('status', '!=', 'deleted')
//                             ->where('inv_period_id_fk', '>=', 52);
//                     });
//             })->pluck('id');

        // $users = DB::connection('qa')->table('users')->whereIn('id', $validUserIds)->get();
        foreach ($transactionGroups as $user) {
            DB::connection('envsogo_main')->table('users')->insert([
                'id'                => $user->id,
                'org_id_fk'         => 1, // คอลัมน์ใหม่ (ใส่ null หรือ default ID องค์กร)
                'username'          => $user->username,
                'password'          => $user->password,
                'prefix'            => $user->prefix,
                'firstname'         => $user->firstname,
                'lastname'          => $user->lastname,
                'email'             => $user->role_id == 3 && $user->email == 0 ? $user->username.rand(1000,9999) . '@example.com' : $user->email,
                'zone_id'           => $user->zone_id,
                'subzone_id'        => $user->subzone_id == 13 ? 20 : $user->subzone_id,
                'tambon_code'       => $user->tambon_code,
                'district_code'     => $user->district_code,
                'province_code'     => $user->province_code,
                'age'               => 0, // คอลัมน์ใหม่
                'weight'            => 0, // คอลัมน์ใหม่
                'height'            => 0, // คอลัมน์ใหม่
                'gender'            => $user->gender == "" ? 'w' : $user->gender,
                'line_id'           => $user->line_id,
                'line_user_id'      => "", // คอลัมน์ใหม่
                'id_card'           => $user->id_card,
                'phone'             => $user->phone,
                'image'             => "", // คอลัมน์ใหม่
                'address'           => $user->address,
                'email_verified_at' => $user->email_verified_at,
                'remember_token'    => $user->remember_token,
                'status'            => "active",
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,
            ]);

            // 2. ดึง User Instance จาก DB ใหม่เพื่อทำ Spatie Role Assignment
            $newUser = User::on('envsogo_main')->find($user->id);

            if ($newUser) {
                // ทุกคนจะได้รับ Role 'user' เป็นพื้นฐาน
                $newUser->assignRole('User');

                // เงื่อนไขเพิ่มเติมตาม role_id เดิม
                if ($user->role_id == 2) {
                    $newUser->assignRole('Admin');
                } elseif ($user->role_id == 5) {
                    $newUser->assignRole('Tabwater Staff');
                }
            }

            // 3. เช็คเงื่อนไข Insert ลงตาราง staffs (role_id 2 หรือ 5)
            if (in_array($user->role_id, [2, 5])) {
                DB::connection('envsogo_main')->table('staffs')->insert([
                    'user_id'    => $user->id,
                    'org_id_fk' => 1,
                    'status'     => "active",
                    'deleted'    => '0',
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
        }
        return response()->json(['message' => 'User migration completed']);

        if (!$request->boolean('migrate_qa_reference_data')) {
            return view('test.index');
        }

        $canRunMigration = Auth::check() && DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', Auth::id())
            ->where('model_has_roles.model_type', User::class)
            ->whereIn('roles.name', ['Admin', 'Super Admin'])
            ->exists();
        abort_unless($canRunMigration, 403);

        return response()->json($this->migrateQaTabwaterReferenceTables());

        $oldZone = DB::connection('qa_tabwater')->table('zones')->get();
        $users = User::all();
        foreach ($users as $user) {
            $user->assignRole('User');
            $user->assignRole('Tabwater User');
        }
        $staffs = User::whereHas('staffs')->get();
        foreach ($staffs as $staff) {
            $staff->assignRole('Staff');
            $isTwman = collect([$staff->username])->contains(function ($value) {
                return Str::contains(strtolower($value), 'twman');
            });
            if ($isTwman == 1) {
                $staff->assignRole('Tabwater Staff');
            } else {
                $staff->assignRole('Admin');
                if ($staff->username == "ssadmin1") {
                    $staff->assignRole('Tabwater Header');
                }
            }
        }

        return 'ss';
        // ->table('users as u')
        // ->leftJoin('user_meter_infos as umi', 'umi.user_id', '=', 'u.id')
        // ->leftJoin('invoice as iv', 'iv.meter_id_fk', '=', 'umi.meter_id')
        // ->whereIn('iv.status', ['invoice', 'init', 'owe']) // 🟢 แยก String ใน Array ให้ถูกต้อง
        // ->select(
        //     'u.id',
        //     'u.line_id',
        //     'u.role_id',
        //     'u.status as user_status',
        //     'umi.meter_id',
        //     'umi.status as umi_status',
        //     'iv.inv_id',
        //     'iv.status as invoice_status' // 🟢 ใส่ alias เพื่อไม่ให้ทับกับ u.status
        // )
        // ->get();

        // return  collect($users)->filter(function ($value) {
        //              if($value->role_id == 2  || $value->role_id == 5 ){
        //                 return $value;
        //             }
        //     })->count();
        return [
            'overAllUsers' => collect($users)->count(),
            'user_role_staff' =>  collect($users)->filter(function ($value) {
                if ($value->role_id == 2  || $value->role_id == 5) {
                    return $value;
                }
            })->count(),
        ];
        $users = User::on('qa')
            ->with(['usermeterinfosx.invoice' => function ($invQuery) {
                $invQuery->where('status', 'owe');
            }])
            // ->where(function ($query) {
            //     // เงื่อนไขที่ 1: role_id เป็น 2 หรือ 5
            //     $query->whereIn('role_id', [2, 5])

            //     // เงื่อนไขที่ 2: หรือมีข้อมูลใน usermeterinfos
            //     ->orWhereHas('usermeterinfos', function ($q) {
            //         $q->where(function ($meterQuery) {
            //             // Case 2.1: สถานะ Active
            //             $meterQuery->where('status', 'active')

            //             // Case 2.2: สถานะ Inactive แต่ต้องมียอดค้างชำระ (owe)
            //             ->orWhere(function ($inactiveQuery) {
            //                 $inactiveQuery->where('status', 'inactive')
            //                     ->whereHas('invoice', function ($invQuery) {
            //                         $invQuery->where('status', 'owe');
            //                     });
            //             });
            //         });
            //     });
            // })
            ->get();
        return $oldMeterInfo = DB::connection('qa')
            ->table('users')
            ->select('id')
            ->where('status', '0')
            ->get();
        return 'ss';
        $users = [
            [2,    "นาง", "พัชราวลัย",    "คะปัญญา",    24,     136.00, 'xx'],
            [4, "นาง", "ประถม", "ชื่นชุ่ม",    537,     96.00, 'xx'],
            [13, "นาง", "อุไร",    "ผาสีดา",    341,     44.00, 'xx'],
            [14, "นาง", "หนูเพียร",    "จันทาศรี",    262,     48.00,      "กรรมการ"],
            [21, "นาง", "วนิดา",    "แสงสว่าง",    386,     28.00, 'xx'],
            [22, "นาง", "วรรณภา",    "ทุมลี",    458,     1, 722.00,      "กรรมการ"],
            [23, "นางสาว", "จันทร์เพ็ญ",    "บุญตาท้าว",    160,     43.00,      "กรรมการ"],
            [26, "นาง", "ทองศรี",    "สุขสวน",    171,     198.00, 'xx'],
            [28, "นาย", "เทียม",    "ศรีจันทร์",    321,     94.00, 'xx'],
            [35, "นาง", "นงค์รักษ์",    "บัวระบัติ",    155,    -60.00, 'xx'],
            [36, "นาง", "บุญมี",    "สุมังคะ",    25,     153.00, 'xx'],
            [44, "นาย", "เสริมชัย",    "วานานวงศ์",    59,     64.00,      "กรรมการ"],
            [45, "นาย", "ทองอินทร์",    "ภูยอดเพชร",    258,     207.00,      "กรรมการ"],
            [46, "นาย", "เสนอ",    "โคตรบรม",    10,    -16.00, 'xx'],
            [47, "นาง", "จินตนา",    "กองอุ่น",    87,     122.00, 'xx'],
            [49, "นาง", "กนกอร",    "เพ็ชรคำ",    150,     28.00, 'xx'],
            [51, "นาย", "ไชยวัน",    "ทุมลี",    241,     1, 720.00,      "กรรมการ"],
            [55, "นาง", "อภิญญา",    "ธรรมพันธ์",    88,     349.00, 'xx'],
            [56, "นาง", "วิจิตร",    "ภูยอดเพชร",    502,     227.00,      "กรรมการ"],
            [65, "นาย", "พัชระ",    "คำศรี",    41,     788.00, 'xx'],
            [71, "นาง", "อินทรา",    "ศรีละวัลย์",    599,     55.00, 'xx'],
            [74, "นาย", "ธีระยุทธิ์",    "บุญตาท้าว",    147,     181.00, 'xx'],
            [75, "นางสาว", "รัตนาภรณ์",    "อุตวงษา",    31,     61.00, 'xx'],
            [86, "นาง", "ทัศนีย์",    "สุขสวน",    396,     182.00, 'xx'],
            [93, "นางสาว", "ทัศนี",    "โคตรบรม",    219,     77.00,      "กรรมการ"],
            [101, "นาง", "จันทร์หอม",    "ถาวรวยัคฆ์",    249,     24.00, 'xx'],
            [104, "นาง", "ดารณี",    "อรัญโชติ",    384,     301.00, 'xx'],
            [107, "นาง", "บัวผัน",    "สายคำภา",    254,     37.00, 'xx'],
            [117, "นาย", "ประยงค์",    "คำศรี",    18,     142.00, 'xx'],
            [119, "นาย", "ธวัชชัย",    "สายคำภา",    35,     43.00, 'xx'],
            [122, "นาย", "ภูริวัจน์",    "วะลัยศรี",    198,     310.00,      "กรรมการ"],
            [132, "นาย", "มนูญ",    "กองโพธิ์ชัย",    442,     133.00, 'xx'],
            [135, "นาง", "อรัญญา",    "วานานวงค์",    480,     49.00,      "กรรมการ"],
            [137, "นาย", "เครื่อง",    "พรมพันธ์",    "ม.3",     42.00, 'xx'],
            [138, "นาง", "ไสย",    "บุญตาท้าว",    303,     53.00, 'xx'],
            [143, "นาย", "วีระศักดิ์",    "สุขสวน",    19,     192.00, 'xx'],
            [144, "นาย", "วันชัย",    "สังข์แดง",    413,     130.00, 'xx'],
            [146, "นาง", "ดารุณี",    "ฮาบละคร",    223,     129.00, 'xx'],
            [148, "นาง", "บัวลอน",    "เดือยพิมย์",    28,     128.00, 'xx'],
        ];

        $i = 1;
        foreach ($users as $user) {
            $pass = substr('0000', strlen($i)) . $i++;
            $_user = User::create([
                'username'      => 'chk14' . $pass,
                'password'      => Hash::make($pass),
                'prefix'        => $user[1],
                'gender'        => $user[1] == "นาย" ? 'm' : 'w',
                'email'         => 'chk14' . $pass . "@gmail.com",
                'firstname'     => $user[2],
                'lastname'      => $user[3],
                'phone'         => '000000000',
                'address'       => $user[4],
                'zone_id'       => 35,
                'subzone_id'    => 52,
                'tambon_code'   => 470106,
                'district_code' => 4701,
                'province_code' => 47,
                'org_id_fk'     => 4,
                'status'        => 'active',
            ]);

            $_user->assignRole('User');
            if (trim($user[6]) == "กรรมการ") {
                Staff::create([
                    'id' => $_user->id,
                    'user_id' => $_user->id,
                    'org_id_fk' => 4,
                    'status' => 'active',
                    'deleted' => "0",
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $_user->assignRole('Recycle Bank Staff');
            }

            // 3. เช็คและเปิดบริการตามที่ติ๊กมา
            // ธนาคารขยะรีไซเคิล
            $org = Organization::find(Auth::user()->org_id_fk);
            $orgIdStr =  substr('000', strlen($org->id)) . $org->id;
            $userIdStr = substr('0000', strlen($_user->id)) . $_user->id;
            // 1. จัดการธนาคารขยะรีไซเคิล (recycle_)

            KpBankAccount::firstOrCreate(
                ['user_id' => $_user->id],
                [
                    'org_id_fk'  => 4,
                    'account_no' => 'RC-' . $org->org_code . "-" . $orgIdStr . $userIdStr,
                    'balance'    => $user[5],
                    'status'     => 'active'
                ]
            );
        }
        return 'xx';
        $next = 0;
        $a = TwInvoice::where('meter_id_fk', 343)
            ->where('org_id_fk', 1)
            ->get();
        $aa  = collect($a)->groupBy('acc_trans_id_fk');
        foreach ($aa as $key => $_a) {
            $aC = collect($_a)->count();
            if ($aC == 1) {
                if ($key == 0) {
                } else {
                    $cashbookPage = 0;
                }
            } elseif ($aC > 1) {
                $i = 0;
                $sumcashbookPage = 0;
                foreach ($_a as $__a) {
                    $i++;
                    if ($aC == $i) {
                        $cashbookPage = $sumcashbookPage;
                        $carriedForward = 0;
                        $sumcashbookPage = 0;
                    } else {
                        $sumcashbookPage = +$__a->totalpaid;
                        $cashbookPage = $sumcashbookPage;
                        $carriedForward = $sumcashbookPage;
                    }
                }
            }
        }
        return;
        set_time_limit(600);

        $org2 = User::where('org_id_fk', 2)->get();
        foreach ($org2 as $user) {
            if ($user->email == 'katsukipai16@gmail.com') {
                $user->assignRole("Admin");
            }
            $user->assignRole("User");
        }
        return;
        $twUers = User::all();
        foreach ($twUers as $twU) {
            // $twU->remember_token = QaUser::find($twU->age)->role_id;
            // $twU->save();
            $twU->assignRole('User');
            if ($twU->remember_token == 2) {
                $twU->assignRole('Finance Staff');
            }
            if ($twU->remember_token == 5) {
                $twU->assignRole('Tabwater Staff');
            }
        }
        return 'ss';
        // $users = DB::connection('qa')->select('select * from users'); 
        // // return collect($users)->count();
        // foreach($users as $user){
        //     try{
        //     $subzone_id = $user->subzone_id;
        //     if($subzone_id == 35){
        //         $subzone_id = 22;
        //     }else if($subzone_id == 13){
        //         $subzone_id =20;
        //     }
        //     DB::connection('envsogo_main')->table('users')->insert([
        //     "org_id_fk" => 1,
        //     "username" => $user->username,
        //     "prefix" => $user->prefix,
        //     "firstname" => $user->firstname,
        //     "lastname" => $user->lastname,
        //     "email" => collect($user->email)->isEmpty() ? $user->email : $user->username.$user->id."@gmail.com",
        //     "password" =>  $user->password,
        //     "id_card" =>  $user->id_card,
        //     "line_id" =>  $user->line_id,
        //     'line_user_id' => $user->line_id,
        //     "image" =>  "",
        //     "phone" =>  $user->phone,
        //     'age' =>  $user->id,
        //     'weight' => 0,
        //     'height' =>  0,
        //     "gender" =>  $user->gender ,
        //     "address" =>  $user->address,
        //     "zone_id" =>  $user->zone_id,
        //     "subzone_id" =>  $subzone_id,
        //     "tambon_code" =>  $user->tambon_code,
        //     "district_code" =>  $user->district_code,
        //     "province_code" =>  $user->province_code,
        //     "status" =>  $user->status == 1 ? 'active' : 'inactive',
        //     ]);

        //     }catch(Error $e){
        //         return $e;
        //     }

        // }
        // User::where('id' ,'>',0)->delete();
        // TwMeterInfos::where('id' ,'>',0)->delete();
        // TwInvoice::where('id' ,'>',0)->delete();
        // TwAccTransactions::where('id' ,'>',0)->delete();


        // $qaUsers = QaUser::with(['user_meter_info.invoices' => function ($q) {

        //     $q->WhereIn('inv_period_id_fk', [62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72]); // อย่าลืม select user_meter_info_id มาด้วยนะครับ

        // }])->get();

        // $userAcctives = collect($qaUsers)->filter(function ($v) {

        //     //มี member role 3 อันนี้มี user_meter_info แต่ เจ้าหน้าที่ role 2,5 ไม่มี

        //     return !collect($v->user_meter_info)->isEmpty() && !collect($v)->contains('role_id', [2, 5]);
        // });
        // $userChunk = collect($userAcctives)->chunk(100);
        // $i = 1;
        // $accsArray = [];
        // $accTArray = [];
        // $invArray = [];
        // foreach ($userChunk as $users) {


        //     foreach ($users as  $user) {
        //         $userNameEmpty = "user" . date("Hms") . $i++;

        //         $newUser = User::create([
        //             "org_id_fk" => 1,
        //             "username" => collect($user->username)->isEmpty() ? $userNameEmpty : $user->username,
        //             "prefix" => $user->prefix,
        //             "firstname" => $user->firstname,
        //             "lastname" => $user->lastname,
        //             "email" => collect($user->email)->isEmpty() || $user->email == 0 ||  $user->email == 1 ? $userNameEmpty . "@hz.lgov" :  $user->email,
        //             "password" => collect($user->password)->isEmpty() ? Hash::make($userNameEmpty) : $user->password,
        //             "id_card" => $user->id_card,
        //             "line_id" => $user->line_id,
        //             'line_user_id' => $user->line_id,
        //             "image" => "",
        //             "phone" => $user->phone,
        //             'age' => $user->id,
        //             'weight' => 0,
        //             'height' => 0,
        //             "gender" => $user->gender,
        //             "address" => $user->address,
        //             "zone_id" => $user->zone_id,
        //             "subzone_id" => $user->subzone_id,
        //             "tambon_code" => $user->tambon_code,
        //             "district_code" => $user->district_code,
        //             "province_code" => $user->province_code,
        //             "status" => $user->status == 1 ? "active" : "inactive",
        //         ]);

        //         $twmeter = TwMeterInfos::create([
        //             "meter_id" => $newUser->id,
        //             "org_id_fk" => 1,
        //             "meter_address" => $user->user_meter_info[0]->meter_address,
        //             'submeter_name' => "",
        //             "user_id" => $newUser->id,
        //             "meternumber" => "HS" . substr("0000", strlen($newUser->id)) . $newUser->id,
        //             "metertype_id" => 1,
        //             "undertake_zone_id" => $user->user_meter_info[0]->undertake_zone_id,
        //             "undertake_subzone_id" => $user->user_meter_info[0]->undertake_subzone_id,
        //             "acceptance_date" => $user->user_meter_info[0]->acceptance_date,
        //             "status" => $user->status == 1 ? "active" : "inactive",
        //             "payment_id" => $user->user_meter_info[0]->payment_id,
        //             // "recorder_id" => $user->user_meter_info[0]-> ,
        //             'cutmeter' => "0",
        //             'factory_no' => "",
        //             'inv_no_index' => $user->user_meter_info[0]->meter_id,
        //             'last_meter_recording' => 0
        //         ]);

        //         $invp = [62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72];
        //         foreach ($user->user_meter_info[0]->invoices as $inv) {
        //             $key = array_search($inv->inv_period_id_fk, $invp);
        //             if ($inv->acc_trans_id_fk > 0) {
        //                 array_push($accsArray, $inv->acc_trans_id_fk);
        //             }

        //             array_push($invArray, [
        //                 'inv_period_id_fk' => $key + 1,
        //                 'meter_id_fk' => $twmeter->id,
        //                 'lastmeter' => $inv->lastmeter,
        //                 'water_used' => $inv->water_used,
        //                 'reserve_meter' => $inv->reserve_meter,
        //                 'inv_type' => $inv->reserve_meter == 0 ? 'r' : 'u',
        //                 'paid' => $inv->paid,
        //                 'vat' => $inv->vat,
        //                 'totalpaid' => $inv->totalpaid,
        //                 'acc_trans_id_fk' => $inv->acc_trans_id_fk,
        //                 'currentmeter' => $inv->currentmeter,
        //                 'recorder_id' => $inv->recorder_id,
        //                 'status' => $inv->status,
        //                 'created_at' => $inv->created_at,
        //                 'updated_at' => $inv->updated_at,
        //                 'org_id_fk' => 1,

        //             ]);
        //         }

        // $accsArrayUniqs = collect($accsArray)->unique();
        // foreach($accsArrayUniqs as $acc){
        //     $accRes = QaAccTrans::where('id', $acc)->get()->first();
        //    // $invs = QaInvoice::where('acc_trans_id_fk', $acc)->get('reserve_meter');

        //    // $reserveSum = collect($invs)->sum('reserve_meter');
        //     array_push($accTArray,[
        //         "org_id_fk" => 1,
        //         "meter_id_fk" =>  $twmeter->id, 
        //         'vatsum'=> $accRes->vatsum,
        //          'reserve_meter_sum'=> $accRes->id,
        //          'paidsum'=> $accRes->paidsum,
        //          'totalpaidsum'=> $accRes->totalpaidsum,
        //          'cashier' => $accRes->cashier,
        //          'status' => '1',
        //          'created_at' => $accRes->created_at,
        //          'updated_at' => $accRes->updated_at,
        //     ]);
        // }

        //     }
        // }


        // foreach(collect($invArray)->chunk(50) as $invC){
        //     foreach($invC as  $inv){
        //         TwInvoice::insert($inv);
        //     }

        // }
        $accT = TwInvoice::where('acc_trans_id_fk', '>', 0)->get('acc_trans_id_fk');
        $uniq = collect($accT)->unique("acc_trans_id_fk");
        $accTArray = [];
        foreach (collect($uniq)->chunk(50) as $uniqC) {
            foreach ($uniqC as $uu) {
                $u = QaAccTrans::find($uu->acc_trans_id_fk);
                $inv = TwInvoice::where('acc_trans_id_fk', $uu->acc_trans_id_fk)->get(['meter_id_fk', 'reserve_meter']);
                array_push($accTArray, [
                    'org_id_fk' => 1,
                    'meter_id_fk' => $inv[0]->meter_id_fk,
                    'vatsum' => $u->vatsum,
                    'reserve_meter_sum' => $uu->acc_trans_id_fk,
                    'paidsum' => $u->paidsum,
                    'totalpaidsum' => $u->totalpaidsum,
                    'cashier' => $u->cashier,
                    'status' => '1',
                    'created_at' => $u->created_at,
                    'updated_at' => $u->updated_at,
                ]);
            }
        }
        foreach (collect($accTArray)->chunk(50) as $accC) {
            foreach ($accC as  $acc) {
                TwAccTransactions::insert($acc);
            }
        }
        return 'xxx';
        return view('test.index');
        //     $invPs = DB::connection('envsogo_kp1')->table('budget_year as b')
        //    ->where('b.id', 2)
        //    ->join('invoice_period as ip', 'ip.budgetyear_id', '=', 'b.id')
        //    ->select(
        //     'b.id',
        //     'ip.id as ipId'
        //    )
        //    ->get()->pluck('ipId');

        //     foreach($invPs as $invP){
        //         DB::connection('envsogo_kp1')->table('invoice_history')
        //         ->where('inv_period_id_fk','=', $invP)
        //         ->delete();
        //     }
        //     return'ss';
        // return DB::connection('envsogo_kp1')->table('invoice')
        //     ->where('status',  'deleted')
        //     // ->where('deleted', '1')
        //     ->delete();
        // return    $this->addNewUsers();


        $users =  DB::connection('envsogo_kp1')->table('users as u')
            ->join('user_meter_infos as umf', 'umf.user_id', '=', 'u.id')
            ->join('invoice as inv', 'inv.meter_id_fk', '=', 'umf.meter_id')
            // ->join('invoice_period as invp', 'invp.id', '=', 'inv.inv_period_id_fk')
            // ->join('budget_year as by', 'by.id', '=', 'invp.budgetyear_id')

            ->select(
                // 'u.id as userId',
                // 'umf.meter_address',
                'umf.meter_id',
                // 'umf.meter_address',
                // 'umf.meternumber',
                // 'umf.metertype_id',
                // 'umf.undertake_zone_id',
                // 'umf.undertake_subzone_id',
                // 'umf.acceptance_date',
                // 'umf.status as umfStatus',
                // 'umf.payment_id',
                // 'umf.discounttype',
                // 'umf.recorder_id',
                // 'inv.acc_trans_id_fk',
                'inv.*',

                // 'inv.status',
                // 'inv.currentmeter'
            )
            // ->where('meter_id_fk', 300)
            ->get();


        foreach ($users as $inv) {

            $accTransIdOld = 0;
            if ($inv->status == 'paid') {
                $acc = DB::connection('envsogo_kp1')->table('acc_transactions')
                    ->where('id',  $inv->acc_trans_id_fk)->get()->first();
                $twAcc = TwAccTransactions::create([
                    'vatsum' => $inv->vat,
                    'reserve_meter_sum' => $inv->reserve_meter,
                    'paidsum' => $inv->paid,
                    'totalpaidsum' => $inv->totalpaid,
                    'cashier' => $acc->cashier,
                    'created_at' => $acc->created_at,
                    'updated_at' => $acc->updated_at
                ]);
                $accTransIdOld = $inv->acc_trans_id_fk;
                $accId = $twAcc->id;
            } else {
                $twAcc = TwAccTransactions::create([
                    'vatsum' => 0,
                    'reserve_meter_sum' => 0,
                    'paidsum' => 0,
                    'totalpaidsum' => 0,
                    'cashier' => 2859,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $accId = $twAcc->id;
            }




            TwInvoice::create([
                'meter_id_fk' => $inv->meter_id_fk,
                'inv_period_id_fk' => $inv->inv_period_id_fk,
                'lastmeter' => $inv->lastmeter,
                'reserve_meter' => $inv->reserve_meter,
                'inv_type' => $inv->inv_type,
                'currentmeter' => $inv->currentmeter,
                'water_used' => $inv->water_used,
                'paid' => $inv->paid,
                'vat' => $inv->vat,
                'totalpaid' => $inv->totalpaid,
                'status' => $inv->status,
                'recorder_id' => $inv->recorder_id,
                'comment' => $accTransIdOld,
                'acc_trans_id_fk' => $accId,
                'printed_time' => 0,
                'created_at' => $inv->created_at,
                'updated_at' => $inv->updated_at
            ]);
        }

        // return $this->addNewTwMeterInfos($users);


    }

    public function migrateQaTabwaterReferenceTables(): array
    {
        $connection = DB::connection('envsogo_main');
        $orgId = 1;

        if (!$connection->table('pios.organizations')->where('id', $orgId)->exists()) {
            throw new \RuntimeException('Destination organization pios.organizations.id=1 does not exist.');
        }

        $periods = $connection->table('qa_tabwater.invoice_period')
            ->where('id', '>=', 52)
            ->get(['id', 'budgetyear_id', 'status']);
        $periodIds = $periods->pluck('id')->map(fn($id) => (int) $id)->values();
        $budgetYearIds = $periods->pluck('budgetyear_id')->map(fn($id) => (int) $id)->unique()->values();

        if ($periods->contains(fn($period) => !in_array($period->status, ['active', 'inactive'], true))) {
            throw new \RuntimeException('A source invoice period uses a status not supported by pios.invoice_period.');
        }

        if ($budgetYearIds->isNotEmpty()) {
            $budgetRowsCount = $connection->table('qa_tabwater.budget_year')->whereIn('id', $budgetYearIds)->count();
            if ($budgetRowsCount !== $budgetYearIds->count()) {
                throw new \RuntimeException('One or more invoice periods reference a missing source budget_year.');
            }
        }

        $missingZones = $connection->select(
            'SELECT COUNT(*) AS total FROM qa_tabwater.subzones s LEFT JOIN pios.zones z ON z.id = s.zone_id WHERE z.id IS NULL'
        );
        if ((int) ($missingZones[0]->total ?? 0) > 0) {
            throw new \RuntimeException('Some source subzones reference zone IDs missing from pios.zones.');
        }

        $periodIdSql = $periodIds->isNotEmpty() ? $periodIds->implode(',') : '0';
        $budgetIdSql = $budgetYearIds->isNotEmpty() ? $budgetYearIds->implode(',') : '0';

        $conflictChecks = [
            'subzones' => 'SELECT COUNT(*) AS total FROM qa_tabwater.subzones s JOIN pios.subzones d ON d.id=s.id WHERE NOT (s.zone_id <=> d.zone_id) OR NOT (s.subzone_name <=> d.subzone_name) OR NOT (s.status <=> d.status) OR NOT (s.created_at <=> d.created_at) OR NOT (s.updated_at <=> d.updated_at) OR d.org_id_fk IS NULL OR d.org_id_fk<>1',
            'budget_year' => "SELECT COUNT(*) AS total FROM qa_tabwater.budget_year s JOIN pios.budget_year d ON d.id=s.id WHERE s.id IN ({$budgetIdSql}) AND (NOT (s.budgetyear_name <=> d.budgetyear_name) OR NOT (s.startdate <=> d.startdate) OR NOT (s.enddate <=> d.enddate) OR NOT (s.status <=> d.status) OR NOT (s.created_at <=> d.created_at) OR NOT (s.updated_at <=> d.updated_at) OR d.org_id_fk<>1)",
            'invoice_period' => "SELECT COUNT(*) AS total FROM qa_tabwater.invoice_period s JOIN pios.invoice_period d ON d.id=s.id WHERE s.id>=52 AND (NOT (s.inv_p_name <=> d.inv_p_name) OR NOT (s.budgetyear_id <=> d.budgetyear_id) OR NOT (s.startdate <=> d.startdate) OR NOT (s.enddate <=> d.enddate) OR NOT (s.status <=> d.status) OR NOT (s.created_at <=> d.created_at) OR NOT (s.updated_at <=> d.updated_at) OR NOT (d.old_inv_p_id <=> s.id) OR d.org_id_fk<>1)",
        ];

        foreach ($conflictChecks as $table => $sql) {
            $result = $connection->selectOne($sql);
            if ((int) ($result->total ?? 0) > 0) {
                throw new \RuntimeException("Conflicting existing IDs/data found in pios.{$table}; no rows were changed.");
            }
        }

        return $connection->transaction(function () use ($connection, $orgId, $periodIdSql, $budgetIdSql, $periodIds, $budgetYearIds) {
            $budgetInserted = 0;
            if ($budgetYearIds->isNotEmpty()) {
                $budgetInserted = $connection->affectingStatement(
                    "INSERT IGNORE INTO pios.budget_year (id, org_id_fk, budgetyear_name, startdate, enddate, status, created_at, updated_at) SELECT s.id, {$orgId}, s.budgetyear_name, s.startdate, s.enddate, s.status, s.created_at, s.updated_at FROM qa_tabwater.budget_year s WHERE s.id IN ({$budgetIdSql})"
                );
            }

            $subzonesInserted = $connection->affectingStatement(
                "INSERT IGNORE INTO pios.subzones (id, org_id_fk, zone_id, subzone_name, status, created_at, updated_at) SELECT s.id, {$orgId}, s.zone_id, s.subzone_name, s.status, s.created_at, s.updated_at FROM qa_tabwater.subzones s"
            );

            $invoiceInserted = 0;
            if ($periodIds->isNotEmpty()) {
                $invoiceInserted = $connection->affectingStatement(
                    "INSERT IGNORE INTO pios.invoice_period (id, org_id_fk, inv_p_name, budgetyear_id, startdate, enddate, status, created_at, updated_at, old_inv_p_id) SELECT s.id, {$orgId}, s.inv_p_name, s.budgetyear_id, s.startdate, s.enddate, s.status, s.created_at, s.updated_at, s.id FROM qa_tabwater.invoice_period s WHERE s.id>=52"
                );
            }

            $copiedCounts = [
                'subzones' => (int) $connection->selectOne('SELECT COUNT(*) AS total FROM qa_tabwater.subzones s JOIN pios.subzones d ON d.id=s.id WHERE d.org_id_fk=1')->total,
                'budget_year' => $budgetYearIds->isEmpty() ? 0 : (int) $connection->selectOne("SELECT COUNT(*) AS total FROM qa_tabwater.budget_year s JOIN pios.budget_year d ON d.id=s.id WHERE s.id IN ({$budgetIdSql}) AND d.org_id_fk=1")->total,
                'invoice_period' => $periodIds->isEmpty() ? 0 : (int) $connection->selectOne("SELECT COUNT(*) AS total FROM qa_tabwater.invoice_period s JOIN pios.invoice_period d ON d.id=s.id WHERE s.id>=52 AND d.org_id_fk=1 AND d.old_inv_p_id=s.id")->total,
            ];

            if (
                $copiedCounts['subzones'] !== $connection->table('qa_tabwater.subzones')->count()
                || $copiedCounts['budget_year'] !== $budgetYearIds->count()
                || $copiedCounts['invoice_period'] !== $periodIds->count()
            ) {
                throw new \RuntimeException('Post-copy verification failed; transaction rolled back.');
            }

            return [
                'organization_id' => $orgId,
                'preserved_primary_keys' => true,
                'invoice_period_ids' => $periodIds->all(),
                'budget_year_ids' => $budgetYearIds->all(),
                'inserted' => [
                    'subzones' => $subzonesInserted,
                    'budget_year' => $budgetInserted,
                    'invoice_period' => $invoiceInserted,
                ],
                'verified_total' => $copiedCounts,
                'invoice_period_old_inv_p_id' => 'Copied from source invoice_period.id',
            ];
        });
    }

    private function addNewUsers()
    {
        $users =  DB::connection('envsogo_kp1')->table('users as u')
            ->get();

        foreach ($users as $user) {
            if ($user->role_id == 3) {
                $email = "user" . $user->id . "@hz.lgov";
            }
            $newuser = new User();
            $newuser->id = $user->id;
            $newuser->username = $user->username;
            $newuser->password = $user->password;
            $newuser->prefix = $user->prefix;
            $newuser->firstname = $user->firstname;
            $newuser->lastname = $user->lastname;
            $newuser->email = $user->role_id == 3 ? $email : $user->email;
            $newuser->line_id = $user->line_id;
            $newuser->id_card = $user->id_card;
            $newuser->phone = $user->phone;
            $newuser->gender = $user->gender == "" ? 'w' : $user->gender;
            $newuser->address = $user->address;
            $newuser->email_verified_at = $user->email_verified_at;
            $newuser->remember_token = $user->remember_token;
            $newuser->status = $user->status == 1 ? 'active' : 'deleted';
            $newuser->created_at = $user->created_at;
            $newuser->updated_at = $user->updated_at;
            $newuser->org_id_fk = 1;;
            $newuser->zone_id = $user->zone_id;
            $newuser->subzone_id = $user->subzone_id == 13 ? 36 : $user->subzone_id;
            $newuser->tambon_code = $user->tambon_code;
            $newuser->district_code = $user->district_code;
            $newuser->province_code = $user->province_code;

            $newuser->save();
            $newuser->assignRole('User');
            if ($user->role_id == 2 || $user->role_id == 5) {
                $staff = Staff::create([
                    'user_id' => $newuser->id,
                    'org_id_fk' => 1,
                    'status' => 'active',
                    'deleted' => '0',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $staff = User::find($newuser->id);
                if ($user->role_id = 2) {

                    $staff->assignRole('Admin');
                } else {
                    $staff->assignRole('Tabwater Staff');
                }
            }
        }
        return 'a';
    }
    private function addNewTwMeterInfos($users)
    {
        $userG = collect($users)->groupBy('meter_id');
        foreach ($userG as $user) {
            // return $user;
            $last_reading_meter = collect($user)->last()->currentmeter;
            TwMeterInfos::create([
                "meter_id" => $user[0]->meter_id,
                "org_id_fk" => 1,
                "meter_address" => $user[0]->meter_address,
                'submeter_name' => "",
                "user_id" => $user[0]->userId,
                "meternumber" => $user[0]->meternumber,
                "metertype_id" => $user[0]->metertype_id,
                "undertake_zone_id" => $user[0]->undertake_zone_id,
                "undertake_subzone_id" => $user[0]->undertake_subzone_id,
                "acceptance_date" => $user[0]->acceptance_date,
                "status" => $user[0]->umfStatus,
                "payment_id" => $user[0]->payment_id,
                "discounttype" => 0,
                "recorder_id" => $user[0]->recorder_id,
                'cutmeter' => '0',
                'factory_no' => "",
                'inv_no_index' => 1,
                'last_meter_recording' => $last_reading_meter
            ]);
        }
    }
}
