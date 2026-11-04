@extends('layouts.admin1')

@section('content')
    <!-- Leaflet CSS & JS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        /* สไตล์ภาชนะแผนที่ปกติ */
        .gis-container {
            position: relative;
            width: 100%;
            height: 68vh;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.25s ease-in-out;
        }

        #gisMap {
            width: 100%;
            height: 100%;
        }

        .gis-floating-panel {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            padding: 12px;
            border-radius: 12px;
            width: 340px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.18);
            max-height: 60vh;
            overflow-y: auto;
            transition: max-height 0.25s ease-in-out;
        }

        /* 1. สไตล์สำหรับโหมดขยายแผนที่เต็มหน้าจอ (Fullscreen Mode) */
        .gis-container.fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 99999 !important;
            border-radius: 0 !important;
            margin: 0 !important;
        }

        .gis-container.fullscreen .gis-floating-panel {
            max-height: 85vh !important;
            top: 20px !important;
            right: 20px !important;
        }

        /* 2. สไตล์สำหรับซ่อน Sidebar / Navbar / Header ของ Admin */
        body.gis-fullscreen-active .sidebar,
        body.gis-fullscreen-active .main-sidebar,
        body.gis-fullscreen-active .app-sidebar,
        body.gis-fullscreen-active .navbar,
        body.gis-fullscreen-active .main-header,
        body.gis-fullscreen-active .app-header,
        body.gis-fullscreen-active footer,
        body.gis-fullscreen-active .main-footer {
            display: none !important;
        }

        body.gis-fullscreen-active .content-wrapper,
        body.gis-fullscreen-active .main-content,
        body.gis-fullscreen-active .page-content {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 0 !important;
        }

        /* 3. ดัน z-index ของ Modal และ Backdrop ให้อยู่เหนือ Fullscreen Map (แก้ไขปัญหา Modal ซ่อนหลังแผนที่) */
        .modal-backdrop {
            z-index: 100000 !important;
        }

        .modal {
            z-index: 100001 !important;
        }

        /* สไตล์ UI ปุ่มสัญลักษณ์ */
        .icon-picker-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .icon-picker-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 2px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .icon-picker-btn:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .icon-picker-btn.active {
            background: #e0f2fe;
            border-color: #0284c7;
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.25);
        }

        .icon-picker-btn .icon-symbol {
            font-size: 20px;
            line-height: 1;
            margin-bottom: 2px;
        }

        .icon-picker-btn .icon-label {
            font-size: 10px;
            font-weight: 600;
            color: #334155;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .pipe-label-style {
            background: rgba(15, 23, 42, 0.9);
            color: #ffffff;
            border: none;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            padding: 2px 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .landmark-emoji-icon {
            font-size: 22px;
            text-align: center;
            line-height: 24px;
        }

        .node-sequence-badge {
            font-size: 0.7rem;
            font-family: monospace;
        }
    </style>

    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold text-dark mb-0">💧 ระบบผังโครงข่ายประปาอัจฉริยะ (Smart Water Utility GIS)</h4>
                <small class="text-muted">กำหนดโซนจ่ายน้ำ ขอบเขตหมู่บ้าน พิกัดอาคารผลิต วาดเส้นท่อเมน/ท่อรอง พร้อมระบบ
                    Multi-Node Snapping</small>
            </div>
            <div>
                <button type="button" class="btn btn-success fw-bold px-4 shadow-sm" onclick="saveAllGisData()"> 💾
                    บันทึกโครงข่าย GIS </button>
            </div>
        </div>

        <!-- แผนที่ Leaflet Container -->
        <div class="gis-container mb-3" id="gisContainer">
            <div id="gisMap"></div>

            <!-- Floating Control Panel -->
            <div class="gis-floating-panel">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-primary mb-0">🛠️ เครื่องมือผังประปา</h6>
                    <div class="d-flex gap-1">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-dark active" id="btnOsm"
                                onclick="switchTile('osm')">🗺️</button>
                            <button type="button" class="btn btn-outline-dark" id="btnSat"
                                onclick="switchTile('sat')">🛰️</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-dark fw-bold" id="btnFullscreen"
                            onclick="toggleMapFullscreen()" title="ขยาย/ย่อ แผนที่เต็มหน้าจอ (หรือกด ESC)"> ⛶ เต็มจอ
                        </button>
                    </div>
                </div>

                <!-- เลือกโซนแสดงผล -->
                <div class="p-2 bg-light border rounded-3 mb-2">
                    <label class="form-label text-xs fw-bold text-primary mb-1">🎯 เลือกโซนพื้นที่แสดงผล:</label>
                    <select id="filterZoneSelect" class="form-select form-select-sm fw-bold border-primary"
                        onchange="filterMapByZone(this.value)">
                        <option value="all">🌐 แสดงทุกโซนพื้นที่ (Overview All)</option>
                        @if(isset($stations))
                            @foreach($stations as $st)
                                <option value="{{ $st->id }}">🏭 โซนสถานี: {{ $st->name }}</option>
                            @endforeach
                        @else
                            <option value="1">🏭 โซนสถานีผลิตน้ำ หมู่ 12</option>
                            <option value="2">🏭 โซนสถานีผลิตน้ำ หนองบัว</option>
                        @endif
                    </select>
                </div>

                <!-- เลือกไอคอน / องค์ประกอบประปา -->
                <div class="mb-2">
                    <label class="form-label text-xs fw-bold text-dark mb-1">1. เลือกสัญลักษณ์ / อาคารประปา /
                        สายทาง:</label>
                    <div class="icon-picker-grid" id="iconPickerContainer">
                        <div class="icon-picker-btn active" data-key="station" data-symbol="🏭"
                            onclick="selectSymbol('station', '🏭', 'โรงผลิตน้ำ', 'marker')"> <span
                                class="icon-symbol">🏭</span> <span class="icon-label">โรงผลิตน้ำ</span> </div>
                        <div class="icon-picker-btn" data-key="raft" data-symbol="🚤"
                            onclick="selectSymbol('raft', '🚤', 'แพสูบน้ำ', 'marker')"> <span class="icon-symbol">🚤</span>
                            <span class="icon-label">แพสูบน้ำ</span>
                        </div>
                        <div class="icon-picker-btn" data-key="water_tower" data-symbol="🏰"
                            onclick="selectSymbol('water_tower', '🏰', 'หอส่งน้ำ/ถังสูง', 'marker')"> <span
                                class="icon-symbol">🏰</span> <span class="icon-label">หอส่งน้ำ</span> </div>
                        <div class="icon-picker-btn" data-key="water_tank" data-symbol="🛢️"
                            onclick="selectSymbol('water_tank', '🛢️', 'ถังพักน้ำ', 'marker')"> <span
                                class="icon-symbol">🛢️</span> <span class="icon-label">ถังพักน้ำ</span> </div>
                        <div class="icon-picker-btn" data-key="junction" data-symbol="🔀"
                            onclick="selectSymbol('junction', '🔀', 'ข้อต่อ/สามแยก', 'marker')"> <span
                                class="icon-symbol">🔀</span> <span class="icon-label">ข้อต่อ/สามแยก</span> </div>
                        <div class="icon-picker-btn" data-key="valve" data-symbol="🛑"
                            onclick="selectSymbol('valve', '🛑', 'วาล์วประตูน้ำ', 'marker')"> <span
                                class="icon-symbol">🛑</span> <span class="icon-label">ประตูน้ำ</span> </div>
                        <div class="icon-picker-btn" data-key="meter" data-symbol="💧"
                            onclick="selectSymbol('meter', '💧', 'มิเตอร์น้ำ', 'marker')"> <span
                                class="icon-symbol">💧</span> <span class="icon-label">มิเตอร์น้ำ</span> </div>
                        <div class="icon-picker-btn" data-key="label_text" data-symbol="🏷️"
                            onclick="selectSymbol('label_text', '🏷️', 'ป้าย Label', 'marker')"> <span
                                class="icon-symbol">🏷️</span> <span class="icon-label">ป้าย Label</span> </div>
                        <div class="icon-picker-btn" data-key="pipe_main" data-symbol="〰️"
                            onclick="selectSymbol('pipe_main', '〰️', 'ท่อเมนหลัก', 'polyline')"> <span
                                class="icon-symbol">〰️</span> <span class="icon-label">ท่อเมนหลัก</span> </div>
                        <div class="icon-picker-btn" data-key="pipe_sub" data-symbol="┈"
                            onclick="selectSymbol('pipe_sub', '┈', 'ท่อรอง/ซอย', 'polyline')"> <span
                                class="icon-symbol">┈</span> <span class="icon-label">ท่อรอง/ซอย</span> </div>
                        <div class="icon-picker-btn" data-key="zone_boundary" data-symbol="⭕"
                            onclick="selectSymbol('zone_boundary', '⭕', 'ขอบเขตโซนจ่ายน้ำ', 'polygon')"> <span
                                class="icon-symbol">⭕</span> <span class="icon-label">โซนจ่ายน้ำ</span> </div>
                        <div class="icon-picker-btn" data-key="village_boundary" data-symbol="🏡"
                            onclick="selectSymbol('village_boundary', '🏡', 'ขอบเขตหมู่บ้าน', 'polygon')"> <span
                                class="icon-symbol">🏡</span> <span class="icon-label">เขตหมู่บ้าน</span> </div>
                    </div>
                </div>

                <!-- เลือกรูปแบบเรขาคณิต -->
                <div class="mb-2">
                    <label class="form-label text-xs fw-bold text-dark mb-1">2. รูปแบบเรขาคณิต:</label>
                    <div class="btn-group btn-group-sm w-100" role="group">
                        <button type="button" class="btn btn-outline-primary active fw-bold" id="geoMarker"
                            onclick="selectGeometryType('marker')"> 📍 หมุดไอคอน </button>
                        <button type="button" class="btn btn-outline-primary fw-bold" id="geoPolyline"
                            onclick="selectGeometryType('polyline')"> 📏 เส้นสายทาง </button>
                        <button type="button" class="btn btn-outline-primary fw-bold" id="geoPolygon"
                            onclick="selectGeometryType('polygon')"> 📐 ขอบเขตพื้นที่ </button>
                    </div>
                </div>

                <div class="alert alert-info py-2 px-2 text-xs mb-2 d-flex align-items-center">
                    <span class="me-1">💡</span>
                    <span id="instructionText">คลิกบนแผนที่เพื่อปักหมุดไอคอน</span>
                </div>

                <!-- ปุ่ม Undo / Redo (แก้ไขโครงสร้างปุ่มให้เรียบร้อย) -->
                <div class="d-flex gap-1 mb-2">
                    <button type="button" class="btn btn-sm btn-outline-warning w-100 fw-bold" id="btnUndo"
                        onclick="undoLastAction()" disabled title="คีย์ลัด: Ctrl + Z"> ↺ เลิกทำ </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 fw-bold" id="btnRedo"
                        onclick="redoLastAction()" disabled title="คีย์ลัด: Ctrl + Y"> ↻ ทำซ้ำ </button>
                </div>

                <div class="d-grid gap-1">
                    <button type="button" class="btn btn-sm btn-success fw-bold" id="btnFinish"
                        onclick="promptForElementDetails()" disabled> ✅ เสร็จสิ้นการวาดพื้นที่/เส้นนี้ </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btnCancel"
                        onclick="cancelCurrentDrawing()" disabled> ❌ ยกเลิกวาดเส้นนี้ </button>
                </div>
            </div>
        </div>

        <!-- Live Topology Inspector Panel -->
        <div class="card bg-dark text-white shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body py-2 px-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success">🔗 Topology Inspector</span>
                        <small class="text-white-50">ลำดับการเชื่อมต่อโหนดและพิกัดเส้นสายทางปัจจุบัน:</small>
                    </div>
                    <small class="text-warning fw-bold" id="liveNodeCountText">โหนดที่จับได้: 0 จุด | พิกัดระหว่างทาง: 0
                        จุด</small>
                </div>
                <div class="mt-1 font-monospace text-xs text-info text-truncate" id="liveNodeSequenceText"> ลำดับโหนด:
                    (ยังไม่มีการวาดเส้น) </div>
            </div>
        </div>

        <!-- ตารางรายการองค์ประกอบ -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-primary mb-0">🏢 ตารางรายการองค์ประกอบผังประปา (Water Utility Network Inventory)
                </h6>
                <span class="badge bg-primary-subtle text-primary fw-bold" id="elementCountBadge">ทั้งหมด 0 รายการ</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-sm">
                        <thead class="table-light text-nowrap">
                            <tr>
                                <th style="width: 50px;" class="text-center">#</th>
                                <th style="width: 140px;">ประจำโซน</th>
                                <th style="width: 140px;">ประเภทองค์ประกอบ</th>
                                <th>ชื่อเรียก / Label กำกับสถานที่</th>
                                <th>ลำดับโหนดที่เชื่อมต่อ (Connected Node Chains)</th>
                                <th style="width: 180px;">พิกัด / จำนวนจุด</th>
                                <th style="width: 120px;" class="text-end pe-3">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="elementTableBody">
                            <tr id="emptyElementRow">
                                <td colspan="7" class="text-center text-muted py-4">
                                    ยังไม่มีรายการองค์ประกอบบนแผนที่ในโซนที่เลือก</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ระบุชื่อสถานที่ -->
    <div class="modal fade" id="gisLabelModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title fw-bold" id="modalTitle">🏷️ ระบุชื่อสถานที่และสังกัดโซนพื้นที่</h6>
                    <button type="button" class="btn-close btn-close-white" onclick="dismissModalCancel()"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">สังกัดโซนสถานีผลิตน้ำ <span
                                class="text-danger">*</span></label>
                        <select id="modalStationSelect" class="form-select">
                            @if(isset($stations))
                                @foreach($stations as $st)
                                    <option value="{{ $st->id }}">🏭 {{ $st->name }}</option>
                                @endforeach
                            @else
                                <option value="1">🏭 สถานีผลิตน้ำ หมู่ 12</option>
                                <option value="2">🏭 สถานีผลิตน้ำ หนองบัว</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">ชื่อ Label กำกับองค์ประกอบ/สายทาง <span
                                class="text-danger">*</span></label>
                        <input type="text" id="modalLabelInput" class="form-control"
                            placeholder="เช่น หอส่งน้ำหนองบัว หรือ ท่อเมนถนนหลัก" autofocus>
                    </div>
                    <div class="mb-3" id="modalSizeGroup" style="display: none;">
                        <label class="form-label small fw-bold">ขนาดท่อ (มม.)</label>
                        <select id="modalSizeSelect" class="form-select">
                            <option value="300">300 มม. (ท่อประธานใหญ่)</option>
                            <option value="200">200 มม.</option>
                            <option value="150" selected>150 มม. (ท่อเมนมาตรฐาน)</option>
                            <option value="100">100 มม.</option>
                            <option value="80">80 มม.</option>
                            <option value="55">55 มม. (ท่อซอย)</option>
                            <option value="40">40 มม.</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary fw-bold"
                        onclick="dismissModalCancel()">ยกเลิก</button>
                    <button type="button" class="btn btn-sm btn-primary fw-bold px-3"
                        onclick="confirmSaveElementDetails()">💾 บันทึกข้อมูล</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let map;
        let osmLayer, satelliteLayer;
        let selectedSymbolKey = 'station';
        let selectedSymbolEmoji = '🏭';
        let selectedSymbolName = 'โรงผลิตน้ำ';
        let selectedGeometryType = 'marker';
        let currentActiveZone = 'all';
        const SNAP_RADIUS_METERS = 25;
        let registeredNodes = [];
        let activePath = [];
        let activeConnectedNodes = [];
        let activeShape = null;
        let pendingTempMarker = null;
        let gisDataStore = { landmarks: [], nodes: [], pipes: [], areas: [] };
        let actionHistoryStack = [];
        let redoHistoryStack = [];
        let labelModalInstance = null;
        let snapHighlightCircle = null;

        document.addEventListener("DOMContentLoaded", function () {
            labelModalInstance = new bootstrap.Modal(document.getElementById('gisLabelModal'));
            initGisMap();

            document.addEventListener('keydown', function (e) {
                if (e.ctrlKey && e.key.toLowerCase() === 'z') { e.preventDefault(); undoLastAction(); }
                else if (e.ctrlKey && e.key.toLowerCase() === 'y') { e.preventDefault(); redoLastAction(); }
                else if (e.key === 'Escape') {
                    const container = document.getElementById('gisContainer');
                    if (container && container.classList.contains('fullscreen')) {
                        toggleMapFullscreen();
                    }
                }
            });
        });

        function toggleMapFullscreen() {
            const container = document.getElementById('gisContainer');
            const btn = document.getElementById('btnFullscreen');
            container.classList.toggle('fullscreen');
            document.body.classList.toggle('gis-fullscreen-active');
            if (container.classList.contains('fullscreen')) {
                btn.innerHTML = '🗗 ย่อจอ';
                btn.classList.replace('btn-dark', 'btn-warning');
            } else {
                btn.innerHTML = '⛶ เต็มจอ';
                btn.classList.replace('btn-warning', 'btn-dark');
            }
            setTimeout(() => {
                if (map) map.invalidateSize();
            }, 250);
        }

        function createEmojiMarkerIcon(emoji) {
            return L.divIcon({
                html: `<div class="landmark-emoji-icon">${emoji}</div>`,
                className: 'custom-landmark-div-icon',
                iconSize: [30, 30], iconAnchor: [15, 15]
            });
        }

        function initGisMap() {
            map = L.map('gisMap').setView([16.2685302, 104.4011436], 14);
            osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxNativeZoom: 19, maxZoom: 25 }).addTo(map);
            satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19 });

            map.on('click', function (e) {
                let latlng = [e.latlng.lat, e.latlng.lng];
                if (['polyline', 'polygon'].includes(selectedGeometryType)) {
                    const snapResult = getSnappedCoordinate(e.latlng);
                    latlng = snapResult.latlng;
                    if (snapResult.snappedNode) {
                        activeConnectedNodes.push(snapResult.snappedNode);
                    }
                }

                if (selectedGeometryType === 'marker') {
                    pendingTempMarker = latlng;
                    openLabelModal();
                } else if (selectedGeometryType === 'polyline') {
                    activePath.push(latlng);
                    let lineStyle = (selectedSymbolKey === 'pipe_main') ? { color: '#0284c7', weight: 6 } : { color: '#38bdf8', weight: 3, dashArray: '6, 6' };
                    if (!activeShape) {
                        activeShape = L.polyline(activePath, lineStyle).addTo(map);
                    } else {
                        activeShape.setLatLngs(activePath);
                    }
                    updateLiveTopologyInspector();
                    toggleDrawButtons(true);
                    updateUndoRedoButtons();
                } else if (selectedGeometryType === 'polygon') {
                    activePath.push(latlng);
                    let polyStyle = (selectedSymbolKey === 'zone_boundary') ? { color: '#0284c7', fillColor: '#38bdf8', fillOpacity: 0.2, weight: 3 } : { color: '#16a34a', fillColor: '#4ade80', fillOpacity: 0.25, weight: 2, dashArray: '5, 5' };
                    if (!activeShape) {
                        activeShape = L.polygon(activePath, polyStyle).addTo(map);
                    } else {
                        activeShape.setLatLngs(activePath);
                    }
                    updateLiveTopologyInspector();
                    toggleDrawButtons(true);
                    updateUndoRedoButtons();
                }
            });

            map.on('contextmenu', function (e) {
                // ป้องกันไม่ให้ Browser เปิด Menu คลิกขวามาตรฐานขึ้นมา
                L.DomEvent.preventDefault(e.originalEvent);

                // ทำงานเฉพาะเมื่อกำลังอยู่ในโหมดวาดเส้นท่อหรือขอบเขตพื้นที่ และมีจุดวาดค้างอยู่
                if (['polyline', 'polygon'].includes(selectedGeometryType) && activePath.length > 0) {
                    undoLastAction(); // เรียกใช้ฟังก์ชันถอยจุดวาดทีละจุด
                }
            });
        }

        function getSnappedCoordinate(clickLatLng) {
            let nearestNode = null;
            let minDistance = Infinity;
            const allAvailableNodes = [...registeredNodes, ...gisDataStore.landmarks];
            allAvailableNodes.forEach(node => {
                const nodeLatLng = L.latLng(node.lat, node.lng);
                const dist = clickLatLng.distanceTo(nodeLatLng);
                if (dist <= SNAP_RADIUS_METERS && dist < minDistance) {
                    minDistance = dist;
                    nearestNode = node;
                }
            });
            if (nearestNode) {
                highlightSnapTarget([nearestNode.lat, nearestNode.lng]);
                return { latlng: [nearestNode.lat, nearestNode.lng], snappedNode: nearestNode };
            }
            return { latlng: [clickLatLng.lat, clickLatLng.lng], snappedNode: null };
        }

        function highlightSnapTarget(latlng) {
            if (snapHighlightCircle) map.removeLayer(snapHighlightCircle);
            snapHighlightCircle = L.circleMarker(latlng, { radius: 12, fillColor: '#22c55e', color: '#15803d', weight: 3, fillOpacity: 0.6 }).addTo(map);
            setTimeout(() => {
                if (snapHighlightCircle) {
                    map.removeLayer(snapHighlightCircle);
                    snapHighlightCircle = null;
                }
            }, 800);
        }

        // ======================================================== 
        // ↺ ฟังก์ชัน Undo รองรับทั้ง "ถอยจุดพิกัดวาดทีละจุด" และ "ย้อนกลับวัตถุชิ้นล่าสุด" 
        // ======================================================== 
        function undoLastAction() {
            // ---------------------------------------------------- 
            // กรณีที่ 1: ขณะกำลังวาดเส้น/พื้นที่อยู่ (มีจุดวาดค้างใน activePath) 
            // ---------------------------------------------------- 
            if (activePath.length > 0) {
                activePath.pop();

                if (activeConnectedNodes.length > 0 && activePath.length < activeConnectedNodes.length) {
                    activeConnectedNodes.pop();
                }

                if (activePath.length === 0) {
                    if (activeShape) {
                        map.removeLayer(activeShape);
                        activeShape = null;
                    }
                    toggleDrawButtons(false);
                } else {
                    if (activeShape) {
                        activeShape.setLatLngs(activePath);
                    }
                }

                updateLiveTopologyInspector();
                updateUndoRedoButtons();
                return;
            }

            // ---------------------------------------------------- 
            // กรณีที่ 2: ไม่ได้วาดเส้นค้างอยู่ -> ย้อนกลับการสร้างชิ้นงานล่าสุด (Object-level Undo) 
            // ---------------------------------------------------- 
            if (actionHistoryStack.length === 0) return;

            const action = actionHistoryStack.pop();

            if (action.layer) map.removeLayer(action.layer);

            const storeArr = gisDataStore[action.categoryKey];
            if (storeArr) {
                const idx = storeArr.indexOf(action.dataStoreRef);
                if (idx > -1) storeArr.splice(idx, 1);
            }

            if (action.categoryKey === 'nodes') {
                const nodeIdx = registeredNodes.indexOf(action.dataStoreRef);
                if (nodeIdx > -1) registeredNodes.splice(nodeIdx, 1);
            }

            renderElementsTable();
            redoHistoryStack.push(action);
            updateUndoRedoButtons();
        }

        function redoLastAction() {
            if (redoHistoryStack.length === 0) return;
            const action = redoHistoryStack.pop();
            if (action.layer) action.layer.addTo(map);
            const storeArr = gisDataStore[action.categoryKey];
            if (storeArr) storeArr.push(action.dataStoreRef);
            renderElementsTable();
            actionHistoryStack.push(action);
            updateUndoRedoButtons();
        }

        function updateUndoRedoButtons() {
            const btnUndo = document.getElementById('btnUndo');
            const btnRedo = document.getElementById('btnRedo');

            if (btnUndo) {
                btnUndo.disabled = (activePath.length === 0 && actionHistoryStack.length === 0);
            }
            if (btnRedo) {
                btnRedo.disabled = (redoHistoryStack.length === 0);
            }
        }

        function updateLiveTopologyInspector() {
            const countText = document.getElementById('liveNodeCountText');
            const seqText = document.getElementById('liveNodeSequenceText');

            // คำนวณความยาวเส้นปัจจุบัน
            const totalMeters = calculatePathDistance(activePath);
            const distStr = formatDistanceText(totalMeters);

            if (selectedGeometryType === 'polyline') {
                countText.innerText = `โหนดที่จับได้: ${activeConnectedNodes.length} จุด | พิกัดระหว่างทาง: ${activePath.length} จุด | 📏 ความยาวท่อ: ${distStr}`;
            } else {
                countText.innerText = `โหนดที่จับได้: ${activeConnectedNodes.length} จุด | พิกัดระหว่างทาง: ${activePath.length} จุด`;
            }

            if (activeConnectedNodes.length === 0) {
                seqText.innerHTML = `ลำดับโหนด: <span class="text-white-50">(ยังจับไม่โดนโหนดอ้างอิง - วาดพิกัดทั่วไป)</span>`;
            } else {
                const chainStr = activeConnectedNodes.map((n, idx) => `[${idx + 1}] ${n.emoji || '📍'} ${n.name} (ID:${n.id})`).join(' ➔ ');
                seqText.innerHTML = `ลำดับโหนด: <span class="text-warning">${chainStr}</span>`;
            }

            // อัปเดต Label Tooltip บนเส้นที่กำลังวาดในแผนที่
            if (activeShape && selectedGeometryType === 'polyline' && activePath.length >= 2) {
                const lastPt = activePath[activePath.length - 1];
                activeShape.bindTooltip(`📏 ความยาว: ${distStr}`, {
                    permanent: true,
                    direction: 'right',
                    className: 'pipe-label-style'
                }).openTooltip(lastPt);
            }
        }

        function selectSymbol(key, symbol, name, defaultGeoType) {
            selectedSymbolKey = key;
            selectedSymbolEmoji = symbol;
            selectedSymbolName = name;
            document.querySelectorAll('.icon-picker-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.querySelector(`.icon-picker-btn[data-key="${key}"]`);
            if (activeBtn) activeBtn.classList.add('active');
            selectGeometryType(defaultGeoType);
        }

        function selectGeometryType(type) {
            selectedGeometryType = type;
            ['geoMarker', 'geoPolyline', 'geoPolygon'].forEach(id => document.getElementById(id).classList.remove('active'));
            if (type === 'marker') document.getElementById('geoMarker').classList.add('active');
            if (type === 'polyline') document.getElementById('geoPolyline').classList.add('active');
            if (type === 'polygon') document.getElementById('geoPolygon').classList.add('active');
            cancelCurrentDrawing();
            updateInstructionText();
        }

        function updateInstructionText() {
            const typeTextMap = {
                'marker': `คลิกบนแผนที่เพื่อปักหมุดไอคอน ${selectedSymbolEmoji} ${selectedSymbolName}`,
                'polyline': `🧲 คลิกปักจุดวาดแนว ${selectedSymbolEmoji} ${selectedSymbolName} (ระบบจะ Snap เชื่อมโหนดอัตโนมัติ)`,
                'polygon': `คลิกปักจุด 3 จุดขึ้นไป เพื่อล้อมขอบเขต ${selectedSymbolEmoji} ${selectedSymbolName}`
            };
            document.getElementById('instructionText').innerText = typeTextMap[selectedGeometryType] || 'คลิกบนแผนที่เพื่อวาด';
        }

        function filterMapByZone(selectedZoneId) {
            currentActiveZone = selectedZoneId;
            const allItems = [...gisDataStore.landmarks, ...gisDataStore.nodes, ...gisDataStore.pipes, ...gisDataStore.areas];
            allItems.forEach(item => {
                if (!item.layer) return;
                if (selectedZoneId === 'all' || item.station_id == selectedZoneId) {
                    if (!map.hasLayer(item.layer)) map.addLayer(item.layer);
                } else {
                    if (map.hasLayer(item.layer)) map.removeLayer(item.layer);
                }
            });
            renderElementsTable();
        }

        function openLabelModal() {
            document.getElementById('modalLabelInput').value = '';
            const sizeGroup = document.getElementById('modalSizeGroup');
            sizeGroup.style.display = (['pipe_main', 'pipe_sub'].includes(selectedSymbolKey) && selectedGeometryType === 'polyline') ? 'block' : 'none';
            if (currentActiveZone !== 'all') document.getElementById('modalStationSelect').value = currentActiveZone;
            labelModalInstance.show();
        }

        function promptForElementDetails() {
            if (selectedGeometryType === 'polyline' && activePath.length < 2) return alert('ต้องมีพิกัดอย่างน้อย 2 จุด');
            if (selectedGeometryType === 'polygon' && activePath.length < 3) return alert('ขอบเขตพื้นที่ต้องมีอย่างน้อย 3 จุดขึ้นไป');
            openLabelModal();
        }

        function confirmSaveElementDetails() {
            const labelText = document.getElementById('modalLabelInput').value.trim() || `${selectedSymbolName}`;
            const stationId = document.getElementById('modalStationSelect').value;
            const stationName = document.getElementById('modalStationSelect').options[document.getElementById('modalStationSelect').selectedIndex].text;
            const pipeSize = document.getElementById('modalSizeSelect').value;
            const totalMeters = calculatePathDistance(activePath);
            let createdLayer = null;
            let dataStoreRef = null;
            let categoryKey = '';

            if (selectedGeometryType === 'marker') {
                const marker = L.marker(pendingTempMarker, { icon: createEmojiMarkerIcon(selectedSymbolEmoji) }).addTo(map);
                marker.bindTooltip(`${selectedSymbolEmoji} ${labelText}`, { permanent: true, className: 'pipe-label-style' }).openTooltip();
                createdLayer = marker;
                categoryKey = ['station', 'raft', 'water_tower', 'water_tank', 'junction', 'valve', 'meter'].includes(selectedSymbolKey) ? 'nodes' : 'landmarks';
                dataStoreRef = {
                    id: Date.now(), name: labelText, station_id: stationId, station_name: stationName,
                    symbol_key: selectedSymbolKey, emoji: selectedSymbolEmoji, geo_type: 'marker',
                    lat: pendingTempMarker[0], lng: pendingTempMarker[1], layer: marker
                };
                gisDataStore[categoryKey].push(dataStoreRef);
                if (categoryKey === 'nodes') registeredNodes.push(dataStoreRef);
            } else if (selectedGeometryType === 'polyline') {
                const connectedNodeIds = activeConnectedNodes.map(n => n.id);
                const connectedNodeNames = activeConnectedNodes.map(n => n.name);
                const midIdx = Math.floor(activePath.length / 2);
                const sizeLabel = ` (${pipeSize}มม.)`;
                activeShape.bindTooltip(`${selectedSymbolEmoji} ${labelText}${sizeLabel}`, { permanent: true, direction: 'center', className: 'pipe-label-style' }).openTooltip(activePath[midIdx]);
                createdLayer = activeShape;
                categoryKey = 'pipes';
                dataStoreRef = {
                    id: Date.now(),
                    name: labelText,
                    station_id: stationId,
                    station_name: stationName,
                    symbol_key: selectedSymbolKey,
                    emoji: selectedSymbolEmoji,
                    size: pipeSize,
                    geo_type: 'polyline',
                    distance_meters: Math.round(totalMeters), // บันทึกความยาวเส้นท่อ (เมตร)
                    connected_node_ids: connectedNodeIds,
                    connected_node_names: connectedNodeNames,
                    path_coordinates: [...activePath],
                    layer: activeShape
                };
                gisDataStore.pipes.push(dataStoreRef);
            } else if (selectedGeometryType === 'polygon') {
                activeShape.bindTooltip(`${selectedSymbolEmoji} ${labelText}`, { permanent: true, direction: 'center', className: 'pipe-label-style' }).openTooltip();
                createdLayer = activeShape;
                categoryKey = 'areas';
                dataStoreRef = {
                    id: Date.now(), name: labelText, station_id: stationId, station_name: stationName,
                    symbol_key: selectedSymbolKey, emoji: selectedSymbolEmoji, geo_type: 'polygon',
                    coordinates: [...activePath], layer: activeShape
                };
                gisDataStore.areas.push(dataStoreRef);
            }

            pushToActionHistory(createdLayer, categoryKey, dataStoreRef);
            labelModalInstance.hide();
            renderElementsTable();
            resetDrawingState();
        }

        function renderElementsTable() {
            const tbody = document.getElementById('elementTableBody');
            const badge = document.getElementById('elementCountBadge');
            const allItems = [...gisDataStore.landmarks, ...gisDataStore.nodes, ...gisDataStore.areas, ...gisDataStore.pipes];
            const filteredItems = currentActiveZone === 'all' ? allItems : allItems.filter(item => item.station_id == currentActiveZone);


            badge.innerText = `ทั้งหมด ${filteredItems.length} รายการ`;

            if (filteredItems.length === 0) {
                tbody.innerHTML = `<tr id="emptyElementRow"><td colspan="7" class="text-center text-muted py-4">ยังไม่มีรายการองค์ประกอบบนแผนที่ในโซนที่เลือก</td></tr>`;
                return;
            }

            let html = '';
            const geoBadgeMap = {
                'marker': '<span class="badge bg-info text-dark">📍 หมุด</span>',
                'polyline': '<span class="badge bg-primary">📏 เส้นท่อ</span>',
                'polygon': '<span class="badge bg-warning text-dark">📐 ขอบเขตพื้นที่</span>'
            };

            filteredItems.forEach((item, index) => {
                const isPoly = item.path_coordinates !== undefined || item.coordinates !== undefined;
                const posArr = item.path_coordinates || item.coordinates;
                let topoInfo = isPoly
                    ? (item.distance_meters ? `📏 ${formatDistanceText(item.distance_meters)} (${posArr.length} จุด)` : `${posArr.length} จุดพิกัด`)
                    : `${item.lat.toFixed(6)}, ${item.lng.toFixed(6)}`; let nodeChainBadge = '<span class="text-muted small">-</span>';
                if (item.connected_node_names && item.connected_node_names.length > 0) {
                    const chainStr = item.connected_node_names.join(' ➔ ');
                    nodeChainBadge = `<span class="badge bg-dark text-warning border border-warning node-sequence-badge">🔗 ${chainStr}</span>`;
                }
                html += ` 
                            <tr> 
                                <td class="text-center fw-bold text-muted">${index + 1}</td> 
                                <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle">${item.station_name}</span></td> 
                                <td>${item.emoji || ''} ${geoBadgeMap[item.geo_type] || ''}</td> 
                                <td> 
                                    <input type="text" class="form-control form-control-sm" value="${item.name}" onchange="updateItemName(${item.id}, this.value)"> 
                                </td> 
                                <td>${nodeChainBadge}</td> 
                                <td><small class="text-muted">${topoInfo}</small></td> 
                                <td class="text-end pe-3"> 
                                    <button type="button" class="btn btn-xs btn-outline-primary me-1" onclick="focusItemOnMap(${item.id})" title="ซูมไปหา">📍 ซูม</button> 
                                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="deleteItemById(${item.id})" title="ลบ">🗑️ ลบ</button> 
                                </td> 
                            </tr> 
                        `;
            });
            tbody.innerHTML = html;
        }

        function updateItemName(itemId, newName) {
            const allItems = [...gisDataStore.landmarks, ...gisDataStore.nodes, ...gisDataStore.areas, ...gisDataStore.pipes];
            const item = allItems.find(i => i.id === itemId);
            if (item) {
                item.name = newName;
                item.layer.setTooltipContent(`${item.emoji || '📍'} ${newName}`);
            }
        }

        function focusItemOnMap(itemId) {
            const allItems = [...gisDataStore.landmarks, ...gisDataStore.nodes, ...gisDataStore.areas, ...gisDataStore.pipes];
            const item = allItems.find(i => i.id === itemId);
            if (item && item.layer) {
                if (item.path_coordinates || item.coordinates) map.fitBounds(item.layer.getBounds(), { padding: [30, 30] });
                else map.flyTo([item.lat, item.lng], 17, { duration: 1.0 });
            }
        }

        function deleteItemById(itemId) {
            ['landmarks', 'nodes', 'areas', 'pipes'].forEach(key => {
                const idx = gisDataStore[key].findIndex(i => i.id === itemId);
                if (idx > -1) {
                    const item = gisDataStore[key][idx];
                    if (item.layer) map.removeLayer(item.layer);
                    gisDataStore[key].splice(idx, 1);
                }
            });
            renderElementsTable();
        }

        function dismissModalCancel() {
            labelModalInstance.hide();
            pendingTempMarker = null;
            if (activeShape) cancelCurrentDrawing();
        }

        // ========================================================
        // 📏 ฟังก์ชันคำนวณระยะทางรวมของเส้นท่อ (หน่วย: เมตร / กิโลเมตร)
        // ========================================================
        function calculatePathDistance(pathArray) {
            if (!pathArray || pathArray.length < 2) return 0;

            let totalMeters = 0;
            for (let i = 0; i < pathArray.length - 1; i++) {
                const pt1 = L.latLng(pathArray[i][0], pathArray[i][1]);
                const pt2 = L.latLng(pathArray[i + 1][0], pathArray[i + 1][1]);
                totalMeters += pt1.distanceTo(pt2); // distanceTo คืนค่าเป็นเมตร
            }
            return totalMeters;
        }

        // ฟังก์ชันแปลงตัวเลขเมตรเป็นข้อความอ่านง่าย (เช่น "350 ม." หรือ "1.25 กม.")
        function formatDistanceText(meters) {
            if (meters >= 1000) {
                return (meters / 1000).toFixed(2) + ' กม.';
            }
            return Math.round(meters) + ' ม.';
        }

        function pushToActionHistory(layer, categoryKey, dataStoreRef) {
            actionHistoryStack.push({ layer, categoryKey, dataStoreRef });
            redoHistoryStack = [];
            updateUndoRedoButtons();
        }

        function cancelCurrentDrawing() {
            if (activeShape) map.removeLayer(activeShape);
            resetDrawingState();
        }

        function resetDrawingState() {
            activePath = []; activeConnectedNodes = []; activeShape = null; pendingTempMarker = null;
            updateLiveTopologyInspector();
            toggleDrawButtons(false);
            updateUndoRedoButtons();
        }

        function toggleDrawButtons(active) {
            document.getElementById('btnFinish').disabled = !active;
            document.getElementById('btnCancel').disabled = !active;
        }

        function switchTile(type) {
            if (type === 'sat') {
                map.removeLayer(osmLayer); map.addLayer(satelliteLayer);
                document.getElementById('btnSat').classList.add('active'); document.getElementById('btnOsm').classList.remove('active');
            } else {
                map.removeLayer(satelliteLayer); map.addLayer(osmLayer);
                document.getElementById('btnOsm').classList.add('active'); document.getElementById('btnSat').classList.remove('active');
            }
        }

        function saveAllGisData() {
            console.log("ส่งข้อมูลผังประปา GIS ทั้งหมดผ่าน AJAX:", gisDataStore);
            fetch("{{ route('tabwater.settings.stations.save_gis_data') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(gisDataStore)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('💾 ' + data.message);
                    } else {
                        alert('❌ เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
                });
        } 
    </script>
@endsection