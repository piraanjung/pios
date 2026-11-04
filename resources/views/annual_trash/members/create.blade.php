@extends('annual_trash.layout')

@section('nav-header', 'ขยะรายปี')
@section('page-topic', 'ลงทะเบียนสมาชิกขยะรายปีใหม่')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h5 class="font-weight-bolder mb-1 text-dark">
                <i class="fas fa-trash-alt text-warning me-2"></i>ลงทะเบียนสมาชิกขยะรายปี (Annual Trash)
            </h5>
            <p class="text-xs text-secondary mb-0">เลือกรายชื่อจากฐานข้อมูลกลาง (Master Users) ที่ยังไม่ได้ลงทะเบียนขยะรายปี</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#quickCreateMasterUserModal">
                <i class="fas fa-user-plus me-1"></i> ไม่พบชื่อ? เพิ่มเข้าฐานข้อมูลกลาง (Master Users)
            </button>
            <a href="{{ route('keptkayas.annual-members.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                <i class="fas fa-arrow-left me-1"></i> กลับหน้ารายชื่อ
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-white border-0 shadow-sm mb-4 py-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-white border-0 shadow-sm mb-4 py-2">{{ session('error') }}</div>
    @endif

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="{{ route('keptkayas.annual-members.create') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-gray-100 border-end-0"><i class="fas fa-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control ps-2" placeholder="ค้นชื่อ, เบอร์โทร, เลขบัตรประชาชน..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="zone_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">--- แสดงทุกโซน ---</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->zone_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn bg-gradient-primary btn-sm w-100 mb-0">
                        <i class="fas fa-search me-1"></i> ค้นหา
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <form action="{{ route('keptkayas.annual-members.store') }}" method="POST">
            @csrf

            <div class="p-3 bg-gray-100 border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2" style="max-width: 420px;">
                    <label class="form-label mb-0 text-xs font-weight-bold text-nowrap">กลุ่มอัตราค่าบริการ: <span class="text-danger">*</span></label>
                    <select name="user_group_id_fk" class="form-select form-select-sm" required>
                        <option value="">-- เลือกประเภทกลุ่ม --</option>
                        @foreach($userGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->usergroup_name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn bg-gradient-success btn-sm px-4 mb-0">
                    <i class="fas fa-check-circle me-1"></i> ยืนยันเพิ่มสมาชิกที่เลือก
                </button>
            </div>

            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="ps-4" style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ - นามสกุล / ที่อยู่</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เลขสมาชิกที่จะได้รับ</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เขตจัดเก็บ (Zone)</th>
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
                                        บ้านเลขที่ {{ $user->address ?? '-' }} {{ $user->user_zone ? '('.$user->user_zone->zone_name.')' : '' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-light text-dark border">
                                        <i class="fas fa-magic me-1 text-primary"></i> รันอัตโนมัติ (ATxxxxx)
                                    </span>
                                </td>
                                <td>
                                    <select name="zone_id[{{ $user->id }}]" class="form-select form-select-sm" style="max-width: 220px;">
                                        @foreach($zones as $zone)
                                            <option value="{{ $zone->id }}" {{ $user->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->zone_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-secondary text-sm">
                                    <i class="fas fa-info-circle fa-2x mb-2 d-block opacity-5"></i>
                                    ไม่พบรายชื่อที่สามารถเพิ่มได้
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white py-3 border-top">
                {{ $availableUsers->withQueryString()->links() }}
            </div>
        </form>
    </div>
</div>

@include('admin.users._quick_create_modal')

<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
