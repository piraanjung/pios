<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ใบเสร็จรับเงิน {{ $fiscalYear }}</title>
    <style>
        @font-face {
            font-family: THSarabunNew;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: THSarabunNew;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
            font-weight: 700;
        }
        * { box-sizing: border-box; }
        body { margin: 0; color: #17212b; font-family: THSarabunNew, sans-serif; font-size: 11pt; background: #e8edf0; }
        .print-toolbar { position: sticky; top: 0; z-index: 3; display: flex; justify-content: center; gap: 8px; padding: 10px; background: #263d4c; }
        .print-toolbar button, .print-toolbar a { border: 0; border-radius: 4px; padding: 7px 14px; color: #fff; background: #147d69; font: inherit; text-decoration: none; cursor: pointer; }
        .receipt-sheet { width: 202mm; height: 140mm; margin: 12px auto; display: grid; grid-template-columns: 1fr 1fr; background: #fff; box-shadow: 0 2px 12px rgba(0,0,0,.15); page-break-after: always; break-after: page; overflow: hidden; }
        .receipt-copy { min-width: 0; height: 140mm; padding: 5mm; display: flex; flex-direction: column; border-right: 1px dashed #59646d; }
        .receipt-copy:last-child { border-right: 0; }
        .receipt-header { display: flex; justify-content: space-between; gap: 6px; padding-bottom: 4px; border-bottom: 1px solid #293f4c; }
        .receipt-title { font-size: 16pt; line-height: 1; font-weight: 700; }
        .copy-label { color: #526879; font-size: 9pt; }
        .org-name { margin-top: 2px; font-size: 10pt; font-weight: 700; }
        .org-address { font-size: 8.5pt; line-height: 1.1; }
        .receipt-ident { text-align: right; white-space: nowrap; font-size: 8.5pt; }
        .customer { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; padding: 5px 0; border-bottom: 1px solid #ccd4da; font-size: 9pt; }
        .customer strong { display: block; color: #435565; }
        .payment-table { width: 100%; margin-top: 5px; border-collapse: collapse; font-size: 9pt; }
        .payment-table th, .payment-table td { border: 1px solid #7e8992; padding: 3px 4px; }
        .payment-table th { background: #edf2f4; text-align: left; }
        .payment-table .amount { width: 29%; text-align: right; white-space: nowrap; }
        .totals { margin-top: auto; padding-top: 6px; text-align: right; font-size: 9.5pt; }
        .total-amount { font-size: 14pt; font-weight: 700; }
        .signature { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 9px; text-align: center; font-size: 9pt; }
        .signature-line { margin-top: 15px; padding-top: 3px; border-top: 1px solid #58636c; }
        @page { size: A5 landscape; margin: 4mm; }
        @media print {
            html, body { width: 202mm; margin: 0; padding: 0; background: #fff; }
            .print-toolbar { display: none !important; }
            .receipt-sheet { width: 202mm; height: 140mm; margin: 0; box-shadow: none; page-break-after: always; break-after: page; }
            .receipt-copy { height: 140mm; }
        }
    </style>
</head>
<body>
    <div class="print-toolbar">
        <button type="button" onclick="window.print()">พิมพ์ใบเสร็จ</button>
        <a href="{{ route('keptkayas.annual_payments.index', ['fy' => $fiscalYear]) }}">กลับหน้ารับชำระ</a>
    </div>

    @foreach($receiptSheets as $sheet)
        @php
            $subscription = $sheet['subscription'];
            $bin = $subscription->AnnualTrash;
            $user = $bin?->user;
            $receiptItems = $sheet['payments']->sortBy('pay_mon')->values();
        @endphp
        <main class="receipt-sheet">
            @foreach(['ต้นขั้ว', 'สำเนา'] as $copyLabel)
                <section class="receipt-copy">
                    <header class="receipt-header">
                        <div>
                            <div class="receipt-title">ใบเสร็จรับเงิน</div>
                            <div class="copy-label">({{ $copyLabel }}) · ค่าธรรมเนียมเก็บขยะรายปี</div>
                            <div class="org-name">{{ $orgInfos['org_type_name'] ?? '' }} {{ $orgInfos['org_name'] ?? '' }}</div>
                            <div class="org-address">
                                {{ $orgInfos['org_address'] ?? '' }}
                                @if(!empty($orgInfos['org_zone'])) หมู่ {{ $orgInfos['org_zone'] }} @endif
                                {{ $orgInfos['org_tambon'] ?? '' }} {{ $orgInfos['org_district'] ?? '' }} {{ $orgInfos['org_province'] ?? '' }} {{ $orgInfos['org_zipcode'] ?? '' }}
                            </div>
                        </div>
                        <div class="receipt-ident">
                            <div><strong>เลขที่</strong> {{ $sheet['receipt_code'] }}</div>
                            <div><strong>วันที่</strong> {{ \Carbon\Carbon::parse($paymentDate)->locale('th')->isoFormat('D MMM YYYY') }}</div>
                            <div><strong>ปีงบประมาณ</strong> {{ $fiscalYear }}</div>
                        </div>
                    </header>

                    <div class="customer">
                        <div><strong>ผู้ชำระ</strong>{{ $user->firstname ?? '-' }} {{ $user->lastname ?? '' }}</div>
                        <div><strong>เลขสมาชิก / รหัสถัง</strong>{{ $user->annualTrashMember->formatted_member_number ?? '-' }} / {{ $bin->bin_code ?? '-' }}</div>
                        <div><strong>ที่อยู่</strong>{{ $user->address ?? '-' }}</div>
                        <div><strong>ประเภท</strong>{{ $bin->kpUserGroup->usergroup_name ?? 'ทั่วไป' }}</div>
                    </div>

                    <table class="payment-table">
                        <thead><tr><th>รายการ / รอบเดือน</th><th class="amount">จำนวนเงิน (บาท)</th></tr></thead>
                        <tbody>
                            @foreach($receiptItems as $payment)
                                <tr>
                                    <td>ค่าธรรมเนียมขยะ · {{ \Carbon\Carbon::createFromDate($payment->pay_yr, $payment->pay_mon, 1)->locale('th')->isoFormat('MMM YYYY') }}</td>
                                    <td class="amount">{{ number_format($payment->amount_paid, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>รวมรับเงิน</th>
                                <th class="amount">{{ number_format($sheet['amount'], 2) }}</th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="totals">
                        <div>({{ \App\Http\Controllers\Api\FunctionsController::convertAmountToLetter(number_format($sheet['amount'], 2)) }})</div>
                        <div class="total-amount">รับเงิน {{ number_format($sheet['amount'], 2) }} บาท</div>
                    </div>

                    <footer class="signature">
                        <div><div class="signature-line">ผู้ชำระเงิน</div></div>
                        <div><div class="signature-line">เจ้าหน้าที่ผู้รับเงิน</div>{{ $staff->firstname ?? '' }} {{ $staff->lastname ?? '' }}</div>
                    </footer>
                </section>
            @endforeach
        </main>
    @endforeach

    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
