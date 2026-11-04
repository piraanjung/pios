@extends('annual_trash.layout')

@section('nav-dashboard', 'active')
@section('nav-header', 'จัดเก็บค่าขยะรายปี')
@section('nav-main', 'ภาพรวม')
@section('nav-current', 'Dashboard')
@section('page-topic', 'Dashboard จัดเก็บค่าขยะรายปี')

@section('style')
    <style>
        .annual-trash-dashboard { margin-top: -2.5rem; position: relative; z-index: 1; }
        .annual-trash-dashboard .metric-card { height: 100%; border: 1px solid #e9ecef; border-radius: .75rem; }
        .annual-trash-dashboard .metric-label { color: #6c757d; font-size: .82rem; }
        .annual-trash-dashboard .metric-value { color: #263238; font-size: 1.35rem; font-weight: 700; }
        .annual-trash-dashboard .metric-icon { width: 42px; height: 42px; display: grid; place-items: center; border-radius: .65rem; }
        .annual-trash-dashboard .filter-panel { border: 1px solid #e9ecef; border-radius: .75rem; }
        .annual-trash-dashboard .table thead th { color: #65717b; font-size: .75rem; text-transform: uppercase; white-space: nowrap; }
        .annual-trash-dashboard .table td { vertical-align: middle; }
        .annual-trash-dashboard .status-pill { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .6rem; border-radius: 2rem; font-size: .75rem; font-weight: 600; white-space: nowrap; }
        .annual-trash-dashboard .status-paid { color: #157347; background: #e8f5ee; }
        .annual-trash-dashboard .status-pending { color: #8a5a00; background: #fff4d6; }
        .annual-trash-dashboard .status-overdue { color: #b42318; background: #fdebea; }
        .annual-trash-dashboard .status-cancelled { color: #667085; background: #eef0f2; }
        @media (max-width: 767px) {
            .annual-trash-dashboard { margin-top: -1.5rem; }
            .annual-trash-dashboard .metric-value { font-size: 1.15rem; }
        }
        @media print {
            .annual-trash-dashboard .no-print { display: none !important; }
            .annual-trash-dashboard { margin-top: 0; }
        }
    </style>
@endsection

@section('content')
    @php
        $collectionRate = $summary['amount_due'] > 0
            ? min(100, ($summary['amount_paid'] / $summary['amount_due']) * 100)
            : 0;
    @endphp

    <div class="annual-trash-dashboard">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
            <div>
                <p class="text-secondary text-sm mb-1">ปีงบประมาณ พ.ศ. {{ $fiscalYear }}</p>
                <h4 class="mb-1">ภาพรวมการจัดเก็บค่าขยะรายปี</h4>
                <p class="text-secondary text-sm mb-0">ติดตามยอดประเมิน การชำระ และรายการเกินกำหนด</p>
            </div>
            <a href="{{ route('keptkayas.annual_payments.index', ['fy' => $fiscalYear]) }}" class="btn btn-primary mb-0">
                <i class="fas fa-cash-register me-1"></i>ไปหน้ารับชำระ
            </a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl">
                <div class="card metric-card shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="metric-label">รายการเรียกเก็บ</div>
                                <div class="metric-value mt-1">{{ number_format($summary['accounts']) }}</div>
                            </div>
                            <div class="metric-icon bg-light text-secondary"><i class="fas fa-file-invoice"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl">
                <div class="card metric-card shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="metric-label">ยอดประเมิน</div>
                                <div class="metric-value mt-1">฿{{ number_format($summary['amount_due'], 2) }}</div>
                            </div>
                            <div class="metric-icon bg-primary-subtle text-primary"><i class="fas fa-coins"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl">
                <div class="card metric-card shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="metric-label">รับชำระแล้ว</div>
                                <div class="metric-value mt-1 text-success">฿{{ number_format($summary['amount_paid'], 2) }}</div>
                            </div>
                            <div class="metric-icon bg-success-subtle text-success"><i class="fas fa-circle-check"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl">
                <div class="card metric-card shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="metric-label">ยอดคงค้าง</div>
                                <div class="metric-value mt-1 text-warning">฿{{ number_format($summary['outstanding'], 2) }}</div>
                            </div>
                            <div class="metric-icon bg-warning-subtle text-warning"><i class="fas fa-hourglass-half"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl">
                <div class="card metric-card shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="metric-label">รอชำระ / ชำระบางส่วน</div>
                                <div class="metric-value mt-1 text-warning">{{ number_format($summary['pending']) }} รายการ</div>
                            </div>
                            <div class="metric-icon bg-danger-subtle text-danger"><i class="fas fa-calendar-xmark"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card filter-panel shadow-sm mb-4 no-print">
            <div class="card-body p-3">
                <form action="{{ route('annual_trash.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-6 col-lg-2">
                        <label for="fiscal_year" class="form-label text-sm">ปีงบประมาณ</label>
                        <select id="fiscal_year" name="fiscal_year" class="form-select">
                            @foreach ($years as $availableYear)
                                <option value="{{ $availableYear }}" @selected((int) $availableYear === (int) $fiscalYear)>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-lg-2">
                        <label for="status" class="form-label text-sm">สถานะ</label>
                        <select id="status" name="status" class="form-select">
                            <option value="all" @selected($status === 'all')>ทุกสถานะ</option>
                            <option value="pending" @selected($status === 'pending')>รอชำระ</option>
                            <option value="partially_paid" @selected($status === 'partially_paid')>ชำระบางส่วน</option>
                            <option value="paid" @selected($status === 'paid')>ชำระครบ</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-5">
                        <label for="search" class="form-label text-sm">ค้นหาสมาชิก</label>
                        <input id="search" name="search" type="search" class="form-control" value="{{ $search }}" placeholder="ชื่อ นามสกุล หรือเบอร์โทร">
                    </div>
                    <div class="col-12 col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-dark mb-0 flex-grow-1"><i class="fas fa-filter me-1"></i>กรอง</button>
                        <a href="{{ route('annual_trash.index') }}" class="btn btn-outline-secondary mb-0">ล้าง</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
            <h5 class="mb-0">รายการค่าขยะรายปี</h5>
            <span class="text-secondary text-sm">{{ number_format($subscriptions->total()) }} รายการ</span>
        </div>

        <div class="card border shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>สมาชิก / ถังขยะ</th>
                            <th class="text-center">ครั้งที่ชำระ</th>
                            <th class="text-end">ยอดเรียกเก็บ</th>
                            <th class="text-end">ชำระแล้ว</th>
                            <th class="text-end">คงค้าง</th>
                            <th class="text-end">อัตรารายเดือน</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subscriptions as $subscription)
                            @php
                                $member = $subscription->user;
                                $bin = $subscription->AnnualTrash;
                                $balance = max(0, (float) $subscription->annual_fee - (float) $subscription->total_paid_amt);
                                if ($subscription->status === 'paid' || $balance <= 0) {
                                    $statusClass = 'status-paid';
                                    $statusLabel = 'ชำระครบ';
                                } elseif ($subscription->status === 'partially_paid' || (float) $subscription->total_paid_amt > 0) {
                                    $statusClass = 'status-pending';
                                    $statusLabel = 'ชำระบางส่วน';
                                } else {
                                    $statusClass = 'status-pending';
                                    $statusLabel = 'รอชำระ';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $member ? trim(($member->prefix ?? '') . ' ' . ($member->firstname ?? '') . ' ' . ($member->lastname ?? '')) : 'ไม่พบข้อมูลสมาชิก' }}</div>
                                    <small class="text-secondary">{{ $member->phone ?? '-' }} · ถัง {{ $bin->bin_code ?? '-' }}</small>
                                </td>
                                <td class="text-center">{{ number_format($subscription->payments->count()) }}</td>
                                <td class="text-end">฿{{ number_format((float) $subscription->annual_fee, 2) }}</td>
                                <td class="text-end text-success">฿{{ number_format((float) $subscription->total_paid_amt, 2) }}</td>
                                <td class="text-end fw-semibold {{ $balance > 0 ? 'text-danger' : 'text-secondary' }}">฿{{ number_format($balance, 2) }}</td>
                                <td class="text-end">฿{{ number_format((float) $subscription->month_fee, 2) }}</td>
                                <td><span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-secondary mb-2"><i class="fas fa-receipt fa-2x"></i></div>
                                    <div class="fw-semibold">ไม่พบรายการในเงื่อนไขนี้</div>
                                    <small class="text-secondary">ลองเปลี่ยนปี สถานะ หรือคำค้นหา</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($subscriptions->hasPages())
                <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <small class="text-secondary">แสดง {{ $subscriptions->firstItem() }}–{{ $subscriptions->lastItem() }} จาก {{ number_format($subscriptions->total()) }} รายการ</small>
                    {{ $subscriptions->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection