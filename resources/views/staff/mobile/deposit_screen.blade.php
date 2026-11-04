<div class="mobile-sub-header bg-success text-white p-3 d-flex align-items-center justify-content-between rounded-bottom-4 shadow-sm mb-3">
    <button type="button" onclick="backToRecycleScreen()" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
        ⬅️ ย้อนกลับ
    </button>
    <h6 class="fw-bold mb-0 text-white">รับซื้อขยะรีไซเคิล</h6>
    <div style="width: 40px;"></div>
</div>

<div class="container-fluid px-2" style="max-width: 600px; margin: 0 auto;">
    <div id="globalPrinterStatus"
        style="background: #fff; padding: 10px 15px; margin-bottom: 12px; border-radius: 12px; border-left: 5px solid #dc3545; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="font-size: 13px; font-weight: bold; color: #495057;">
            🖨️ เครื่องพิมพ์: <span id="lblGlobalPrinterName" style="color: #dc3545;">🔴 ยังไม่ได้เชื่อมต่อ</span>
        </div>
        <span id="lblGlobalPrinterIndicator" style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%;"></span>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
        <span class="fw-bold text-dark small">ข้อมูลสมาชิกผู้ฝากขยะ</span>
        <span class="badge bg-light text-secondary rounded-pill border px-3 py-1" style="font-size: 12px;">
            👤 <span id="lblActiveStaffName"></span>
        </span>
    </div>

    <div class="soft-card mb-3 p-3" style="border-left: 5px solid #28a745;">
        <small class="text-muted fw-bold d-block mb-1">ผู้มาติดต่อ / สมาชิก</small>
        <h6 id="lblDepositMemberName" class="fw-bold mb-1 text-dark">คุณ...</h6>
        <small class="text-success fw-bold">💳 เลขที่บัญชี: <span id="lblDepositMemberAcc">-</span></small>
    </div>

    <div class="soft-card p-3 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="fw-bold text-dark mb-0">เลือกหมวดหมู่ขยะ</label>
            <button type="button" id="btnScanTrashQR" class="btn btn-sm btn-light text-primary rounded-pill fw-bold px-3">
                📷 สแกน QR ขยะ
            </button>
        </div>

        <!-- หมวดหมู่ขยะหลัก -->
        <div class="row g-2 text-center" id="groupFiltersContainer"></div>

        <!-- Bottom Sheet Modal: เลือกรายการขยะย่อย -->
        <div id="categoryItemsModal" class="weight-modal-container">
            <div class="weight-modal-overlay" onclick="closeCategoryModal()"></div>
            <div class="weight-modal-content" style="max-height: 80vh; overflow-y: auto;">
                <div class="weight-modal-handle"></div>
                <div class="weight-modal-header">
                    <span style="font-size: 17px; font-weight: bold; color: #1e3c72;">เลือกรายการขยะ</span>
                    <button type="button" class="btn-weight-modal-close" onclick="closeCategoryModal()">✕</button>
                </div>
                <div class="weight-modal-body">
                    <div class="bg-light rounded-4 p-3 mb-2">
                        <div id="item-buttons-container"
                            style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; max-height: 300px; overflow-y: auto;">
                            <div style="grid-column: span 2; text-align: center; color: #6c757d; padding: 30px 0;">
                                👋<br><small>เลือกหมวดหมู่ด้านบนเพื่อเริ่มรายการ</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Sheet Modal: กรอกน้ำหนัก -->
        <div id="weightInputModal" class="weight-modal-container">
            <div class="weight-modal-overlay" onclick="closeWeightModal()"></div>
            <div class="weight-modal-content">
                <div class="weight-modal-handle"></div>
                <div class="weight-modal-header">
                    <div>
                        <small class="text-muted d-block">รายการที่เลือก:</small>
                        <span id="modal-selected-item-name" style="font-size: 18px; font-weight: bold; color: #1e3c72;">-</span>
                    </div>
                    <button type="button" class="btn-weight-modal-close" onclick="closeWeightModal()">✕</button>
                </div>
                <div class="weight-modal-body">
                    <div class="p-3 bg-white border rounded-4">
                        <div class="row g-2 mb-3 align-items-end">
                            <div class="col-7">
                                <label class="small text-muted mb-1 d-block">จำนวน / น้ำหนัก</label>
                                <input type="number" step="0.01" id="amount_in_units" class="form-control text-center fw-bold fs-3"
                                    placeholder="0.00" style="height: 56px; background: #f8f9fa;">
                            </div>
                            <div class="col-5">
                                <div id="unit-buttons-container" style="display: flex; gap: 6px; height: 56px; align-items: stretch;"></div>
                                <input type="hidden" id="kp_units_idfk">
                            </div>
                        </div>
                        <button type="button" id="btnAddToCart" class="btn btn-success w-100 py-3 rounded-pill shadow fw-bold">
                            ➕ เพิ่มรายการลงตะกร้า
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="floatingCartContainer"></div>
    </div>
</div>

