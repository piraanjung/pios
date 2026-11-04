@extends('layouts.print')

@php
    use App\Http\Controllers\Api\FunctionsController;
    $fnc = new FunctionsController();

    $orgTypeName = $org?->orgType?->name ?? '';
    $orgName = trim($orgTypeName . ($org->org_name ?? 'องค์การบริหารส่วนตำบลขามป้อม'));
    $orgDeptName = $org->org_dept_name ?? 'กิจการประปา';
    $orgAddress = $org->org_address ?? 'หมู่ 1';
    $orgTambon = $org?->tambons?->tambon_name ?? 'ขามป้อม';
    $orgDistrict = $org?->districts?->district_name ?? 'พระยืน';
    $orgProvince = $org?->provinces?->province_name ?? 'ขอนแก่น';
    $orgZipcode = $org->org_zipcode ?? '40320';
    $orgPhone = $org->org_dept_phone ?: ($org->org_phone ?? '043-002389');
    $orgHeadName = $org->org_head_name ?: 'ร้อยตำรวจตรีชวรินทร์ อบสุกลิ่น';
    $orgLogo = !empty($org->org_logo_img) ? asset('logo/' . $org->org_logo_img) : asset('/logo/khampom.png');

    $currentYearThai = (int) date('Y') + 543;
    $currentMonthThai = $fnc->fullThaiMonth(date('m'));
    $todayThaiStr = date('j') . ' ' . $currentMonthThai . ' ' . $currentYearThai;

    $dueTimestamp = strtotime('+15 days');
    $dueYearThai = (int) date('Y', $dueTimestamp) + 543;
    $dueMonthThai = $fnc->fullThaiMonth(date('m', $dueTimestamp));
    $defaultDueDateStr = date('j', $dueTimestamp) . ' ' . $dueMonthThai . ' ' . $dueYearThai;
@endphp

@section('style')
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: "Sarabun", sans-serif;
            color: #111;
            background: #e2e8f0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Toolbar บนหน้าจอ (ซ่อนเวลาสั่ง Print) */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #1e293b;
            color: #fff;
            padding: 12px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
        }

        .print-page-wrapper {
            max-width: 210mm;
            margin: 15px auto;
        }

        /* =========================================================
           รูปแบบที่ 1: ใบแจ้งหนี้ค่าน้ำประปา (2 ใบต่อหน้า A4 + ต้นขั้วฉีก)
           ========================================================= */
        .bill-card {
            background: #fff;
            /* border: 1px solid #333; */
            margin-bottom: 12mm;
            padding: 0;
            page-break-inside: avoid;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .bill-main {
            padding: 10px 14px;
        }

        .bill-stub {
            padding: 10px 12px;
            border-left: 1.5px dashed #444;
            background-color: #fafafa;
            position: relative;
        }

        .bill-stub::before {
            content: "✂ ส่วนสำหรับเจ้าหน้าที่เก็บเงิน";
            font-size: 9pt;
            color: #555;
            display: block;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .bill-header-title {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.25;
        }

        .bill-sub-text {
            font-size: 11pt;
            line-height: 1.35;
        }

        .bill-sign { font-size: 11.5pt; page-break-inside: avoid; }
        .bill-sign .sign-line { margin-top: 22px; }
        .bill-sign .sign-name { margin-top: 2px; }
        .bill-sign .sign-role { font-weight: bold; }

        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 6px;
        }

        .bill-table th,
        .bill-table td {
            border: 1px solid #333;
            padding: 3px 5px;
            font-size: 10.5pt;
        }

        .bill-table thead th {
            background-color: #f1f5f9 !important;
            text-align: center;
            font-weight: bold;
        }

        .bill-summary-box {
            border: 1.5px solid #111;
            background-color: #f8fafc !important;
            padding: 5px 8px;
            margin-top: 4px;
        }

        /* =========================================================
           รูปแบบที่ 2: หนังสือแจ้งเตือนชำระหนี้ราชการ (ตราครุฑ 1 หน้า A4)
           ========================================================= */
        .official-page {
            background: #fff;
            padding: 15mm 18mm;
            margin-bottom: 15px;
            min-height: 290mm;
            box-sizing: border-box;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            font-size: 11pt;
            line-height: 1.65;
            page-break-after: always;
        }

        .official-indent {
            text-indent: 2.5rem;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print,
            .btnprint {
                display: none !important;
            }
            .print-page-wrapper {
                max-width: 100% !important;
                margin: 0 !important;
            }
            .bill-card {
                box-shadow: none !important;
                margin-bottom: 8mm !important;
            }
            .official-page {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 12mm 15mm !important;
            }
            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
@endsection

@section('content')
    <!-- แถบควบคุมการพิมพ์ (แสดงเฉพาะบนหน้าจอ ไม่ติดเวลาสั่งพิมพ์) -->
    <div class="print-toolbar no-print">
        <div class="container-fluid d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <i class="fas fa-print fa-lg text-info mr-2"></i>
                <div>
                    <div class="font-weight-bold">ระบบพิมพ์ใบแจ้งหนี้และหนังสือแจ้งเตือนค้างชำระ ({{ count($oweArray) }} รายการ)</div>
                    <small class="text-light opacity-75">สามารถสลับรูปแบบเอกสารและระบุวันครบกำหนดชำระก่อนสั่งพิมพ์ได้ทันที</small>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center">
                <div class="mr-3 mb-2 mb-md-0 d-flex align-items-center">
                    <label class="small mb-0 mr-2 text-nowrap">รูปแบบเอกสาร:</label>
                    <select id="layoutSwitcher" class="form-control form-control-sm" style="width: 270px;">
                        <option value="bill" {{ ($print_type ?? 'bill') === 'bill' ? 'selected' : '' }}>
                            📄 ใบแจ้งหนี้ค่าน้ำประปา (1 ใบ/A4 + ต้นขั้วฉีก)
                        </option>
                        <option value="official_letter" {{ ($print_type ?? 'bill') === 'official_letter' ? 'selected' : '' }}>
                            🏛️ หนังสือแจ้งเตือนชำระหนี้ (ตราครุฑ 1 หน้า/A4)
                        </option>
                    </select>
                </div>

                <div class="mr-3 mb-2 mb-md-0 d-flex align-items-center">
                    <label class="small mb-0 mr-2 text-nowrap">กำหนดชำระภายใน:</label>
                    <input type="text" id="dueDateInput" class="form-control form-control-sm"
                        style="width: 160px;" value="{{ $defaultDueDateStr }}">
                </div>

                <button type="button" class="btn btn-success btn-sm font-weight-bold mr-2 px-3" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> สั่งพิมพ์ (Print)
                </button>
                <a href="{{ route('admin.owepaper.index') }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> กลับหน้าเลือก
                </a>
            </div>
        </div>
    </div>

    <div class="print-page-wrapper">
        {{-- =======================================================================
             LAYOUT 1: ใบแจ้งหนี้ค่าน้ำประปา / ใบแจ้งยอดค้างชำระ (2 ใบต่อหน้า A4)
             ======================================================================= --}}
        <div id="layout_bill" class="{{ ($print_type ?? 'bill') === 'bill' ? '' : 'd-none' }}">
            @foreach ($oweArray as $idx => $item)
                @php
                    $meter = $item['meter'];
                    $invoices = $item['res'];
                    $latest = $item['latest'];
                    $user = $meter?->user;

                    $fullName = trim(($user->prefix ?? '') . ($user->firstname ?? '-') . ' ' . ($user->lastname ?? ''));
                    $memberCode = 'U' . str_pad((string) ($meter->user_id ?? 0), 4, '0', STR_PAD_LEFT);
                    $address = $meter->meter_address ?: ($user->address ?? '-');
                    $zoneName = $meter?->undertake_zone?->zone_name ?? ($user?->user_zone?->zone_name ?? '-');
                    $subzoneName = $meter?->undertake_subzone?->subzone_name ?? '-';

                    $currentBills = $invoices->where('status', 'invoice');
                    $oweBills = $invoices->where('status', 'owe');

                    $sumCurrent = (float) $currentBills->sum('totalpaid');
                    $sumOwe = (float) $oweBills->sum('totalpaid');
                    $grandTotal = (float) $item['owe_sum'];
                @endphp

                <div class="bill-card">
                    <div class="row no-gutters">
                        <!-- ฝั่งซ้าย: ใบแจ้งหนี้สำหรับผู้ใช้น้ำ -->
                        <div class="col-12 bill-main p-5">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $orgLogo }}" alt="Logo" style="width: 48px; height: 48px; object-fit: contain;" class="mr-2"
                                        onerror="this.style.display='none'">
                                    <div>
                                        <div class="bill-header-title">{{ $orgDeptName }} {{ $orgName }}</div>
                                        <div class="bill-sub-text text-muted">
                                            {{ $orgAddress }} ต.{{ $orgTambon }} อ.{{ $orgDistrict }} จ.{{ $orgProvince }} {{ $orgZipcode }} | โทร. {{ $orgPhone }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-dark px-2 py-1" style="font-size: 11pt;">ใบแจ้งหนี้ค่าน้ำประปา</span>
                                    <div class="bill-sub-text mt-1">วันที่พิมพ์: {{ $todayThaiStr }}</div>
                                </div>
                            </div>

                            <!-- ข้อมูลผู้ใช้น้ำและมิเตอร์ -->
                            <div class="row bill-sub-text mb-1">
                                <div class="col-7">
                                    <div><strong>ชื่อผู้ใช้น้ำ:</strong> {{ $fullName }} <span class="text-muted">({{ $memberCode }})</span></div>
                                    <div><strong>ที่อยู่:</strong> บ้านเลขที่ {{ $address }} {{ $zoneName }} ต.{{ $orgTambon }}</div>
                                </div>
                                <div class="col-5 text-right">
                                    <div><strong>เลขมิเตอร์:</strong> <span class="text-primary font-weight-bold">{{ $meter->meternumber ?? '-' }}</span></div>
                                    <div><strong>เส้นทาง:</strong> {{ $subzoneName }} @if($meter->factory_no) | <strong>เลขโรงงาน:</strong> {{ $meter->factory_no }} @endif</div>
                                </div>
                            </div>

                            <!-- ตารางแจกแจงรายการใบแจ้งหนี้และค้างชำระ -->
                            <table class="bill-table">
                                <thead>
                                    <tr>
                                        <th>เลขบิล</th>
                                        <th>รอบบิล</th>
                                        <th>สถานะ</th>
                                        <th>มิเตอร์ก่อน</th>
                                        <th>มิเตอร์หลัง</th>
                                        <th>ใช้น้ำ (หน่วย)</th>
                                        <th>ค่าน้ำ</th>
                                        <th>ค่ารักษามาตร</th>
                                        <th>VAT</th>
                                        <th>รวม (บาท)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $inv)
                                        <tr>
                                            <td class="text-center">#{{ $inv->id }}</td>
                                            <td class="text-center font-weight-bold">{{ $inv->invoice_period->inv_p_name ?? '-' }}</td>
                                            <td class="text-center">
                                                @if ($inv->status === 'owe')
                                                    <span class="text-danger font-weight-bold">ค้างชำระ</span>
                                                @else
                                                    <span class="text-primary font-weight-bold">งวดปัจจุบัน</span>
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format((float) $inv->lastmeter) }}</td>
                                            <td class="text-right">{{ number_format((float) $inv->currentmeter) }}</td>
                                            <td class="text-right font-weight-bold">{{ number_format((float) $inv->water_used) }}</td>
                                            <td class="text-right">{{ number_format((float) $inv->paid, 2) }}</td>
                                            <td class="text-right">{{ number_format((float) $inv->reserve_meter, 2) }}</td>
                                            <td class="text-right">{{ number_format((float) $inv->vat, 2) }}</td>
                                            <td class="text-right font-weight-bold">{{ number_format((float) $inv->totalpaid, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- สรุปยอดรวมและวันครบกำหนดชำระ -->
                            <div class="bill-summary-box d-flex justify-content-between align-items-center">
                                <div class="bill-sub-text">
                                    <span>งวดปัจจุบัน ({{ $currentBills->count() }} งวด): <b>{{ number_format($sumCurrent, 2) }}</b> บ.</span>
                                    <span class="mx-2">|</span>
                                    <span class="text-danger">ค้างชำระสะสม ({{ $oweBills->count() }} งวด): <b>{{ number_format($sumOwe, 2) }}</b> บ.</span>
                                </div>
                                <div class="text-right" style="font-size: 12.5pt;">
                                    <strong>รวมต้องชำระทั้งสิ้น: <span class="text-danger">{{ number_format($grandTotal, 2) }}</span> บาท</strong>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2 bill-sub-text text-muted">
                                <div>
                                    โปรดชำระเงินภายในวันที่ <strong class="text-dark due-date-text">{{ $defaultDueDateStr }}</strong>
                                    (หากเกินกำหนดอาจถูกระงับการใช้น้ำชั่วคราว)
                                </div>
                                {{-- <div>ลำดับที่ {{ $idx + 1 }}</div> --}}
                            </div>

                            <!-- ลายเซ็น -->
                            <div class="row no-gutters bill-sign mt-3">
                                <div class="col-12 text-center">
                                    <div class="sign-line">ลงชื่อ ..........................................................</div>
                                    <div class="sign-name">( นางสาวธนฤทัย ธนภัควงศ์ )</div>
                                    <div class="sign-role">ผู้รับเงิน</div>
                                </div>
                                {{-- <div class="col-6 text-center">
                                    <div class="sign-line">ลงชื่อ ..........................................................</div>
                                    <div class="sign-name">( นางสาววิไลภรณ์ เวชกามา )</div>
                                    <div class="sign-role">ผู้อำนวยการกองคลัง</div>
                                </div> --}}
                            </div>
                        </div>

                        <!-- ฝั่งขวา: ต้นขั้วสำหรับเจ้าหน้าที่เก็บเงิน -->
                        {{-- <div class="col-4 bill-stub d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between bill-sub-text mb-1">
                                    <span><strong>มิเตอร์:</strong> {{ $meter->meternumber ?? '-' }}</span>
                                    <span><strong>รหัส:</strong> {{ $memberCode }}</span>
                                </div>
                                <div class="bill-sub-text mb-1">
                                    <strong>ผู้ใช้น้ำ:</strong> {{ $fullName }}
                                </div>
                                <div class="bill-sub-text mb-2">
                                    <strong>บ้านเลขที่:</strong> {{ $address }} ({{ $zoneName }} / {{ $subzoneName }})
                                </div>

                                <div class="border-top border-bottom py-1 my-1 bill-sub-text">
                                    <div class="d-flex justify-content-between">
                                        <span>บิลปัจจุบัน ({{ $currentBills->count() }} งวด):</span>
                                        <span>{{ number_format($sumCurrent, 2) }} บ.</span>
                                    </div>
                                    <div class="d-flex justify-content-between text-danger">
                                        <span>ค้างชำระ ({{ $oweBills->count() }} งวด):</span>
                                        <span>{{ number_format($sumOwe, 2) }} บ.</span>
                                    </div>
                                    <div class="d-flex justify-content-between font-weight-bold mt-1" style="font-size: 10.5pt;">
                                        <span>ยอดชำระสุทธิ:</span>
                                        <span class="text-danger">{{ number_format($grandTotal, 2) }} บาท</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center my-1">
                                <textarea class="qrcode_text d-none" data-target="qr_bill_{{ $meter->meter_id }}">{{ $item['qrcode'] }}</textarea>
                                <div id="qr_bill_{{ $meter->meter_id }}" class="d-inline-block"></div>
                                <div style="font-size: 8pt;" class="text-muted">สแกน QR เพื่อรับชำระ / อ้างอิง</div>
                            </div>

                            <div class="bill-sub-text text-center mt-1 pt-1 border-top">
                                <div>ผู้รับเงิน............................................</div>
                                <div class="mt-1">วันที่........../........../..........</div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                @if (($idx + 1) < count($oweArray))
                    <div class="page-break"></div>
                @endif
            @endforeach
        </div>

        {{-- =======================================================================
             LAYOUT 2: หนังสือแจ้งเตือนชำระหนี้ค่าน้ำประปา (หนังสือราชการตราครุฑ)
             ======================================================================= --}}
        <div id="layout_official" class="{{ ($print_type ?? 'bill') === 'official_letter' ? '' : 'd-none' }}">
            @foreach ($oweArray as $idx => $item)
                @php
                    $meter = $item['meter'];
                    $invoices = $item['res'];
                    $user = $meter?->user;

                    $fullName = trim(($user->prefix ?? '') . ($user->firstname ?? '-') . ' ' . ($user->lastname ?? ''));
                    $address = $meter->meter_address ?: ($user->address ?? '-');
                    $zoneName = $meter?->undertake_zone?->zone_name ?? ($user?->user_zone?->zone_name ?? '-');
                    $grandTotal = (float) $item['owe_sum'];
                @endphp

                <div class="official-page">
                    <!-- ส่วนหัวสำหรับพับซองจดหมาย -->
                    <div class="row">
                        <div class="col-12">
                            <div class="font-weight-bold">{{ $orgDeptName }} {{ $orgName }}</div>
                            <div>{{ $orgAddress }} ต.{{ $orgTambon }}</div>
                            <div>อ.{{ $orgDistrict }} จ.{{ $orgProvince }} {{ $orgZipcode }}</div>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col-5"></div>
                        <div class="col-7">
                            <div class="font-weight-bold">เรียน &nbsp; {{ $fullName }}</div>
                            <div class="pl-4">บ้านเลขที่ {{ $address }} {{ $zoneName }} ต.{{ $orgTambon }}</div>
                            <div class="pl-4">อ.{{ $orgDistrict }} จ.{{ $orgProvince }} {{ $orgZipcode }}</div>
                        </div>
                    </div>
                    <hr style="border-top: 1px dashed #666;">

                    <!-- ส่วนหัวหนังสือราชการตราครุฑ -->
                    <div class="row align-items-end mt-3">
                        <div class="col-4">ที่ ขก 78002/ว.ประปา</div>
                        <div class="col-4 text-center">
                            <img src="{{ asset('/logo/krut.png') }}" style="width: 2.6cm; height: 2.6cm;" alt="ตราครุฑ">
                        </div>
                        <div class="col-4">
                            <div>สำนักงาน{{ $orgName }}</div>
                            <div>{{ $orgAddress }} ต.{{ $orgTambon }} อ.{{ $orgDistrict }} จ.{{ $orgProvince }} {{ $orgZipcode }}</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6"></div>
                        <div class="col-6">วันที่ {{ $todayThaiStr }}</div>
                    </div>

                    <div class="mt-2"><strong>เรื่อง</strong> &nbsp; ขอให้ชำระหนี้ค้างค่าน้ำประปา</div>
                    <div class="mt-1"><strong>เรียน</strong> &nbsp; <b>{{ $fullName }}</b></div>

                    <div class="official-indent mt-2">
                        ตามที่ท่านเป็นผู้ใช้น้ำของ {{ $orgDeptName }}{{ $orgName }}
                        เลขที่มิเตอร์ผู้ใช้น้ำ <b>{{ $meter->meternumber ?? '-' }}</b>
                        ในนามของ <b>{{ $fullName }}</b> (บ้านเลขที่ {{ $address }} {{ $zoneName }})
                        ปรากฏว่ามียอดค้างชำระและใบแจ้งหนี้ค่าน้ำประปา ดังรายละเอียดต่อไปนี้
                    </div>

                    <!-- ตารางแจกแจงรายการค้างชำระรายงวด -->
                    <table class="bill-table my-3">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>เลขที่ใบแจ้งหนี้</th>
                                <th>ประจำเดือน (รอบบิล)</th>
                                <th>สถานะ</th>
                                <th>เลขมิเตอร์ก่อน</th>
                                <th>เลขมิเตอร์หลัง</th>
                                <th>หน่วยน้ำที่ใช้</th>
                                <th>ค่าน้ำ (บาท)</th>
                                <th>ค่ารักษามาตร</th>
                                <th>VAT</th>
                                <th>รวมเป็นเงิน (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $k => $inv)
                                <tr>
                                    <td class="text-center">{{ $k + 1 }}</td>
                                    <td class="text-center">#{{ $inv->id }}</td>
                                    <td class="text-center">{{ $inv->invoice_period->inv_p_name ?? '-' }}</td>
                                    <td class="text-center">{{ $inv->status === 'owe' ? 'ค้างชำระ' : 'แจ้งหนี้' }}</td>
                                    <td class="text-right">{{ number_format((float) $inv->lastmeter) }}</td>
                                    <td class="text-right">{{ number_format((float) $inv->currentmeter) }}</td>
                                    <td class="text-right">{{ number_format((float) $inv->water_used) }}</td>
                                    <td class="text-right">{{ number_format((float) $inv->paid, 2) }}</td>
                                    <td class="text-right">{{ number_format((float) $inv->reserve_meter, 2) }}</td>
                                    <td class="text-right">{{ number_format((float) $inv->vat, 2) }}</td>
                                    <td class="text-right font-weight-bold">{{ number_format((float) $inv->totalpaid, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light font-weight-bold">
                                <td colspan="10" class="text-right">รวมเป็นเงินที่ต้องชำระทั้งสิ้น ({{ count($invoices) }} งวด)</td>
                                <td class="text-right text-danger">{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="official-indent">
                        ในการนี้ จึงขอให้ท่านโปรดติดต่อชำระค่าน้ำประปาจำนวนรวมทั้งสิ้น
                        <b>{{ number_format($grandTotal, 2) }} บาท</b>
                        ได้ที่ กองคลัง {{ $orgName }} ให้แล้วเสร็จภายในวันที่
                        <b class="due-date-text">{{ $defaultDueDateStr }}</b>
                        หากพ้นกำหนดดังกล่าว กิจการประปามีความจำเป็นต้องระงับการใช้น้ำชั่วคราว
                        และจะดำเนินการจ่ายน้ำใหม่หลังจากได้รับการชำระหนี้ค้างทั้งหมดเรียบร้อยแล้ว
                    </div>

                    <div class="official-indent mt-2">
                        จึงเรียนมาเพื่อโปรดชำระค่าน้ำประปาภายในกำหนด หวังเป็นอย่างยิ่งว่าจะได้รับความร่วมมือด้วยดีเช่นเคย
                        และหากท่านได้ชำระเงินก่อนได้รับหนังสือฉบับนี้แล้ว ต้องขออภัยมา ณ โอกาสนี้ด้วย
                    </div>

                    <div class="row mt-4 align-items-center">
                        <div class="col-4 text-center">
                            <div class="border p-2 d-inline-block bg-white">
                                <div class="small font-weight-bold border-bottom pb-1 mb-1">สแกน QR Code ชำระค่าน้ำประปา</div>
                                <textarea class="qrcode_text d-none" data-target="qr_official_{{ $meter->meter_id }}">{{ $item['qrcode'] }}</textarea>
                                <div id="qr_official_{{ $meter->meter_id }}" class="my-1"></div>
                                <div class="small">ยอดชำระ {{ number_format($grandTotal, 2) }} บาท</div>
                            </div>
                        </div>
                        <div class="col-8 text-center">
                            <div>ขอแสดงความนับถือ</div>
                            <div style="margin-top: 3.2rem;">({{ $orgHeadName }})</div>
                            <div>ผู้บริหาร{{ $orgName }}</div>
                            <div class="small text-muted mt-2">{{ $orgDeptName }} {{ $orgName }} โทร. {{ $orgPhone }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery.qrcode@1.0.3/jquery.qrcode.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.btnprint').hide();

            // สร้าง QR Code ทุกจุด
            $('.qrcode_text').each(function () {
                const targetId = $(this).data('target');
                const qrText = $(this).val();
                const $target = $('#' + targetId);
                if ($target.length) {
                    $target.empty().qrcode({
                        text: qrText,
                        width: 90,
                        height: 90
                    });
                }
            });

            // สลับรูปแบบเอกสารบนหน้าพรีวิว
            $('#layoutSwitcher').on('change', function () {
                const val = $(this).val();
                if (val === 'official_letter') {
                    $('#layout_bill').addClass('d-none');
                    $('#layout_official').removeClass('d-none');
                } else {
                    $('#layout_official').addClass('d-none');
                    $('#layout_bill').removeClass('d-none');
                }
            });

            // แก้ไขวันครบกำหนดชำระแบบ Real-time
            $('#dueDateInput').on('keyup change', function () {
                const val = $(this).val().trim() || '_____________________';
                $('.due-date-text').text(val);
            });
        });
    </script>
@endsection
