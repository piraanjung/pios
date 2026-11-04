@extends('layouts.print')

@php
    $orgTypeName = $org?->orgType?->name ?? '';
    $orgName = trim($orgTypeName . ($org->org_name ?? ''));
    $orgDeptName = $org->org_dept_name ?? 'กิจการประปา';
    $orgAddress = $org->org_address ?? '';
    $orgTambon = $org?->tambons?->tambon_name ?? '';
    $orgDistrict = $org?->districts?->district_name ?? '';
    $orgProvince = $org?->provinces?->province_name ?? '';
    $orgZipcode = $org->org_zipcode ?? '';
    $orgLogo = !empty($org->org_logo_img) ? asset('logo/' . $org->org_logo_img) : asset('/logo/khampom.png');
    $dateRange = $fromdateTh === $todateTh ? $fromdateTh : $fromdateTh . ' - ' . $todateTh;
@endphp

@section('style')
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: "Sarabun", sans-serif;
            background: #e2e8f0;
            color: #000;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .toolbar {
            background: #1e293b;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
        }

        .cover-page {
            width: 210mm;
            min-height: 297mm;
            margin: 15px auto;
            padding: 15mm 18mm;
            background: #fff;
            box-sizing: border-box;
            font-size: 14pt;
            line-height: 1.6;
        }

        .cover-title {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
        }

        .cover-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .cover-table th,
        .cover-table td {
            border: 1px solid #000;
            padding: 3px 6px;
            font-size: 12pt;
        }

        .cover-table th {
            background: #f1f5f9 !important;
            text-align: center;
        }

        .indent {
            text-indent: 2.5rem;
        }

        .sign-box {
            text-align: center;
            font-size: 13pt;
            line-height: 1.7;
        }

        @media print {
            body {
                background: #fff !important;
            }

            .no-print {
                display: none !important;
            }

            .cover-page {
                margin: 0;
                padding: 0;
                width: auto;
                min-height: 0;
            }

            @page {
                size: A4 portrait;
                margin: 15mm 18mm;
            }
        }
    </style>
@endsection

@section('content')
    <div class="toolbar no-print">
        <button type="button" class="btn btn-success btn-sm font-weight-bold px-3" onclick="window.print()">
            สั่งพิมพ์ใบนำส่งเงิน
        </button>
        <button type="button" class="btn btn-outline-light btn-sm" onclick="window.close()">ปิด</button>
    </div>

    <div class="cover-page">
        <div class="text-center">
            <img src="{{ $orgLogo }}" alt="Logo" style="height: 2.4cm;" onerror="this.style.display='none'">
        </div>
        <div class="cover-title mt-1">ใบนำส่งเงินรายได้ค่าน้ำประปา</div>
        <div class="text-center">{{ $orgDeptName }} {{ $orgName }}</div>

        <div class="d-flex justify-content-between mt-3">
            <div>ที่ ........................................</div>
            <div>วันที่ {{ $todayThai ?? '........../........../..........' }}</div>
        </div>

        <div class="mt-2"><strong>เรื่อง</strong> &nbsp; นำส่งเงินรับชำระค่าน้ำประปาประจำวันที่ {{ $dateRange }}</div>
        <div><strong>เรียน</strong> &nbsp; ผู้อำนวยการกองคลัง / หัวหน้าฝ่ายการเงินและบัญชี</div>

        <div class="indent mt-2">
            ด้วย{{ $orgDeptName }} {{ $orgName }}
            ได้รับชำระค่าน้ำประปาจากผู้ใช้น้ำ จำนวน <strong>{{ number_format($totals['meter_count']) }}</strong> ราย
            รวม <strong>{{ number_format($totals['bill_count']) }}</strong> ใบแจ้งหนี้
            ประจำวันที่ {{ $dateRange }} จึงขอนำส่งเงินดังรายละเอียดต่อไปนี้
        </div>

        <div class="mt-2" style="font-size: 12pt;">
            ปีงบประมาณ: <strong>{{ $filters['budgetyear'] }}</strong> &nbsp;|&nbsp;
            รอบบิล: <strong>{{ $filters['inv_period'] }}</strong> &nbsp;|&nbsp;
            หมู่ที่: <strong>{{ $filters['zone'] }}</strong> &nbsp;|&nbsp;
            เส้นทางจด: <strong>{{ $filters['subzone'] }}</strong> &nbsp;|&nbsp;
            ผู้รับเงิน: <strong>{{ $cashierName }}</strong>
        </div>

        <table class="cover-table">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รอบบิล</th>
                    <th>จำนวนใบ</th>
                    <th>หน่วยน้ำ</th>
                    <th>ค่าน้ำ (บาท)</th>
                    <th>ค่ารักษามาตร (บาท)</th>
                    <th>VAT 7% (บาท)</th>
                    <th>รวมเป็นเงิน (บาท)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $i => $row)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ $row['period'] }}</td>
                        <td class="text-right">{{ number_format($row['bill_count']) }}</td>
                        <td class="text-right">{{ number_format($row['water_used']) }}</td>
                        <td class="text-right">{{ number_format($row['paid'], 2) }}</td>
                        <td class="text-right">{{ number_format($row['reserve_meter'], 2) }}</td>
                        <td class="text-right">{{ number_format($row['vat'], 2) }}</td>
                        <td class="text-right">{{ number_format($row['totalpaid'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">ไม่มีรายการรับชำระในช่วงที่เลือก</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2" class="text-center">รวมทั้งสิ้น</td>
                    <td class="text-right">{{ number_format($totals['bill_count']) }}</td>
                    <td class="text-right">{{ number_format($totals['water_used']) }}</td>
                    <td class="text-right">{{ number_format($totals['paid'], 2) }}</td>
                    <td class="text-right">{{ number_format($totals['reserve_meter'], 2) }}</td>
                    <td class="text-right">{{ number_format($totals['vat'], 2) }}</td>
                    <td class="text-right">{{ number_format($totals['totalpaid'], 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-2">
            <strong>รวมเงินนำส่งทั้งสิ้น {{ number_format($totals['totalpaid'], 2) }} บาท</strong>
            ({{ $amountText }})
        </div>

        <div class="mt-2">
            ประเภทเงิน: &#9744; เงินสด &nbsp;&nbsp; &#9744; เงินโอน &nbsp;&nbsp; &#9744; เช็ค &nbsp;&nbsp; &#9744; อื่น ๆ ........................
        </div>
        <div>หมายเหตุ ........................................................................................................</div>

        <div class="indent mt-3">จึงเรียนมาเพื่อโปรดตรวจรับเงินและลงบัญชีต่อไป</div>

        <div class="row mt-4 sign-box">
            <div class="col-4">
                <div>ลงชื่อ ............................ ผู้นำส่ง</div>
                <div>( ............................ )</div>
                <div>ตำแหน่ง ............................</div>
                <div>วันที่ ........../........../..........</div>
            </div>
            <div class="col-4">
                <div>ลงชื่อ ............................ ผู้ตรวจสอบ</div>
                <div>( ............................ )</div>
                <div>ตำแหน่ง ............................</div>
                <div>วันที่ ........../........../..........</div>
            </div>
            <div class="col-4">
                <div>ลงชื่อ ............................ ผู้รับเงิน</div>
                <div>( ............................ )</div>
                <div>ตำแหน่ง ............................</div>
                <div>วันที่ ........../........../..........</div>
            </div>
        </div>

        <div class="text-muted mt-4" style="font-size: 10pt;">
            เอกสารนี้ใช้ปะหน้ารายงานการรับชำระค่าน้ำประจำวัน (แนบไฟล์ Excel / รายงานประกอบ)
        </div>
    </div>
@endsection
