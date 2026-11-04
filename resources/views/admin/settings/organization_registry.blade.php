<section class="card mb-4" id="organization_registry">
    <div class="card-header pb-0 d-flex flex-wrap justify-content-between align-items-start gap-2">
        <div>
            <p class="text-xs text-primary text-uppercase mb-1">Organization profile</p>
            <h5 class="mb-1">ทะเบียนข้อมูลองค์กร</h5>
            <p class="text-sm text-secondary mb-0">บันทึกลงตาราง organizations โดยตรง</p>
        </div>
        <span class="badge bg-light text-dark border">องค์กร {{ $organization->org_code }}</span>
    </div>
    <div class="card-body">
        @if(session('organization_success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('organization_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.organization.update') }}" enctype="multipart/form-data" id="organizationProfileForm">
            @csrf
            <div class="row g-3">
                <div class="col-12"><h6 class="border-bottom pb-2 mb-0">ข้อมูลหน่วยงาน</h6></div>
                <div class="col-md-4">
                    <label class="form-label" for="org_code">รหัสองค์กร</label>
                    <input class="form-control @error('org_code') is-invalid @enderror" id="org_code" name="org_code" value="{{ old('org_code', $organization->org_code) }}" required maxlength="50">
                    @error('org_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="org_type_id">ประเภทองค์กร</label>
                    <select class="form-select" id="org_type_id" name="org_type_id">
                        <option value="">ไม่ระบุ</option>
                        @foreach($organizationTypes as $type)
                            <option value="{{ $type->id }}" @selected((string) old('org_type_id', $organization->org_type_id) === (string) $type->id)>{{ $type->name }} ({{ $type->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="org_name">ชื่อองค์กร</label>
                    <input class="form-control" id="org_name" name="org_name" value="{{ old('org_name', $organization->org_name) }}" required maxlength="255">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="org_dept_name">ชื่อหน่วยงาน/กอง</label>
                    <input class="form-control" id="org_dept_name" name="org_dept_name" value="{{ old('org_dept_name', $organization->org_dept_name) }}" maxlength="255">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_dept_short_name">ชื่อย่อหน่วยงาน</label>
                    <input class="form-control" id="org_dept_short_name" name="org_dept_short_name" value="{{ old('org_dept_short_name', $organization->org_dept_short_name) }}" maxlength="100">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_dept_phone">โทรศัพท์หน่วยงาน</label>
                    <input class="form-control" id="org_dept_phone" name="org_dept_phone" value="{{ old('org_dept_phone', $organization->org_dept_phone) }}" maxlength="50">
                </div>

                <div class="col-12 mt-4"><h6 class="border-bottom pb-2 mb-0">ที่อยู่และพื้นที่รับผิดชอบ</h6></div>
                <div class="col-md-6">
                    <label class="form-label" for="org_address">ที่อยู่</label>
                    <textarea class="form-control" id="org_address" name="org_address" rows="2" maxlength="2000">{{ old('org_address', $organization->org_address) }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_route">ถนน/เส้นทาง</label>
                    <input class="form-control" id="org_route" name="org_route" value="{{ old('org_route', $organization->org_route) }}" maxlength="255">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_zipcode">รหัสไปรษณีย์</label>
                    <input class="form-control" id="org_zipcode" name="org_zipcode" value="{{ old('org_zipcode', $organization->org_zipcode) }}" maxlength="10">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_province_id_fk">จังหวัด</label>
                    <select class="form-select" id="org_province_id_fk" name="org_province_id_fk">
                        <option value="">เลือกจังหวัด</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}" @selected((string) old('org_province_id_fk', $organization->org_province_id_fk) === (string) $province->id)>{{ $province->province_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_district_id_fk">อำเภอ</label>
                    <select class="form-select" id="org_district_id_fk" name="org_district_id_fk">
                        <option value="">เลือกอำเภอ</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" @selected((string) old('org_district_id_fk', $organization->org_district_id_fk) === (string) $district->id)>{{ $district->district_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_tambon_id_fk">ตำบล</label>
                    <select class="form-select" id="org_tambon_id_fk" name="org_tambon_id_fk">
                        <option value="">เลือกตำบล</option>
                        @foreach($tambons as $tambon)
                            <option value="{{ $tambon->id }}" @selected((string) old('org_tambon_id_fk', $organization->org_tambon_id_fk) === (string) $tambon->id)>{{ $tambon->tambon_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="org_zone_id_fk">โซน/หมู่</label>
                    <select class="form-select" id="org_zone_id_fk" name="org_zone_id_fk">
                        <option value="">เลือกโซน</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" @selected((string) old('org_zone_id_fk', $organization->org_zone_id_fk) === (string) $zone->id)>{{ $zone->zone_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="org_phone">โทรศัพท์องค์กร</label>
                    <input class="form-control" id="org_phone" name="org_phone" value="{{ old('org_phone', $organization->org_phone) }}" maxlength="50">
                </div>

                <div class="col-12 mt-4"><h6 class="border-bottom pb-2 mb-0">ผู้รับผิดชอบและระบบ</h6></div>
                <div class="col-md-4">
                    <label class="form-label" for="org_head_name">หัวหน้าหน่วยงาน</label>
                    <input class="form-control" id="org_head_name" name="org_head_name" value="{{ old('org_head_name', $organization->org_head_name) }}" maxlength="255">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="fin_head_name">หัวหน้าการเงิน</label>
                    <input class="form-control" id="fin_head_name" name="fin_head_name" value="{{ old('fin_head_name', $organization->fin_head_name) }}" maxlength="255">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="tw_head_name">หัวหน้าฝ่ายเก็บขยะ/บริการ</label>
                    <input class="form-control" id="tw_head_name" name="tw_head_name" value="{{ old('tw_head_name', $organization->tw_head_name) }}" maxlength="255">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="meternumbercode">รหัสเลขมิเตอร์</label>
                    <input class="form-control" id="meternumbercode" name="meternumbercode" value="{{ old('meternumbercode', $organization->meternumbercode) }}" maxlength="255">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="cutmeter_count">จำนวนครั้งค้างก่อนตัดบริการ</label>
                    <input class="form-control" id="cutmeter_count" name="cutmeter_count" type="number" min="0" value="{{ old('cutmeter_count', $organization->cutmeter_count ?? 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="vat">VAT (%)</label>
                    <input class="form-control" id="vat" name="vat" type="number" min="0" max="100" step="0.01" value="{{ old('vat', $organization->vat ?? 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="lat">ละติจูดองค์กร</label>
                    <input class="form-control" id="lat" name="lat" type="number" min="-90" max="90" step="any" value="{{ old('lat', $organization->lat) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="lng">ลองจิจูดองค์กร</label>
                    <input class="form-control" id="lng" name="lng" type="number" min="-180" max="180" step="any" value="{{ old('lng', $organization->lng) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="org_database">ฐานข้อมูลขององค์กร</label>
                    <input class="form-control" id="org_database" value="{{ $organization->org_database }}" readonly>
                    <small class="text-secondary">แก้ไขฐานข้อมูลผ่านผู้ดูแลระบบเท่านั้น</small>
                </div>

                <div class="col-12 mt-4"><h6 class="border-bottom pb-2 mb-0">โลโก้และเอกสาร</h6></div>
                @foreach([
                    'org_logo_upload' => ['label' => 'โลโก้องค์กร', 'current' => $organization->org_logo_img, 'accept' => 'image/*'],
                    'org_head_license_upload' => ['label' => 'เอกสารหัวหน้าหน่วยงาน', 'current' => $organization->org_head_license_img, 'accept' => 'image/*,.pdf'],
                    'fin_head_license_upload' => ['label' => 'เอกสารหัวหน้าการเงิน', 'current' => $organization->fin_head_license_img, 'accept' => 'image/*,.pdf'],
                    'tw_head_license_upload' => ['label' => 'เอกสารหัวหน้าฝ่ายบริการ', 'current' => $organization->tw_head_license_img, 'accept' => 'image/*,.pdf'],
                ] as $field => $file)
                    <div class="col-md-6">
                        <label class="form-label" for="{{ $field }}">{{ $file['label'] }}</label>
                        <input class="form-control" id="{{ $field }}" name="{{ $field }}" type="file" accept="{{ $file['accept'] }}">
                        @if($file['current'])
                            <a class="small d-inline-block mt-1" href="{{ $field === 'org_logo_upload' ? asset('logo/'.$file['current']) : asset($file['current']) }}" target="_blank" rel="noopener">เปิดไฟล์ปัจจุบัน</a>
                        @endif
                    </div>
                @endforeach

                <div class="col-12 d-flex justify-content-end mt-4">
                    <button class="btn btn-primary mb-0" type="submit"><i class="fas fa-save me-1"></i> บันทึกทะเบียนองค์กร</button>
                </div>
            </div>
        </form>
    </div>
</section>

