<?php

namespace App\Exports;

use App\Models\Tabwater\TwInvoice;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class P17ReportExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldAutoSize
{
    protected Builder $invoicesQuery;
    protected int $orgId;

    public function __construct(Builder $invoicesQuery, int $orgId)
    {
        $this->invoicesQuery = $invoicesQuery;
        $this->orgId = $orgId;
    }

    public function query()
    {
        return $this->invoicesQuery->orderBy('meter_id_fk')->orderByDesc('id');
    }

    // กำหนดหัวตาราง Excel ให้ตรงกับ ป.17
    public function headings(): array
    {
        return [
            'รหัสมิเตอร์',
            'ชื่อ - สกุล',
            'เลขที่บิล',
            'เลขมาตรก่อน',
            'เลขมาตรหลัง',
            'ยอดบิล',
            'หนี้ยกมา',
            'รวมหนี้',
            'วันที่ชำระ',
            'เลขธุรกรรม',
            'จำนวนเงินที่ชำระ',
            'คงค้างยกไป'
        ];
    }

    public function map($invoice): array
    {
        $meterInfo = $invoice->tw_meter_infos;
        $user = $meterInfo->user ?? null;
        $fullName = $user 
            ? trim(($user->prefix ?? '') . ' ' . $user->firstname . ' ' . $user->lastname)
            : '-';
        $broughtForward = (float) ($invoice->previous_balance ?? 0);
        $grandTotal = (float) $invoice->totalpaid + $broughtForward;
        $transaction = $invoice->tw_acc_transactions;
        $paidAmount = (float) ($transaction->totalpaidsum ?? 0);

        if ($paidAmount <= 0 && $invoice->acc_trans_id_fk) {
            $paidAmount = (float) TwInvoice::where('org_id_fk', $this->orgId)
                ->where('acc_trans_id_fk', $invoice->acc_trans_id_fk)
                ->sum('totalpaid');
        } elseif (!$invoice->acc_trans_id_fk && $invoice->status === 'paid') {
            $paidAmount = (float) $invoice->totalpaid;
        }

        $carriedForward = max(0, $grandTotal - $paidAmount);

        return [
            $meterInfo->meternumber ?? '-',
            $fullName,
            $invoice->id,
            (float) $invoice->lastmeter,
            (float) $invoice->currentmeter,
            $invoice->totalpaid,
            $broughtForward,
            $grandTotal,
            $transaction && $transaction->updated_at
                ? \Carbon\Carbon::parse($transaction->updated_at)->format('d/m/Y')
                : '-',
            $transaction->id ?? '-',
            $paidAmount,
            $carriedForward
        ];
    }

    // อ่านข้อมูลทีละ 1,000 รายการ เพื่อไม่ให้ Memory เต็ม
    public function chunkSize(): int
    {
        return 1000;
    }
}
