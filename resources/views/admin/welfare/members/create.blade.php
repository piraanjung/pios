@extends('layouts.welfare')
@section('title_page', 'ลงทะเบียนสมาชิกกองทุนฌาปนกิจ')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h5 class="font-weight-bolder text-dark mb-1">
                <i class="fas fa-hands-helping text-primary me-2"></i>ลงทะเบียนสมาชิกกองทุนฌาปนกิจ / สวัสดิการชุมชน
            </h5>
            <p class="text-xs text-secondary mb-0">ดึงรายชื่อจากฐานข้อมูลกลาง (Master Users) ที่ยังไม่ได้เข้าร่วมกองทุน</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#quickCreateMasterUserModal">
                <i class="fas fa-user-plus me-1"></i> ไม่พบชื่อ? เพิ่มเข้าฐานข้อมูลกลาง (Master Users)
            </button>
            <a href="{{ route('admin.welfare.members.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                <i class="fas fa-arrow-left me-1"></i> กลับหน้ารายชื่อสมาชิก
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

    <!-- ฟอร์มค้นหา (Soft UI) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.welfare.members.create') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-gray-100 border-end-0"><i class="fas fa-search text-secondary"></i></span>
                        <input type="text" name="search" class="form-control ps-2" placeholder="ค้นหาด้วยชื่อ, นามสกุล หรือเบอร์โทรศัพท์..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn bg-gradient-primary btn-sm w-100 mb-0">
                        <i class="fas fa-search me-1"></i> ค้นหา
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.welfare.members.create') }}" class="btn btn-outline-secondary btn-sm mb-0 px-3">ล้าง</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- ตารางรายชื่อให้ติ๊กเลือก -->
    <div class="card border-0 shadow-sm">
        <form action="{{ route('admin.welfare.members.bulkStore') }}" method="POST">
            @csrf
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="ps-4" style="width: 50px;">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ-นามสกุล / ที่อยู่</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เบอร์โทรศัพท์</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ประเภทสมาชิกกองทุนที่ต้องการสมัคร</th>
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
                                    บ้านเลขที่ {{ $user->address ?? '-' }}
                                    {{ $user->user_zone ? '('.$user->user_zone->zone_name.')' : '' }}
                                </small>
                            </td>
                            <td>
                                <span class="text-xs font-weight-bold text-secondary">{{ $user->phone ?? '-' }}</span>
                            </td>
                            <td>
                                <select name="membership_type[{{ $user->id }}]" class="form-select form-select-sm" style="max-width: 260px;">
                                    <option value="both">ขายขยะ + สมทบฌาปนกิจ (ปกติ)</option>
                                    <option value="contribution_only">สมทบฌาปนกิจอย่างเดียว (ไม่ขายขยะ)</option>
                                    <option value="waste_only">สะสมยอดขายขยะอย่างเดียว</option>
                                </select>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary text-sm">
                                <i class="fas fa-info-circle fa-2x mb-2 d-block opacity-5"></i>
                                ไม่พบรายชื่อสมาชิกกลางที่สามารถเพิ่มได้ (หรือเพิ่มครบหมดแล้ว)
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
                        <i class="fas fa-check-circle me-1"></i> ยืนยันเพิ่มสมาชิกที่เลือก
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
