@extends('layouts.super-admin')

@section('nav-header', 'สมาชิก')
@section('nav-current', 'ตารางสมาชิกหลัก (Master Users)')
@section('page-topic', 'จัดการสมาชิกหลักและสิทธิ์บริการ (Master Users)')
@section('nav-keptkayas.users', 'active')

@section('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-check-input {
            width: 1.25rem !important;
            height: 1.25rem !important;
            cursor: pointer;
        }
        .table td { vertical-align: middle !important; }
    </style>
@endsection

@section('content')
<div class="container-fluid py-2">
    @if(session('success'))
        <div class="alert alert-success text-white border-0 shadow-sm mb-3 py-2" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-white border-0 shadow-sm mb-3 py-2" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header pb-0 bg-white">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h6 class="font-weight-bolder mb-0 text-dark">
                        <i class="fas fa-users text-primary me-2"></i>ศูนย์กลางทะเบียนราษฎร / สมาชิกหลัก (Master Users)
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                        ตารางหลัก (Single Source of Truth) สำหรับลงทะเบียนประชาชนก่อนกระจายเข้าสู่แต่ละโมดูลบริการ
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" id="btnSaveBatch" class="btn bg-gradient-success btn-sm mb-0">
                        <i class="fas fa-save me-1"></i> บันทึกสิทธิ์ที่เลือก
                    </button>
                    <button type="button" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#quickCreateMasterUserModal">
                        <i class="fas fa-bolt me-1"></i> เพิ่มด่วน (Quick Add)
                    </button>
                    <a href="{{ route('admin.users.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                        <i class="fas fa-user-plus me-1"></i> เพิ่มผู้ใช้ใหม่ (แบบเต็ม)
                    </a>
                </div>
            </div>

            {{-- ฟอร์มค้นหาและกรองข้อมูล (Soft UI) --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 pb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-gray-100 border-end-0"><i class="fas fa-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control ps-2"
                               placeholder="ค้นหา ชื่อ, นามสกุล, เบอร์โทร, เลขบัตรประชาชน..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="zone_id" class="form-select form-select-sm">
                        <option value="">-- ทุกโซน / ชุมชน --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="service_filter" class="form-select form-select-sm">
                        <option value="">-- บริการทั้งหมด (ทุกโมดูล) --</option>
                        <option value="tabwater" {{ request('service_filter') == 'tabwater' ? 'selected' : '' }}>💧 ประปา (Tabwater)</option>
                        <option value="recycle" {{ request('service_filter') == 'recycle' ? 'selected' : '' }}>♻️ ธนาคารขยะรีไซเคิล</option>
                        <option value="food_waste" {{ request('service_filter') == 'food_waste' ? 'selected' : '' }}>🌱 ธนาคารขยะเปียก</option>
                        <option value="annual_trash" {{ request('service_filter') == 'annual_trash' ? 'selected' : '' }}>🗑️ จัดการขยะรายปี</option>
                        <option value="welfare" {{ request('service_filter') == 'welfare' ? 'selected' : '' }}>🤝 สวัสดิการชุมชน/ฌาปนกิจ</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn bg-gradient-primary btn-sm w-100 mb-0">
                        <i class="fas fa-search me-1"></i> ค้นหา
                    </button>
                    @if(request()->hasAny(['search', 'zone_id', 'service_filter']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm mb-0 px-3" title="ล้างตัวกรอง">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ฟอร์มบันทึกสถานะสิทธิ์บริการ --}}
        <form id="mainBatchForm" action="{{ route('admin.users.update_service') }}" method="POST">
            @csrf
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ-นามสกุล / เบอร์โทร</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ที่อยู่ / โซน</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">เปิด/ปิด สิทธิ์บริการขยะ</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">สถานะโมดูลทั้งหมด</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">จัดการ</th>
                            </tr>
                            <tr class="bg-light border-top">
                                <th colspan="3" class="text-end text-xxs text-secondary pe-3">ติ๊กเลือกทั้งหมดในหน้านี้:</th>
                                <th class="text-center py-2">
                                    <div class="d-flex justify-content-center gap-4">
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="selectAllRecycle">
                                            <div class="text-xxs font-weight-bold text-dark mt-1">รีไซเคิล</div>
                                        </div>
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="selectAllFood">
                                            <div class="text-xxs font-weight-bold text-dark mt-1">ขยะเปียก</div>
                                        </div>
                                        <div>
                                            <input class="form-check-input" type="checkbox" id="selectAllAnnual">
                                            <div class="text-xxs font-weight-bold text-dark mt-1">รายปี</div>
                                        </div>
                                    </div>
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $hasRecycle = $user->kpRecycleBankMember || $user->recycleBankAccount || ($user->wastePreference && $user->wastePreference->is_recycle_bank);
                                    $hasFood = $user->foodWasteAccount || $user->foodWasteBankAccount || ($user->wastePreference && $user->wastePreference->is_food_waste_bank);
                                    $hasAnnual = $user->annualTrashMember || $user->annualTrashSubscription || ($user->wastePreference && $user->wastePreference->is_annual_collection);
                                    $hasWater = $user->usermeterinfos && $user->usermeterinfos->isNotEmpty();
                                    $hasWelfare = !empty($user->welfareMember);
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <span class="text-xs font-weight-bold text-secondary">#{{ sprintf('%05d', $user->id) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm font-weight-bold">{{ $user->prefix }} {{ $user->firstname }} {{ $user->lastname }}</h6>
                                            <p class="text-xs text-secondary mb-0">
                                                <i class="fas fa-phone-alt me-1 text-xxs"></i>{{ $user->phone ?? $user->username ?? '-' }}
                                                @if($user->id_card)
                                                    <span class="ms-2 text-xxs text-muted"><i class="far fa-id-card me-1"></i>{{ $user->id_card }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs">
                                            <div><strong>บ้านเลขที่:</strong> {{ $user->address ?? '-' }}</div>
                                            <div class="text-secondary">
                                                <strong>โซน:</strong> {{ $user->user_zone->zone_name ?? $user->kpUserPreference->user_pref_zone->zone_name ?? '-' }}
                                                @if($user->user_subzone)
                                                    ({{ $user->user_subzone->subzone_name }})
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center gap-4">
                                            {{-- 1. ธนาคารขยะรีไซเคิล --}}
                                            <div class="form-check mb-0" title="ธนาคารขยะรีไซเคิล">
                                                <input class="form-check-input chk-recycle" type="checkbox"
                                                       name="services[{{$user->id}}][recycle]" value="1"
                                                       {{ $hasRecycle ? 'checked' : '' }}
                                                       {{ auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Recycle Bank Staff']) ? '' : 'disabled' }}>
                                            </div>

                                            {{-- 2. ธนาคารขยะเปียก --}}
                                            <div class="form-check mb-0" title="ธนาคารขยะเปียก">
                                                <input class="form-check-input chk-food" type="checkbox"
                                                       name="services[{{$user->id}}][food_waste]" value="1"
                                                       {{ $hasFood ? 'checked' : '' }}
                                                       {{ auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'FoodWaste Staff']) ? '' : 'disabled' }}>
                                            </div>

                                            {{-- 3. ขยะรายปี --}}
                                            <div class="form-check mb-0" title="ขยะรายปี">
                                                <input class="form-check-input chk-annual" type="checkbox"
                                                       name="services[{{$user->id}}][annual_trash]" value="1"
                                                       {{ $hasAnnual ? 'checked' : '' }}
                                                       {{ auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Annual Trash Staff']) ? '' : 'disabled' }}>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex flex-wrap justify-content-center gap-1">
                                            @if($hasWater)
                                                <span class="badge badge-sm bg-gradient-info">ประปา ({{ $user->usermeterinfos->count() }})</span>
                                            @endif
                                            @if($hasRecycle)
                                                <span class="badge badge-sm bg-gradient-success">รีไซเคิล</span>
                                            @endif
                                            @if($hasFood)
                                                <span class="badge badge-sm bg-gradient-primary">ขยะเปียก</span>
                                            @endif
                                            @if($hasAnnual)
                                                <span class="badge badge-sm bg-gradient-warning">ขยะรายปี</span>
                                            @endif
                                            @if($hasWelfare)
                                                <span class="badge badge-sm bg-gradient-dark">สวัสดิการ</span>
                                            @endif
                                            @if(!$hasWater && !$hasRecycle && !$hasFood && !$hasAnnual && !$hasWelfare)
                                                <span class="text-xxs text-muted">ยังไม่ผูกบริการ</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-link text-primary px-2 mb-0" title="แก้ไขข้อมูล">
                                            <i class="fas fa-edit me-1"></i>แก้ไข
                                        </a>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-link text-info px-2 mb-0" title="ดูรายละเอียด">
                                            <i class="fas fa-eye me-1"></i>ดูข้อมูล
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary">
                                        <i class="fas fa-users-slash fa-2x mb-2 d-block opacity-5"></i>
                                        ไม่พบข้อมูลผู้ใช้งานในระบบฐานข้อมูลกลาง
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center border-top">
            <div class="text-xs text-secondary">
                แสดง {{ $users->firstItem() ?? 0 }} ถึง {{ $users->lastItem() ?? 0 }} จากทั้งหมด {{ $users->total() }} รายการ
            </div>
            <div>
                {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@include('admin.users._quick_create_modal')
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupSelectAll(triggerId, targetClass) {
        const trigger = document.getElementById(triggerId);
        if (trigger) {
            trigger.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('tbody .' + targetClass + ':not(:disabled)');
                checkboxes.forEach(chk => chk.checked = this.checked);
            });
        }
    }

    setupSelectAll('selectAllRecycle', 'chk-recycle');
    setupSelectAll('selectAllFood', 'chk-food');
    setupSelectAll('selectAllAnnual', 'chk-annual');

    const btnSave = document.getElementById('btnSaveBatch');
    const mainForm = document.getElementById('mainBatchForm');

    if (btnSave && mainForm) {
        btnSave.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'ยืนยันการปรับเปลี่ยนสิทธิ์บริการ?',
                text: "ระบบจะทำการเพิ่ม/เปิด/ปิด บัญชีบริการตามที่คุณเลือกให้ผู้ใช้แต่ละคนผ่าน UserService",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2dce89',
                cancelButtonColor: '#f5365c',
                confirmButtonText: 'ตกลง, บันทึกเลย',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    mainForm.submit();
                }
            });
        });
    }
});
</script>
@endsection
