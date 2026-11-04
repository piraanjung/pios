@extends('layouts.admin1')

@section('mainheader')
    พิมพ์ใบแจ้งหนี้ / ใบแจ้งเตือนชำระหนี้ค่าน้ำประปา
@endsection
@section('nav-header')
    จัดการใบแจ้งหนี้
@endsection
@section('nav')
    <a href="{{ route('admin.owepaper.index') }}">พิมพ์ใบแจ้งหนี้ / แจ้งเตือนค้างชำระ</a>
@endsection
@section('owepaper')
    active
@endsection

@section('style')
    <style>
        .kpi-card {
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 0;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }
        .filter-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }
        .member-row {
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        .member-row:hover {
            background-color: #f1f5f9 !important;
        }
        .member-row.row-selected {
            background-color: #eff6ff !important;
        }
        .detail-row {
            background-color: #f8fafc;
        }
        .badge-pill-custom {
            padding: 0.35em 0.65em;
            font-size: 0.8rem;
            border-radius: 50rem;
        }
        .sticky-action-bar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: #ffffff;
            border-bottom: 2px solid #e2e8f0;
        }
        .custom-checkbox-lg {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    @php
        $totalMembers = $members->count();
        $totalOweBills = 0;
        $totalInvoiceBills = 0;
        $grandTotalAmount = 0;

        foreach ($members as $m) {
            $totalOweBills += $m->tw_invoices->where('status', 'owe')->count();
            $totalInvoiceBills += $m->tw_invoices->where('status', 'invoice')->count();
            $grandTotalAmount += (float) $m->tw_invoices->sum('totalpaid');
        }
    @endphp

    <!-- 1. KPI Summary Cards -->
    <div class="row mb-3">
        <div class="col-lg-3 col-sm-6 mb-2">
            <div class="card kpi-card shadow-sm border-left border-primary h-100">
                <div class="card-body py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">ผู้ใช้น้ำที่แสดงผล</div>
                        <div class="h4 mb-0 font-weight-bold text-dark">
                            <span id="visibleMemberCount">{{ number_format($totalMembers) }}</span> <small class="text-muted">ราย</small>
                        </div>
                    </div>
                    <div class="text-primary opacity-50">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-2">
            <div class="card kpi-card shadow-sm border-left border-info h-100">
                <div class="card-body py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">ใบแจ้งหนี้ (Invoice / Owe)</div>
                        <div class="h5 mb-0 font-weight-bold text-dark">
                            <span class="badge badge-info" id="visibleInvoiceBills">{{ number_format($totalInvoiceBills) }} งวดปัจจุบัน</span>
                            <span class="badge badge-danger ml-1" id="visibleOweBills">{{ number_format($totalOweBills) }} ค้างชำระ</span>
                        </div>
                    </div>
                    <div class="text-info opacity-50">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-2">
            <div class="card kpi-card shadow-sm border-left border-warning h-100">
                <div class="card-body py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ยอดเงินรวมที่แสดงผล</div>
                        <div class="h4 mb-0 font-weight-bold text-danger">
                            <span id="visibleTotalAmount">{{ number_format($grandTotalAmount, 2) }}</span> <small class="text-muted">บาท</small>
                        </div>
                    </div>
                    <div class="text-warning opacity-50">
                        <i class="fas fa-coins fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-2">
            <div class="card kpi-card shadow-sm border-left border-success h-100 bg-light">
                <div class="card-body py-3 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">เลือกเตรียมพิมพ์แล้ว</div>
                        <div class="h4 mb-0 font-weight-bold text-success">
                            <span id="selectedCountBadge">0</span> <small class="text-muted">ราย</small>
                            (<span id="selectedSumBadge">0.00</span> <small>฿</small>)
                        </div>
                    </div>
                    <div class="text-success opacity-50">
                        <i class="fas fa-print fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Filter Bar (Zone, Subzone, Status, Search) -->
    <div class="card shadow-sm mb-3">
        <div class="card-body filter-bar py-3">
            <div class="row align-items-end">
                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-secondary mb-1">
                        <i class="fas fa-map-marked-alt text-primary"></i> หมู่ที่ (Zone)
                    </label>
                    <select class="form-control form-control-sm" id="zoneFilter">
                        <option value="all" {{ $zone_id_selected == 'all' ? 'selected' : '' }}>-- ทั้งหมดทุกหมู่ --</option>
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}" {{ (string) $zone_id_selected === (string) $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-secondary mb-1">
                        <i class="fas fa-route text-info"></i> เส้นทางจดมิเตอร์ (Subzone)
                    </label>
                    <select class="form-control form-control-sm" id="subzoneFilter">
                        <option value="all" {{ $subzone_id_selected == 'all' ? 'selected' : '' }}>-- ทั้งหมดทุกเส้นทาง --</option>
                        @foreach ($subzones as $sz)
                            <option value="{{ $sz->id }}" data-zone-id="{{ $sz->zone_id }}"
                                {{ (string) $subzone_id_selected === (string) $sz->id ? 'selected' : '' }}>
                                {{ $sz->subzone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-secondary mb-1">
                        <i class="fas fa-filter text-warning"></i> สถานะใบแจ้งหนี้
                    </label>
                    <select class="form-control form-control-sm" id="statusFilter">
                        <option value="all" {{ $status_selected == 'all' ? 'selected' : '' }}>ทั้งหมด (Owe + Invoice)</option>
                        <option value="has_owe" {{ $status_selected == 'owe' ? 'selected' : '' }}>เฉพาะที่มีค้างชำระ (Owe)</option>
                        <option value="has_invoice" {{ $status_selected == 'invoice' ? 'selected' : '' }}>เฉพาะมีบิลรอบปัจจุบัน (Invoice)</option>
                    </select>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-secondary mb-1">
                        <i class="fas fa-search text-success"></i> ค้นหาผู้ใช้น้ำ / มิเตอร์
                    </label>
                    <input type="text" id="keywordFilter" class="form-control form-control-sm"
                        placeholder="ชื่อ-สกุล, เลขมิเตอร์ TW-..., บ้านเลขที่...">
                </div>

                <div class="col-md-1 mb-2 mb-md-0">
                    <button type="button" id="resetFilterBtn" class="btn btn-sm btn-outline-secondary btn-block" title="ล้างตัวกรอง">
                        <i class="fas fa-redo"></i> รีเซ็ต
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Form & Member Table -->
    <form action="{{ route('admin.owepaper.print') }}" method="POST" id="owePrintForm" target="_blank">
        @csrf
        <input type="hidden" name="from_view" value="owepaper">

        <div class="card shadow-sm">
            <!-- Sticky Action Toolbar -->
            <div class="card-header sticky-action-bar py-3">
                <div class="row align-items-center justify-content-between">
                    <div class="col-md-5 mb-2 mb-md-0 d-flex align-items-center flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-primary mr-2" id="btnSelectVisible">
                            <i class="fas fa-check-square"></i> เลือกทั้งหมดที่แสดง
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary mr-2" id="btnUnselectAll">
                            <i class="far fa-square"></i> ยกเลิกเลือกทั้งหมด
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" id="btnToggleAllDetails">
                            <i class="fas fa-list-ul"></i> แสดง/ซ่อนรายละเอียดทุกแถว
                        </button>
                    </div>

                    <div class="col-md-7 d-flex align-items-center justify-content-md-end flex-wrap">
                        <div class="d-flex align-items-center mr-3 mb-2 mb-md-0">
                            <label class="small font-weight-bold text-dark mb-0 mr-2 text-nowrap">รูปแบบเอกสาร:</label>
                            <select name="print_type" id="printTypeSelect" class="form-control form-control-sm" style="min-width: 260px;">
                                <option value="bill">📄 ใบแจ้งหนี้ค่าน้ำประปา (มีส่วนฉีกเก็บเงิน + QR)</option>
                                <option value="official_letter">🏛️ หนังสือราชการแจ้งเตือนค้างชำระ (ตราครุฑ)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary font-weight-bold shadow-sm" id="btnSubmitPrint">
                            <i class="fas fa-print mr-1"></i> พิมพ์ใบแจ้งหนี้ที่เลือก (<span id="btnSelectedCount">0</span>)
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="oweMemberTable">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="text-center" style="width: 45px;">
                                    <input type="checkbox" id="masterCheckbox" class="custom-checkbox-lg" title="เลือก/ยกเลิกทั้งหมดที่แสดง">
                                </th>
                                <th style="width: 45px;" class="text-center">#</th>
                                <th>เลขมิเตอร์ / รหัสสมาชิก</th>
                                <th>ชื่อ-สกุลผู้ใช้น้ำ</th>
                                <th class="text-center">บ้านเลขที่</th>
                                <th class="text-center">หมู่ที่ (Zone)</th>
                                <th class="text-center">เส้นทาง (Subzone)</th>
                                <th class="text-center">จำนวนงวด</th>
                                <th class="text-right">ค่าน้ำรวม</th>
                                <th class="text-right">ค่ารักษามาตร</th>
                                <th class="text-right">VAT</th>
                                <th class="text-right">รวมต้องชำระ (บาท)</th>
                                <th class="text-center" style="width: 120px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($members as $idx => $member)
                                @php
                                    $invoices = $member->tw_invoices;
                                    $oweCount = $invoices->where('status', 'owe')->count();
                                    $invCount = $invoices->where('status', 'invoice')->count();
                                    $totalBills = $invoices->count();

                                    $sumWaterUsed = (float) $invoices->sum('water_used');
                                    $sumPaid = (float) $invoices->sum('paid');
                                    $sumReserve = (float) $invoices->sum('reserve_meter');
                                    $sumVat = (float) $invoices->sum('vat');
                                    $sumTotalPaid = (float) $invoices->sum('totalpaid');

                                    $fullName = trim(($member->user->prefix ?? '') . ($member->user->firstname ?? '-') . ' ' . ($member->user->lastname ?? ''));
                                    $memberCode = 'U' . str_pad((string) $member->user_id, 4, '0', STR_PAD_LEFT);
                                    $address = $member->meter_address ?: ($member->user->address ?? '-');
                                    $zoneName = $member->undertake_zone->zone_name ?? '-';
                                    $subzoneName = $member->undertake_subzone->subzone_name ?? '-';

                                    $searchText = strtolower(
                                        $member->meternumber . ' ' .
                                        ($member->factory_no ?? '') . ' ' .
                                        $memberCode . ' ' .
                                        $fullName . ' ' .
                                        $address . ' ' .
                                        $zoneName . ' ' .
                                        $subzoneName
                                    );
                                @endphp

                                <tr class="member-row"
                                    data-meter-id="{{ $member->meter_id }}"
                                    data-zone-id="{{ $member->undertake_zone_id }}"
                                    data-subzone-id="{{ $member->undertake_subzone_id }}"
                                    data-owe-count="{{ $oweCount }}"
                                    data-inv-count="{{ $invCount }}"
                                    data-total-amount="{{ $sumTotalPaid }}"
                                    data-search="{{ $searchText }}">
                                    <td class="text-center align-middle" onclick="event.stopPropagation();">
                                        <input type="checkbox"
                                            class="custom-checkbox-lg meter-checkbox"
                                            name="meter_id[{{ $member->meter_id }}]"
                                            value="on"
                                            data-meter-id="{{ $member->meter_id }}"
                                            data-amount="{{ $sumTotalPaid }}">
                                    </td>
                                    <td class="text-center align-middle text-muted small">{{ $idx + 1 }}</td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-primary">{{ $member->meternumber }}</div>
                                        <div class="small text-muted">
                                            รหัส: {{ $memberCode }}
                                            @if ($member->factory_no)
                                                | เลขโรงงาน: {{ $member->factory_no }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark">{{ $fullName }}</div>
                                        @if ($member->submeter_name)
                                            <div class="small text-info"><i class="fas fa-tag"></i> {{ $member->submeter_name }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">{{ $address }}</td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-light border">{{ $zoneName }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-light border text-secondary">{{ $subzoneName }}</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if ($invCount > 0)
                                            <span class="badge badge-info badge-pill-custom" title="ใบแจ้งหนี้รอบปัจจุบัน">
                                                แจ้งหนี้ {{ $invCount }}
                                            </span>
                                        @endif
                                        @if ($oweCount > 0)
                                            <span class="badge badge-danger badge-pill-custom" title="ค้างชำระสะสม">
                                                ค้าง {{ $oweCount }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-right align-middle">{{ number_format($sumPaid, 2) }}</td>
                                    <td class="text-right align-middle">{{ number_format($sumReserve, 2) }}</td>
                                    <td class="text-right align-middle">{{ number_format($sumVat, 2) }}</td>
                                    <td class="text-right align-middle font-weight-bold text-danger h6 mb-0">
                                        {{ number_format($sumTotalPaid, 2) }}
                                    </td>
                                    <td class="text-center align-middle" onclick="event.stopPropagation();">
                                        <button type="button" class="btn btn-xs btn-outline-info btn-toggle-detail mr-1"
                                            data-target="#detail_{{ $member->meter_id }}" title="ดูรายละเอียดรายงวด">
                                            <i class="fas fa-chevron-down"></i> รายงวด
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-print-single"
                                            data-meter-id="{{ $member->meter_id }}" title="พิมพ์เฉพาะรายนี้">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- แถวแสดงรายละเอียดใบแจ้งหนี้ย่อยของมิเตอร์นี้ -->
                                <tr id="detail_{{ $member->meter_id }}" class="detail-row d-none">
                                    <td colspan="13" class="p-3">
                                        <div class="card card-body p-2 mb-0 border shadow-none">
                                            <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                                                <span class="font-weight-bold text-dark small">
                                                    <i class="fas fa-receipt text-primary mr-1"></i>
                                                    รายละเอียดใบแจ้งหนี้ของ <b>{{ $fullName }}</b> ({{ $member->meternumber }})
                                                </span>
                                                <span class="small text-muted">
                                                    รวม {{ $totalBills }} งวด | ใช้น้ำรวม {{ number_format($sumWaterUsed) }} หน่วย
                                                </span>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0 bg-white small">
                                                    <thead class="bg-light text-center">
                                                        <tr>
                                                            <th>เลขที่ใบแจ้งหนี้</th>
                                                            <th>รอบบิล</th>
                                                            <th>สถานะ</th>
                                                            <th class="text-right">มิเตอร์ก่อน</th>
                                                            <th class="text-right">มิเตอร์หลัง</th>
                                                            <th class="text-right">หน่วยน้ำที่ใช้</th>
                                                            <th class="text-right">ค่าน้ำ (บาท)</th>
                                                            <th class="text-right">ค่ารักษามาตร (บาท)</th>
                                                            <th class="text-right">VAT (บาท)</th>
                                                            <th class="text-right">รวมเป็นเงิน (บาท)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($invoices as $inv)
                                                            <tr>
                                                                <td class="text-center font-weight-bold">#{{ $inv->id }}</td>
                                                                <td class="text-center">{{ $inv->invoice_period->inv_p_name ?? '-' }}</td>
                                                                <td class="text-center">
                                                                    @if ($inv->status === 'owe')
                                                                        <span class="badge badge-danger">ค้างชำระ (Owe)</span>
                                                                    @else
                                                                        <span class="badge badge-info">แจ้งหนี้ (Invoice)</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-right">{{ number_format((float) $inv->lastmeter) }}</td>
                                                                <td class="text-right">{{ number_format((float) $inv->currentmeter) }}</td>
                                                                <td class="text-right font-weight-bold">{{ number_format((float) $inv->water_used) }}</td>
                                                                <td class="text-right">{{ number_format((float) $inv->paid, 2) }}</td>
                                                                <td class="text-right">{{ number_format((float) $inv->reserve_meter, 2) }}</td>
                                                                <td class="text-right">{{ number_format((float) $inv->vat, 2) }}</td>
                                                                <td class="text-right font-weight-bold text-danger">{{ number_format((float) $inv->totalpaid, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                                        <h5>ไม่พบรายการใบแจ้งหนี้หรือยอดค้างชำระ</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- แจ้งเตือนกรณีกรองแล้วไม่พบข้อมูล -->
                <div id="emptyFilterState" class="text-center py-5 text-muted d-none">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <h6>ไม่พบข้อมูลผู้ใช้น้ำที่ตรงกับเงื่อนไขการกรอง</h6>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
<script>
$(document).ready(function () {
    // 1. กรอง Subzone ตาม Zone ที่เลือก
    function updateSubzoneOptions(selectedZoneId, resetSelection = true) {
        const $subzoneSelect = $('#subzoneFilter');
        if (resetSelection) {
            $subzoneSelect.val('all');
        }

        $subzoneSelect.find('option').each(function () {
            const val = $(this).val();
            if (val === 'all') {
                $(this).show();
                return;
            }
            const optionZoneId = String($(this).data('zone-id'));
            if (selectedZoneId === 'all' || String(selectedZoneId) === optionZoneId) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // 2. ฟังก์ชันกรองตารางแบบ Real-time
    function applyFilters() {
        const zoneVal = String($('#zoneFilter').val());
        const subzoneVal = String($('#subzoneFilter').val());
        const statusVal = String($('#statusFilter').val());
        const keyword = ($('#keywordFilter').val() || '').toLowerCase().trim();

        let visibleMembers = 0;
        let visibleInvBills = 0;
        let visibleOweBills = 0;
        let visibleTotalAmount = 0;

        $('.member-row').each(function () {
            const $row = $(this);
            const meterId = $row.data('meter-id');
            const rowZone = String($row.data('zone-id'));
            const rowSubzone = String($row.data('subzone-id'));
            const oweCount = parseInt($row.data('owe-count')) || 0;
            const invCount = parseInt($row.data('inv-count')) || 0;
            const totalAmount = parseFloat($row.data('total-amount')) || 0;
            const searchStr = String($row.data('search') || '');

            let match = true;
            if (zoneVal !== 'all' && rowZone !== zoneVal) match = false;
            if (subzoneVal !== 'all' && rowSubzone !== subzoneVal) match = false;
            if (statusVal === 'has_owe' && oweCount <= 0) match = false;
            if (statusVal === 'has_invoice' && invCount <= 0) match = false;
            if (keyword !== '' && searchStr.indexOf(keyword) === -1) match = false;

            if (match) {
                $row.show();
                visibleMembers++;
                visibleInvBills += invCount;
                visibleOweBills += oweCount;
                visibleTotalAmount += totalAmount;
            } else {
                $row.hide();
                $('#detail_' + meterId).addClass('d-none');
            }
        });

        $('#visibleMemberCount').text(visibleMembers.toLocaleString());
        $('#visibleInvoiceBills').text(visibleInvBills.toLocaleString() + ' งวดปัจจุบัน');
        $('#visibleOweBills').text(visibleOweBills.toLocaleString() + ' ค้างชำระ');
        $('#visibleTotalAmount').text(visibleTotalAmount.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));

        if (visibleMembers === 0 && $('.member-row').length > 0) {
            $('#emptyFilterState').removeClass('d-none');
        } else {
            $('#emptyFilterState').addClass('d-none');
        }

        updateSelectionStats();
    }

    // 3. อัปเดตสถิติรายการที่ติ๊กเลือก
    function updateSelectionStats() {
        let selectedCount = 0;
        let selectedSum = 0;

        $('.meter-checkbox:checked').each(function () {
            selectedCount++;
            selectedSum += parseFloat($(this).data('amount')) || 0;
        });

        $('#selectedCountBadge').text(selectedCount.toLocaleString());
        $('#btnSelectedCount').text(selectedCount.toLocaleString());
        $('#selectedSumBadge').text(selectedSum.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));

        // เช็คสถานะ masterCheckbox เฉพาะแถวที่แสดงอยู่
        const $visibleCheckboxes = $('.member-row:visible .meter-checkbox');
        const $visibleChecked = $('.member-row:visible .meter-checkbox:checked');
        $('#masterCheckbox').prop(
            'checked',
            $visibleCheckboxes.length > 0 && $visibleCheckboxes.length === $visibleChecked.length
        );
    }

    // ผูก Event ตัวกรอง
    $('#zoneFilter').on('change', function () {
        updateSubzoneOptions($(this).val(), true);
        applyFilters();
    });

    $('#subzoneFilter, #statusFilter').on('change', function () {
        applyFilters();
    });

    $('#keywordFilter').on('keyup', function () {
        applyFilters();
    });

    $('#resetFilterBtn').on('click', function () {
        $('#zoneFilter').val('all');
        updateSubzoneOptions('all', true);
        $('#statusFilter').val('all');
        $('#keywordFilter').val('');
        applyFilters();
    });

    // คลิกที่แถวเพื่อติ๊กเลือก/ยกเลิก
    $('.member-row').on('click', function () {
        const $cb = $(this).find('.meter-checkbox');
        $cb.prop('checked', !$cb.prop('checked')).trigger('change');
    });

    $('.meter-checkbox').on('change', function () {
        const $row = $(this).closest('.member-row');
        $row.toggleClass('row-selected', $(this).is(':checked'));
        updateSelectionStats();
    });

    // เลือก/ยกเลิกทั้งหมดที่แสดงอยู่
    $('#masterCheckbox').on('change', function () {
        const isChecked = $(this).is(':checked');
        $('.member-row:visible .meter-checkbox').each(function () {
            $(this).prop('checked', isChecked);
            $(this).closest('.member-row').toggleClass('row-selected', isChecked);
        });
        updateSelectionStats();
    });

    $('#btnSelectVisible').on('click', function () {
        $('.member-row:visible .meter-checkbox').each(function () {
            $(this).prop('checked', true);
            $(this).closest('.member-row').addClass('row-selected');
        });
        updateSelectionStats();
    });

    $('#btnUnselectAll').on('click', function () {
        $('.meter-checkbox').prop('checked', false);
        $('.member-row').removeClass('row-selected');
        updateSelectionStats();
    });

    // เปิด/ปิดดูรายละเอียดรายงวด
    $('.btn-toggle-detail').on('click', function () {
        const target = $(this).data('target');
        const $detailRow = $(target);
        $detailRow.toggleClass('d-none');
        $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
    });

    let allDetailsOpen = false;
    $('#btnToggleAllDetails').on('click', function () {
        allDetailsOpen = !allDetailsOpen;
        $('.member-row:visible').each(function () {
            const meterId = $(this).data('meter-id');
            $('#detail_' + meterId).toggleClass('d-none', !allDetailsOpen);
        });
    });

    // พิมพ์เดี่ยวเฉพาะมิเตอร์นั้น
    $('.btn-print-single').on('click', function () {
        const meterId = $(this).data('meter-id');
        $('.meter-checkbox').prop('checked', false);
        $('.member-row').removeClass('row-selected');

        const $cb = $(`.meter-checkbox[data-meter-id="${meterId}"]`);
        $cb.prop('checked', true).trigger('change');
        $('#owePrintForm').trigger('submit');
    });

    // ตรวจสอบก่อนส่งพิมพ์
    $('#owePrintForm').on('submit', function (e) {
        if ($('.meter-checkbox:checked').length === 0) {
            alert('⚠️ กรุณาเลือกผู้ใช้น้ำอย่างน้อย 1 รายการเพื่อพิมพ์ใบแจ้งหนี้');
            e.preventDefault();
            return false;
        }
    });

    // เริ่มต้นตอนโหลดหน้า
    updateSubzoneOptions($('#zoneFilter').val(), false);
    applyFilters();
});
</script>
@endsection
