<?php

namespace App\Http\Controllers\Tabwater;

use App\Models\User;
use App\Models\Tabwater\TwInvoice;
use App\Models\Tabwater\InvoicePeriod;
use App\Models\Admin\Zone;
use App\Models\Admin\Subzone;
use App\Exports\P17ReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\Tabwater\TwMeterInfos;

class WaterLedgerController extends Controller
{
    public function p17Report(Request $request)
    {
        $orgId = Auth::user()->org_id_fk;
        $periods = InvoicePeriod::orderBy('id', 'desc')->get();
        $periodId = $request->input('inv_period_id_fk');
        $search = $request->input('search');
        $userId = $request->input('user_id');
        $meterNumber = $request->input('meternumber');
        $zoneId = $request->input('zone_id');
        $subzoneId = $request->input('subzone_id');
        $zones = Zone::where('org_id_fk', $orgId)->orderBy('zone_name')->get(['id', 'zone_name']);
        $subzones = Subzone::where('org_id_fk', $orgId)->orderBy('subzone_name')->get(['id', 'zone_id', 'subzone_name']);

        $invoicesQuery = TwInvoice::with(['tw_meter_infos.user', 'tw_acc_transactions', 'invoice_period'])
            ->whereIn('status', ['invoice', 'owe', 'paid'])
            ->whereHas('tw_meter_infos', function ($meterQuery) use ($orgId, $userId, $meterNumber, $search, $zoneId, $subzoneId) {
                $meterQuery->where('org_id_fk', $orgId);
                $meterQuery->whereHas('user', fn($userQuery) => $userQuery->where('org_id_fk', $orgId));

                if ($zoneId) {
                    $meterQuery->where('undertake_zone_id', $zoneId);
                }

                if ($subzoneId) {
                    $meterQuery->where('undertake_subzone_id', $subzoneId);
                }

                if ($userId) {
                    $meterQuery->where('user_id', $userId);
                }

                if ($meterNumber) {
                    $meterQuery->where(function ($query) use ($meterNumber) {
                        $query->where('meter_id', 'LIKE', "%{$meterNumber}%")
                            ->orWhereRaw(
                                "CONCAT('TW-', LPAD(COALESCE(org_id_fk, 1), 3, '0'), '-', LPAD(meter_id, 4, '0')) LIKE ?",
                                ["%{$meterNumber}%"]
                            );
                    });
                }

                if ($search) {
                    $meterQuery->where(function ($query) use ($search, $orgId) {
                        $query->where('meter_id', 'LIKE', "%{$search}%")
                            ->orWhereRaw(
                                "CONCAT('TW-', LPAD(COALESCE(org_id_fk, 1), 3, '0'), '-', LPAD(meter_id, 4, '0')) LIKE ?",
                                ["%{$search}%"]
                            )
                            ->orWhereHas('user', function ($userQuery) use ($search, $orgId) {
                                $userQuery->where('org_id_fk', $orgId)
                                    ->where(function ($nameQuery) use ($search) {
                                        $nameQuery->whereRaw(
                                            "CONCAT(COALESCE(prefix,''), COALESCE(firstname,''), ' ', COALESCE(lastname,'')) LIKE ?",
                                            ["%{$search}%"]
                                        )
                                            ->orWhere('firstname', 'LIKE', "%{$search}%")
                                            ->orWhere('lastname', 'LIKE', "%{$search}%");
                                    });
                            });
                    });
                }
            });

        // Logic เลือกงวด: Default คือบิลล่าสุดของมิเตอร์นั้นๆ
        if ($periodId) {
            $invoicesQuery->where('inv_period_id_fk', $periodId);
        } else {
            $invoicesQuery->whereIn('id', function ($sub) use ($orgId) {
                $sub->selectRaw('MAX(id)')
                    ->from('tw_invoice')
                    ->where('org_id_fk', $orgId)
                    ->whereIn('status', ['invoice', 'owe', 'paid'])
                    ->groupBy('meter_id_fk');
            });
        }

        if ($request->input('export') === 'excel') {
            return Excel::download(
                new P17ReportExport(clone $invoicesQuery, $orgId),
                'ทะเบียนรายตัวลูกหนี้-ป17.xlsx'
            );
        }

        $invoices = $invoicesQuery->orderBy('meter_id_fk')
            ->orderByDesc('id')
            ->paginate(50)
            ->withQueryString();

        // 3. คำนวณยอดตามค่าที่บันทึกไว้ในใบแจ้งหนี้
        foreach ($invoices as $invoice) {
            // previous_balance is the outstanding balance carried into this invoice.
            $invoice->computed_brought_forward = (float) ($invoice->previous_balance ?? 0);
            $invoice->computed_total_due = $invoice->totalpaid + $invoice->computed_brought_forward;

            // B. ยอดจ่าย: ถ้ามี acc_trans_id_fk ให้ Sum ทั้งกลุ่ม
            if (!empty($invoice->acc_trans_id_fk)) {
                $transaction = $invoice->tw_acc_transactions;
                $paidAmount = (float) ($transaction->totalpaidsum ?? 0);
                if ($paidAmount <= 0) {
                    $paidAmount = (float) TwInvoice::where('org_id_fk', $orgId)
                        ->where('acc_trans_id_fk', $invoice->acc_trans_id_fk)
                        ->sum('totalpaid');
                }
                $invoice->computed_paid_amount = $paidAmount;
                $invoice->computed_pay_date = $transaction && $transaction->updated_at
                    ? \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y')
                    : '-';
                $invoice->computed_transaction_id = $transaction->id ?? '-';
            } else {
                $invoice->computed_paid_amount = ($invoice->status == 'paid') ? $invoice->totalpaid : 0;
                $invoice->computed_pay_date = '-';
                $invoice->computed_transaction_id = '-';
            }

            // C. คงค้างยกไป = (หนี้รวม - จ่าย)
            $invoice->computed_carried_forward = max(0, $invoice->computed_total_due - $invoice->computed_paid_amount);
        }

        return view('reports.p17', compact(
            'invoices', 'periods', 'periodId', 'search', 'userId', 'meterNumber',
            'zones', 'subzones', 'zoneId', 'subzoneId'
        ));
    }
}
