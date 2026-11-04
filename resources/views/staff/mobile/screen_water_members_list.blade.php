<!-- ส่วนหัว + ปุ่มย้อนกลับไปหน้าเลือกเส้นทาง -->
<div class="mobile-sub-header bg-primary text-white p-3 d-flex align-items-center justify-content-between rounded-bottom-4 shadow-sm mb-3">
    <button type="button" onclick="navigateTo('tabwaterRecordScreen')" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
        ⬅️ เส้นทาง
    </button>
    <div class="text-center">
        <small class="d-block text-white-50" id="currentSubzoneTitle" style="font-size: 0.72rem;"></small>
        <h6 class="fw-bold mb-0 text-white">รายการจดมิเตอร์น้ำ</h6>
    </div>
    <span class="badge bg-white text-primary px-2 py-1 rounded-pill fw-bold" id="progressBadge">0 / 0</span>
</div>

<div class="container px-2" style="max-width: 600px; margin: 0 auto;">
    <!-- แถบสถานะเครื่องพิมพ์ -->
    <div id="global_printer_status" class="connect_bluethooth_status mb-3"
        style="background: #fff; padding: 10px 15px; border-radius: 12px; border-left: 5px solid #dc3545; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.05); cursor: pointer;">
        <div style="font-size: 13px; font-weight: bold; color: #495057;">
            🖨️ สถานะเครื่องพิมพ์: <span id="lbl_global_printer_name" style="color: #dc3545;">🔴 ยังไม่ได้เชื่อมต่อ (แตะเพื่อเชื่อมต่อ)</span>
        </div>
        <span id="lbl_global_printer_indicator" style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%;"></span>
    </div>

    <!-- ปุ่มถ่ายรูป OCR -->
    <div class="mb-3 text-center">
        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2 fw-bold" onclick="triggerMeterCamera()">
            📷 ถ่ายรูปหน้าปัดมิเตอร์เพื่ออ่านตัวเลข (AI OCR)
        </button>
        <input type="file" id="meterCameraInput" accept="image/*" capture="environment" class="d-none" onchange="handleImageCapture(event)">

        <div id="cropContainer" class="d-none mt-2" style="max-height: 300px; overflow: hidden;">
            <img id="imageToCrop" src="" style="max-width: 100%;">
        </div>

        <button type="button" id="btnProcessOcr" class="btn btn-sm btn-success rounded-pill w-100 mt-2 d-none" onclick="processCroppedOcrWithClientSide()">
            🔍 อ่านตัวเลขจากพื้นที่ที่เลือก
        </button>
    </div>

    <!-- Search Box + ปุ่ม QR Code -->
    <div class="row g-2 mb-3">
        <div class="col">
            <input type="text" id="searchMemberInput" class="form-control rounded-pill border-0 shadow-sm px-3"
                placeholder="🔍 ค้นหาบ้านเลขที่, ชื่อ หรือเลขมิเตอร์...">
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-primary rounded-pill shadow-sm px-3 fw-bold" onclick="openQrScannerTabwaterModal()">
                📷 QR
            </button>
        </div>
    </div>

    <!-- Container สำหรับ Render รายการสมาชิก -->
    <div id="membersListContainer" class="row g-2 pb-5">
        <!-- JS จะ Render การ์ดสมาชิกใส่ตรงนี้ -->
    </div>
</div>

<!-- Modal หน้าจอกล้องสแกน QR Code -->
<div class="modal fade" id="qrScannerTabwaterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-bold">📷 สแกน QR Code มิเตอร์/สมาชิก</h6>
                <button type="button" class="btn-close" onclick="closeQrScannerTabwaterModal()"></button>
            </div>
            <div class="modal-body p-3 text-center">
                <div id="qr-reader" style="width: 100%; max-width: 400px; margin: 0 auto;"></div>
                <small class="text-muted d-block mt-2">ส่องกล้องไปที่ QR Code บนป้ายมิเตอร์ หรือการ์ดสมาชิก</small>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับกรอก/บันทึกเลขมิเตอร์ -->
<div class="modal fade" id="recordMeterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h6 class="modal-title fw-bold text-white mb-0">📝 บันทึกเลขมิเตอร์น้ำ</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label text-muted small mb-0">ชื่อสมาชิก</label>
                    <h5 class="fw-bold text-dark mb-0" id="modalMeterUserName">-</h5>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <label class="form-label text-muted small mb-1">เลขจดครั้งก่อน</label>
                        <input type="text" id="modalLastMeterInput" class="form-control bg-light fw-bold" readonly>
                    </div>
                </div>

                <div class="mb-3 text-center">
                    <label class="form-label fw-bold text-primary small mb-2">เลขจดครั้งนี้ (แตะช่องที่ต้องการเริ่มพิมพ์) *</label>

                    <div class="d-flex justify-content-center gap-2" id="meterDigitContainer">
                        <input type="number" pattern="[0-9]*" maxlength="1"
                            class="form-control form-control-lg text-center fw-bold fs-3 meter-digit-input" id="digit1"
                            data-index="0" autocomplete="off">
                        <input type="number" pattern="[0-9]*" maxlength="1"
                            class="form-control form-control-lg text-center fw-bold fs-3 meter-digit-input" id="digit2"
                            data-index="1" autocomplete="off">
                        <input type="number" pattern="[0-9]*" maxlength="1"
                            class="form-control form-control-lg text-center fw-bold fs-3 meter-digit-input" id="digit3"
                            data-index="2" autocomplete="off">
                        <input type="number" pattern="[0-9]*" maxlength="1"
                            class="form-control form-control-lg text-center fw-bold fs-3 meter-digit-input" id="digit4"
                            data-index="3" autocomplete="off">
                    </div>

                    <input type="hidden" id="modalCurrentMeterInput" value="0">
                    <input type="number" pattern="[0-9]*" id="hiddenMeterInput"
                        style="opacity: 0; position: absolute; left: -9999px;" autocomplete="off">
                    <small class="text-muted d-block mt-1">แตะที่กล่องตัวเลข แล้วพิมพ์เลขมิเตอร์ได้เลย</small>
                </div>

                <div class="alert alert-info d-flex justify-content-between align-items-center py-2 px-3 rounded-3 mb-3 border-0">
                    <span class="mb-0 small fw-bold">ปริมาณน้ำที่ใช้:</span>
                    <h5 class="fw-bold mb-0 text-primary"><span id="modalWaterUsageText">0</span> ลบ.ม.</h5>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" id="btn-save-and-print" class="btn btn-success rounded-pill fw-bold py-2 flex-grow-2" style="flex: 2;">
                        <i class="fas fa-print me-1"></i> 
                        <div>บันทึก</div>
                        <div>และพิมพ์ใบแจ้งหนี้</div>
                    </button>
                    <button type="button" id="btn-save-only" class="btn btn-primary rounded-pill fw-bold py-2 flex-grow-1" style="flex: 1;">
                        <i class="fas fa-save me-1"></i>
                        <div>บันทึกข้อมูล</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>