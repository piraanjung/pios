<?php

namespace App\Http\Controllers\Tabwater;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\FunctionsController;
use App\Models\Admin\Organization;
use App\Models\Admin\Subzone;
use App\Models\Admin\Zone;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Tabwater\TwCutmeter;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\TwMeterInfos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class OwePaperController extends Controller
{
    public function index(Request $request)
    {
        $orgId = Auth::user()->org_id_fk ?? null;

        $zones = Zone::when($orgId, fn($q) => $q->where('org_id_fk', $orgId))->orderBy('id')->get();
        if ($zones->isEmpty()) {
            $zones = Zone::orderBy('id')->get();
        }

        $subzones = Subzone::when($orgId, fn($q) => $q->where('org_id_fk', $orgId))->orderBy('zone_id')->orderBy('id')->get();
        if ($subzones->isEmpty()) {
            $subzones = Subzone::orderBy('zone_id')->orderBy('id')->get();
        }

        $invoice_period = InvoicePeriod::where('status', 'active')
            ->when($orgId, fn($q) => $q->where('org_id_fk', $orgId))
            ->first();

        $zone_id_selected = $request->get('zone_id', 'all');
        $subzone_id_selected = $request->get('subzone_id', 'all');
        $status_selected = $request->get('status', 'all');

        $members = TwMeterInfos::with([
            'user',
            'undertake_zone',
            'undertake_subzone',
            'meter_type.rateConfigs',
            'tw_invoices' => function ($q) {
                $q->whereIn('status', ['owe', 'invoice'])
                    ->with(['invoice_period.budgetyear'])
                    ->orderBy('inv_period_id_fk', 'asc');
            }
        ])
            ->whereHas('tw_invoices', function ($q) use ($status_selected) {
                if ($status_selected === 'owe') {
                    $q->where('status', 'owe');
                } elseif ($status_selected === 'invoice') {
                    $q->where('status', 'invoice');
                } else {
                    $q->whereIn('status', ['owe', 'invoice']);
                }
            })
            ->when($zone_id_selected !== 'all' && $zone_id_selected !== '', function ($q) use ($zone_id_selected) {
                $q->where('undertake_zone_id', $zone_id_selected);
            })
            ->when($subzone_id_selected !== 'all' && $subzone_id_selected !== '', function ($q) use ($subzone_id_selected) {
                $q->where('undertake_subzone_id', $subzone_id_selected);
            })
            ->orderBy('undertake_zone_id')
            ->orderBy('undertake_subzone_id')
            ->orderBy('meter_id')
            ->get();

        $oweInvCountGroupByUserId = $members->count();

        return view('owepaper.index', compact(
            'zones',
            'subzones',
            'zone_id_selected',
            'subzone_id_selected',
            'status_selected',
            'members',
            'oweInvCountGroupByUserId',
            'invoice_period'
        ));
    }

    public function print(Request $request)
    {
        date_default_timezone_set('Asia/Bangkok');

        $from_view = $request->get('from_view', 'owepaper');
        $print_type = $request->get('print_type', 'bill'); // 'bill' หรือ 'official_letter'
        $selectedMeters = $request->get('meter_id', []);

        if (empty($selectedMeters)) {
            return redirect()->back()->with([
                'message' => 'กรุณาเลือกผู้ใช้น้ำอย่างน้อย 1 รายการเพื่อพิมพ์ใบแจ้งหนี้',
                'color' => 'warning',
            ]);
        }

        $orgId = Auth::user()->org_id_fk ?? null;
        $org = Organization::with(['provinces', 'districts', 'tambons', 'zones', 'orgType'])
            ->when($orgId, fn($q) => $q->where('id', $orgId))
            ->first() ?? Organization::with(['provinces', 'districts', 'tambons', 'zones', 'orgType'])->first();

        $oweArray = [];

        foreach ($selectedMeters as $key => $val) {
            $meterId = ($val === 'on' || $val === '1') ? $key : $val;
            if (empty($meterId)) {
                continue;
            }

            // ดึงรายการใบแจ้งหนี้ที่สถานะเป็น owe หรือ invoice ของมิเตอร์นี้ทั้งหมด
            $oweByInvoicePeriod = TwInvoice::where('meter_id_fk', $meterId)
                ->whereIn('status', ['owe', 'invoice'])
                ->with([
                    'invoice_period.budgetyear',
                    'tw_meter_infos.user.user_zone',
                    'tw_meter_infos.undertake_zone',
                    'tw_meter_infos.undertake_subzone',
                    'tw_meter_infos.meter_type.rateConfigs',
                ])
                ->orderBy('inv_period_id_fk', 'asc')
                ->get();

            if ($oweByInvoicePeriod->isEmpty()) {
                continue;
            }

            // อัปเดตข้อมูลการพิมพ์แจ้งเตือนอย่างปลอดภัย (ถ้ามีตาราง/คอลัมน์รองรับ)
            try {
                if (Schema::hasColumn('tw_invoice', 'printed_time')) {
                    foreach ($oweByInvoicePeriod as $invoice) {
                        TwInvoice::where('id', $invoice->id)->update([
                            'printed_time' => ((int) $invoice->printed_time) + 1
                        ]);
                    }
                }

                $meterInfo = $oweByInvoicePeriod->first()->tw_meter_infos;
                $oweCount = $oweByInvoicePeriod->where('status', 'owe')->count();
                if ($oweCount > 0 && Schema::hasTable('tw_cutmeter')) {
                    $checkInitData = TwCutmeter::where('meter_id_fk', $meterId)
                        ->whereIn('status', ['pending', 'cutmeter'])
                        ->first();

                    if (!$checkInitData) {
                        TwCutmeter::create([
                            'meter_id_fk'   => $meterId,
                            'owe_count'     => $oweCount,
                            'warning_print' => 1,
                            'status'        => 'pending',
                        ]);
                    } else {
                        $checkInitData->update([
                            'warning_print' => ((int) $checkInitData->warning_print) + 1,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                // ข้ามกรณีโครงสร้างตาราง cutmeter หรือ printed_time แตกต่างกัน เพื่อไม่ให้การพิมพ์ล่ม
            }

            $oweSum = (float) $oweByInvoicePeriod->sum('totalpaid');
            $latestInvoice = $oweByInvoicePeriod->last();

            $init = "000000000000000000";
            $meter_id_str = substr($init, strlen((string) $meterId)) . $meterId;
            $accTransId = (string) ($latestInvoice->acc_trans_id_fk ?? 0);
            $acc_trans_id_fk_str = substr($init, strlen($accTransId)) . $accTransId;
            $paidVal = str_replace([".", ","], "", number_format($oweSum, 2));
            $qrcodeStr = "|099400035262000\n" . $meter_id_str . "\n" . $acc_trans_id_fk_str . "\n" . $paidVal;

            $oweArray[] = [
                'res'     => $oweByInvoicePeriod->values(),
                'meter'   => $oweByInvoicePeriod->first()->tw_meter_infos,
                'latest'  => $latestInvoice,
                'owe_sum' => $oweSum,
                'qrcode'  => $qrcodeStr,
            ];
        }

        return view('owepaper.print', compact('oweArray', 'from_view', 'print_type', 'org'));
    }
}
