@extends('layouts.admin1')

@section('nav-header', 'ผังเมือง & สาธารณูปโภค')
@section('nav-main', 'สรุปและรายงานสถิติ (GIS Analytics)')
@section('nav-topic')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="font-weight-bolder mb-0 text-dark">
                <i class="fas fa-chart-line text-primary me-2"></i> สรุปและรายงานสถิติสาธารณูปโภค (GIS Analytics)
            </h5>
            <p class="text-xs text-muted mb-0">รายงานภาพรวมสินทรัพย์ งานซ่อมบำรุง และบัญชีคุมทรัพย์สินโครงสร้างพื้นฐาน</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-outline-dark mb-0" onclick="window.print()">
                <i class="fas fa-print me-1"></i> พิมพ์รายงาน
            </button>
            <a href="{{ route('smart_utility.elec') }}" class="btn btn-sm btn-primary mb-0">
                <i class="fas fa-map-marked-alt me-1"></i> เปิดดูแผนที่ GIS
            </a>
        </div>
    </div>
@endsection

@section('styles')
<style>
    @media print {
        .sidenav, .navbar, .btn, .no-print {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3">

    <!-- KPI Summary Row across 4 Modules -->
    <div class="row g-2 mb-3">
        <!-- Elec -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-muted">💡 ระบบไฟฟ้าสาธารณะ</span>
                        <a href="{{ route('smart_utility.elec') }}" class="text-xs text-primary font-weight-bold">ดูแผนที่ &rarr;</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-lightbulb text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bolder mb-0 text-dark">{{ number_format($countsByModule['elec'] ?? 0) }} <small class="text-xs text-muted">จุด</small></h4>
                            <p class="text-xs text-muted mb-0">เสาไฟ / โคมไฟส่องสว่าง</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Water -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-muted">💧 ระบบประปาและแหล่งน้ำ</span>
                        <a href="{{ route('smart_utility.water') }}" class="text-xs text-info font-weight-bold">ดูแผนที่ &rarr;</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-tint text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bolder mb-0 text-dark">{{ number_format($countsByModule['water'] ?? 0) }} <small class="text-xs text-muted">จุด</small></h4>
                            <p class="text-xs text-muted mb-0">มิเตอร์น้ำ / ประตูน้ำ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Road -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-muted">🛣️ ถนน & ทางระบายน้ำ</span>
                        <a href="{{ route('smart_utility.road') }}" class="text-xs text-secondary font-weight-bold">ดูแผนที่ &rarr;</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-secondary shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-road text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bolder mb-0 text-dark">{{ number_format($countsByModule['road'] ?? 0) }} <small class="text-xs text-muted">จุด</small></h4>
                            <p class="text-xs text-muted mb-0">เส้นทาง / ท่อระบายน้ำ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bldg -->
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-muted">🏢 อาคาร & สิ่งปลูกสร้าง</span>
                        <a href="{{ route('smart_utility.bldg') }}" class="text-xs text-primary font-weight-bold">ดูแผนที่ &rarr;</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-building text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-weight-bolder mb-0 text-dark">{{ number_format($countsByModule['bldg'] ?? 0) }} <small class="text-xs text-muted">แห่ง</small></h4>
                            <p class="text-xs text-muted mb-0">สถานที่และอาคารราชการ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Performance & Asset Health Status -->
    <div class="row g-2 mb-3">
        <div class="col-lg-6">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0 border-bottom">
                    <h6 class="font-weight-bold text-dark mb-1">
                        <i class="fas fa-tasks text-primary me-2"></i> ประสิทธิภาพการดำเนินงานซ่อมบำรุง
                    </h6>
                    <p class="text-xs text-muted mb-2">สรุปสถานะใบแจ้งเหตุและความคืบหน้าของกองช่าง</p>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 text-center mb-3">
                        <div class="col-4">
                            <div class="p-2 border rounded bg-light">
                                <span class="text-xs text-danger font-weight-bold">รอรับเรื่อง</span>
                                <h4 class="font-weight-bolder text-danger mb-0">{{ number_format($ticketsSummary['open'] ?? 0) }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded bg-light">
                                <span class="text-xs text-warning font-weight-bold">กำลังซ่อม</span>
                                <h4 class="font-weight-bolder text-warning mb-0">{{ number_format($ticketsSummary['in_progress'] ?? 0) }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 border rounded bg-light">
                                <span class="text-xs text-success font-weight-bold">เสร็จเรียบร้อย</span>
                                <h4 class="font-weight-bolder text-success mb-0">{{ number_format($ticketsSummary['resolved'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>

                    @php
                        $tot = $ticketsSummary['total'] ?? 0;
                        $res = $ticketsSummary['resolved'] ?? 0;
                        $rate = $tot > 0 ? round(($res / $tot) * 100) : 100;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between text-xs font-weight-bold mb-1">
                            <span>อัตราการแก้ปัญหาเสร็จสิ้น (Resolution Rate)</span>
                            <span>{{ $rate }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $rate }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-xs border h-100">
                <div class="card-header pb-0 border-bottom">
                    <h6 class="font-weight-bold text-dark mb-1">
                        <i class="fas fa-heartbeat text-success me-2"></i> ความสมบูรณ์ของระบบทรัพย์สิน (Asset Condition)
                    </h6>
                    <p class="text-xs text-muted mb-2">สัดส่วนสภาพการใช้งานของอุปกรณ์ทั้งหมด</p>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-dark">🟢 ใช้งานได้ปกติ (Good)</span>
                        <span class="badge bg-success">{{ number_format($countsByStatus['good'] ?? 0) }} จุด</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-dark">🔴 ชำรุด / เสียหาย / ดับ (Bad)</span>
                        <span class="badge bg-danger">{{ number_format($countsByStatus['bad'] ?? 0) }} จุด</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-xs font-weight-bold text-dark">🟡 อยู่ระหว่างรอตรวจสอบ / เฝ้าระวัง (Wait)</span>
                        <span class="badge bg-warning">{{ number_format($countsByStatus['wait'] ?? 0) }} จุด</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Ledger Table -->
    <div class="card shadow-xs border mb-4">
        <div class="card-header pb-0 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="font-weight-bold text-dark mb-1">
                    <i class="fas fa-book text-primary me-2"></i> ทะเบียนคุมทรัพย์สินสาธารณูปโภค (Asset Inventory Ledger)
                </h6>
                <p class="text-xs text-muted mb-2">รายการจุดพิกัด รหัสทรัพย์สิน สถานะ และพิกัดภูมิสารสนเทศ</p>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead class="bg-gray-100 text-xs font-weight-bold text-dark">
                        <tr>
                            <th class="ps-3">ระบบ</th>
                            <th>รหัสทรัพย์สิน</th>
                            <th>ชื่อ / จุดสังเกต</th>
                            <th>หมู่บ้าน</th>
                            <th>พิกัด (Lat, Lng)</th>
                            <th class="text-center">สถานะ</th>
                            <th class="text-end pe-3 no-print">เปิดแผนที่</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @forelse($items as $item)
                            <tr>
                                <td class="ps-3">
                                    @if($item->module === 'elec')
                                        <span class="badge bg-warning text-dark">💡 ไฟฟ้า</span>
                                    @elseif($item->module === 'water')
                                        <span class="badge bg-info text-white">💧 ประปา</span>
                                    @elseif($item->module === 'road')
                                        <span class="badge bg-secondary text-white">🛣️ ถนน</span>
                                    @else
                                        <span class="badge bg-primary text-white">🏢 อาคาร</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-dark">{{ $item->item_code }}</td>
                                <td>{{ $item->name ?: ($item->item_type ?: '-') }}</td>
                                <td>{{ $item->village ?: '-' }}</td>
                                <td class="text-muted">{{ number_format($item->lat, 5) }}, {{ number_format($item->lng, 5) }}</td>
                                <td class="text-center">
                                    @if($item->status === 'good')
                                        <span class="badge bg-success">ปกติ</span>
                                    @elseif($item->status === 'bad')
                                        <span class="badge bg-danger">ชำรุด</span>
                                    @else
                                        <span class="badge bg-warning">รอตรวจ</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3 no-print">
                                    <a href="{{ route('smart_utility.' . $item->module) }}" class="btn btn-xs btn-outline-primary mb-0">
                                        <i class="fas fa-crosshairs me-1"></i> ดูบนผัง
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">ยังไม่มีรายการทรัพย์สินในระบบ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($items, 'links'))
                <div class="p-3 border-top d-flex justify-content-end no-print">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Tickets Log -->
    <div class="card shadow-xs border mb-4">
        <div class="card-header pb-0 border-bottom">
            <h6 class="font-weight-bold text-dark mb-1">
                <i class="fas fa-history text-secondary me-2"></i> ประวัติรายการแจ้งเหตุและงานซ่อมล่าสุด
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead class="bg-gray-100 text-xs font-weight-bold text-dark">
                        <tr>
                            <th class="ps-3">รหัสงาน</th>
                            <th>ระบบ</th>
                            <th>จุดอ้างอิง</th>
                            <th>รายละเอียดปัญหา</th>
                            <th>สถานที่</th>
                            <th class="text-center">สถานะ</th>
                            <th>วันที่แจ้ง</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @forelse($recentTickets as $tk)
                            <tr>
                                <td class="ps-3 font-weight-bold text-dark">{{ $tk->ticket_code }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $tk->module === 'elec' ? '💡 ไฟฟ้า' : ($tk->module === 'water' ? '💧 ประปา' : ($tk->module === 'road' ? '🛣️ ถนน' : '🏢 อาคาร')) }}
                                    </span>
                                </td>
                                <td>{{ $tk->ref_code ?: '-' }}</td>
                                <td class="text-dark font-weight-bold">{{ $tk->detail }}</td>
                                <td>{{ $tk->location ?: ($tk->village ?: '-') }}</td>
                                <td class="text-center">
                                    @if($tk->status === 'open')
                                        <span class="badge bg-danger">รอรับเรื่อง</span>
                                    @elseif($tk->status === 'in_progress')
                                        <span class="badge bg-warning">กำลังซ่อม</span>
                                    @elseif($tk->status === 'resolved')
                                        <span class="badge bg-success">ซ่อมเสร็จแล้ว</span>
                                    @else
                                        <span class="badge bg-secondary">ยกเลิก</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $tk->created_at ? $tk->created_at->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">ไม่มีประวัติงานซ่อม</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

