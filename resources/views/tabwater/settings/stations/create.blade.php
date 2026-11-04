@extends('layouts.admin1') 

@section('content') 
<!-- Leaflet CSS & JS CDN --> 
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" /> 
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> 

<style> 
    .gis-map-wrapper { 
        position: relative; 
        width: 100%; 
    } 
    .gis-toolbar { 
        position: absolute; 
        top: 10px; 
        right: 10px; 
        z-index: 1000; 
        background: rgba(255, 255, 255, 0.95); 
        padding: 10px; 
        border-radius: 8px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.15); 
        width: 230px;
    } 
    .gis-legend { 
        background: rgba(255, 255, 255, 0.9); 
        padding: 6px 10px; 
        border-radius: 6px; 
        font-size: 11px; 
        box-shadow: 0 1px 5px rgba(0,0,0,0.1); 
    } 
    .gis-legend-dot { 
        display: inline-block; 
        width: 12px; 
        height: 3px; 
        margin-right: 4px; 
        vertical-align: middle; 
    } 
    /* Style สำหรับ Label ป้ายชื่อท่อบนแผนที่ */
    .pipe-label-style {
        background: rgba(15, 23, 42, 0.85);
        color: #ffffff;
        border: none;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        padding: 2px 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
</style> 

<div class="container-fluid py-4"> 
    <form action="{{ route('tabwater.settings.stations.store') }}" method="POST" id="stationForm">
        @csrf
        <div class="d-flex justify-content-between align-items-center mb-3"> 
            <h4 class="fw-bold text-dark mb-0">➕ เพิ่มสถานีผลิตน้ำประปาและโครงข่าย GIS ใหม่</h4> 
            <div>
                <a href="{{ route('tabwater.settings.stations.index') }}" class="btn btn-outline-secondary btn-sm fw-bold me-1">ยกเลิก</a>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">💾 บันทึกสถานีและ GIS</button>
            </div>
        </div> 

        <div class="row g-3">
            <!-- ฝั่งซ้าย: ฟอร์มข้อมูลสถานีผลิตน้ำ -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-3 mb-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="fw-bold text-primary mb-0">🏭 ข้อมูลสถานีผลิตน้ำ</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">รหัสสถานี</label>
                            <input type="text" name="code" class="form-control form-control-sm" placeholder="เช่น ST-01" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-bold">ชื่อสถานีผลิตน้ำ</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="เช่น สถานีผลิตน้ำประปาหนองบัว" required>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">ละติจูด (Lat)</label>
                                <input type="text" name="lat" id="inputLat" class="form-control form-control-sm bg-light" readonly required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">ลองจิจูด (Lng)</label>
                                <input type="text" name="lng" id="inputLng" class="form-control form-control-sm bg-light" readonly required>
                            </div>
                        </div>
                        <small class="text-muted d-block mb-3">* คลิกบนแผนที่เพื่อปักหมุดที่ตั้งสถานีหลัก</small>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold">เกณฑ์เตือน NTU</label>
                                <input type="number" step="0.1" name="max_turbidity_ntu" class="form-control form-control-sm" value="5.0">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold">K-Factor</label>
                                <input type="number" step="0.01" name="secchi_k_factor" class="form-control form-control-sm" value="1.00">
                            </div>
                        </div>

                        <!-- Hidden Inputs สำหรับส่งข้อมูลท่อและแพสูบน้ำ JSON เข้า Controller -->
                        <input type="hidden" name="pipelines_json" id="pipelinesJson" value="[]">
                        <input type="hidden" name="pumping_raft_json" id="pumpingRaftJson" value="[]">
                    </div>
                </div>
            </div>

            <!-- ฝั่งขวา: แผนที่ Leaflet สำหรับปักหมุด/วาดท่อ/วาดแพสูบน้ำ -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 mb-4"> 
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2"> 
                        <div class="d-flex align-items-center gap-2"> 
                            <h6 class="fw-bold text-primary mb-0">🗺️ วาดตำแหน่งไอคอนสถานี แนวท่อ และแพสูบน้ำ</h6> 
                        </div> 
                        <!-- Legend สัญลักษณ์สายท่อ --> 
                        <div class="gis-legend d-flex gap-3 align-items-center m-0"> 
                            <span><i class="gis-legend-dot" style="background: #0284c7; height: 4px;"></i> ท่อประธาน</span> 
                            <span><i class="gis-legend-dot" style="background: #16a34a; height: 3px;"></i> ท่อจ่ายน้ำ</span> 
                            <span><i class="gis-legend-dot" style="background: #ca8a04; height: 2px;"></i> ท่อบริการ</span> 
                            <span><i class="gis-legend-dot" style="background: #dc2626; height: 3px;"></i> แพสูบน้ำ</span> 
                        </div> 
                    </div> 
                    <div class="card-body p-2"> 
                        <div class="gis-map-wrapper"> 
                            <div id="allStationsMap" style="height: 520px; width: 100%; border-radius: 8px;"></div> 
                            
                            <!-- Floating Toolbar วาดองค์ประกอบ GIS --> 
                            <div class="gis-toolbar"> 
                                <div class="fw-bold text-secondary text-xs mb-1">🛠️ เครื่องมือวาดแผนที่</div> 
                                
                                <div class="btn-group btn-group-sm w-100 mb-2" role="group"> 
                                    <button type="button" class="btn btn-outline-dark active" id="btnTileOsm" onclick="switchBaseTile('osm')">🗺️ แผนที่</button> 
                                    <button type="button" class="btn btn-outline-dark" id="btnTileSat" onclick="switchBaseTile('sat')">🛰️ ดาวเทียม</button> 
                                </div> 

                                <hr class="my-1">

                                <!-- โหมดเลือกการวาด -->
                                <div class="mb-2">
                                    <label class="form-label text-xs fw-bold mb-1">โหมดการวาด:</label>
                                    <select id="drawModeSelect" class="form-select form-select-sm" onchange="changeDrawMode()">
                                        <option value="station">📍 ปักหมุดสถานีผลิตน้ำ</option>
                                        <option value="pipe">🚰 วาดแนวท่อประปา</option>
                                        <option value="raft">🚤 วาดขอบเขตแพสูบน้ำ (4 จุด)</option>
                                    </select>
                                </div>

                                <!-- ตั้งค่ารายละเอียดท่อ (แสดงเมื่อเลือกโหมดท่อ) -->
                                <div id="pipeOptionGroup" style="display: none;">
                                    <div class="mb-2">
                                        <label class="form-label text-xs fw-bold mb-1">ระดับ & ขนาดท่อ:</label>
                                        <select id="pipeTypeSelect" class="form-select form-select-sm" onchange="updatePipeConfig()">
                                            <option value="main" data-color="#0284c7" data-weight="6" data-size="150">🔵 ท่อประธาน (150 มม.)</option>
                                            <option value="sub_main" data-color="#16a34a" data-weight="4" data-size="80">🟢 ท่อจ่ายน้ำ (80 มม.)</option>
                                            <option value="distribution" data-color="#ca8a04" data-weight="2.5" data-size="40">🟡 ท่อบริการ (40 มม.)</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label text-xs fw-bold mb-1">ชื่อเส้นท่อ:</label>
                                        <input type="text" id="pipeNameInput" class="form-control form-control-sm" placeholder="เช่น ท่อเมนถนนสายหลัก">
                                    </div>
                                </div>

                                <!-- ปุ่มควบคุมการวาด -->
                                <div class="d-grid gap-1 mt-2">
                                    <button type="button" class="btn btn-sm btn-success fw-bold" id="btnFinishDraw" onclick="finishCurrentDraw()" disabled>
                                        ✅ เสร็จสิ้นการวาด
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="btnCancelDraw" onclick="cancelCurrentDraw()" disabled>
                                        ❌ ยกเลิกเส้นนี้
                                    </button>
                                </div>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
    </form>
</div> 

<script> 
let map; 
let osmLayer, satelliteLayer; 

// ตัวแปรสำหรับจัดการ GIS Elements
let stationMarker = null;
let currentDrawMode = 'station'; // 'station', 'pipe', 'raft'

let isDrawing = false;
let currentPath = [];
let currentPolyline = null;
let currentPolygon = null;

// Storage เก็บข้อมูลไว้แปลงส่ง JSON
let pipelinesStore = [];
let raftPointsStore = [];

// ตั้งค่าคุณสมบัติเส้นท่อเริ่มต้น
let currentPipeColor = '#0284c7';
let currentPipeWeight = 6;
let currentPipeSize = 150;

function createStationIcon() { 
    return L.icon({ 
        iconUrl: '{{ asset("imgs/tower.png") }}', 
        iconSize: [36, 36], 
        iconAnchor: [18, 36], 
        popupAnchor: [0, -36] 
    }); 
} 

document.addEventListener("DOMContentLoaded", function() { 
    initOverviewMap(); 
}); 

function initOverviewMap() { 
    let defaultLat = 16.2685302; 
    let defaultLng = 104.4011436; 

    // 1. สร้าง Leaflet Map
    map = L.map('allStationsMap').setView([defaultLat, defaultLng], 14); 

    // 2. Tile Layers
    osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
        maxNativeZoom: 19, 
        maxZoom: 25, 
        attribution: '© OpenStreetMap' 
    }).addTo(map); 

    satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { 
        maxZoom: 19, 
        attribution: 'Tiles &copy; Esri' 
    }); 

    // 3. Event การคลิกบนแผนที่ตามโหมด
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        if (currentDrawMode === 'station') {
            // ปักหมุดไอคอนสถานี
            if (stationMarker) {
                stationMarker.setLatLng(e.latlng);
            } else {
                stationMarker = L.marker(e.latlng, { icon: createStationIcon(), draggable: true }).addTo(map);
                stationMarker.on('dragend', function(evt) {
                    const pos = evt.target.getLatLng();
                    updateStationInputs(pos.lat, pos.lng);
                });
            }
            updateStationInputs(lat, lng);

        } else if (currentDrawMode === 'pipe') {
            // วาดแนวท่อประปา
            isDrawing = true;
            currentPath.push([lat, lng]);
            
            if (!currentPolyline) {
                currentPolyline = L.polyline(currentPath, {
                    color: currentPipeColor,
                    weight: currentPipeWeight,
                    opacity: 0.85
                }).addTo(map);
            } else {
                currentPolyline.setLatLngs(currentPath);
            }
            updateDrawButtons(true);

        } else if (currentDrawMode === 'raft') {
            // วาดขอบเขตแพสูบน้ำ (Polygon 4 จุด)
            if (currentPath.length >= 4) {
                alert('แพสูบน้ำกำหนดไว้ที่ 4 จุดพิกัด กรุณากด "เสร็จสิ้นการวาด"');
                return;
            }

            isDrawing = true;
            currentPath.push([lat, lng]);

            if (!currentPolygon) {
                currentPolygon = L.polygon(currentPath, {
                    color: '#dc2626',
                    fillColor: '#ef4444',
                    fillOpacity: 0.4,
                    weight: 3
                }).addTo(map);
            } else {
                currentPolygon.setLatLngs(currentPath);
            }
            updateDrawButtons(true);
        }
    });
} 

function updateStationInputs(lat, lng) {
    document.getElementById('inputLat').value = lat.toFixed(6);
    document.getElementById('inputLng').value = lng.toFixed(6);
}

function changeDrawMode() {
    currentDrawMode = document.getElementById('drawModeSelect').value;
    document.getElementById('pipeOptionGroup').style.display = (currentDrawMode === 'pipe') ? 'block' : 'none';
    cancelCurrentDraw();
}

function updatePipeConfig() {
    const select = document.getElementById('pipeTypeSelect');
    const option = select.options[select.selectedIndex];
    currentPipeColor = option.getAttribute('data-color');
    currentPipeWeight = parseFloat(option.getAttribute('data-weight'));
    currentPipeSize = option.getAttribute('data-size');
}

function finishCurrentDraw() {
    if (currentDrawMode === 'pipe') {
        if (currentPath.length < 2) return alert('แนวท่อต้องมีอย่างน้อย 2 จุดพิกัด');

        const pipeName = document.getElementById('pipeNameInput').value.trim() || 'แนวท่อประปา';
        const pipeType = document.getElementById('pipeTypeSelect').value;

        // คำนวณจุดกึ่งกลางเส้นเพื่อปัก Label
        const midIdx = Math.floor(currentPath.length / 2);
        const centerLatLng = currentPath[midIdx];

        // สร้าง Tooltip แสดงชื่อและขนาดท่อ
        const labelText = `🚰 ${pipeName} (${currentPipeSize} มม.)`;
        currentPolyline.bindTooltip(labelText, {
            permanent: true,
            direction: 'center',
            className: 'pipe-label-style'
        }).openTooltip(centerLatLng);

        // เก็บลง Array
        pipelinesStore.push({
            name: pipeName,
            level: pipeType,
            size_mm: currentPipeSize,
            color: currentPipeColor,
            weight: currentPipeWeight,
            path_coordinates: currentPath
        });

        document.getElementById('pipelinesJson').value = JSON.stringify(pipelinesStore);
        document.getElementById('pipeNameInput').value = '';

    } else if (currentDrawMode === 'raft') {
        if (currentPath.length < 3) return alert('แพสูบน้ำต้องมีพิกัดอย่างน้อย 3-4 จุดเพื่อสร้างพื้นที่');

        // ปัก Label กลางพื้นที่แพสูบน้ำ
        currentPolygon.bindTooltip('🚤 แพสูบน้ำ', {
            permanent: true,
            direction: 'center',
            className: 'pipe-label-style'
        }).openTooltip();

        raftPointsStore = currentPath;
        document.getElementById('pumpingRaftJson').value = JSON.stringify(raftPointsStore);
    }

    resetDrawState();
}

function cancelCurrentDraw() {
    if (currentPolyline) map.removeLayer(currentPolyline);
    if (currentPolygon) map.removeLayer(currentPolygon);
    resetDrawState();
}

function resetDrawState() {
    isDrawing = false;
    currentPath = [];
    currentPolyline = null;
    currentPolygon = null;
    updateDrawButtons(false);
}

function updateDrawButtons(active) {
    document.getElementById('btnFinishDraw').disabled = !active;
    document.getElementById('btnCancelDraw').disabled = !active;
}

// สลับ Tile Layer แผนที่
function switchBaseTile(type) { 
    if (type === 'sat') { 
        map.removeLayer(osmLayer); 
        map.addLayer(satelliteLayer); 
        document.getElementById('btnTileSat').classList.add('active'); 
        document.getElementById('btnTileOsm').classList.remove('active'); 
    } else { 
        map.removeLayer(satelliteLayer); 
        map.addLayer(osmLayer); 
        document.getElementById('btnTileOsm').classList.add('active'); 
        document.getElementById('btnTileSat').classList.remove('active'); 
    } 
} 
</script> 
@endsection