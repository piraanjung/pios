<div class="mobile-page-wrapper">
    <!-- Top Bar ของหน้าหลัก -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <small class="text-muted d-block" style="font-size: 0.75rem;">ยินดีต้อนรับเจ้าหน้าที่ปฏิบัติงาน</small>
            <h5 class="fw-bold mb-0 text-dark" id="staffName">เจ้าหน้าที่</h5>
        </div>
        <button type="button" class="btn btn-light rounded-circle shadow-sm" style="width: 42px; height: 42px;" onclick="toggleFullSidebar()" title="เปิดเมนู">
            <i class="fa-solid fa-bars text-primary"></i>
        </button>
    </div>

    <!-- การ์ดโปรไฟล์เจ้าหน้าที่ & สถานะเครื่องพิมพ์ -->
    <div class="soft-card profile-card p-3 mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 mb-2" style="font-size: 0.7rem;">
                    <i class="fa-solid fa-building me-1"></i><span id="lblActiveOrgName">หน่วยงานที่สังกัด</span>
                </span>
                <h6 class="fw-bold mb-1 text-dark" id="lblMainStaffName">พร้อมปฏิบัติงานภาคสนาม</h6>
                <div class="d-flex align-items-center gap-2 mt-1">
                    <span class="text-success small fw-semibold" style="font-size: 0.75rem;">● ออนไลน์</span>
                    <span class="text-muted small" style="font-size: 0.75rem;">|</span>
                    <a href="javascript:void(0)" onclick="navigateTo('settingsScreen')" class="text-decoration-none small" style="font-size: 0.75rem;">
                        🖨️ <span id="lblHomePrinterStatus" class="text-danger fw-semibold">เช็คเครื่องพิมพ์</span>
                    </a>
                </div>
            </div>
            <div class="text-center">
                <img src="{{ asset('logo/ko_envsogo.png') }}" alt="Staff" class="rounded-circle bg-white p-1 shadow-sm" width="56" height="56" style="object-fit: contain;">
            </div>
        </div>
    </div>

    <!-- เมนูหลักสไตล์ Mobile App Grid -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold text-dark mb-0">เมนูปฏิบัติงานภาคสนาม</h6>
        <small class="text-muted" style="font-size: 0.75rem;">แตะเพื่อเข้าสู่โมดูล</small>
    </div>

    <div class="row g-3 mb-3">
        <!-- 1. งานประปา -->
        <div class="col-6">
            <div class="mobile-module-card gradient-blue" onclick="navigateTo('tabwaterScreen')">
                <div class="module-icon-wrap">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold text-white mb-1">งานประปา</h6>
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">จดมิเตอร์น้ำ & แจ้งซ่อม</small>
                </div>
            </div>
        </div>

        <!-- 2. ธนาคารขยะรีไซเคิล -->
        <div class="col-6">
            <div class="mobile-module-card gradient-green" onclick="navigateTo('recycleBankScreen')">
                <div class="module-icon-wrap">
                    <i class="fa-solid fa-recycle"></i>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold text-white mb-1">ธนาคารขยะ</h6>
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">รับซื้อขยะรีไซเคิล & พิมพ์สลิป</small>
                </div>
            </div>
        </div>

        <!-- 3. คลังพัสดุ (ยืม/คืน) -->
        <div class="col-6">
            <div class="mobile-module-card gradient-purple" onclick="navigateTo('inventoryScreen')">
                <div class="module-icon-wrap">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold text-white mb-1">คลังพัสดุ</h6>
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">เบิก/ยืม-คืน พัสดุอุปกรณ์</small>
                </div>
            </div>
        </div>

        <!-- 4. ตั้งค่าเครื่องพิมพ์ Bluetooth -->
        <div class="col-6">
            <div class="mobile-module-card gradient-orange" onclick="navigateTo('settingsScreen')">
                <div class="module-icon-wrap">
                    <i class="fa-solid fa-print"></i>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold text-white mb-1">เครื่องพิมพ์ใบเสร็จ</h6>
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">เชื่อมต่อ Bluetooth Printer</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ทางลัดด่วน (Quick Actions) -->
    <div class="soft-card p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold text-dark" style="font-size: 0.85rem;">ทางลัดปฏิบัติงานด่วน</span>
        </div>

        <div class="d-flex align-items-center justify-content-between py-2 border-bottom" style="cursor: pointer;" onclick="navigateTo('tabwaterRecordScreen')">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info bg-opacity-10 p-2 rounded-3 text-info">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size: 0.82rem;">สายจดมิเตอร์น้ำประปา</div>
                    <small class="text-muted" style="font-size: 0.7rem;">เข้าสู่เส้นทางจดมิเตอร์ที่รับผิดชอบทันที</small>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
        </div>

        <div class="d-flex align-items-center justify-content-between pt-2" style="cursor: pointer;" onclick="navigateTo('water-complain')">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark" style="font-size: 0.82rem;">รายการแจ้งเหตุท่อแตก / ร้องเรียน</div>
                    <small class="text-muted" style="font-size: 0.7rem;">ตรวจสอบและรับงานซ่อมประปาภาคสนาม</small>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right text-muted small"></i>
        </div>
    </div>
</div>

