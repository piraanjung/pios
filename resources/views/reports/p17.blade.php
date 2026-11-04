@extends('layouts.admin1')
@section('nav-header', 'รายงาน')
@section('nav-topic', 'ทะเบียนรายตัวลูกหนี้ (ป.17)')
@section('content')
<div class="p17-report">
<div class="mb-4">
    <h3 class="mb-1">ทะเบียนรายตัวลูกหนี้ (ป.17)</h3>
    <p class="text-sm text-secondary mb-0">ประเภทใช้มาตรวัดน้ำ · พบ {{ number_format($invoices->total()) }} รายการ</p>
</div>

    <!-- Filter Form -->
    <div class="card mb-4 no-print">
        <div class="card-body">
            <form method="GET" action="{{ route('reports.p17') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">งวดประจำเดือน</label>
                    <select name="inv_period_id_fk" class="form-select">
                        <option value="">บิลล่าสุดของแต่ละมิเตอร์</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" {{ $periodId == $period->id ? 'selected' : '' }}>
                                {{ $period->inv_p_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="zone_id">หมู่</label>
                    <select name="zone_id" id="zone_id" class="form-select">
                        <option value="">ทุกหมู่</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ (string) $zoneId === (string) $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label" for="subzone_id">เส้นทาง</label>
                    <select name="subzone_id" id="subzone_id" class="form-select">
                        <option value="">ทุกเส้นทาง</option>
                        @foreach($subzones as $subzone)
                            <option value="{{ $subzone->id }}" data-zone-id="{{ $subzone->zone_id }}" {{ (string) $subzoneId === (string) $subzone->id ? 'selected' : '' }}>
                                {{ $subzone->subzone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">ค้นหา (ชื่อ-สกุล / เลขมาตร)</label>
                    <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="ชื่อหรือรหัสมิเตอร์">
                </div>

                <div class="col-md-2">
                    <label class="form-label">เลขมาตรวัดน้ำ</label>
                    <input type="text" name="meternumber" class="form-control" value="{{ $meterNumber }}" placeholder="เช่น TW-001-0001">
                </div>

                <div class="col-md-2">
                    <label class="form-label">รหัสผู้ใช้ (User ID)</label>
                    <input type="number" name="user_id" class="form-control" value="{{ $userId }}" placeholder="รหัสผู้ใช้">
                </div>

                <div class="col-12 d-flex align-items-end justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>ค้นหา</button>
                    <a href="{{ route('reports.p17') }}" class="btn btn-outline-secondary">ล้าง</a>
                    <button type="submit" name="export" value="excel" class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i>Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ตารางแสดงผล ป.17 -->
    <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle text-center p17-table">
    <thead class="table-light">
        <tr>
            <th rowspan="2">เลขมาตร</th>
            <th rowspan="2">ชื่อ - สกุล</th>
            <th rowspan="2">เลขที่บิล</th>
            <th colspan="2">เลขมาตร</th>
            <th rowspan="2">ยอดบิล</th>
            <th rowspan="2">หนี้ยกมา</th>
            <th rowspan="2">รวมหนี้</th>
            <th colspan="3" class="bg-success text-white">การชำระ</th>
            <th rowspan="2">คงค้างยกไป</th>
        </tr>
        <tr>
            <th>ก่อน</th><th>หลัง</th>
            <th>วันที่ชำระ</th><th>เลขธุรกรรม</th><th>จำนวนเงิน</th>
        </tr>
    </thead>
    <tbody>
        @forelse($invoices as $row)
            @php
                $user = optional($row->tw_meter_infos)->user;
                $fullName = $user
                    ? trim(($user->prefix ?? '') . ' ' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? ''))
                    : '-';
            @endphp
            <tr>
                <td class="text-nowrap">{{ $row->tw_meter_infos->meternumber ?? '-' }}</td>
                <td class="text-start text-nowrap">{{ $fullName }}</td>
                <td>{{ $row->id }}</td>
                <td class="text-end">{{ number_format((float) $row->lastmeter) }}</td>
                <td class="text-end">{{ number_format((float) $row->currentmeter) }}</td>
                <td class="text-end">{{ number_format((float) $row->totalpaid, 2) }}</td>
                <td class="text-end">{{ number_format((float) $row->computed_brought_forward, 2) }}</td>
                <td class="text-end fw-bold">{{ number_format((float) $row->computed_total_due, 2) }}</td>
                <td class="text-nowrap">{{ $row->computed_pay_date }}</td>
                <td>{{ $row->computed_transaction_id }}</td>
                <td class="text-end fw-bold text-success">{{ number_format((float) $row->computed_paid_amount, 2) }}</td>
                <td class="text-end fw-bold text-danger">{{ number_format((float) $row->computed_carried_forward, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="12" class="py-4 text-center text-secondary">ไม่พบรายการตามเงื่อนไขที่เลือก</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3 no-print">
        <small class="text-secondary">
            แสดง {{ $invoices->firstItem() ?? 0 }}–{{ $invoices->lastItem() ?? 0 }} จาก {{ number_format($invoices->total()) }} รายการ
        </small>
        {{ $invoices->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('style')
    <style>
        .p17-report .p17-table { min-width: 1050px; font-size: .875rem; }
        .p17-report .p17-table thead th { vertical-align: middle; white-space: nowrap; }
        .p17-report .p17-table tbody td { padding: .55rem .65rem; }
        @media print {
            .p17-report .no-print { display: none !important; }
            .p17-report .p17-table { min-width: 0; font-size: 9pt; }
        }
    </style>
@endsection

@section('script')
    <script>
        $(function() {
            const zoneSelect = document.getElementById('zone_id');
            const subzoneSelect = document.getElementById('subzone_id');

            function filterSubzones(resetSelection) {
                const zoneId = zoneSelect.value;
                Array.from(subzoneSelect.options).forEach(function(option) {
                    if (!option.value) return;
                    option.hidden = Boolean(zoneId && option.dataset.zoneId !== zoneId);
                });

                if (resetSelection || subzoneSelect.selectedOptions[0]?.hidden) {
                    subzoneSelect.value = '';
                }
            }

            zoneSelect.addEventListener('change', function() {
                filterSubzones(true);
            });
            filterSubzones(false);
        });
    </script>
@endsection