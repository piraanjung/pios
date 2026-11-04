<div class="mobile-sub-header bg-dark text-white p-3 d-flex align-items-center justify-content-between rounded-bottom-4 shadow-sm mb-3">
    <button type="button" onclick="backToMenuFromSettings()" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
        ⬅️ หน้าหลัก
    </button>
    <h6 class="fw-bold mb-0 text-white">ตั้งค่าเครื่องพิมพ์ใบเสร็จ</h6>
    <div style="width: 40px;"></div>
</div>

<div class="container px-2" style="max-width: 500px; margin: 10px auto;">
    <div class="soft-card text-center p-4">
        <div style="font-size: 48px; margin-bottom: 8px;">🖨️</div>
        <h5 class="fw-bold mb-1">Bluetooth Thermal Printer (58mm)</h5>
        <p class="text-muted small mb-3">
            กรุณาเปิดเครื่องพิมพ์ใบเสร็จและเปิด Bluetooth บนสมาร์ทโฟนก่อนกดเชื่อมต่อ
        </p>

        <div class="p-3 bg-light rounded-3 border mb-3">
            <span id="status" class="fw-bold text-danger" style="font-size: 14px;">
                🔴 ยังไม่ได้เชื่อมต่ออุปกรณ์
            </span>
        </div>

        <div id="status-card" class="status-badge bg-light text-secondary border rounded-3 p-2 mb-3 d-flex align-items-center justify-content-center gap-2">
            <span class="material-icons-round text-primary">🖨️</span>
            <div class="text-start">
                <small class="d-block text-uppercase fw-bold" style="font-size: 0.65rem;">สถานะการเชื่อมต่อ</small>
                <span id="status-text" class="small fw-bold">ยังไม่ได้เชื่อมต่อ</span>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="button" id="connectButton" class="btn btn-info text-white rounded-pill fw-bold py-2 shadow-sm">
                🔄 ค้นหา & เชื่อมต่อเครื่องพิมพ์ Bluetooth
            </button>
            <button type="button" id="printImageButton" onclick="printReceipt()" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                <span id="printBtnText">🧾 ทดสอบพิมพ์ใบเสร็จ</span>
            </button>
        </div>

        <canvas id="canvas" style="display: none;"></canvas>
        <canvas id="receiptCanvas" width="384" height="550" style="display: none;"></canvas>
    </div>
</div>

