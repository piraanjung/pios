@extends('layouts.admin1')

@section('nav-excel', 'active')
@section('nav-header', 'จัดการข้อมูล')
@section('nav-topic', 'นำเข้าผู้ใช้งานด้วย Excel')

@section('content')
    <div class="excel-import-page">
        <div class="mb-4">
            <h3 class="mb-1">นำเข้าผู้ใช้งานด้วย Excel</h3>
            <p class="text-sm text-secondary mb-0">ดาวน์โหลด template กรอกข้อมูล แล้วอัปโหลดไฟล์กลับเข้าสู่ระบบ</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>ตรวจสอบไฟล์ Excel:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">ข้อมูลผู้ใช้งาน</h5>
            </div>
            <div class="card-body">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-7">
                        <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="user-excel-file" class="form-label">เลือกไฟล์ Excel</label>
                            <input id="user-excel-file" type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">รองรับไฟล์ .xlsx, .xls และ .csv</div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="fas fa-upload me-1"></i>อัปโหลดข้อมูล
                            </button>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <a href="{{ route('admin.users.download_template') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i>ดาวน์โหลด template
                        </a>
                        <p class="small text-secondary mt-3 mb-2">คอลัมน์ที่ต้องกรอก:</p>
                        <ul class="small text-secondary mb-0">
                            <li><code>username</code>, <code>password</code></li>
                            <li><code>firstname</code>, <code>lastname</code></li>
                            <li>ข้อมูลอื่นเป็น optional; <code>gender</code> ใช้ male/female/other และ <code>status</code> ใช้ active/inactive</li>
                            <li><code>is_annual_collection</code> และ <code>is_recycle_bank</code> ใช้ 1 หรือ 0</li>
                        </ul>
                        <p class="small text-secondary mt-2 mb-0">ระบบกำหนดองค์กรจากบัญชีผู้ใช้อัปโหลด ไม่ต้องกรอก org ID</p>
                    </div>
                </div>
                <details class="mt-4">
                    <summary class="text-sm font-weight-bold">ดูรหัสหมู่และเส้นทางสำหรับกรอกในไฟล์</summary>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>zone_id</th>
                                    <th>หมู่</th>
                                    <th>subzone_id</th>
                                    <th>เส้นทาง</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($zones as $zone)
                                    @forelse ($zone->subzone as $subzone)
                                        <tr>
                                            <td>{{ $zone->id }}</td>
                                            <td>{{ $zone->zone_name }}</td>
                                            <td>{{ $subzone->id }}</td>
                                            <td>{{ $subzone->subzone_name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>{{ $zone->id }}</td>
                                            <td>{{ $zone->zone_name }}</td>
                                            <td colspan="2" class="text-secondary">ไม่มีเส้นทาง</td>
                                        </tr>
                                    @endforelse
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <style>
        .excel-import-page { max-width: 1100px; }
        .excel-import-page code { color: #344767; }
    </style>
@endsection