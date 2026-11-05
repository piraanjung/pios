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
        @endphp
        <x-soft-ui.datatable id="citizenSummaryTable" :headers="$headers">
            <x-slot:actions>
                <x-soft-ui.button color="primary" size="sm" icon="fas fa-user-plus">
                    ลงทะเบียนประชาชนใหม่
                </x-soft-ui.button>
                <x-soft-ui.button color="info" size="sm" :outline="true" icon="fas fa-file-export" class="ms-2">
                    ส่งออก Excel
                </x-soft-ui.button>
            </x-slot:actions>

            <tr>
                <td><span class="text-xs font-weight-bold">ACC-0001-000001</span></td>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-xs">นายธนากร พัฒนาเมือง</h6>
                            <p class="text-xs text-secondary mb-0">thanakorn@envsogo.test</p>
                        </div>
                    </div>
                </td>
                <td><span class="text-xs font-weight-bold">081-999-8888</span></td>
                <td><span class="badge badge-sm bg-gradient-info">350 แต้ม</span></td>
                <td><span class="text-xs font-weight-bold text-success">฿1,750.00</span></td>
                <td><x-soft-ui.badge color="success">ใช้งานปกติ</x-soft-ui.badge></td>
                <td>
                    <button class="btn btn-link text-secondary mb-0"><i class="fas fa-eye text-xs"></i></button>
                    <button class="btn btn-link text-dark mb-0"><i class="fas fa-edit text-xs"></i></button>
                </td>
            </tr>
            <tr>
                <td><span class="text-xs font-weight-bold">ACC-0001-000002</span></td>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-xs">นางสาวสมหญิง สดใส</h6>
                            <p class="text-xs text-secondary mb-0">somying@envsogo.test</p>
                        </div>
                    </div>
                </td>
                <td><span class="text-xs font-weight-bold">082-123-4567</span></td>
                <td><span class="badge badge-sm bg-gradient-info">1,240 แต้ม</span></td>
                <td><span class="text-xs font-weight-bold text-success">฿6,200.00</span></td>
                <td><x-soft-ui.badge color="success">ใช้งานปกติ</x-soft-ui.badge></td>
                <td>
                    <button class="btn btn-link text-secondary mb-0"><i class="fas fa-eye text-xs"></i></button>
                    <button class="btn btn-link text-dark mb-0"><i class="fas fa-edit text-xs"></i></button>
                </td>
            </tr>
            <tr>
                <td><span class="text-xs font-weight-bold">ACC-0001-000003</span></td>
                <td>
                    <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-xs">นายวิชัย ใจบริการ</h6>
                            <p class="text-xs text-secondary mb-0">wichai@envsogo.test</p>
                        </div>
                    </div>
                </td>
                <td><span class="text-xs font-weight-bold">089-876-5432</span></td>
                <td><span class="badge badge-sm bg-gradient-secondary">0 แต้ม</span></td>
                <td><span class="text-xs font-weight-bold text-muted">฿0.00</span></td>
                <td><x-soft-ui.badge color="warning">รอยืนยันตัวตน</x-soft-ui.badge></td>
                <td>
                    <button class="btn btn-link text-secondary mb-0"><i class="fas fa-eye text-xs"></i></button>
                    <button class="btn btn-link text-dark mb-0"><i class="fas fa-edit text-xs"></i></button>
                </td>
            </tr>
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
