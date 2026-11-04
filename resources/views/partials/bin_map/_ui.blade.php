{{-- UI ร่วมของแผนที่ถังขยะ (เศษอาหาร / รายปี): toolbar, ตัวกรองตามเหตุแจ้ง, modal ผูกสมาชิก --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-stage { position: relative; overflow: hidden; border-radius: 8px; background: #e8edf0; }
    #map { height: min(76vh, 820px); min-height: 520px; width: 100%; }
    .map-pin-icon { color: #167d69; font-size: 25px; line-height: 30px; text-align: center; text-shadow: 0 1px 3px #fff, 0 0 2px #fff; }
    .map-pin-icon.is-new { color: #c44e32; }
    .map-toolbar { position: absolute; z-index: 1000; top: 14px; left: 56px; display: flex; flex-direction: column; gap: 6px; padding: 6px; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(33, 45, 55, .25); }
    .map-tool { position: relative; width: 44px; height: 44px; border: 1px solid #dbe2e8; background: #fff; border-radius: 10px; font-size: 18px; color: #167d69; cursor: pointer; }
    .map-tool:hover:not(:disabled) { background: #f1f5f9; }
    .map-tool.active { background: #167d69; color: #fff; border-color: #167d69; }
    .map-tool:disabled { opacity: .35; cursor: not-allowed; }
    .map-tool .badge-count { position: absolute; top: -6px; right: -6px; min-width: 20px; height: 20px; padding: 0 5px; border-radius: 10px; background: #c44e32; color: #fff; font-size: 11px; line-height: 20px; }
    .map-hint { position: absolute; z-index: 1000; top: 14px; left: 50%; transform: translateX(-50%); background: #1e293b; color: #fff; padding: 6px 16px; border-radius: 20px; font-size: 13px; }
    .map-stage.placing #map { cursor: crosshair; }
    .map-filter { position: absolute; z-index: 1000; left: 14px; bottom: 18px; right: 70px; display: flex; flex-wrap: wrap; gap: 6px; }
    .map-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px 4px 8px; border: 1px solid #dbe2e8; border-radius: 20px; background: #fff; font-size: 12px; cursor: pointer; box-shadow: 0 2px 8px rgba(33, 45, 55, .2); user-select: none; }
    .map-chip .dot { width: 14px; height: 14px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 1px rgba(0, 0, 0, .25); }
    .map-chip.off { opacity: .45; background: #f1f5f9; }
    .map-chip .cnt { color: #64748b; }
    .select2-container { z-index: 2000; }
    .map-stage:fullscreen { width: 100vw; height: 100vh; border-radius: 0; }
    .map-stage:fullscreen #map { height: 100vh; min-height: 100vh; }
    .map-stage:-webkit-full-screen { width: 100vw; height: 100vh; border-radius: 0; }
    .map-stage:-webkit-full-screen #map { height: 100vh; min-height: 100vh; }
    .leaflet-control-layers { border: 0 !important; border-radius: 6px !important; box-shadow: 0 2px 10px rgba(33, 45, 55, .18) !important; }
    @media (max-width: 767px) { #map { height: 70vh; min-height: 420px; } }
</style>

<div class="map-stage" id="mapStage">
    <div id="map" data-latitude="{{ $mapCfg['lat'] ?? '' }}" data-longitude="{{ $mapCfg['lng'] ?? '' }}" aria-label="แผนที่ตำแหน่งถังขยะ"></div>

    <div class="map-toolbar">
        <button class="map-tool" type="button" id="placeBinTool" title="วางถังขยะ (คลิกบนแผนที่ได้หลายจุด)" aria-pressed="false">
            <i class="fas fa-trash-alt" aria-hidden="true"></i>
        </button>
        <button class="map-tool" type="button" id="fitAllBinsButton" disabled title="แสดงถังทั้งหมด">
            <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
        </button>
        <button class="map-tool" type="button" id="fullscreenMapButton" title="ขยายแผนที่เต็มจอ">
            <i class="fas fa-expand" id="fullscreenMapIcon" aria-hidden="true"></i>
        </button>
    </div>
    <div class="map-hint" id="mapHint" hidden></div>
    <div class="map-filter" id="issueFilter" aria-label="กรองตามเหตุแจ้ง"></div>
</div>

<div class="modal fade" id="binModal" tabindex="-1" aria-labelledby="binModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="mapBinForm" class="modal-content" onsubmit="return false">
            <div class="modal-header">
                <h6 class="modal-title" id="binModalTitle"><i class="fas fa-trash-alt me-1"></i> ผูกถังกับสมาชิก</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger py-2" id="binFormError" hidden></div>
                <div class="mb-3">
                    <label class="form-label" for="member_id">สมาชิก</label>
                    <select class="form-select" id="member_id" style="width:100%"><option value=""></option></select>
                    <div class="text-warning text-xs mt-1" id="noMemberNote" hidden>ยังไม่มีสมาชิกให้เลือก</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="bin_ids">ถังที่ลงทะเบียนไว้ (เลือกได้หลายถัง)</label>
                    <select class="form-select" id="bin_ids" multiple style="width:100%"></select>
                    <div class="text-xs text-secondary mt-1" id="binOptNote">ถังแรกใช้พิกัดที่ปัก ถังถัดไปจะเลื่อนพิกัดเล็กน้อยเพื่อให้เห็นว่าซ้อนกัน</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="location_description">รายละเอียดตำแหน่ง</label>
                    <input class="form-control" id="location_description" maxlength="255" placeholder="บ้านเลขที่ / จุดสังเกต">
                </div>
                <div class="row g-2">
                    <div class="col-6"><label class="form-label">ละติจูด</label><input class="form-control" id="pin_lat" readonly></div>
                    <div class="col-6"><label class="form-label">ลองจิจูด</label><input class="form-control" id="pin_lng" readonly></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn bg-gradient-success" id="binSubmit"><i class="fas fa-save me-1"></i> บันทึก</button>
            </div>
        </form>
    </div>
</div>
