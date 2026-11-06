@extends('layouts.soft-ui-pro')

@section('nav-topic', 'ภาพรวมระบบบริหารจัดการสิ่งแวดล้อมและบริการสาธารณะ')
@section('nav-header', 'หน้าแรก')
@section('nav-main', 'แดชบอร์ดหลัก')

@section('content')
<div class="row">
    <!-- Stat Cards Row -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <x-soft-ui.stat-card
            title="ยอดเงินกองทุนสวัสดิการ"
            value="฿245,890"
            percentage="+15.3%"
            percentageColor="text-success"
            percentageText="จากเดือนก่อนหน้า"
            icon="fas fa-coins"
            gradient="primary"
        />
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <x-soft-ui.stat-card
            title="จำนวนสมาชิกธนาคารขยะ"
            value="1,420 คน"
            percentage="+8.2%"
            percentageColor="text-success"
            percentageText="สมาชิกใหม่ 45 คน"
            icon="fas fa-users"
            gradient="info"
        />
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <x-soft-ui.stat-card
            title="ปริมาณขยะรีไซเคิลสะสม"
            value="8,740 กก."
            percentage="+22.4%"
            percentageColor="text-success"
            percentageText="ลดคาร์บอน 12.5 ตัน"
            icon="fas fa-recycle"
            gradient="success"
        />
    </div>
    <div class="col-xl-3 col-sm-6">
        <x-soft-ui.stat-card
            title="ตู้รับซื้อ AI Kiosk ออนไลน์"
            value="6 / 6 ตู้"
            percentage="100%"
            percentageColor="text-success"
            percentageText="พร้อมใช้งานทุกจุด"
            icon="fas fa-robot"
            gradient="dark"
        />
    </div>
</div>

<!-- Alert Banner -->
<div class="row mt-4">
    <div class="col-12">
        <x-soft-ui.alert color="success" icon="fas fa-check-circle" :dismissible="true">
            <strong>ระบบอัปเดตสำเร็จ!</strong> สถาปัตยกรรม Vertical Slice Architecture (VSA) และ Soft UI Dashboard PRO พร้อมใช้งานเต็มรูปแบบ
        </x-soft-ui.alert>
    </div>
</div>

<!-- Main Table and Info Cards -->
<div class="row mt-4">
    <div class="col-lg-8 col-md-12 mb-4">
        @php
            $headers = ['รหัสบัญชี', 'ชื่อ-นามสกุล ประชาชน', 'เบอร์โทรศัพท์', 'คะแนนสะสม', 'ยอดเงินคงเหลือ', 'สถานะ', 'จัดการ'];
            $citizens = [
                [
                    'code' => 'ACC-0001-000001',
                    'name' => 'นายธนากร พัฒนาเมือง',
                    'email' => 'thanakorn@envsogo.test',
                    'phone' => '081-999-8888',
                    'points' => '350 แต้ม',
                    'points_badge' => 'bg-gradient-info',
                    'balance' => '฿1,750.00',
                    'balance_color' => 'text-success',
                    'status' => 'ใช้งานปกติ',
                    'status_color' => 'success',
                ],
                [
                    'code' => 'ACC-0001-000002',
                    'name' => 'นางสาวสมหญิง สดใส',
                    'email' => 'somying@envsogo.test',
                    'phone' => '082-123-4567',
                    'points' => '1,240 แต้ม',
                    'points_badge' => 'bg-gradient-info',
                    'balance' => '฿6,200.00',
                    'balance_color' => 'text-success',
                    'status' => 'ใช้งานปกติ',
                    'status_color' => 'success',
                ],
                [
                    'code' => 'ACC-0001-000003',
                    'name' => 'นายวิชัย ใจบริการ',
                    'email' => 'wichai@envsogo.test',
                    'phone' => '089-876-5432',
                    'points' => '0 แต้ม',
                    'points_badge' => 'bg-gradient-secondary',
                    'balance' => '฿0.00',
                    'balance_color' => 'text-muted',
                    'status' => 'รอยืนยันตัวตน',
                    'status_color' => 'warning',
                ],
            ];
        @endphp
        <x-soft-ui.datatable id="citizenSummaryTable" :headers="$headers">
            <x-slot:actions>
                <x-soft-ui.button color="primary" size="sm" icon="fas fa-user-plus" class="w-100 w-sm-auto">
                    ลงทะเบียนประชาชนใหม่
                </x-soft-ui.button>
                <x-soft-ui.button color="info" size="sm" :outline="true" icon="fas fa-file-export" class="w-100 w-sm-auto">
                    ส่งออก Excel
                </x-soft-ui.button>
            </x-slot:actions>

            @foreach ($citizens as $citizen)
                <tr>
                    <td><span class="text-xs font-weight-bold">{{ $citizen['code'] }}</span></td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-xs">{{ $citizen['name'] }}</h6>
                                <p class="text-xs text-secondary mb-0">{{ $citizen['email'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td><span class="text-xs font-weight-bold">{{ $citizen['phone'] }}</span></td>
                    <td><span class="badge badge-sm {{ $citizen['points_badge'] }}">{{ $citizen['points'] }}</span></td>
                    <td><span class="text-xs font-weight-bold {{ $citizen['balance_color'] }}">{{ $citizen['balance'] }}</span></td>
                    <td><x-soft-ui.badge :color="$citizen['status_color']">{{ $citizen['status'] }}</x-soft-ui.badge></td>
                    <td>
                        <button class="btn btn-link text-secondary mb-0" aria-label="ดูรายละเอียด"><i class="fas fa-eye text-xs"></i></button>
                        <button class="btn btn-link text-dark mb-0" aria-label="แก้ไขข้อมูล"><i class="fas fa-edit text-xs"></i></button>
                    </td>
                </tr>
            @endforeach

            <x-slot:mobileCards>
                <div class="d-flex flex-column gap-3">
                    @foreach ($citizens as $citizen)
                        <div class="card shadow-none border border-light-subtle rounded-3 p-3 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                                <span class="badge bg-light text-dark text-xxs font-weight-bolder">
                                    <i class="fas fa-id-card me-1 text-primary"></i> {{ $citizen['code'] }}
                                </span>
                                <x-soft-ui.badge :color="$citizen['status_color']">{{ $citizen['status'] }}</x-soft-ui.badge>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar avatar-sm bg-gradient-primary rounded-circle me-2 d-flex align-items-center justify-content-center text-white text-xs font-weight-bold">
                                    {{ mb_substr($citizen['name'], 0, 1) }}
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="mb-0 text-sm font-weight-bold text-dark text-truncate">{{ $citizen['name'] }}</h6>
                                    <p class="text-xs text-secondary mb-0 text-truncate">
                                        <i class="fas fa-envelope me-1 opacity-6"></i> {{ $citizen['email'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="row g-2 p-2 bg-gray-100 rounded-3 mb-2 text-center">
                                <div class="col-4 border-end border-light">
                                    <span class="text-xxs text-secondary d-block font-weight-bold">เบอร์โทร</span>
                                    <span class="text-xs font-weight-bold text-dark text-truncate d-block">{{ $citizen['phone'] }}</span>
                                </div>
                                <div class="col-4 border-end border-light">
                                    <span class="text-xxs text-secondary d-block font-weight-bold">คะแนน</span>
                                    <span class="badge badge-sm {{ $citizen['points_badge'] }}">{{ $citizen['points'] }}</span>
                                </div>
                                <div class="col-4">
                                    <span class="text-xxs text-secondary d-block font-weight-bold">ยอดเงิน</span>
                                    <span class="text-xs font-weight-bold {{ $citizen['balance_color'] }} text-truncate d-block">{{ $citizen['balance'] }}</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 pt-1">
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-0 px-3 py-1 text-xs" aria-label="ดูรายละเอียด">
                                    <i class="fas fa-eye me-1"></i> ดูข้อมูล
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm mb-0 px-3 py-1 text-xs" aria-label="แก้ไขข้อมูล">
                                    <i class="fas fa-edit me-1"></i> แก้ไข
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-slot:mobileCards>
        </x-soft-ui.datatable>
    </div>

    <!-- Quick Actions Card -->
    <div class="col-lg-4 col-md-12">
        <x-soft-ui.card title="เมนูด่วนและบริการประชาชน">
            <p class="text-xs text-secondary mb-3">เข้าถึงฟีเจอร์หลักของแต่ละ Bounded Context ได้อย่างรวดเร็ว</p>
            <div class="d-grid gap-2">
                <x-soft-ui.button color="primary" class="w-100 text-start" icon="fas fa-tint">
                    ระบบประปาหมู่บ้าน (Tabwater Billing)
                </x-soft-ui.button>
                <x-soft-ui.button color="success" class="w-100 text-start" icon="fas fa-recycle">
                    ธนาคารขยะรีไซเคิล (KeptKaya Bank)
                </x-soft-ui.button>
                <x-soft-ui.button color="info" class="w-100 text-start" icon="fas fa-robot">
                    ตู้รับซื้อ AIroBacT AI Kiosk
                </x-soft-ui.button>
                <x-soft-ui.button color="warning" class="w-100 text-start" icon="fas fa-hand-holding-heart">
                    กองทุนสวัสดิการชุมชน (Welfare Fund)
                </x-soft-ui.button>
            </div>
        </x-soft-ui.card>
    </div>
</div>
@endsection
