@extends('annual_trash.layout')

@section('nav-header', 'ขยะรายปี')
@section('page-topic', 'รายชื่อสมาชิกขยะรายปี')

@section('content')
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-header pb-0 bg-white border-0 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="mb-0 fw-bold"><i class="fas fa-dumpster text-primary me-2"></i>รายชื่อสมาชิกขยะรายปี</h6>
            <small class="text-muted">รายการผู้ลงทะเบียนรับบริการเก็บขยะรายปี</small>
        </div>
        <a href="{{ route('keptkayas.annual-members.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 mb-0">
            <i class="fas fa-plus me-1"></i> ลงทะเบียนสมาชิกใหม่
        </a>
    </div>

    @if(session('success'))
        <div class="mx-4 mt-3 alert alert-success text-white border-0 rounded-3 py-2 text-sm">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card-body px-0 pt-3 pb-2">
        <!-- ฟอร์มค้นหา -->
        <div class="px-4 mb-3">
            <form action="{{ route('keptkayas.annual-members.index') }}" method="GET" class="row g-2">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control form-control-sm bg-light border-0" placeholder="ค้นหาชื่อ, เบอร์โทร, เลขสมาชิก..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm bg-light border-0" onchange="this.form.submit()">
                        <option value="all">-- สถานะทั้งหมด --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>ใช้งาน (Active)</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>ยกเลิก (Inactive)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-secondary w-100 mb-0">ค้นหา</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('keptkayas.annual-members.index') }}" class="btn btn-sm btn-outline-secondary mb-0">ล้าง</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เลขสมาชิก</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ชื่อ - นามสกุล</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">ประเภทกลุ่ม</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">เขต/โซน</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">สถานะ</th>
                        <th class="text-end pe-4 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $index => $member)
                        <tr>
                            <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $members->firstItem() + $index }}</p></td>
                            <td>
                                <span class="badge bg-light text-primary border font-weight-bold">
                                    {{ $member->formatted_member_number }}
                                </span>
                            </td>
                            <td>
                                <h6 class="mb-0 text-sm fw-bold">{{ optional($member->user)->prefix }}{{ optional($member->user)->firstname }} {{ optional($member->user)->lastname }}</h6>
                                <small class="text-muted text-xxs">{{ optional($member->user)->address }}</small>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ optional($member->userGroup)->usergroup_name ?? '-' }}</p>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ optional($member->zone)->zone_name ?? '-' }}</p>
                            </td>
                            <td class="text-center">
                                @if($member->status == 'active')
                                    <span class="badge bg-success text-xxs">ปกติ</span>
                                @else
                                    <span class="badge bg-secondary text-xxs">ระงับ</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('keptkayas.annual-members.edit', $member->id) }}" class="btn btn-link text-dark p-0 mb-0 me-2" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('keptkayas.annual-members.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบสมาชิกใช่หรือไม่?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 mb-0" title="ลบ">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-4">ไม่พบข้อมูลสมาชิกขยะรายปี</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 pt-3">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection