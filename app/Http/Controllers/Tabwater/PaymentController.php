<?php

namespace App\Http\Controllers\Tabwater;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FunctionsController;
use App\Http\Controllers\Tabwater\InvoiceController;
use App\Models\Admin\BudgetYear;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Admin\Subzone;
use App\Models\User;
use App\Models\Tabwater\TwMeterInfos;
use App\Models\Admin\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Organization;
use App\Models\Tabwater\TwAccTransactions;
use App\Models\Tabwater\TwCutmeter;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\TwInvoiceHistory;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // 1. จัดการ Session ของ Subzone (เหมือนเดิม)
        if ($request->session()->has('payment_subzone_selected')) {
            if (collect($request->get('subzone_id_lists'))->isNotEmpty()) {
                $subzone_selected = $request->get('subzone_id_lists');
                $request->session()->forget('payment_subzone_selected');
                $request->session()->put('payment_subzone_selected', collect($subzone_selected)->toJson());
            } else {
                $subzone_selected = json_decode($request->session()->get('payment_subzone_selected'));
            }
        } else {
            $subzone_id_array = collect(Zone::getOrgSubzone('id'))->pluck('id');
            $subzone_selected = $subzone_id_array;
            $request->session()->put('payment_subzone_selected', collect($subzone_selected)->toJson());
        }

        $inv_period_id = $request->get('inv_period_id', 0);

        // 2. Query ข้อมูล (ปรับใหม่ ใช้ tw_invoices และ whereHas)
        $query = TwMeterInfos::query()
            ->whereIn('status', ['active', 'inactive', 'deleted']);

        // กรอง Subzone
        if (collect($subzone_selected)->isNotEmpty()) {
            $query->whereIn('undertake_subzone_id', $subzone_selected);
        }

        // --- จุดเปลี่ยนสำคัญ: ใช้ whereHas เช็คจากตาราง tw_invoice โดยตรง ---
        // (Database จะกรองเฉพาะคนที่มีหนี้มาให้เลย ไม่ต้องทำ Loop Filter เอง)
        $query->whereHas('tw_invoices', function ($q) use ($inv_period_id) {
            $q->whereIn('status', ['owe', 'invoice']); // สถานะที่ค้างชำระ
            if ($inv_period_id != 0) {
                $q->where('inv_period_id_fk', $inv_period_id);
            }
        });

        // Eager Load (ดึงความสัมพันธ์มาเตรียมไว้)
        $invoices = $query->with([
            'user',                // ดึง User เลยป้องกัน N+1
            'undertake_zone',
            'undertake_subzone',
            'tw_invoices' => function ($query) use ($inv_period_id) {
                // เลือกเฉพาะบิลที่ค้างจ่ายมาแสดง
                $query->whereIn('status', ['owe', 'invoice'])
                    ->orderBy('id', 'asc'); // เรียงตามลำดับบิลเก่าไปใหม่

                if ($inv_period_id != 0) {
                    $query->where('inv_period_id_fk', $inv_period_id);
                }
            }
        ])->get();

        // 3. ข้อมูลประกอบอื่นๆ (เหมือนเดิม)
        $subzones = Zone::getOrgSubzone('array');
        $selected_subzone_name_array = [];
        if ($request->has('check-input-select-all')) {
            array_push($selected_subzone_name_array, 'ทุกเส้นทางจดมิเตอร์');
        } else {
            foreach ($subzone_selected as $subzone_id) {
                $subzone = Subzone::find($subzone_id);
                if ($subzone) {
                    $selected_subzone_name_array[] = $subzone->subzone_name;
                }
            }
        }

        // คำนวณยอดรวม (ปรับมาใช้ tw_invoices)
        $total_water_used = $invoices->sum(function ($meter) {
            return $meter->tw_invoices->sum('water_used');
        });

        $current_budgetyear = BudgetYear::where('status', 'active')->with([
            'invoice_period' => function ($q) {
                $q->select('id', 'budgetyear_id', 'inv_p_name')->where('status', '<>', 'deleted');
            }
        ])->get();

        $select_all = count($subzones) == count($subzone_selected);
        $orgInfos = Organization::getOrgName(Auth::user()->org_id_fk);

        return view('payment.index', compact(
            'orgInfos',
            'invoices',
            'subzones',
            'subzone_selected',
            'select_all',
            'selected_subzone_name_array',
            'total_water_used',
            'current_budgetyear'
        ));
    }

    public function index_search_by_suzone(Request $request)
    {
        $subzone_search_lists = $request->get('subzone_id_lists');

        if ($request->session()->has('payment_subzone_selected')) {
            $request->session()->forget('payment_subzone_selected');
        }
        $request->session()->put('payment_subzone_selected', collect($subzone_search_lists)->toJson());

        $usermeterinfos = TwMeterInfos::whereIn('status', ['active', 'inactive'])
            ->with([
                'invoice' => function ($query) {
                    return $query->select('meter_id_fk', 'inv_id', 'inv_period_id_fk', 'status')
                        ->whereIn('status', ['owe', 'invoice']);
                },
                'meter_type' => function ($query) {
                    $query->select('id', 'price_per_unit');
                }
            ])->whereIn('undertake_subzone_id', $subzone_search_lists)
            ->get(['meter_id', 'org_id_fk', 'undertake_subzone_id', 'undertake_zone_id', 'user_id', 'metertype_id', 'owe_count']);

        $invoices = collect($usermeterinfos)->filter(function ($v) {
            return collect($v->invoice)->isNotEmpty();
        });
        $subzones = collect(Subzone::all())->sortBy('zone_id');
        $page = 'index_search_by_suzone';

        return view('payment.index', compact('invoices', 'subzones', 'subzone_search_lists', 'page'));
    }

    public function create(Request $request)
    {
        $paymentsArr = [];
        $total = 0;
        if (!is_array($request->get('data'))) {
            // เปิดหน้านี้ตรง ๆ โดยไม่ได้เลือกรายการจากหน้ารับชำระ
            return redirect()->route('payment.index');
        }
        foreach ($request->get('data') as $inv) {
            $v = TwInvoice::where('id', $inv['inv_id'])
                ->with('usermeterinfos', 'usermeterinfos.user_profile', 'invoice_period')
                ->get();
            array_push($paymentsArr, $v);

            $total += ($v[0]->currentmeter - $v[0]->lastmeter) * 8;
        }
        $payments = collect($paymentsArr)->flatten();
        // view payment.invoice_sum ถูกถอดไปแล้ว กลับไปหน้ารับชำระ
        return redirect()->route('payment.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'payments' => 'required|array|min:1',
            'meter_id' => 'required', // ตรวจสอบ meter_id ด้วย
        ]);

        // 1. ดึง ID ของ Invoice ที่เลือกมาทั้งหมด
        $selected_payments = collect($request->get('payments'))->filter(function ($v) {
            return isset($v['on']);
        });

        if ($selected_payments->isEmpty()) {
            return back()->with('error', 'กรุณาเลือกรายการชำระเงิน');
        }

        $invoice_ids = $selected_payments->pluck('iv_id')->toArray();
        $meter_id = $request->meter_id;

        // 2. ดึงข้อมูล Invoice ทั้งหมดในครั้งเดียว (ลด Query)
        $invoices = TwInvoice::whereIn('id', $invoice_ids)
            ->where('status', '!=', 'paid') // กันพลาดจ่ายซ้ำ
            ->get();

        if ($invoices->isEmpty()) {
            return back()->with('error', 'ไม่พบรายการที่เลือก หรือถูกชำระไปแล้ว');
        }

        // 3. คำนวณยอดรวมสำหรับ Transaction เดียว
        $sum_vat = $invoices->sum('vat');
        $sum_reserve = $invoices->sum('reserve_meter');
        $sum_paid = $invoices->sum('paid'); // ค่าน้ำ
        $sum_total = $invoices->sum('totalpaid'); // ยอดสุทธิ

        // 4. สร้าง Transaction (ใบเสร็จรับเงิน Header) เพียง 1 รายการ
        $accTrans = new TwAccTransactions();
        $accTrans->meter_id_fk       = $meter_id;
        $accTrans->vatsum            = $sum_vat;
        $accTrans->reserve_meter_sum = $sum_reserve;
        $accTrans->paidsum           = $sum_paid;
        $accTrans->totalpaidsum      = $sum_total;
        $accTrans->status            = '1';
        $accTrans->cashier           = Auth::id();
        $accTrans->created_at        = now(); // ใช้ now() ของ Laravel
        $accTrans->updated_at        = now();
        $accTrans->save();

        // 5. เตรียมข้อมูล User และ Running Number ใบเสร็จ
        $twUserInfo = TwMeterInfos::find($meter_id);
        $receipt_running_no = (new FunctionsController())->nextOrgInvoiceSequence(Auth::user()->org_id_fk); // เลขที่ใบเสร็จรับเงินตาม org
        $nextLastmeter = $twUserInfo->last_meter_recording;

        // 6. Loop Update Invoice (Link ไปหา Transaction)

        foreach ($invoices as $inv) {
            $inv->status          = 'paid';
            $inv->acc_trans_id_fk = $accTrans->id; // **Key สำคัญ: ผูกบิลกับ Transaction**
            $inv->updated_at      = now();
            $inv->save();

            // เช็คเลขมิเตอร์ล่าสุด
            if ($nextLastmeter <= $inv->currentmeter) {
                $nextLastmeter = $inv->currentmeter;
            }
        }

        // 7. จัดการ Cutmeter และสถานะ User (Logic เดิมของคุณ แต่ยุบรวม Query)
        $remaining_owe_count = TwInvoice::where('meter_id_fk', $meter_id)
            ->whereIn('status', ['owe', 'invoice'])
            ->count();

        $cutmeter = TwCutmeter::where('meter_id_fk', $meter_id)
            ->whereIn('status', ['pending', 'cutmeter'])
            ->latest() // เอาตัวล่าสุด
            ->first();

        if ($cutmeter) {
            // กรณีปลดหนี้หมด และโดนตัดมิเตอร์อยู่ -> เปลี่ยนสถานะเป็นรอติดตั้ง/ผ่าน
            if ($remaining_owe_count == 0 && $twUserInfo->cutmeter == 1) {

                if ($cutmeter->status == 'cutmeter') {
                    // Logic เดิม: cutmeter -> passed
                    $cutmeter->status = 'passed';
                    $cutmeter->save(); // Save สถานะ passed ก่อน (ตาม code เดิม)
                }

                // Update เป็น install/complete
                $cutmeter->update([
                    'status'        => ($cutmeter->status == "pending") ? "complete" : "install",
                    'owe_count'     => $remaining_owe_count,
                    "warning_print" => 0,
                    'updated_at'    => now(),
                ]);

                // Update User Info ตามสถานะ Cutmeter
                $is_active = ($cutmeter->status == 'init' || $cutmeter->status == 'complete');
                $twUserInfo->cutmeter = $is_active ? 0 : 1;
                $twUserInfo->status   = $is_active ? 'active' : 'inactive';
            } else {
                // ยังเหลือหนี้ หรือไม่ได้โดนตัด -> อัปเดตแค่ยอดค้าง
                $cutmeter->update(['owe_count' => $remaining_owe_count]);
            }
        }

        // 8. Update User Info สุดท้าย
        $twUserInfo->owe_count            = $remaining_owe_count;
        // Update สถานะตัดมิเตอร์ (ถ้าไม่ได้เข้าเงื่อนไขข้างบน ก็เช็คตามจำนวนบิล)
        // หมายเหตุ: ตรงนี้ต้องระวัง Logic ตีกันกับข้างบน ถ้าข้างบน set active แล้ว ตรงนี้อาจจะทับ
        // แต่ตาม Code เดิมคุณทำแบบนี้ ผมคงไว้ก่อน
        if (!$cutmeter) {
            $twUserInfo->cutmeter = $remaining_owe_count < 2 ? '0' : '1';
        }

        $twUserInfo->last_meter_recording = $nextLastmeter;
        $twUserInfo->inv_no_index         = $receipt_running_no; // เก็บค่ารันของ org ล่าสุดไว้เพื่อ fallback
        $twUserInfo->updated_at           = now();
        $twUserInfo->save();

        // 9. ส่งไปพิมพ์ใบเสร็จ (ส่ง Transaction ID ไปเลย แม่นยำกว่า)
        return $this->receipt_print_by_trans($accTrans->id);
    }
    /**
     * Summary of receipt_print_by_trans
     * @param mixed $acc_trans_id
     * @return \Illuminate\Contracts\View\View
     */
    private function receipt_print_by_trans($acc_trans_id)
    {
        $invoicesPaidForPrint = TwInvoice::where('acc_trans_id_fk', $acc_trans_id)
            ->with([
                'invoice_period:id,inv_p_name',
                'tw_meter_infos' => function ($query) {
                    $query->select('meter_id', 'org_id_fk', 'user_id', 'undertake_zone_id', 'undertake_subzone_id', 'meter_address', 'submeter_name')
                          ->with([
                              'user:id,prefix,firstname,lastname,phone,address,tambon_code,district_code,province_code',
                              'user.user_tambon',
                              'user.user_district',
                              'user.user_province',
                              'undertake_zone:id,zone_name',
                              'undertake_subzone:id,subzone_name'
                          ]);
                },
                'tw_acc_transactions' => function ($query) {
                    $query->select('id', 'paidsum', 'vatsum', 'totalpaidsum', 'cashier', 'updated_at')
                        ->with('cashier_info:id,prefix,firstname,lastname');
                }
            ])
            ->get();

        $type = 'paid_receipt';
        $from_blade = 'payment.index';
        return view('payment.receipt_print', compact('invoicesPaidForPrint', 'type', 'from_blade'));
    }
    public function store_by_inv_no(Request $request)
    {
        $arr = [];

        foreach ($request->get('datas', []) as $dataArray) {
            $parts = explode("|", $dataArray);
            $req_meter_id = $parts[0] ?? null;
            if (!$req_meter_id) {
                continue;
            }

            $inv_infos = TwInvoice::where('meter_id_fk', $req_meter_id)
                ->whereIn('status', ['invoice', 'owe'])
                ->get();

            if ($inv_infos->isEmpty()) {
                continue;
            }

            $paid_sum = $inv_infos->sum('paid');
            $vat_sum = $inv_infos->sum('vat');
            $reserve_meter_sum = $inv_infos->sum('reserve_meter');
            $totalpaid_sum = $inv_infos->sum('totalpaid');

            $acc_trans = new TwAccTransactions();
            $acc_trans->meter_id_fk       = $req_meter_id;
            $acc_trans->paidsum           = $paid_sum;
            $acc_trans->vatsum            = $vat_sum;
            $acc_trans->reserve_meter_sum = $reserve_meter_sum;
            $acc_trans->totalpaidsum      = $totalpaid_sum;
            $acc_trans->status            = '1';
            $acc_trans->cashier           = Auth::id();
            $acc_trans->created_at        = now();
            $acc_trans->updated_at        = now();
            $acc_trans->save();

            foreach ($inv_infos as $inv) {
                $inv->status          = 'paid';
                $inv->recorder_id     = Auth::id();
                $inv->acc_trans_id_fk = $acc_trans->id;
                $inv->updated_at      = now();
                $inv->save();
            }

            $usermeter_info = TwMeterInfos::find($req_meter_id);
            if ($usermeter_info) {
                $usermeter_info->owe_count    = max(0, intval($usermeter_info->owe_count) - $inv_infos->count());
                $usermeter_info->inv_no_index = (new FunctionsController())->nextOrgInvoiceSequence(Auth::user()->org_id_fk);
                $usermeter_info->updated_at   = now();
                $usermeter_info->save();
            }

         $invoicesPaidForPrint = TwInvoice::where('acc_trans_id_fk', $acc_trans->id)
                ->with([
                    'invoice_period:id,inv_p_name',
                    'tw_meter_infos' => function ($query) {
                        $query->select('meter_id', 'org_id_fk', 'user_id', 'undertake_zone_id', 'undertake_subzone_id', 'meter_address', 'submeter_name')
                              ->with([
                                  'user:id,prefix,firstname,lastname,phone,address,tambon_code,district_code,province_code',
                                  'user.user_tambon',
                                  'user.user_district',
                                  'user.user_province',
                                  'undertake_zone:id,zone_name',
                                  'undertake_subzone:id,subzone_name',
                              ]);
                    },
                    'tw_acc_transactions' => function ($query) {
                        $query->select('id', 'paidsum', 'vatsum', 'totalpaidsum', 'cashier', 'updated_at')
                              ->with('cashier_info:id,prefix,firstname,lastname');
                    }
                ])
                ->get();

            if ($invoicesPaidForPrint->isNotEmpty()) {
                $arr[] = $invoicesPaidForPrint;
            }
        }

        $type = 'paid_receipt';
        $from_blade = 'payment.index';
        return view('payment.receipt_print_multi', compact('arr', 'type', 'from_blade'));
    }


    public function receipt_print(Request $request, $account_id_fk = null)
    {
        if ($account_id_fk) {
            return $this->receipt_print_by_trans($account_id_fk);
        }
        if ($request->session()->has('account_id_fk')) {
            return $this->receipt_print_by_trans($request->session()->get('account_id_fk'));
        }

        return redirect()->route('payment.index');
    }
   public function receipt_print_history($id)
{
    $receipt_id = $id;

    // ตาราง tw_invoice ไม่มีคอลัมน์ inv_no
    $invoiceTable = TwInvoice::where('acc_trans_id_fk', $receipt_id)
        ->with([
            'invoice_period:id,inv_p_name',
            'tw_meter_infos' => function ($query) {
                $query->select('meter_id', 'org_id_fk', 'user_id', 'meter_address', 'undertake_zone_id', 'undertake_subzone_id', 'submeter_name')
                      ->with([
                          // ดึงข้อมูล User พร้อมความสัมพันธ์ ตำบล อำเภอ จังหวัด ที่อยู่ใน Model User
                          'user:id,prefix,firstname,lastname,phone,tambon_code,district_code,province_code',
                          'user.user_tambon:id,tambon_name', // ชื่อฟังก์ชันความสัมพันธ์ใน Model User
                          'user.user_district:id,district_name',
                          'user.user_province:id,province_name',
                          'undertake_subzone:id,subzone_name',
                          'undertake_zone:id,zone_name',
                      ]);
            },
            'tw_acc_transactions' => function ($query) {
                return $query->select('id', 'paidsum', 'vatsum', 'totalpaidsum', 'cashier', 'updated_at')
                    ->where('status', 1)
                    ->with('cashier_info:id,prefix,firstname,lastname');
            }
        ])
        ->get(['id', 'inv_period_id_fk', 'meter_id_fk', 'lastmeter', 'currentmeter', 'status', 'acc_trans_id_fk', 'recorder_id', 'updated_at', 'created_at', 'vat', 'reserve_meter', 'paid', 'totalpaid']);

    $invoiceHistoryTable = TwInvoiceHistory::where('acc_trans_id_fk', $receipt_id)
        ->with([
            'invoice_period:id,inv_p_name',
            'tw_meter_infos' => function ($query) {
                $query->select('meter_id', 'org_id_fk', 'user_id', 'meter_address', 'undertake_zone_id', 'undertake_subzone_id', 'submeter_name')
                      ->with([
                          'user:id,prefix,firstname,lastname,phone,tambon_code,district_code,province_code',
                          'user.user_tambon:id,tambon_name',
                          'user.user_district:id,district_name',
                          'user.user_province:id,province_name',
                          'undertake_subzone:id,subzone_name',
                          'undertake_zone:id,zone_name',
                      ]);
            },
            'tw_acc_transactions' => function ($query) {
                return $query->select('id', 'paidsum', 'vatsum', 'totalpaidsum', 'cashier', 'updated_at')
                    ->where('status', 1)
                    ->with('cashier_info:id,prefix,firstname,lastname');
            }
        ])
        ->get([
            'id',
            'inv_period_id_fk',
            'meter_id_fk',
            'lastmeter',
            'currentmeter',
            'status',
            'acc_trans_id_fk',
            'recorder_id',
            'updated_at',
            'created_at',
            'vat',
            'reserve_meter',
            'paid',
            'totalpaid',
        ]);

    $invoicesPaidForPrint = collect($invoiceTable)->merge($invoiceHistoryTable);

    $newId = (new FunctionsController())->createInvoiceNumberString($receipt_id);
    $type = 'payment_search';
    $from_blade = 'payment.history';

    return view('payment.receipt_print', compact('invoicesPaidForPrint', 'newId', 'type', 'from_blade'));
}
    public function search(Request $request)
    {
        // 1. ดึงชื่อองค์กร
        $orgInfos = Organization::getOrgName(Auth::user()->org_id_fk);

        // 2. ส่วนของ Invoice: เปิดเฉพาะกรณีเลือกมิเตอร์ที่ถูกต้อง
        $inv_by_budgetyear = [];
        $selected_user_info = trim((string) $request->input('user_info', ''));
        if ($selected_user_info === 'all') {
            $selected_user_info = '';
        }

        if ($selected_user_info !== '') {
            $meter_id = (int) $selected_user_info;

            if ($meter_id > 0 && TwMeterInfos::where('meter_id', $meter_id)->exists()) {
                $invoiceCtrl = new InvoiceController();
                $invoice_infos = json_decode($invoiceCtrl->get_invoice_and_invoice_history(
                    $meter_id,
                    'paid'
                )->content(), true);

                $invoice_infos = is_array($invoice_infos) ? $invoice_infos : [];
                $inv_by_budgetyear = collect($invoice_infos)
                    ->filter(fn ($invoice_info) => isset($invoice_info['invoice_period']['budgetyear_id']))
                    ->groupBy(fn ($invoice_info) => $invoice_info['invoice_period']['budgetyear_id'])
                    ->values();
            }
        }

        // 3. Query User: รองรับการค้นหาตามชื่อ, ที่อยู่, เลขมิเตอร์ (TW-xxx-xxxx)
        $usersQuery = User::with([
            'user_zone:id,zone_name',
            'usermeterinfos' => function ($query) {
                $query->select('meter_id', 'user_id', 'org_id_fk');
            }
        ])
            ->role('User')
            ->whereHas('usermeterinfos');

        if ($selected_user_info !== '') {
            $searchText = strtolower($selected_user_info);

            $usersQuery->where(function ($query) use ($searchText) {
                $query->whereRaw('LOWER(CONCAT(prefix, firstname, lastname)) LIKE ?', ['%' . $searchText . '%'])
                    ->orWhereRaw('LOWER(address) LIKE ?', ['%' . $searchText . '%'])
                    ->orWhereHas('usermeterinfos', function ($meterQuery) use ($searchText) {
                        $meterQuery->where(function ($subQuery) use ($searchText) {
                            $subQuery->where('meter_id', (int) $searchText)
                                ->orWhereRaw("LOWER(CONCAT('TW-', LPAD(org_id_fk, 3, '0'), '-', LPAD(meter_id, 4, '0'))) LIKE ?", ['%' . $searchText . '%']);
                        });
                    });
            });
        }

      $users = $usersQuery->get(['prefix', 'firstname', 'lastname', 'address', 'id', 'zone_id']);

        // 4. Query Zone
        $zones = Zone::where('status', 'active')->get();

        // 5. Query Invoice Period
        $invoice_period = InvoicePeriod::where('status', 'active')->orderByDesc('id')->first();

        return view('payment.search', compact('zones', 'invoice_period', 'users', 'inv_by_budgetyear', 'orgInfos'));
    }
    public function paymenthistory($inv_period = '', $subzone_id = '')
    {
        $invoices_paid = TwInvoice::where('inv_period_id_fk', $inv_period)
            ->where('status', 'paid')
            ->whereHas('tw_meter_infos', function ($q) use ($subzone_id) {
                $q->where('undertake_subzone_id', $subzone_id);
            })
            ->with(['tw_meter_infos' => function ($query) {
                $query->select('meter_id', 'org_id_fk', 'undertake_subzone_id', 'meter_address', 'metertype_id', 'user_id');
            }])
            ->get();

        $invoices_paid = $invoices_paid->sortBy(function ($invoice) {
            return $invoice->tw_meter_infos->user_id ?? 0;
        });

        return view('payment.paymenthistory', compact('invoices_paid'));
    }

    public function destroy($acc_trans_id_fk)
    {
        TwAccTransactions::where('id', $acc_trans_id_fk)->update([
            'status'        => 2,
            'paidsum'       => 0,
            'vatsum'        => 0,
            'totalpaidsum'  => 0,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        TwInvoice::where('acc_trans_id_fk', $acc_trans_id_fk)->update([
            'status'            => 'owe',
            'recorder_id'       => Auth::id(),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        $invCount = TwInvoice::where('acc_trans_id_fk', $acc_trans_id_fk)->count();
        $invGet   = TwInvoice::where('acc_trans_id_fk', $acc_trans_id_fk)->get(['meter_id_fk'])->first();
        if ($invGet) {
            $uMeterInfo = TwMeterInfos::find($invGet->meter_id_fk);
            if ($uMeterInfo) {
                $uMeterInfo->owe_count  = max(0, intval($uMeterInfo->owe_count) + $invCount);
                $uMeterInfo->updated_at = date('Y-m-d H:i:s');
                $uMeterInfo->save();
            }
        }
        return redirect()->route('payment.search')->with(['color' => 'success', 'message' => 'ทำการยกเลิกใบเสร็จเลขที่ ' . $acc_trans_id_fk . ' เรียบร้อยแล้ว']);
    }
}
