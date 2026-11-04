@extends('annual_trash.layout')

@section('nav-header', 'ขยะรายปี')
@section('page-topic', 'แก้ไขข้อมูลสมาชิกขยะรายปี')

@section('content')
<div class="container-fluid py-2">
    <div class="card border-0 shadow-sm rounded-4 col-md-8 mx-auto">
        <div class="card-header bg-white border-0 pb-0">
            <h5 class="fw-bold"><i class="fas fa-edit text-primary me-2"></i>แก้ไขข้อมูลสมาชิก</h5>
            <p class="text-xs text-muted mb-0">สมาชิก: {{ $member->user->prefix }}{{ $member->user->firstname }} {{ $member->user->lastname }} ({{ $member->formatted_member_number }})</p>
        </div>
        <div class="card-body">
            <form action="{{ route('keptkayas.annual-members.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-sm fw-bold">ประเภทกลุ่มผู้เสียค่าธรรมเนียม</label>
                    <select name="user_group_id_fk" class="form-select" required>
                        @foreach($userGroups as $group)
                            <option value="{{ $group->id }}" {{ $member->user_group_id_fk == $group->id ? 'selected' : '' }}>
                                {{ $group->usergroup_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-sm fw-bold">เขตจัดเก็บ (Zone)</label>
                    <select name="zone_id" class="form-select">
                        <option value="">-- ไม่ระบุ --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ $member->zone_id == $zone->id ? 'selected' : '' }}>
                                {{ $zone->zone_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-sm fw-bold">สถานะการเป็นสมาชิก</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ $member->status == 'active' ? 'selected' : '' }}>ปกติ (Active)</option>
                        <option value="inactive" {{ $member->status == 'inactive' ? 'selected' : '' }}>ระงับการใช้งาน (Inactive)</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('keptkayas.annual-members.index') }}" class="btn btn-light rounded-pill px-4">ยกเลิก</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection