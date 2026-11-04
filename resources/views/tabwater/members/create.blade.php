@extends('layouts.admin1')

@section('title_page', 'เพิ่มผู้ใช้น้ำประปาใหม่')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h5 class="font-weight-bolder text-dark mb-1">
                <i class="fas fa-tint text-info me-2"></i>ลงทะเบียนผู้ใช้น้ำประปาใหม่ (Tabwater)
            </h5>
            <p class="text-xs text-secondary mb-0">ดึงรายชื่อจากฐานข้อมูลกลาง (Master Users) ที่ยังไม่มีมิเตอร์ประปาในระบบ</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#quickCreateMasterUserModal">
                <i class="fas fa-user-plus me-1"></i> ไม่พบชื่อ? เพิ่มเข้าฐานข้อมูลกลาง (Master Users)
            </button>
            <a href="{{ route('tabwater.members.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                <i class="fas fa-arrow-left me-1"></i> กลับหน้ารายชื่อผู้ใช้น้ำ
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-white border-0 shadow-sm mb-4 py-2" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-white border-0 shadow-sm mb-4 py-2" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- กล่องกรองข้อมูล (Soft UI Search & Filter by Zone) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('tabwater.members.create') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label text-xs font-weight-bold text-secondary mb-1">ค้นหาข้อมูลผู้ใช้</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-gray-100 border-end-0"><i class="fas fa-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control ps-2" placeholder="ค้นหาด้วยชื่อ, นามสกุล, เบอร์โทร หรือเลขบัตร..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-xs font-weight-bold text-secondary mb-1">กรองตามโซน/เขต (Zone)</label>
                    <select name="zone_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">--- แสดงทุกโซน/เขต ---</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn bg-gradient-primary btn-sm w-100 mb-0">
                        <i class="fas fa-filter me-1"></i> กรองข้อมูล
                    </button>
                    @if(request()->hasAny(['search', 'zone_id']))
                        <a href="{{ route('tabwater.members.create') }}" class="btn btn-outline-secondary btn-sm px-3 mb-0" title="ล้างการกรอง">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- ตารางรายชื่อให้ติ๊กเลือกเพื่อลงทะเบียน -->
    <div class="card border-0 shadow-sm">
        <form action="{{ route('tabwater.members.bulkstore') }}" method="POST">
            @csrf
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="ps-4" style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ-นามสกุล / ที่อยู่</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="min-width: 150px;">เลขซีเรียล (Factory No.)</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="min-width: 130px;">เลขอ่านเริ่มต้น</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="min-width: 170px;">ประเภทมิเตอร์</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2" style="min-width: 160px;">เขตจัดเก็บ (Zone)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($availableUsers as $user)
                            <tr>
                                <td class="ps-4">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="form-check-input user-checkbox">
                                </td>
                                <td>
                                    <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $user->prefix }}{{ $user->firstname }} {{ $user->lastname }}</h6>
                                    <small class="text-secondary text-xs">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        บ้านเลขที่ {{ $user->address ?? '-' }} {{ $user->user_zone ? '('.$user->user_zone->zone_name.')' : '' }}
                                    </small>
                                </td>
                                <td>
                                    <input type="text" name="factory_no[{{ $user->id }}]" class="form-control form-control-sm" placeholder="SN โรงงาน">
                                </td>
                                <td>
                                    <input type="number" step="1" name="last_meter_recording[{{ $user->id }}]" class="form-control form-control-sm" value="0">
                                </td>
                                <td>
                                    <select name="metertype_id[{{ $user->id }}]" class="form-select form-select-sm">
                                        @foreach($meterTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name ?? $type->meter_type_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="undertake_zone_id[{{ $user->id }}]" class="form-select form-select-sm">
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ $user->zone_id == $zone->id ? 'selected' : '' }}>
                                                {{ $zone->zone_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary text-sm">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block opacity-5"></i>
                                    ไม่พบรายชื่อสมาชิกกลางที่สามารถเพิ่มได้ (หรือลงทะเบียนมิเตอร์ประปาครบแล้ว)
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white py-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="text-secondary text-xs">
                    แสดงผล {{ $availableUsers->firstItem() ?? 0 }} - {{ $availableUsers->lastItem() ?? 0 }} จากทั้งหมด {{ $availableUsers->total() }} รายการ
                </div>
                <div>
                    {{ $availableUsers->withQueryString()->links() }}
                </div>
                @if($availableUsers->isNotEmpty())
                    <button type="submit" class="btn bg-gradient-success btn-sm px-4 mb-0">
                        <i class="fas fa-check-circle me-1"></i> ยืนยันเพิ่มผู้ใช้น้ำที่เลือก
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

@include('admin.users._quick_create_modal')

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
