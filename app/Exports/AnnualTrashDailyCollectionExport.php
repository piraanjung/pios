<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnnualTrashDailyCollectionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Collection $payments)
    {
    }

    public function collection(): Collection
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'วันที่รับชำระ',
            'เลขที่รายการ',
            'เลขสมาชิก',
            'ชื่อ-นามสกุล',
            'ที่อยู่',
            'โซน',
            'รหัสถัง',
            'ประเภทผู้ใช้',
            'เดือนที่ชำระ',
            'ปีที่ชำระ',
            'ผู้รับเงิน',
            'จำนวนเงิน (บาท)',
            'หมายเหตุ',
        ];
    }

    public function map($payment): array
    {
        $subscription = $payment->subscription;
        $bin = $subscription?->AnnualTrash;
        $user = $bin?->user;

        return [
            $payment->id,
            $payment->pay_date?->format('d/m/Y'),
            'PAY-' . str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT),
            $user?->annualTrashMember?->formatted_member_number ?? '-',
            trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: '-',
            $user->address ?? '-',
            $user?->user_zone?->zone_name ?? '-',
            $bin->bin_code ?? '-',
            $bin?->kpUserGroup?->usergroup_name ?? 'ทั่วไป',
            $payment->pay_mon,
            $payment->pay_yr,
            trim(($payment->staff->firstname ?? '') . ' ' . ($payment->staff->lastname ?? '')) ?: '-',
            (float) $payment->amount_paid,
            $payment->notes ?? '',
        ];
    }
}