@extends('layouts.annual_trash')
@section('nav-header', 'สมาชิก ธนาคารขยะ, จัดการถังขยะรายปี')
@section('nav-current', 'เพิ่มสมาชิก ธนาคารขยะ, จัดการถังขยะรายปี')
@section('page-topic', 'เพิ่มสมาชิก ธนาคารขยะ, จัดการถังขยะรายปี')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-11 mx-auto">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header pb-0 p-3 bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h6 class="mb-0 font-weight-bolder text-dark">
                            <i class="fas fa-user-check text-primary me-2"></i>เพิ่มสมาชิกธนาคารขยะรีไซเคิล (จาก Master Users)
                        </h6>
                        <p class="text-xs text-secondary mb-0">เลือกผู้ใช้งานจากตารางหลัก (<code>users</code>) หรือสร้างผู้ใช้ใหม่พร้อมเปิดบัญชีธนาคารขยะ</p>
                    </div>
                    <button type="button" class="btn bg-gradient-info btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#quickCreateMasterUserModal">
                        <i class="fas fa-user-plus me-1"></i> เพิ่มรายชื่อเข้าตารางหลัก (Master Users)
                    </button>
                </div>
                <div class="card-body p-3">
                    <ul class="nav nav-pills nav-fill p-1 bg-gray-100 rounded-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active font-weight-bold text-sm" id="pills-table-tab" data-bs-toggle="pill" data-bs-target="#pills-table" type="button" role="tab" aria-controls="pills-table" aria-selected="true">
                                <i class="fas fa-users me-1 text-primary"></i> 1. ดึงจากผู้ใช้งานในระบบหลัก (Master Users) — แนะนำ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link font-weight-bold text-sm" id="pills-manual-tab" data-bs-toggle="pill" data-bs-target="#pills-manual" type="button" role="tab" aria-controls="pills-manual" aria-selected="false">
                                <i class="fas fa-user-edit me-1 text-success"></i> 2. เพิ่มสมาชิกใหม่ (กรอกข้อมูลใหม่ทั้งหมด)
                            </button>
                        </li>
                    </ul>
                    <hr class="mt-2 mb-4">

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-table" role="tabpanel" aria-labelledby="pills-table-tab">
                            <form action="{{ route('keptkayas.users.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="mode" value="batch_select">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <p class="text-xs text-secondary mb-0">เลือกผู้ใช้งานจากตาราง <code>users</code> ที่ยังไม่ได้เป็นสมาชิกธนาคารขยะ เพื่อเพิ่มเป็นสมาชิก</p>
                                    <button type="submit" class="btn bg-gradient-success btn-sm mb-0" id="batch-add-btn" disabled>
                                        <i class="fas fa-check-circle me-1"></i> เพิ่มสมาชิกที่เลือก
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table align-items-center mb-0">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 60px;">เลือก</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ชื่อผู้ใช้งาน</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ชื่อ-นามสกุล</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">เบอร์โทร / อีเมล</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($nonMemberUsers as $user)
                                                <tr>
                                                    <td>
                                                        <div class="form-check text-center mb-0">
                                                            <input class="form-check-input user-select-checkbox" type="checkbox" name="selected_user_ids[]" value="{{ $user->id }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs font-weight-bold mb-0">{{ $user->username }}</p>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark text-xs font-weight-bold">{{ $user->prefix }}{{ $user->firstname }} {{ $user->lastname }}</span>
                                                    </td>
                                                    <td>
                                                        <p class="text-xs text-secondary mb-0">{{ $user->phone ?? $user->email ?? '-' }}</p>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-secondary text-sm">
                                                        ไม่พบผู้ใช้งานที่ยังไม่ได้เป็นสมาชิกธนาคารขยะ
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="pills-manual" role="tabpanel" aria-labelledby="pills-manual-tab">
                            <form action="{{ route('keptkayas.users.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="mode" value="manual">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label text-xs font-weight-bold">ชื่อผู้ใช้งาน</label>
                                        <input type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label text-xs font-weight-bold">อีเมล</label>
                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="prefix" class="form-label text-xs font-weight-bold">คำนำหน้า</label>
                                        <input type="text" class="form-control form-control-sm" id="prefix" name="prefix" value="{{ old('prefix') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="firstname" class="form-label text-xs font-weight-bold">ชื่อจริง</label>
                                        <input type="text" class="form-control form-control-sm" id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="lastname" class="form-label text-xs font-weight-bold">นามสกุล</label>
                                        <input type="text" class="form-control form-control-sm" id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label text-xs font-weight-bold">รหัสผ่าน</label>
                                        <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label text-xs font-weight-bold">ยืนยันรหัสผ่าน</label>
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
                                <button type="submit" class="btn bg-gradient-primary btn-sm mt-2">บันทึกผู้ใช้งานใหม่</button>
                                <a href="{{ route('keptkayas.users.index') }}" class="btn bg-gradient-secondary btn-sm mt-2">ยกเลิก</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.users._quick_create_modal')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.user-select-checkbox');
        const batchAddBtn = document.getElementById('batch-add-btn');

        function updateBatchButtonState() {
            let checkedCount = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) checkedCount++;
            });
            if (batchAddBtn) batchAddBtn.disabled = checkedCount === 0;
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBatchButtonState);
        });

        updateBatchButtonState();
    });
</script>
@endsection
