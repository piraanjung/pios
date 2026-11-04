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
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .gis-toolbar .btn-group-sm > .btn {
        font-size: 11px;
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
</style>

<div class="container-fluid py-4"> 
    <div class="d-flex justify-content-between align-items-center mb-3"> 
        <h4 class="fw-bold text-dark mb-0">🏭 ตั้งค่าและแผนที่สถานีผลิตน้ำประปา</h4> 
        <a href="{{ route('tabwater.settings.stations.create') }}" class="btn btn-primary btn-sm fw-bold"> 
            ➕ เพิ่มสถานีใหม่ 
        </a> 
    </div> 

    @if(session('success')) 
        <div class="alert alert-success alert-dismissible fade show fw-bold text-sm" role="alert"> 
            {{ session('success') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
        </div> 
    @endif 

    <!-- การ์ดแสดงแผนที่รวมสถานีทั้งหมด (Leaflet.js Interactive GIS) --> 
    <div class="card shadow-sm border-0 rounded-3 mb-4"> 
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2"> 
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold text-primary mb-0">🗺️ แผนที่ตำแหน่งสถานีและโครงข่ายท่อประปา GIS</h6> 
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold"> 
                    ทั้งหมด {{ $allStationsMap->count() }} สถานี 
                </span> 
            </div>
            <!-- Legend สัญลักษณ์สายท่อ -->
            <div class="gis-legend d-flex gap-3 align-items-center m-0">
                <span><i class="gis-legend-dot" style="background: #0284c7; height: 4px;"></i> ท่อประธาน (Main)</span>
                <span><i class="gis-legend-dot" style="background: #16a34a; height: 3px;"></i> ท่อจ่ายน้ำ (Sub)</span>
                <span><i class="gis-legend-dot" style="background: #ca8a04; height: 2px;"></i> ท่อบริการ (Distribution)</span>
            </div>
        </div> 
        <div class="card-body p-2"> 
            <!-- กรอบ Leaflet Map พร้อม Floating Toolbar --> 
            <div class="gis-map-wrapper">
                <div id="allStationsMap" style="height: 480px; width: 100%; border-radius: 8px;"></div> 
                
                <!-- Floating Toolbar สำหรับควบคุมแผนที่ GIS -->
                <div class="gis-toolbar">
                    <div class="fw-bold text-secondary text-xs mb-1">🛠️ เครื่องมือ GIS</div>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-dark active" id="btnTileOsm" onclick="switchBaseTile('osm')">🗺️ แผนที่</button>
                        <button type="button" class="btn btn-outline-dark" id="btnTileSat" onclick="switchBaseTile('sat')">🛰️ ดาวเทียม</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-bold text-start" onclick="resetMapBounds()">
                        🎯 โฟกัสทุกสถานี
                    </button>
                </div>
            </div>
        </div> 
    </div> 

    <!-- ตารางรายการสถานี --> 
    <div class="card shadow-sm border-0 rounded-3"> 
        <div class="card-body p-0"> 
            <div class="table-responsive"> 
                <table class="table table-hover align-middle mb-0 text-sm"> 
                    <thead class="table-light border-bottom text-nowrap"> 
                        <tr> 
                            <th>รหัส</th> <th>ชื่อสถานี</th> <th>ช่างผู้รับผิดชอบ</th> <th>อุปกรณ์</th> <th>เกณฑ์เตือน NTU</th> <th>เกณฑ์เตือน pH</th> <th>เกณฑ์ตัดปั๊ม %</th> <th>K-Factor</th> <th class="text-end">จัดการ</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        @forelse($stations as $st) 
                            <tr> 
                                <td class="fw-bold text-muted">{{ $st->code ?? '-' }}</td> 
                                <td class="fw-bold text-primary">
                                    {{ $st->name }}
                                    @if($st->lat &&$st->lng)
                                        <button type="button" class="btn btn-xs btn-link p-0 text-decoration-none ms-1" title="ซูมไปยังตำแหน่งบนแผนที่" onclick="focusStationOnMap({{ $st->lat }}, {{ $st->lng }}, '{{ addslashes($st->name) }}')">📍</button>
                                    @endif
                                </td> 
                                <td> 
                                    @forelse($st->staffs as $staff) 
                                        <span class="badge bg-secondary-subtle text-secondary me-1"> 
                                            {{ $staff->user->firstname ?? $staff->name }} 
                                        </span> 
                                    @empty 
                                        <span class="text-muted small">-</span> 
                                    @endforelse 
                                </td> 
                                <td><span class="badge bg-info text-dark fw-bold">{{ $st->devices->count() }} ชิ้น</span></td> 
                                <td class="fw-bold text-danger">> {{ number_format($st->max_turbidity_ntu, 1) }} NTU</td> 
                                <td class="fw-bold text-warning">{{ $st->min_ph }} - {{$st->max_ph }}</td> 
                                <td class="fw-bold text-primary">{{ $st->max_water_level_percent }}%</td> 
                                <td class="fw-bold text-dark">{{ $st->secchi_k_factor }}</td> 
                                <td class="text-end text-nowrap"> 
                                    <a href="{{ route('tabwater.settings.stations.edit', $st->id) }}" class="btn btn-sm btn-outline-primary fw-bold me-1"> แก้ไข </a> 
                                    <form action="{{ route('tabwater.settings.stations.destroy', $st->id) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันการลบสถานีนี้?')"> 
                                        @csrf 
                                        @method('DELETE') 
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">ลบ</button> 
                                    </form> 
                                </td> 
                            </tr> 
                        @empty 
                            <tr> 
                                <td colspan="9" class="text-center text-muted py-4">ยังไม่มีข้อมูลสถานีผลิตน้ำ</td> 
                            </tr> 
                        @endforelse 
                    </tbody> 
                </table> 
            </div> 
        </div> 
    </div> 
    
    <div class="mt-3"> 
        {{ $stations->links() }} 
    </div> 
</div> 

<script> 
const stationsData = @json($allStationsMap); 
const pipelinesData = @json($allPipelines ?? []); 

let map;
let osmLayer, satelliteLayer;
const stationMarkersMap = {};
const bounds = []; 

function createStationIcon() { 
    return L.icon({ 
        iconUrl: '{{ asset("imgs/tower.png") }}', 
        iconSize: [32, 32], 
        iconAnchor: [16, 32], 
        popupAnchor: [0, -32] 
    }); 
} 

document.addEventListener("DOMContentLoaded", function() { 
    initOverviewMap(); 
}); 

function initOverviewMap() { 
    let defaultLat = 17.154100; 
    let defaultLng = 104.148600; 
    
    if (stationsData.length > 0 && stationsData[0].lat && stationsData[0].lng) { 
        defaultLat = parseFloat(stationsData[0].lat); 
        defaultLng = parseFloat(stationsData[0].lng); 
    } 

    // 1. สร้าง Leaflet Map
    map = L.map('allStationsMap').setView([defaultLat, defaultLng], 12); 

    // 2. Tile Layers (OSM & Esri Satellite)
    osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
       maxNativeZoom: 19, // ซูมสูงสุดของไฟล์ภาพจริง
    maxZoom: 25,       // ให้ Leaflet ช่วยขยายภาพต่อได้โดยไม่ขึ้นหน้าต่างเทา
    }).addTo(map);

    satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: 'Tiles &copy; Esri'
    });

    // 3. ปักหมุดสถานีผลิตน้ำทั้งหมด 
    stationsData.forEach(function (station) { 
        const lat = parseFloat(station.lat); 
        const lng = parseFloat(station.lng); 
        
        if (!isNaN(lat) && !isNaN(lng)) { 
            const latLng = [lat, lng];
            bounds.push(latLng); 
            
            const marker = L.marker(latLng, { icon: createStationIcon() }).addTo(map); 
            stationMarkersMap[station.id] = marker;

            const contentString = ` 
                <div style="padding: 4px; font-family: sans-serif; font-size: 12px; line-height: 1.4;"> 
                    <div style="font-weight: bold; color: #0d6efd; font-size: 14px; margin-bottom: 4px;"> 
                        🏭 ${station.name} (${station.code || '-'}) 
                    </div> 
                    <div style="color: #495057; margin-bottom: 6px;"> 
                        <b>พิกัด:</b> ${lat.toFixed(6)}, ${lng.toFixed(6)}<br> 
                        <b>เกณฑ์ NTU:</b> <span style="color: red;">> ${station.max_turbidity_ntu}</span> | <b>เกณฑ์ pH:</b> ${station.min_ph} - ${station.max_ph} 
                    </div> 
                    <div style="text-align: right;"> 
                        <a href="/tabwater/settings/stations/${station.id}/edit" style="display: inline-block; padding: 2px 8px; background: #0d6efd; color: white; text-decoration: none; border-radius: 4px; font-size: 11px; font-weight: bold;"> 
                            ✏️ แก้ไขสถานี 
                        </a> 
                    </div> 
                </div> 
            `; 
            marker.bindPopup(contentString); 
        } 
    }); 

    // 4. วาดเส้นแนวท่อประปา (Polylines) 
    pipelinesData.forEach(function (pipe) { 
        if (pipe.path_coordinates && pipe.path_coordinates.length > 0) { 
            const formattedPath = pipe.path_coordinates.map(coord => [ 
                parseFloat(coord[0] || coord.lat), 
                parseFloat(coord[1] || coord.lng || coord.long) 
            ]); 
            
            let lineColor = '#0284c7'; 
            let lineWeight = 5; 
            
            if (pipe.level === 'sub_main') { 
                lineColor = '#16a34a'; 
                lineWeight = 4; 
            } else if (pipe.level === 'distribution') { 
                lineColor = '#ca8a04'; 
                lineWeight = 2; 
            } 
            
            const polyline = L.polyline(formattedPath, { 
                color: lineColor, 
                weight: lineWeight, 
                opacity: 0.8 
            }).addTo(map); 
            
            polyline.bindPopup(` 
                <div style="padding: 4px; font-family: sans-serif; font-size: 12px;"> 
                    <div style="font-weight: bold; color: ${lineColor}; font-size: 13px;"> 
                        🚰 ${pipe.name} (${pipe.pipe_code || '-'}) 
                    </div> 
                    <div style="color: #495057; margin-top: 4px;"> 
                        <b>ระดับท่อ:</b> ${(pipe.level || '').toUpperCase()}<br> 
                        <b>ขนาด:</b> ${pipe.size_mm || '-'} มม. (${pipe.material || '-'}) 
                    </div> 
                </div> 
            `); 
        } 
    }); 

    // 5. ปรับแผนที่ให้อยู่ในขอบเขตหมุดสถานีทั้งหมด 
    resetMapBounds();
} 

// ฟังก์ชันสลับ Base Tile Layer (Street Map / Satellite)
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

// ฟังก์ชันรีเซ็ตขอบเขตแผนที่กลับไปยังทุกสถานี
function resetMapBounds() {
    if (bounds.length > 0) { 
        map.fitBounds(bounds, { padding: [30, 30] }); 
    }
}

// ฟังก์ชัน Quick Zoom จากตารางรายการสถานี
function focusStationOnMap(lat, lng, name) {
    if (map && lat && lng) {
        map.flyTo([lat, lng], 16, { duration: 1.2 });
        // เลื่อนหน้าจอไปยังแผนที่ Smooth Scroll
        document.getElementById('allStationsMap').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
</script> 
@endsection