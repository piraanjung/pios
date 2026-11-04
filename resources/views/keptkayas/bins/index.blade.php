@extends('layouts.admin1')

@section('nav-header', 'จัดการถังขยะรายปี')
@section('nav-current', 'ทะเบียนถังขยะ')
@section('page-topic', 'ทะเบียนถังขยะรายปี')

@section('navigation')
    @include('annual_trash.navigation')
@endsection

@section('style')
<style>
    .bin-stat { min-height: 104px; }
    .bin-code { font-family: Consolas, 'Courier New', monospace; font-weight: 700; letter-spacing: 0; }
    .run-preview { min-height: 24px; font-variant-numeric: tabular-nums; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <p class="text-sm text-secondary mb-1">คลังถัง / Annual collection</p>
            <h5 class="mb-0">ทะเบียนถังขยะรายปี</h5>
        </div>
        <span class="text-sm text-secondary">{{ number_format($summary['total']) }} ถังในหน่วยงาน</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="card bin-stat"><div class="card-body"><span class="text-sm text-secondary">ทั้งหมด</span><div class="h4 mb-0 mt-2">{{ number_format($summary['total']) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card bin-stat"><div class="card-body"><span class="text-sm text-secondary">พร้อมใช้งาน</span><div class="h4 text-warning mb-0 mt-2">{{ number_format($summary['available']) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card bin-stat"><div class="card-body"><span class="text-sm text-secondary">กำลังใช้งาน</span><div class="h4 text-success mb-0 mt-2">{{ number_format($summary['active']) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card bin-stat"><div class="card-body"><span class="text-sm text-secondary">ชำรุด / ยกเลิก</span><div class="h4 text-danger mb-0 mt-2">{{ number_format($summary['issues']) }}</div></div></div></div>
    </div>

    <div class="card mb-4">
        <div class="card-header pb-0">
            <h6 class="mb-1">สร้างรหัสถังหลายใบ</h6>
            <p class="text-sm text-secondary mb-0">กำหนด prefix และจำนวน ระบบจะต่อเลขจากรหัสล่าสุดในหน่วยงานนี้</p>
        </div>
        <div class="card-body">
            <form id="bulkBinForm" action="{{ route('keptkayas.bins.store') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label" for="prefix">Prefix</label>
                        <input class="form-control" id="prefix" name="prefix" value="{{ old('prefix', $binPrefix) }}" maxlength="30" pattern="[A-Za-z0-9-]+" required>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label" for="quantity">จำนวน</label>
                        <input class="form-control" id="quantity" name="quantity" type="number" min="1" max="500" value="{{ old('quantity', 10) }}" required>
                    </div>
                    <div class="col-sm-6 col-lg-2">
                        <label class="form-label" for="status">สถานะเริ่มต้น</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">พร้อมใช้งาน</option>
                            <option value="active">กำลังใช้งาน</option>
                            <option value="damaged">ชำรุด</option>
                            <option value="removed">ยกเลิก</option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <label class="form-label" for="description">หมายเหตุ</label>
                        <input class="form-control" id="description" name="description" maxlength="191" value="{{ old('description') }}" placeholder="ไม่บังคับ">
                    </div>
                    <div class="col-lg-2 d-grid">
                        <button class="btn btn-primary mb-0" type="submit"><i class="fas fa-layer-group me-1"></i> สร้างชุด</button>
                    </div>
                </div>
                <div class="run-preview text-sm text-secondary mt-3" id="runPreview" aria-live="polite">กำลังตรวจสอบช่วงรหัส...</div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div><h6 class="mb-1">ถังในทะเบียน</h6><p class="text-sm text-secondary mb-0">{{ $bins->total() }} รายการ</p></div>
                <form action="{{ route('keptkayas.bins.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                    <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="ค้นหารหัสหรือหมายเหตุ" aria-label="ค้นหาถัง">
                    <select class="form-select" name="status" aria-label="กรองสถานะ">
                        <option value="">ทุกสถานะ</option>
                        @foreach(['pending' => 'พร้อมใช้งาน', 'active' => 'กำลังใช้งาน', 'damaged' => 'ชำรุด', 'removed' => 'ยกเลิก'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-secondary mb-0" type="submit" title="ค้นหา"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="card-body px-0 pb-2">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead><tr><th>รหัสถัง</th><th>ผู้ใช้งาน</th><th>สถานะ</th><th>หมายเหตุ</th><th class="text-end">จัดการ</th></tr></thead>
                    <tbody>
                        @forelse($bins as $bin)
                            @php
                                $user = $bin->foodwaste_bin?->fw_user_preference?->user;
                                $statusStyle = ['active' => 'success', 'pending' => 'warning', 'damaged' => 'danger', 'removed' => 'secondary'][$bin->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td><span class="bin-code">{{ $bin->bin_code }}</span></td>
                                <td>{{ trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: 'ยังไม่ผูกผู้ใช้' }}</td>
                                <td><span class="badge bg-{{ $statusStyle }}">{{ ['active' => 'กำลังใช้งาน', 'pending' => 'พร้อมใช้งาน', 'damaged' => 'ชำรุด', 'removed' => 'ยกเลิก'][$bin->status] ?? $bin->status }}</span></td>
                                <td>{{ $bin->description ?: '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('keptkayas.bins.show', $bin->id) }}" class="btn btn-sm btn-outline-secondary mb-0" title="ดูรายละเอียด"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('keptkayas.bins.edit', $bin->id) }}" class="btn btn-sm btn-outline-primary mb-0" title="แก้ไข"><i class="fas fa-pen"></i></a>
                                    <form action="{{ route('keptkayas.bins.destroy', $bin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ลบรหัสถัง {{ $bin->bin_code }} หรือไม่?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger mb-0" type="submit" title="ลบ"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-secondary">ไม่พบข้อมูลถังขยะ</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end px-3">{{ $bins->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    (() => {
        const prefixInput = document.getElementById('prefix');
        const quantityInput = document.getElementById('quantity');
        const preview = document.getElementById('runPreview');
        let timer;

        async function updatePreview() {
            const prefix = prefixInput.value.trim();
            const quantity = quantityInput.value;
            if (!prefix || !quantity) {
                preview.textContent = 'ใส่ prefix และจำนวนเพื่อดูช่วงรหัส';
                return;
            }
            preview.textContent = 'กำลังตรวจสอบช่วงรหัส...';
            try {
                const query = new URLSearchParams({ prefix, quantity });
                const response = await fetch(`{{ route('keptkayas.bins.preview_codes') }}?${query}`);
                if (!response.ok) throw new Error('preview failed');
                const data = await response.json();
                preview.textContent = `จะสร้าง ${quantity} ใบ: ${data.start_code} ถึง ${data.end_code}`;
            } catch {
                preview.textContent = 'ตรวจสอบช่วงรหัสไม่ได้ กรุณาตรวจ prefix และจำนวน';
            }
        }

        [prefixInput, quantityInput].forEach(input => input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(updatePreview, 250);
        }));
        updatePreview();
    })();
</script>
@endsection
