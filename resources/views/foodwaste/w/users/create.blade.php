@extends('layouts.foodwaste')

@section('title_page', 'ลงทะเบียนสมาชิกธนาคารขยะเปียก')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h5 class="font-weight-bolder mb-1 text-dark">
                <i class="fas fa-seedling text-success me-2"></i>ลงทะเบียนสมาชิกธนาคารขยะเปียก (Food Waste)
            </h5>
            <p class="text-xs text-secondary mb-0">
                ดึงรายชื่อจากฐานข้อมูลกลาง (Master Users: <code>users</code>) เพื่อลงทะเบียนเข้าสู่โมดูลธนาคารขยะเปียก
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#quickCreateMasterUserModal">
                <i class="fas fa-user-plus me-1"></i> ไม่พบชื่อ? เพิ่มเข้าฐานข้อมูลกลาง (Master Users)
            </button>
            <a href="{{ route('foodwaste.users.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
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

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header pb-0 p-3 bg-white">
            <ul class="nav nav-pills nav-fill p-1 bg-gray-100 rounded-3" id="foodwaste-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active font-weight-bold text-sm" id="pills-table-tab" data-bs-toggle="pill" data-bs-target="#pills-table" type="button" role="tab" aria-selected="true">
                        <i class="fas fa-users me-1 text-primary"></i> 1. เลือกจากฐานข้อมูลกลาง (Master Users) — แนะนำ
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link font-weight-bold text-sm" id="pills-manual-tab" data-bs-toggle="pill" data-bs-target="#pills-manual" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-user-edit me-1 text-success"></i> 2. สร้างผู้ใช้ใหม่พร้อมลงทะเบียนขยะเปียกทันที
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-3">
            <div class="tab-content" id="foodwaste-tabContent">
                {{-- TAB 1: เลือกจากตารางหลัก Master Users --}}
                <div class="tab-pane fade show active" id="pills-table" role="tabpanel" aria-labelledby="pills-table-tab">
                    <form action="{{ route('foodwaste.users.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mode" value="batch_select">

                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                            <div class="input-group input-group-sm" style="max-width: 360px;">
                                <span class="input-group-text bg-gray-100 border-end-0"><i class="fas fa-search text-secondary"></i></span>
                                <input type="text" id="filterMasterUserInput" class="form-control ps-2" placeholder="พิมพ์ค้นหาชื่อ, เบอร์โทร หรือที่อยู่ในตาราง...">
                            </div>
                            <button type="submit" class="btn bg-gradient-success btn-sm mb-0" id="batch-add-btn" disabled>
                                <i class="fas fa-check-circle me-1"></i> ยืนยันเพิ่มสมาชิกขยะเปียกที่เลือก
                            </button>
                        </div>

                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0" id="masterUsersTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="ps-4" style="width: 50px;">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input" type="checkbox" id="selectAllMasterUsers">
                                            </div>
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ-นามสกุล</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เบอร์โทรศัพท์ / Username</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ที่อยู่ / โซน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($nonMemberUsers ?? [] as $user)
                                        <tr class="master-user-row">
                                            <td class="ps-4">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input user-select-checkbox" type="checkbox" name="selected_user_ids[]" value="{{ $user->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-sm font-weight-bold">{{ $user->prefix }}{{ $user->firstname }} {{ $user->lastname }}</h6>
                                                <span class="text-xxs text-secondary">#{{ sprintf('%05d', $user->id) }}</span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $user->phone ?? '-' }}</p>
                                                <p class="text-xxs text-secondary mb-0">{{ $user->username }}</p>
                                            </td>
                                            <td>
                                                <span class="text-xs text-secondary">
                                                    บ้านเลขที่ {{ $user->address ?? '-' }}
                                                    {{ $user->user_zone ? '('.$user->user_zone->zone_name.')' : '' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-secondary text-sm">
                                                <i class="fas fa-info-circle fa-2x mb-2 d-block opacity-5"></i>
                                                ไม่พบรายชื่อในฐานข้อมูลกลางที่ยังไม่ได้เป็นสมาชิกธนาคารขยะเปียก
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                {{-- TAB 2: กรอกฟอร์มเพิ่มใหม่เข้าทั้ง Master Users และ Foodwaste --}}
                <div class="tab-pane fade" id="pills-manual" role="tabpanel" aria-labelledby="pills-manual-tab">
                    <form action="{{ route('foodwaste.users.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="mode" value="manual">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label text-xs font-weight-bold">ชื่อผู้ใช้งาน (Username) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label text-xs font-weight-bold">อีเมล</label>
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="prefix" class="form-label text-xs font-weight-bold">คำนำหน้า</label>
                                <input type="text" class="form-control form-control-sm @error('prefix') is-invalid @enderror" id="prefix" name="prefix" value="{{ old('prefix') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="firstname" class="form-label text-xs font-weight-bold">ชื่อจริง <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="lastname" class="form-label text-xs font-weight-bold">นามสกุล <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-xs font-weight-bold">รหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label text-xs font-weight-bold">ยืนยันรหัสผ่าน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_card" class="form-label text-xs font-weight-bold">เลขบัตรประชาชน</label>
                                <input type="text" class="form-control form-control-sm" id="id_card" name="id_card" value="{{ old('id_card') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label text-xs font-weight-bold">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control form-control-sm" id="phone" name="phone" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label text-xs font-weight-bold">เพศ</label>
                                <select class="form-select form-select-sm" id="gender" name="gender">
                                    <option value="">เลือกเพศ</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>ชาย</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>หญิง</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>อื่นๆ</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label text-xs font-weight-bold">สถานะผู้ใช้</label>
                                <select class="form-select form-select-sm" id="status" name="status" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label text-xs font-weight-bold">ที่อยู่</label>
                            <textarea class="form-control form-control-sm" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <button type="submit" class="btn bg-gradient-primary btn-sm">
                            <i class="fas fa-save me-1"></i> บันทึกผู้ใช้งานและเปิดบัญชีขยะเปียก
                        </button>
                        <a href="{{ route('foodwaste.users.index') }}" class="btn bg-gradient-secondary btn-sm">ยกเลิก</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.users._quick_create_modal')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.user-select-checkbox');
    const selectAll = document.getElementById('selectAllMasterUsers');
    const batchAddBtn = document.getElementById('batch-add-btn');
    const searchInput = document.getElementById('filterMasterUserInput');

    function updateBatchButtonState() {
        let checkedCount = 0;
        checkboxes.forEach(cb => { if (cb.checked) checkedCount++; });
        if (batchAddBtn) batchAddBtn.disabled = checkedCount === 0;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateBatchButtonState));

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.master-user-row:not([style*="display: none"]) .user-select-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
            updateBatchButtonState();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.master-user-row').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
});
</script>
@endsection
