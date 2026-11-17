@extends('layouts.super-admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Modern Header & Faceted Filter Card (v0 Style) -->
            <div class="card border-0 shadow-xs mb-4 border-radius-xl bg-white" style="border: 1px solid rgba(226, 232, 240, 0.9);">
                <div class="card-header pb-3 bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-gradient-primary rounded-pill px-3 py-1 text-xs font-weight-bold">
                                    <i class="fas fa-building me-1"></i> พอร์ทัลผู้ดูแลระบบ
                                </span>
                                <span class="badge bg-light text-dark border rounded-pill px-3 py-1 text-xs font-weight-bold" id="orgCountBadge">
                                    ทั้งหมด {{ count($organizations) }} แห่ง
                                </span>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-0" style="letter-spacing: -0.3px;">
                                เลือกหน่วยงานเพื่อเข้าจัดการระบบ
                            </h4>
                            <p class="text-xs text-secondary mb-0">
                                ค้นหาและเลือกเทศบาลหรือหน่วยงานที่ต้องการเข้าบริหารจัดการหรือสลับบริบทการทำงาน
                            </p>
                        </div>
                    </div>

                    <!-- Faceted Filter Toolbar -->
                    <div class="row g-2 mt-2 align-items-center">
                        <div class="col-md-6 col-lg-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-radius-start-md border-end-0">
                                    <i class="fas fa-search text-secondary"></i>
                                </span>
                                <input type="text" 
                                       id="orgSearchInput" 
                                       class="form-control form-control-sm bg-light border-start-0 border-radius-end-md ps-0" 
                                       placeholder="พิมพ์ค้นหาชื่อหน่วยงาน, ตำบล หรืออำเภอ..."
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-3">
                            <form action="{{ route('admin.org_selector') }}" method="GET" class="m-0" id="typeFilterForm">
                                <select name="org_type" class="form-select form-select-sm bg-light border-radius-md" onchange="this.form.submit()">
                                    <option value="">--- แสดงทุกประเภทหน่วยงาน ---</option>
                                    @foreach($orgTypes as $type)
                                        <option value="{{ $type->id }}" {{ request('org_type') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <div class="col-md-2 col-lg-4 text-md-end">
                            <button type="button" class="btn btn-sm btn-link text-secondary mb-0 p-0 text-xs" onclick="resetOrgFilter()">
                                <i class="fas fa-redo-alt me-1"></i> ล้างคำค้นหา
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="orgTable">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">หน่วยงาน</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">พื้นที่ (ตำบล / อำเภอ)</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">สถานะระบบ</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 pe-3">ดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody id="orgTableBody">
                                @forelse($organizations as $org)
                                <tr class="org-row hover-elevate" 
                                    data-org-name="{{ mb_strtolower($org->org_name ?? '', 'UTF-8') }}"
                                    data-tambon="{{ mb_strtolower($org->tambon_name ?? '', 'UTF-8') }}"
                                    data-district="{{ mb_strtolower($org->district_name ?? '', 'UTF-8') }}"
                                    data-id="{{ $org->id }}">
                                    <td class="ps-3">
                                        <div class="d-flex px-2 py-2 align-items-center">
                                            @php
                                                $logoUrl = $org->getLogoUrl(true);
                                                $orgInitial = mb_substr(trim($org->org_name ?? 'อ'), 0, 1, 'UTF-8');
                                            @endphp
                                            <div class="avatar avatar-sm me-3 border-radius-md bg-white shadow-xs d-flex align-items-center justify-content-center overflow-hidden" 
                                                 style="width: 40px; height: 40px; min-width: 40px; border: 1px solid rgba(226, 232, 240, 0.9);">
                                                @if($logoUrl)
                                                    <img src="{{ $logoUrl }}" 
                                                         class="w-100 h-100" 
                                                         alt="{{ $org->org_name }}"
                                                         style="object-fit: contain;"
                                                         onerror="this.onerror=null; this.src='{{ asset('logo/ko_envsogo.png') }}';">
                                                @else
                                                    <span class="font-weight-bold text-xs text-primary">{{ $orgInitial }}</span>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $org->org_name }}</h6>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-light text-secondary border rounded-pill px-2 py-0 text-xxs">ID: {{ $org->id }}</span>
                                                    @if(!empty($org->org_code))
                                                        <span class="text-xxs text-muted">{{ $org->org_code }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <span class="text-xs font-weight-bold text-dark">
                                                <i class="fas fa-map-marker-alt me-1 text-primary text-xxs"></i>
                                                {{ $org->tambon_name ?: '-' }}
                                            </span>
                                            <span class="text-xxs text-secondary ps-3">{{ $org->district_name ?: '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success rounded-pill px-3 shadow-xs">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                    </td>
                                    <td class="align-middle text-center pe-3">
                                        <form action="{{ route('admin.set_org_context') }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="org_id" value="{{ $org->id }}">
                                            <button type="submit" class="btn btn-sm bg-gradient-primary rounded-pill px-3 py-1 mb-0 shadow-xs hover-elevate font-weight-bold text-xs">
                                                เข้าใช้งาน <i class="fas fa-arrow-right ms-1"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted text-sm">
                                        <i class="fas fa-inbox fa-2x mb-2 opacity-5 d-block"></i>
                                        ไม่พบข้อมูลหน่วยงานในระบบ
                                    </td>
                                </tr>
                                @endforelse

                                <!-- Empty Search State (Hidden by Default) -->
                                <tr id="noOrgRow" style="display: none;">
                                    <td colspan="4" class="text-center py-4 text-muted text-sm">
                                        <i class="fas fa-search fa-2x mb-2 opacity-5 d-block"></i>
                                        ไม่พบหน่วยงานที่ตรงกับคำค้นหา "<span id="searchTermDisplay" class="font-weight-bold"></span>"
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('orgSearchInput');
    var rows = document.querySelectorAll('.org-row');
    var noOrgRow = document.getElementById('noOrgRow');
    var searchTermDisplay = document.getElementById('searchTermDisplay');
    var badge = document.getElementById('orgCountBadge');
    var totalRows = rows.length;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var query = this.value.trim().toLowerCase();
            var visibleCount = 0;

            rows.forEach(function(row) {
                var orgName = row.getAttribute('data-org-name') || '';
                var tambon = row.getAttribute('data-tambon') || '';
                var district = row.getAttribute('data-district') || '';
                var id = row.getAttribute('data-id') || '';

                if (query === '' || orgName.includes(query) || tambon.includes(query) || district.includes(query) || id.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0 && query !== '') {
                noOrgRow.style.display = '';
                searchTermDisplay.textContent = query;
            } else {
                noOrgRow.style.display = 'none';
            }

            if (badge) {
                badge.textContent = 'แสดง ' + visibleCount + ' / ' + totalRows + ' แห่ง';
            }
        });
    }

    window.resetOrgFilter = function() {
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
    };
});
</script>
@endsection
