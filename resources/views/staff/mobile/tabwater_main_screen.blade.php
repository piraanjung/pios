<div class="main-screen-container">
    <div class="container py-3">
        <!-- ส่วนหัว: ปุ่มกลับหน้าหลัก วันที่ และปุ่มเปิดเมนู -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button type="button" class="btn btn-light btn-sm rounded-pill shadow-sm px-3 fw-bold" onclick="navigateTo('mainScreen')">
                <i class="fa-solid fa-arrow-left me-1 text-primary"></i> หน้าหลัก
            </button>
            <div class="text-muted fw-semibold" style="font-size: 0.85rem;">💧 ระบบงานประปา</div>
            <button type="button" class="btn btn-light rounded-circle shadow-sm" style="width: 38px; height: 38px;" onclick="toggleFullSidebar()">
                <i class="fa-solid fa-bars text-primary"></i>
            </button>
        </div>

        <h5 class="fw-bold text-dark mb-3">ภารกิจงานประปา (Water Tasks)</h5>

        <!-- ส่วนกริดการ์ดเมนู 4 ช่อง -->
        <div class="row g-3 mb-4">
            <!-- การ์ดที่ 1: จดมิเตอร์ประปา -->
            <div class="col-6">
                <div class="task-card card-pink" onclick="navigateTo('tabwaterRecordScreen')">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon-box">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                        <i class="fa-solid fa-arrow-right card-arrow"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-1">จดมิเตอร์ประปา</h6>
                        <small class="text-white-50">เลือกสายจดมิเตอร์</small>
                    </div>
                </div>
            </div>

            <!-- การ์ดที่ 2: เรื่องร้องเรียน / แจ้งเหตุ -->
            <div class="col-6">
                <div class="task-card card-orange" onclick="navigateTo('water-complain')">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon-box">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </div>
                        <i class="fa-solid fa-arrow-right card-arrow"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-1">รับเรื่องแจ้งเหตุ</h6>
                        <small class="text-white-50">ท่อแตก / ซ่อมประปา</small>
                    </div>
                </div>
            </div>

            <!-- การ์ดที่ 3: ตัดมิเตอร์ / ค้างชำระ -->
            <div class="col-6">
                <div class="task-card card-purple" onclick="navigateTo('tabwaterRecordScreen')">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon-box">
                            <i class="fa-solid fa-faucet-drip"></i>
                        </div>
                        <i class="fa-solid fa-arrow-right card-arrow"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-1">ตรวจสอบมิเตอร์</h6>
                        <small class="text-white-50">เช็คสถานะรายสาย</small>
                    </div>
                </div>
            </div>

            <!-- การ์ดที่ 4: ตั้งค่าเครื่องพิมพ์ใบแจ้งหนี้ -->
            <div class="col-6">
                <div class="task-card card-blue" onclick="navigateTo('settingsScreen')">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="card-icon-box">
                            <i class="fa-solid fa-print"></i>
                        </div>
                        <i class="fa-solid fa-arrow-right card-arrow"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-white mb-1">ตั้งค่าเครื่องพิมพ์</h6>
                        <small class="text-white-50">พิมพ์ใบแจ้งหนี้ 58mm</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- การ์ดทางลัดเข้าหน้าจดมิเตอร์ -->
        <div class="soft-card p-3 shadow-sm d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="navigateTo('tabwaterRecordScreen')">
            <div>
                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">เริ่มปฏิบัติงานจดมิเตอร์ประจำรอบบิล</h6>
                <div class="d-flex align-items-center text-muted mb-2" style="font-size: 0.75rem;">
                    <i class="fa-regular fa-clock me-1 text-primary"></i> รองรับสแกน QR Code และอ่านเลขมิเตอร์ด้วย AI OCR
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill" style="font-size: 0.7rem;">แตะเพื่อเลือกเส้นทาง ➔</span>
            </div>
            <div class="fs-2">💧</div>
        </div>
    </div>
</div>
