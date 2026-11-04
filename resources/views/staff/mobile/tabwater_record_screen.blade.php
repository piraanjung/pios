<div class="mobile-sub-header bg-primary text-white p-3 d-flex align-items-center justify-content-between rounded-bottom-4 shadow-sm mb-3">
    <button type="button" onclick="navigateTo('tabwaterScreen')" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
        ⬅️ ย้อนกลับ
    </button>
    <h6 class="fw-bold mb-0 text-white">เลือกเส้นทางจดมิเตอร์</h6>
    <div style="width: 40px;"></div>
</div>

<div class="container p-2" style="max-width: 600px; margin: 0 auto;">
    <!-- การ์ดแสดงข้อมูลเจ้าหน้าที่ & รอบบิล -->
    <div class="soft-card mb-3 p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="badge bg-primary px-3 py-2 rounded-pill inv_period" style="font-size: 13px;">รอบบิลที่ -</span>
            <small class="text-muted">เจ้าหน้าที่จดมิเตอร์</small>
        </div>
        <h6 class="fw-bold text-dark mb-0 twman_name">คุณ...</h6>
    </div>

    <div class="alert alert-warning fw-bold mb-3 py-2 px-3 text-sm rounded-3 border-0 shadow-sm">
        📍 เส้นทางจดมิเตอร์ที่รับผิดชอบ
    </div>

    <!-- รายการ Subzone จะถูก Render ใส่ในนี้ -->
    <div class="row g-3" id="subzone">
        <!-- ข้อมูล Render จาก JavaScript -->
    </div>
</div>
