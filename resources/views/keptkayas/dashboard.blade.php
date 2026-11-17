@extends('layouts.keptkaya')

@section('nav-dashboard', 'active')
@section('nav-header', 'ภาพรวมการจัดเก็บขยะ')
@section('page-topic', 'ภาพรวมระบบธนาคารขยะ')

@section('content')
<div class="app-content">
    <div class="container-fluid py-2">
        
        <!-- Modern Hero Header Card (v0 Enterprise Pattern) -->
        <div class="card border-0 shadow-sm mb-4 border-radius-xl overflow-hidden bg-white" style="border: 1px solid rgba(226, 232, 240, 0.9);">
            <div class="card-body p-4 position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-gradient-primary rounded-pill px-3 py-1 font-weight-bold text-xs">
                                <i class="fas fa-recycle me-1"></i> ระบบธนาคารขยะรีไซเคิล
                            </span>
                            <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs">
                                <i class="far fa-calendar-alt me-1 text-primary"></i> ปีงบประมาณ 2567
                            </span>
                        </div>
                        <h3 class="font-weight-bolder text-dark mb-1" style="letter-spacing: -0.5px;">
                            ระบบบริหารจัดการค่าขยะมูลฝอยและขยะรีไซเคิล
                        </h3>
                        <p class="text-sm text-secondary mb-0">
                            ติดตามความคืบหน้าการจัดเก็บ ยอดรับชำระ และข้อมูลครัวเรือนในเขตเทศบาลแบบเรียลไทม์
                        </p>
                    </div>
                    <div class="col-lg-5 col-md-4 text-md-end mt-3 mt-md-0">
                        <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                            <a href="{{ route('keptkayas.purchase.select_route') }}" class="btn btn-sm bg-gradient-primary rounded-pill mb-0 px-3 py-2 shadow-xs hover-elevate">
                                <i class="fas fa-truck me-1"></i> ออกหน่วยรับซื้อขยะ
                            </a>
                            <a href="{{ route('accessmenu') }}" class="btn btn-sm btn-outline-dark rounded-pill mb-0 px-3 py-2 shadow-xs hover-elevate">
                                <i class="fas fa-th me-1"></i> เมนูรวมระบบ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4-KPI Bento Metric Cards (v0 Style with Delta Indicators) -->
        <div class="row g-3 mb-4">
            <!-- Metric 1: Annual Target -->
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-xs border-radius-xl h-100 bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-xxs text-uppercase font-weight-bold text-secondary">เป้าหมายทั้งปี</span>
                                <h4 class="font-weight-bolder text-dark mb-0 mt-1">1,250,000 ฿</h4>
                            </div>
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ni ni-money-coins text-lg opacity-10 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-xs text-muted pt-2 border-top">
                            <span>เป้าหมายงบประมาณ</span>
                            <span class="badge bg-light text-primary border rounded-pill px-2 py-0">คงที่</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Collected with Progress -->
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-xs border-radius-xl h-100 bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-xxs text-uppercase font-weight-bold text-secondary">รับชำระแล้ว</span>
                                <h4 class="font-weight-bolder text-success mb-0 mt-1">900,000 ฿</h4>
                            </div>
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ni ni-check-bold text-lg opacity-10 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="pt-2 border-top">
                            <div class="d-flex align-items-center justify-content-between text-xs mb-1">
                                <span class="text-success font-weight-bold"><i class="fas fa-arrow-up me-1"></i>72%</span>
                                <span class="text-muted">ความคืบหน้า</span>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-gradient-success" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Pending Balance -->
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-xs border-radius-xl h-100 bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-xxs text-uppercase font-weight-bold text-secondary">ค้างชำระ (ต้องติดตาม)</span>
                                <h4 class="font-weight-bolder text-danger mb-0 mt-1">350,000 ฿</h4>
                            </div>
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ni ni-fat-remove text-lg opacity-10 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-xs text-muted pt-2 border-top">
                            <span>คิดเป็น 28%</span>
                            <span class="badge bg-danger-soft text-danger border rounded-pill px-2 py-0">ติดตามเร่งด่วน</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric 4: Households in System -->
            <div class="col-xl-3 col-sm-6">
                <div class="card border-0 shadow-xs border-radius-xl h-100 bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="text-xxs text-uppercase font-weight-bold text-secondary">ครัวเรือนในระบบ</span>
                                <h4 class="font-weight-bolder text-dark mb-0 mt-1">2,500 ราย</h4>
                            </div>
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="ni ni-building text-lg opacity-10 text-white" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between text-xs text-muted pt-2 border-top">
                            <span><span class="text-success font-weight-bold">1,800</span> จ่ายแล้ว</span>
                            <span><span class="text-danger font-weight-bold">700</span> ค้างจ่าย</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row (7:5 Split View) -->
        <div class="row g-3 mb-4">
            <!-- Left Chart: Payments by Zone -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-xs border-radius-xl h-100 bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-header pb-0 pt-3 bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">สถิติการชำระเงินแยกตามหมู่บ้าน</h6>
                            <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-arrow-up text-success me-1"></i>
                                <span class="font-weight-bold text-dark">หมู่ที่ 4</span> มียอดชำระเงินสูงสุดในปีนี้ (50,000 บาท)
                            </p>
                        </div>
                        <span class="badge bg-light text-secondary border rounded-pill px-3 text-xs">หน่วย: บาท</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart position-relative" style="height: 280px;">
                            <canvas id="chart-zone-payment" class="chart-canvas" height="280"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Chart: Payment Status Doughnut -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-xs border-radius-xl h-100 bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-header pb-0 pt-3 bg-transparent border-0">
                        <h6 class="font-weight-bold text-dark mb-0">สัดส่วนผู้ชำระแล้ว vs ค้างชำระ</h6>
                        <p class="text-xs text-secondary mb-0">ภาพรวมสัดส่วนครัวเรือนทั้งหมด 2,500 ราย</p>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-center">
                        <div class="chart position-relative" style="height: 230px;">
                            <canvas id="chart-status-pie" class="chart-canvas" height="230"></canvas>
                        </div>
                        <div class="d-flex justify-content-center gap-4 mt-3 pt-2 border-top text-xs">
                            <div class="d-flex align-items-center">
                                <span class="badge badge-dot me-2 bg-success"></span>
                                <span class="text-dark font-weight-bold">ชำระแล้ว: 1,800 ราย (72%)</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge badge-dot me-2 bg-danger"></span>
                                <span class="text-dark font-weight-bold">ค้างชำระ: 700 ราย (28%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top 5 Zones Summary Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-xs border-radius-xl bg-white" style="border: 1px solid rgba(226, 232, 240, 0.8);">
                    <div class="card-header pb-2 p-3 bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h6 class="font-weight-bold text-dark mb-0">สรุปยอดจัดเก็บแยกตามโซน (Top 5)</h6>
                            <p class="text-xs text-secondary mb-0">เรียงตามยอดจัดเก็บรวมสูงสุดในรอบปีงบประมาณปัจจุบัน</p>
                        </div>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs">
                            <i class="fas fa-layer-group me-1 text-primary"></i> 5 อันดับแรก
                        </span>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">ชื่อหมู่บ้าน/โซน</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">จำนวนลูกหนี้คงเหลือ</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ยอดรับชำระแล้ว (บาท)</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ความคืบหน้า</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-3">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover-elevate">
                                    <td class="ps-3">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar avatar-sm rounded-circle bg-gradient-primary text-white me-2 d-flex align-items-center justify-content-center text-xs font-weight-bold">
                                                1
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">หมู่ที่ 4 บ้านนาเจริญ</h6>
                                                <span class="text-xxs text-secondary">โซน 4</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-danger-soft text-danger border rounded-pill px-2">8 ราย (ค้าง)</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bolder text-dark">50,000 ฿</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="d-flex justify-content-between align-items-center text-xxs mb-1">
                                                <span class="text-success font-weight-bold">92%</span>
                                                <span class="text-muted">เป้าหมาย</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-gradient-success w-92" role="progressbar" style="width: 92%" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center pe-3">
                                        <span class="badge badge-sm bg-gradient-success rounded-pill px-3">เป้าหมายเกือบครบ</span>
                                    </td>
                                </tr>
                                <tr class="hover-elevate">
                                    <td class="ps-3">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar avatar-sm rounded-circle bg-gradient-primary text-white me-2 d-flex align-items-center justify-content-center text-xs font-weight-bold">
                                                2
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">หมู่ที่ 1 บ้านเหนือ</h6>
                                                <span class="text-xxs text-secondary">โซน 1</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-danger-soft text-danger border rounded-pill px-2">15 ราย (ค้าง)</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bolder text-dark">45,000 ฿</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="d-flex justify-content-between align-items-center text-xxs mb-1">
                                                <span class="text-success font-weight-bold">80%</span>
                                                <span class="text-muted">เป้าหมาย</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-gradient-info w-80" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center pe-3">
                                        <span class="badge badge-sm bg-gradient-info rounded-pill px-3">เป็นไปตามแผน</span>
                                    </td>
                                </tr>
                                <tr class="hover-elevate">
                                    <td class="ps-3">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar avatar-sm rounded-circle bg-gradient-primary text-white me-2 d-flex align-items-center justify-content-center text-xs font-weight-bold">
                                                3
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">หมู่ที่ 6 บ้านหนองผือ</h6>
                                                <span class="text-xxs text-secondary">โซน 6</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-danger-soft text-danger border rounded-pill px-2">22 ราย (ค้าง)</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bolder text-dark">40,000 ฿</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="d-flex justify-content-between align-items-center text-xxs mb-1">
                                                <span class="text-info font-weight-bold">75%</span>
                                                <span class="text-muted">เป้าหมาย</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-gradient-info w-75" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center pe-3">
                                        <span class="badge badge-sm bg-gradient-info rounded-pill px-3">เป็นไปตามแผน</span>
                                    </td>
                                </tr>
                                <tr class="hover-elevate">
                                    <td class="ps-3">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar avatar-sm rounded-circle bg-gradient-primary text-white me-2 d-flex align-items-center justify-content-center text-xs font-weight-bold">
                                                4
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">หมู่ที่ 5 บ้านดอน</h6>
                                                <span class="text-xxs text-secondary">โซน 5</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-danger-soft text-danger border rounded-pill px-2">35 ราย (ค้าง)</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bolder text-dark">30,000 ฿</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="d-flex justify-content-between align-items-center text-xxs mb-1">
                                                <span class="text-warning font-weight-bold">55%</span>
                                                <span class="text-muted">เป้าหมาย</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-gradient-warning w-55" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center pe-3">
                                        <span class="badge badge-sm bg-gradient-warning rounded-pill px-3">ต้องเร่งรัด</span>
                                    </td>
                                </tr>
                                <tr class="hover-elevate">
                                    <td class="ps-3">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="avatar avatar-sm rounded-circle bg-gradient-primary text-white me-2 d-flex align-items-center justify-content-center text-xs font-weight-bold">
                                                5
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">หมู่ที่ 2 บ้านใต้</h6>
                                                <span class="text-xxs text-secondary">โซน 2</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-danger-soft text-danger border rounded-pill px-2">50 ราย (ค้าง)</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-sm font-weight-bolder text-dark">20,000 ฿</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="d-flex justify-content-between align-items-center text-xxs mb-1">
                                                <span class="text-danger font-weight-bold">35%</span>
                                                <span class="text-muted">เป้าหมาย</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-gradient-danger w-35" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center pe-3">
                                        <span class="badge badge-sm bg-gradient-danger rounded-pill px-3">ต้องติดตามด่วน</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('soft-ui/assets/js/plugins/chartjs.min.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Check if Chart is available
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js not loaded');
        return;
    }

    // --- Chart 1: Bar Chart (Payment by Zone) ---
    var elChart1 = document.getElementById("chart-zone-payment");
    if (elChart1) {
        var ctx1 = elChart1.getContext("2d");
        new Chart(ctx1, {
            type: "bar",
            data: {
                labels: ["หมู่ 1", "หมู่ 2", "หมู่ 3", "หมู่ 4", "หมู่ 5", "หมู่ 6"],
                datasets: [{
                    label: "ยอดชำระแล้ว (บาท)",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false,
                    backgroundColor: "#0ea5e9", // Sky-500
                    hoverBackgroundColor: "#0284c7",
                    data: [45000, 20000, 15000, 50000, 30000, 40000],
                    maxBarThickness: 16
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'ยอดชำระ: ' + Number(context.raw).toLocaleString() + ' บาท';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                            color: '#e2e8f0'
                        },
                        ticks: {
                            display: true,
                            padding: 8,
                            color: '#64748b', // Slate 500
                            font: { size: 12, family: "Kanit" },
                            callback: function(value) {
                                return (value / 1000) + 'k';
                            }
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: true,
                            color: '#64748b',
                            padding: 10,
                            font: { size: 12, family: "Kanit" }
                        }
                    },
                },
            },
        });
    }

    // --- Chart 2: Doughnut Chart (Payment Status) ---
    var elChart2 = document.getElementById("chart-status-pie");
    if (elChart2) {
        var ctx2 = elChart2.getContext("2d");
        new Chart(ctx2, {
            type: "doughnut",
            data: {
                labels: ["ชำระแล้ว (1,800 ราย)", "ค้างชำระ (700 ราย)"],
                datasets: [{
                    label: "จำนวน (ราย)",
                    cutout: "70%",
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    backgroundColor: ['#10b981', '#f43f5e'], // Emerald-500, Rose-500
                    hoverBackgroundColor: ['#059669', '#e11d48'],
                    data: [1800, 700],
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + Number(context.raw).toLocaleString() + ' ครัวเรือน';
                            }
                        }
                    }
                }
            },
        });
    }
});
</script>
@endsection
