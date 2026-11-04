@extends('annual_trash.layout')

@section('nav-header', 'รายงาน')
@section('nav-current', 'สรุปรับเงินประจำวัน')
@section('nav-main', 'รายงานการรับชำระ')
@section('page-topic', 'สรุปรับเงินประจำวัน')
@section('nav-daily', 'active')

@section('style')
<style>
    .daily-stat { min-height: 100px; }
    .payment-id { font-family: Consolas, 'Courier New', monospace; font-variant-numeric: tabular-nums; }
    @media print {
        .no-print, .sidenav, #navbarBlur { display: none !important; }
        .main-content { margin: 0 !important; }
        .container-fluid { padding: 0 !important; }
        .card { box-shadow: none !important; border: 0 !important; }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-sm text-secondary mb-1">Annual Trash / Reports</p>
            <h5 class="mb-0">สรุปรับเงินประจำวัน</h5>
        </div>
        <div class="d-flex gap-2 no-print">
            <a class="btn btn-outline-success btn-sm mb-0" href="{{ route('keptkayas.reports.daily_collection', ['from_date' => $fromDate, 'to_date' => $toDate, 'zone_id' => $zoneId, 'export' => 'excel']) }}">
                <i class="fas fa-file-excel me-1" aria-hidden="true"></i> Export Excel
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm mb-0" onclick="window.print()">
                <i class="fas fa-print me-1" aria-hidden="true"></i> พิมพ์รายงาน
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif

    <form method="GET" action="{{ route('keptkayas.reports.daily_collection') }}" class="card mb-4 no-print">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-sm-5 col-lg-3">
                    <label class="form-label" for="from_date">ตั้งแต่วันที่</label>
                    <input class="form-control" id="from_date" name="from_date" type="date" value="{{ $fromDate }}" required>
                </div>
                <div class="col-sm-5 col-lg-3">
                    <label class="form-label" for="to_date">ถึงวันที่</label>
                    <input class="form-control" id="to_date" name="to_date" type="date" value="{{ $toDate }}" required>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label" for="zone_id">โซน</label>
                    <select class="form-select" id="zone_id" name="zone_id">
                        <option value="all" @selected($zoneId === 'all')>ทุกโซน</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" @selected((string) $zoneId === (string) $zone->id)>{{ $zone->zone_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 col-lg-2 d-grid">
                    <button class="btn btn-primary mb-0" type="submit"><i class="fas fa-search me-1"></i> แสดงรายการ</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="card daily-stat"><div class="card-body"><span class="text-sm text-secondary">ยอดรับชำระ</span><div class="h4 text-success mb-0 mt-2">{{ number_format($summary['amount'], 2) }} <small>บาท</small></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card daily-stat"><div class="card-body"><span class="text-sm text-secondary">รายการรับเงิน</span><div class="h4 mb-0 mt-2">{{ number_format($summary['transactions']) }} <small>รายการ</small></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card daily-stat"><div class="card-body"><span class="text-sm text-secondary">จำนวนถัง</span><div class="h4 mb-0 mt-2">{{ number_format($summary['bins']) }} <small>ถัง</small></div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card daily-stat"><div class="card-body"><span class="text-sm text-secondary">จำนวนสมาชิก</span><div class="h4 mb-0 mt-2">{{ number_format($summary['members']) }} <small>คน</small></div></div></div></div>
    </div>

    <div class="card">
        <div class="card-header pb-0">
            <h6 class="mb-1">รายการรับชำระ</h6>
            <p class="text-sm text-secondary mb-0">{{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}</p>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead><tr><th>ลำดับ</th><th>วันที่</th><th>รายการ</th><th>สมาชิก</th><th>โซน</th><th>รหัสถัง</th><th>ประเภท</th><th>ผู้รับเงิน</th><th class="text-end">จำนวนเงิน (บาท)</th></tr></thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                            @php
                                $subscription = $payment->subscription;
                                $bin = $subscription?->AnnualTrash;
                                $user = $bin?->user;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->pay_date)->format('d/m/Y') }}</td>
                                <td class="payment-id">PAY-{{ str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: '-' }}</td>
                                <td>{{ $user?->user_zone?->zone_name ?? '-' }}</td>
                                <td>{{ $bin->bin_code ?? '-' }}</td>
                                <td>{{ $bin->kpUserGroup->usergroup_name ?? 'ทั่วไป' }}</td>
                                <td>{{ trim(($payment->staff->firstname ?? '') . ' ' . ($payment->staff->lastname ?? '')) ?: '-' }}</td>
                                <td class="text-end">{{ number_format($payment->amount_paid, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-5 text-secondary">ไม่พบรายการรับชำระในช่วงวันที่เลือก</td></tr>
                        @endforelse
                    </tbody>
                    @if($payments->isNotEmpty())
                        <tfoot><tr class="fw-bold"><td colspan="8" class="text-end">รวมรับชำระ</td><td class="text-end">{{ number_format($summary['amount'], 2) }}</td></tr></tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection