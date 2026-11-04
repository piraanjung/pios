@extends('layouts.admin1')

@section('nav-header', 'ผังเมือง & สาธารณูปโภค')
@section('nav-main', $moduleInfo['title'])
@section('nav-topic')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h5 class="font-weight-bolder mb-0 text-dark">
                <span class="me-1">{{ $moduleInfo['icon'] }}</span> {{ $moduleInfo['title'] }}
            </h5>
            <p class="text-xs text-muted mb-0">{{ $moduleInfo['subtitle'] }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-gradient-{{ $moduleInfo['theme'] }} text-white px-3 py-2 text-xs border-radius-md shadow-sm">
                {{ $moduleInfo['badge'] }}
            </span>
            <a href="{{ route('smart_utility.reports') }}" class="btn btn-xs btn-outline-secondary mb-0">
                <i class="fas fa-chart-pie me-1"></i> ดูรายงานสรุป
            </a>
            <a href="{{ route('smart_utility.citizen') }}" target="_blank" class="btn btn-xs btn-outline-primary mb-0">
                <i class="fas fa-bullhorn me-1"></i> ลิงก์แจ้งเหตุประชาชน
            </a>
        </div>
    </div>
@endsection

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <style>
        /* Modern GIS Container */
        .gis-wrapper {
            position: relative;
            width: 100%;
            height: calc(100vh - 200px);
            min-height: 720px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.9);
            background: #0f172a;
        }

        #gisMap {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Glassmorphism Floating Bars */
        .glass-panel {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 14px;
            box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.14);
            z-index: 999;
            transition: all 0.2s ease;
        }

        .floating-top-left {
            position: absolute;
            top: 16px;
            left: 16px;
            max-width: 480px;
            width: calc(100% - 32px);
            padding: 10px 14px;
        }

        .floating-top-right {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 999;
        }

        .floating-bottom-bar {
            position: absolute;
            bottom: 16px;
            left: 16px;
            right: 16px;
            padding: 8px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            z-index: 999;
        }

        /* Mode Banner Guide */
        .mode-guide-banner {
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
            background: rgba(15, 23, 42, 0.94);
            color: #ffffff;
            padding: 9px 20px;
            border-radius: 30px;
            font-size: 13.5px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            display: none;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255,255,255,0.2);
            animation: fadeInDown 0.3s ease;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translate(-50%, -15px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }

        /* Quick Stat Badges */
        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
        }
        .stat-pill b {
            font-size: 13.5px;
            color: #0f172a;
        }

        /* Floating buttons */
        .gis-btn {
            border: 0;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            background: #ffffff;
            color: #1e293b;
        }
        .gis-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.14);
        }
        .gis-btn.active-tool {
            background: #f59e0b !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.4) !important;
        }

        /* Module Switcher Tabs */
        .module-nav-pills {
            display: flex;
            gap: 4px;
            background: rgba(241, 245, 249, 0.95);
            padding: 4px;
            border-radius: 12px;
        }
        .module-nav-item {
            padding: 6px 11px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .module-nav-item.active {
            background: #ffffff;
            color: #0f172a;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        /* ======================================================== */
        /* ✨ Pixel-Perfect SVG Teardrop Pin (Tip points directly at LatLng) */
        /* ======================================================== */
        .custom-svg-pin {
            background: transparent !important;
            border: none !important;
        }
        .pin-wrapper {
            position: relative;
            width: 32px;
            height: 40px;
            cursor: pointer;
            transition: transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3));
        }
        .custom-svg-pin:hover .pin-wrapper {
            transform: scale(1.18) translateY(-3px);
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.35));
        }
        .pin-wrapper svg {
            display: block;
            width: 32px;
            height: 40px;
        }
        .pin-emoji {
            position: absolute;
            top: 4px;
            left: 0;
            width: 32px;
            text-align: center;
            font-size: 13.5px;
            line-height: 20px;
            pointer-events: none;
            user-select: none;
        }
        .pin-badge-bad {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: #ffffff;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            border: 1.5px solid #ffffff;
            font-size: 9px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-red 1.6s infinite;
        }

        /* Draggable active states */
        .is-draggable-active .pin-wrapper {
            transform: scale(1.3) translateY(-8px);
            filter: drop-shadow(0 14px 18px rgba(0,0,0,0.45));
            cursor: grab;
            animation: bounce-pin 1s infinite alternate;
        }
        .is-draggable-active .pin-wrapper:active {
            cursor: grabbing;
        }

        @keyframes bounce-pin {
            from { transform: scale(1.25) translateY(-5px); }
            to { transform: scale(1.3) translateY(-10px); }
        }

        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Snapping Magnet Visual Indicator */
        .snap-tooltip {
            background: #10b981 !important;
            border: none !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11.5px !important;
            border-radius: 6px !important;
            padding: 3px 8px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25) !important;
        }
        .snap-tooltip:before {
            border-top-color: #10b981 !important;
        }

        /* Pipeline Map Label Tooltips */
        .pipe-label-tooltip {
            background: rgba(15, 23, 42, 0.85) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            border-radius: 6px !important;
            padding: 2px 7px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2) !important;
        }
        .zone-label-tooltip {
            background: rgba(2, 132, 199, 0.88) !important;
            border: 1px solid #ffffff !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 12px !important;
            border-radius: 8px !important;
            padding: 4px 10px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        }

        /* Custom Popup */
        .leaflet-popup-content-wrapper {
            border-radius: 14px;
            padding: 4px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.22);
            border: 1px solid rgba(226, 232, 240, 0.9);
        }
        .gis-popup {
            padding: 8px 10px;
            font-family: inherit;
            min-width: 250px;
        }
        .gis-popup h6 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .gis-popup p {
            margin: 0 0 6px;
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Inline Pin Drop Form in Popup */
        .inline-pin-popup {
            padding: 6px 8px;
            min-width: 290px;
        }

        /* Fullscreen Map Mode */
        .gis-wrapper.is-fullscreen {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 99999 !important;
            border-radius: 0 !important;
        }
        .is-dropping-mode #gisMap {
            cursor: crosshair !important;
        }
        .is-drawing-mode #gisMap, .is-drawing-zone-mode #gisMap {
            cursor: cell !important;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid px-2 px-md-3">

    <!-- Top KPI Stats Row -->
    <div class="row g-2 mb-3">
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-{{ $moduleInfo['theme'] }} shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <span style="font-size: 20px;">{{ $moduleInfo['icon'] }}</span>
                        </div>
                        <div>
                            <p class="text-xs text-muted mb-0 font-weight-bold">จำนวนจุดทรัพย์สินทั้งหมด</p>
                            <h5 class="font-weight-bolder mb-0 text-dark" id="kpiTotalItems">{{ number_format($stats['total_items']) }} <small class="text-xs text-muted">จุด</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-check-circle text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-muted mb-0 font-weight-bold">สภาพพร้อมใช้งาน (ปกติ)</p>
                            <h5 class="font-weight-bolder mb-0 text-success" id="kpiGoodItems">{{ number_format($stats['good_items']) }} <small class="text-xs text-muted">จุด</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-exclamation-triangle text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-muted mb-0 font-weight-bold">จุดแจ้งชำรุด / เสีย / รั่ว</p>
                            <h5 class="font-weight-bolder mb-0 text-danger" id="kpiBadItems">{{ number_format($stats['bad_items']) }} <small class="text-xs text-muted">จุด</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-xs border">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md me-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px;">
                            <i class="fas fa-tools text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-muted mb-0 font-weight-bold">ใบสั่งซ่อมรอดำเนินการ</p>
                            <h5 class="font-weight-bolder mb-0 text-warning" id="kpiPendingTickets">{{ number_format($stats['pending_tickets']) }} <small class="text-xs text-muted">งาน</small></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GIS Map Container -->
    <div class="gis-wrapper" id="gisWrapper">
        <div id="gisMap"></div>

        <!-- Mode Instruction Banner -->
        <div class="mode-guide-banner" id="modeGuideBanner">
            <i class="fas fa-info-circle text-warning"></i>
            <span id="modeGuideText">คลิกบนแผนที่เพื่อวางหมุด และสามารถลากหมุดไปปล่อยจุดที่ต้องการได้อย่างอิสระ</span>
            <div class="d-flex align-items-center gap-1" id="bannerActionButtons">
                <button class="btn btn-xs btn-outline-light mb-0 py-1 px-2" id="btnCancelMode">ยกเลิก</button>
            </div>
        </div>

        <!-- Floating Top-Left Bar: Search, Stations & Layer Filter -->
        <div class="glass-panel floating-top-left">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="mapSearchInput" class="form-control border-start-0 ps-0" placeholder="ค้นหารหัส, เลขเสา, มิเตอร์, ท่อ หรือชื่อจุด..." />
                </div>
                <button type="button" class="btn btn-sm btn-dark mb-0 px-2" id="btnMyLoc" title="ตำแหน่ง GPS ของฉัน">
                    <i class="fas fa-crosshairs"></i>
                </button>
            </div>

            <!-- Water Station Network Filter Selector -->
            @if($module === 'water')
                <div class="d-flex align-items-center gap-2 mb-2 p-1 rounded bg-light border">
                    <span class="text-xs font-weight-bold text-dark ms-1"><i class="fas fa-industry text-primary me-1"></i> โรงผลิตน้ำ:</span>
                    <select id="waterStationFilter" class="form-select form-select-sm py-1 border-0 bg-white font-weight-bold text-xs" style="flex: 1;">
                        <option value="">🌐 ภาพรวมทุกสถานี (Overall Network)</option>
                        @foreach($stations as $st)
                            <option value="{{ $st->id }}" data-lat="{{ $st->lat }}" data-lng="{{ $st->lng }}">
                                🏭 {{ $st->name }} ({{ $st->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                <div class="module-nav-pills">
                    <a href="{{ route('smart_utility.elec') }}" class="module-nav-item {{ $module === 'elec' ? 'active' : '' }}">💡 ไฟฟ้า</a>
                    <a href="{{ route('smart_utility.water') }}" class="module-nav-item {{ $module === 'water' ? 'active' : '' }}">💧 ประปา</a>
                    <a href="{{ route('smart_utility.road') }}" class="module-nav-item {{ $module === 'road' ? 'active' : '' }}">🛣️ ถนน</a>
                    <a href="{{ route('smart_utility.bldg') }}" class="module-nav-item {{ $module === 'bldg' ? 'active' : '' }}">🏢 อาคาร</a>
                </div>

                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-xs btn-outline-dark mb-0 active" id="btnTileStreet">ถนน</button>
                    <button type="button" class="btn btn-xs btn-outline-dark mb-0" id="btnTileSat">ดาวเทียม</button>
                </div>
            </div>
        </div>

        <!-- Floating Top-Right Tool Actions -->
        <div class="floating-top-right">
            <button class="gis-btn" id="btnTogglePinDrop">
                <i class="fas fa-map-pin text-primary"></i> <span id="pinDropBtnText">ปักหมุดลงแผนที่</span>
            </button>
            <button class="gis-btn" id="btnToggleDrawLine">
                <i class="fas fa-route text-info"></i> <span id="drawLineBtnText">{{ $module === 'water' ? 'วาดแนวท่อ' : 'วาดแนวเส้น' }}</span>
            </button>
            <button class="gis-btn" id="btnToggleDrawZone">
                <i class="fas fa-draw-polygon text-success"></i> <span id="drawZoneBtnText">วาดพื้นที่ (Area)</span>
            </button>
            <button class="gis-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLayerFilter">
                <i class="fas fa-layer-group text-dark"></i> สลับเลเยอร์
            </button>
            <button class="gis-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAssetList">
                <i class="fas fa-list-ul text-dark"></i> รายการทรัพย์สิน (<span id="assetCountBadge">{{ $stats['total_items'] }}</span>)
            </button>
            <button class="gis-btn position-relative" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTicketList">
                <i class="fas fa-wrench text-warning"></i> งานซ่อม
                @if($stats['pending_tickets'] > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $stats['pending_tickets'] }}
                    </span>
                @endif
            </button>
            <button class="gis-btn" id="btnToggleFullscreen" title="ขยายเต็มจอ">
                <i class="fas fa-expand-arrows-alt"></i>
            </button>
            <button class="gis-btn" data-bs-toggle="modal" data-bs-target="#modalConfig" title="ตั้งค่าแผนที่">
                <i class="fas fa-cog"></i>
            </button>
        </div>

        <!-- Floating Bottom Status Bar -->
        <div class="glass-panel floating-bottom-bar">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="stat-pill"><i class="fas fa-map-marker-alt text-primary"></i> <span id="currentModuleLabel">{{ $moduleInfo['badge'] }}</span></span>
                <span class="stat-pill"><i class="fas fa-route text-info"></i> แนวเส้น: <b id="lineCountBadge">{{ $stats['total_lines'] }}</b> เส้น</span>
                <span class="stat-pill"><i class="fas fa-draw-polygon text-success"></i> พื้นที่: <b id="zoneCountBadge">0</b> โซน</span>
                <span class="stat-pill"><i class="fas fa-magnet text-success"></i> แม่เหล็กดูดเข้าหมุด: <b>เปิดใช้งาน</b></span>
                @if($module === 'water' && !empty($stats['system_meters']))
                    <span class="stat-pill"><i class="fas fa-tint text-primary"></i> มิเตอร์ในระบบประปา: <b>{{ number_format($stats['system_meters']) }}</b> เครื่อง</span>
                @endif
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-xs btn-outline-secondary mb-0" id="btnSyncCloud" title="ซิงค์ข้อมูลกับฐานข้อมูล">
                    <i class="fas fa-sync-alt me-1"></i> ซิงค์ข้อมูล
                </button>
                <button class="btn btn-xs btn-outline-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalBackup">
                    <i class="fas fa-file-export me-1"></i> สำรอง/นำเข้า
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas: เลเยอร์แผนที่ (Layer Filter Drawer) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasLayerFilter" style="width: 360px; z-index: 100000;">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title font-weight-bold">
            <i class="fas fa-layer-group text-primary me-2"></i> จัดการเลเยอร์และหมวดหมู่
        </h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3">
        <p class="text-xs text-muted mb-3">เปิด-ปิดการแสดงผลข้อมูลบนแผนที่ เพื่อวิเคราะห์ระบบภาพรวมหรือเฉพาะส่วน</p>

        @if($module === 'water')
            <div class="mb-3">
                <h6 class="text-xs font-weight-bold text-dark text-uppercase mb-2">โครงสร้างระบบประปา</h6>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkStations" checked onchange="toggleWaterSubLayer('stations', this.checked)">
                    <label class="form-check-label text-xs font-weight-bold" for="layerChkStations">🏭 โรงผลิตน้ำ & แพสูบน้ำดิบ</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkPipeNodes" checked onchange="toggleWaterSubLayer('nodes', this.checked)">
                    <label class="form-check-label text-xs font-weight-bold" for="layerChkPipeNodes">🔀 จุดแยกท่อใหญ่ & ประตูน้ำ</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkMainPipes" checked onchange="toggleWaterSubLayer('main_pipes', this.checked)">
                    <label class="form-check-label text-xs font-weight-bold text-primary" for="layerChkMainPipes">🔷 ท่อเมนหลัก (Main Transmission)</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkSubPipes" checked onchange="toggleWaterSubLayer('sub_pipes', this.checked)">
                    <label class="form-check-label text-xs font-weight-bold text-info" for="layerChkSubPipes">🔹 ท่อจ่ายรอง / ท่อซอย (Distribution)</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkMeters" checked onchange="toggleWaterSubLayer('meters', this.checked)">
                    <label class="form-check-label text-xs font-weight-bold" for="layerChkMeters">💧 มิเตอร์ผู้ใช้น้ำ (Water Meters)</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkZones" checked onchange="toggleWaterSubLayer('zones', this.checked)">
                    <label class="form-check-label text-xs font-weight-bold text-success" for="layerChkZones">⬡ พื้นที่บริการ & สระพักน้ำ (Zones)</label>
                </div>
            </div>
        @else
            <div class="mb-3">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkItemsGen" checked onchange="itemsLayer.eachLayer(l => l._map ? gisMap.removeLayer(l) : gisMap.addLayer(l))">
                    <label class="form-check-label text-xs font-weight-bold" for="layerChkItemsGen">📍 แสดงจุดทรัพย์สิน</label>
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="layerChkShapesGen" checked onchange="shapesLayer.eachLayer(l => l._map ? gisMap.removeLayer(l) : gisMap.addLayer(l))">
                    <label class="form-check-label text-xs font-weight-bold" for="layerChkShapesGen">🛣️ แสดงแนวเส้นและพื้นที่</label>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Offcanvas: รายการทรัพย์สิน (Asset Drawer) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAssetList" style="width: 420px; z-index: 100000;">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title font-weight-bold">
            <i class="fas fa-cubes text-primary me-2"></i> รายการทรัพย์สิน ({{ $moduleInfo['title'] }})
        </h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3">
        <div class="mb-3">
            <input type="text" id="drawerSearchInput" class="form-control form-control-sm mb-2" placeholder="ค้นหาตามรหัส หรือชื่อ..." />
            <div class="d-flex gap-1">
                <select id="drawerVillageFilter" class="form-select form-select-sm">
                    <option value="">-- ทุกหมู่บ้าน --</option>
                </select>
                <select id="drawerStatusFilter" class="form-select form-select-sm">
                    <option value="">-- ทุกสถานะ --</option>
                    <option value="good">🟢 ปกติ</option>
                    <option value="bad">🔴 ชำรุด/ดับ</option>
                    <option value="wait">🟡 รอตรวจ</option>
                </select>
            </div>
        </div>

        <div id="drawerAssetItems" class="d-flex flex-column gap-2" style="max-height: calc(100vh - 200px); overflow-y: auto;">
            <div class="text-center py-4 text-muted">กำลังโหลดข้อมูล...</div>
        </div>
    </div>
</div>

<!-- Offcanvas: รายการแจ้งซ่อม / ใบสั่งซ่อม (Ticket Drawer) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasTicketList" style="width: 440px; z-index: 100000;">
    <div class="offcanvas-header border-bottom">
        <h6 class="offcanvas-title font-weight-bold">
            <i class="fas fa-wrench text-warning me-2"></i> งานแจ้งซ่อม & ใบสั่งงาน ({{ $moduleInfo['badge'] }})
        </h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-xs text-muted">รายการทั้งหมด</span>
            <button class="btn btn-xs btn-primary mb-0" id="btnOpenNewTicketModal">
                <i class="fas fa-plus me-1"></i> เปิดใบสั่งซ่อมใหม่
            </button>
        </div>

        <div id="drawerTicketItems" class="d-flex flex-column gap-2" style="max-height: calc(100vh - 180px); overflow-y: auto;">
            <div class="text-center py-4 text-muted">ไม่มีรายการแจ้งซ่อม</div>
        </div>
    </div>
</div>

<!-- Modal: แจ้งซ่อม / สั่งซ่อม (Ticket Modal) -->
<div class="modal fade" id="modalCreateTicket" tabindex="-1" style="z-index: 100005;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold">
                    <i class="fas fa-wrench text-warning me-2"></i> เปิดใบสั่งซ่อม / รับแจ้งเหตุ
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTicketSave">
                <div class="modal-body p-3">
                    <input type="hidden" id="ticketModule" value="{{ $module }}" />

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label text-xs font-weight-bold mb-1">รหัสอ้างอิงจุด / เสา (ถ้ามี)</label>
                            <input type="text" id="ticketRefCode" class="form-control form-control-sm" />
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs font-weight-bold mb-1">หมู่บ้าน</label>
                            <input type="text" id="ticketVillage" class="form-control form-control-sm" />
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold mb-1">สถานที่เกิดเหตุ / จุดสังเกต</label>
                        <input type="text" id="ticketLocation" class="form-control form-control-sm" placeholder="เช่น หน้าร้านค้าป้าพร" />
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold mb-1">รายละเอียดปัญหาที่ชำรุด *</label>
                        <textarea id="ticketDetail" class="form-control form-control-sm" rows="3" required placeholder="ระบุอาการชำรุด เช่น หลอดไฟไม่ติด, ท่อเมนแตกรั่วไหลนอง..."></textarea>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label text-xs font-weight-bold mb-1">ละติจูด</label>
                            <input type="number" step="any" id="ticketLat" class="form-control form-control-sm" />
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs font-weight-bold mb-1">ลองจิจูด</label>
                            <input type="number" step="any" id="ticketLng" class="form-control form-control-sm" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-sm btn-secondary mb-0" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-sm btn-warning mb-0">
                        <i class="fas fa-check-circle me-1"></i> บันทึกและเปิดงานซ่อม
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: ตั้งค่าระบบ & พิกัดศูนย์กลาง -->
<div class="modal fade" id="modalConfig" tabindex="-1" style="z-index: 100005;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold">
                    <i class="fas fa-cog text-dark me-2"></i> ตั้งค่าข้อมูลและพิกัดศูนย์กลางแผนที่
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formConfigSave">
                <div class="modal-body p-3">
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold mb-1">ชื่อหน่วยงาน / อปท. *</label>
                        <input type="text" id="cfgOrgName" class="form-control form-control-sm" value="{{ $config->org_name }}" required />
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold mb-1">พื้นที่ / ตำบล / อำเภอ</label>
                        <input type="text" id="cfgAreaName" class="form-control form-control-sm" value="{{ $config->area_name }}" />
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label text-xs font-weight-bold mb-1">พิกัดศูนย์กลาง Lat</label>
                            <input type="number" step="any" id="cfgCenterLat" class="form-control form-control-sm" value="{{ $config->center_lat }}" required />
                        </div>
                        <div class="col-6">
                            <label class="form-label text-xs font-weight-bold mb-1">พิกัดศูนย์กลาง Lng</label>
                            <input type="number" step="any" id="cfgCenterLng" class="form-control form-control-sm" value="{{ $config->center_lng }}" required />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-xs font-weight-bold mb-1">คำนำหน้ารหัส ({{ $moduleInfo['badge'] }})</label>
                        <input type="text" id="cfgModulePrefix" class="form-control form-control-sm" value="{{ $config->{$module . '_prefix'} ?? strtoupper($module) }}" />
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-sm btn-secondary mb-0" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-sm btn-dark mb-0">บันทึกการตั้งค่า</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: สำรอง / นำเข้า JSON -->
<div class="modal fade" id="modalBackup" tabindex="-1" style="z-index: 100005;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title font-weight-bold">
                    <i class="fas fa-file-archive text-info me-2"></i> จัดการไฟล์สำรองข้อมูล (JSON Backup)
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div class="mb-3">
                    <label class="form-label text-xs font-weight-bold">1. ดาวน์โหลดไฟล์สำรองข้อมูล</label>
                    <p class="text-xs text-muted mb-2">ส่งออกข้อมูลจุดพิกัด แนวสายไฟ แนวท่อ และประวัติทั้งหมดเป็นไฟล์ JSON</p>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-0 w-100" id="btnExportJSON">
                        <i class="fas fa-download me-1"></i> ดาวน์โหลด JSON Backup
                    </button>
                </div>
                <hr class="horizontal dark my-3" />
                <div class="mb-2">
                    <label class="form-label text-xs font-weight-bold">2. นำเข้าข้อมูลจากไฟล์ JSON</label>
                    <input type="file" id="importJsonFileInput" class="form-control form-control-sm mb-2" accept=".json" />
                    <button type="button" class="btn btn-sm btn-danger mb-0 w-100" id="btnImportJSON">
                        <i class="fas fa-upload me-1"></i> นำเข้าข้อมูล (แทนที่ข้อมูลเดิม)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script>
    window.CURRENT_MODULE = "{{ $module }}";
    window.API_BASE = "{{ url('smart-utility/api') }}";
    window.CSRF_TOKEN = "{{ csrf_token() }}";

    const MODULE_COLOR = "{{ $moduleInfo['color'] }}";

    let gisMap = null;
    let streetTile = null;
    let satelliteTile = null;

    // Layer Groups
    let itemsLayer = L.layerGroup();
    let shapesLayer = L.layerGroup();
    let zonesLayer = L.layerGroup();

    // Water Specific Sub-Layers
    let waterStationsLayer = L.layerGroup();
    let waterPipeNodesLayer = L.layerGroup();
    let waterMainPipesLayer = L.layerGroup();
    let waterSubPipesLayer = L.layerGroup();
    let waterMetersLayer = L.layerGroup();

    let allItems = [];
    let allShapes = { lines: [], zones: [], ann: [] };
    let allTickets = [];
    let appConfig = @json($config);
    let waterStations = @json($stations ?? []);

    // Active Station Filter
    let selectedStationId = "";

    // Pin Drop Mode
    let isDroppingPinMode = false;
    let tempDropMarker = null;

    // Line Drawing & Snapping Mode
    let isDrawingLine = false;
    let linePoints = [];
    let connectedAssets = [];
    let activeDrawingPolyline = null;
    let drawingPreviewLine = null;
    let snapIndicatorCircle = null;

    // Area / Polygon Drawing Mode
    let isDrawingZone = false;
    let zonePoints = [];
    let activeDrawingPolygon = null;

    // Moving existing marker state
    let movingMarkerId = null;
    let movingMarkerOriginalLatLng = null;

    document.addEventListener('DOMContentLoaded', function () {
        initGISMap();
        loadGISData();
        setupEventListeners();
    });

    /**
     * ✨ Generate pixel-perfect SVG Teardrop Pin
     * Bottom needle tip is at [16, 40], matching iconAnchor: [16, 40]
     */
    function createSvgPin(color, iconType, isBad = false, isDraggable = false) {
        let pinColor = isBad ? '#ef4444' : color;
        let iconSvg = '';

        if (iconType === 'plant') {
            iconSvg = '🏭';
            pinColor = '#4338ca'; // Indigo
        } else if (iconType === 'pump_raw') {
            iconSvg = '🚤';
            pinColor = '#0d9488'; // Teal
        } else if (iconType === 'junction_major') {
            iconSvg = '🔀';
            pinColor = '#d97706'; // Amber
        } else if (iconType === 'junction_sub') {
            iconSvg = '🔹';
            pinColor = '#0284c7'; // Blue
        } else if (iconType === 'valve') {
            iconSvg = '🚰';
            pinColor = '#059669'; // Green
        } else if (iconType === 'tank') {
            iconSvg = '🏢';
            pinColor = '#7c3aed'; // Purple
        } else if (iconType === 'meter') {
            iconSvg = '💧';
            pinColor = '#0284c7';
        } else if (iconType === 'elec') {
            iconSvg = '💡';
        } else if (iconType === 'road') {
            iconSvg = '🛣️';
        } else if (iconType === 'bldg') {
            iconSvg = '🏢';
        } else {
            iconSvg = '📍';
        }

        return L.divIcon({
            className: 'custom-svg-pin' + (isDraggable ? ' is-draggable-active' : ''),
            html: `
                <div class="pin-wrapper">
                    <svg width="32" height="40" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 0C7.16344 0 0 7.16344 0 16C0 26 16 40 16 40C16 40 32 26 32 16C32 7.16344 24.8366 0 16 0Z" fill="${pinColor}"/>
                        <circle cx="16" cy="15" r="9" fill="#ffffff"/>
                    </svg>
                    <span class="pin-emoji">${iconSvg}</span>
                    ${isBad ? '<span class="pin-badge-bad">!</span>' : ''}
                </div>
            `,
            iconSize: [32, 40],
            iconAnchor: [16, 40],    // 🎯 EXACT NEEDLE TIP!
            popupAnchor: [0, -42]    // Popup opens cleanly above the pin
        });
    }

    function initGISMap() {
        const centerLat = parseFloat(appConfig.center_lat) || 13.690000;
        const centerLng = parseFloat(appConfig.center_lng) || 101.200000;

        gisMap = L.map('gisMap', {
            center: [centerLat, centerLng],
            zoom: 15,
            zoomControl: false,
            doubleClickZoom: false
        });

        L.control.zoom({ position: 'bottomright' }).addTo(gisMap);

        // Tiles
        streetTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(gisMap);

        satelliteTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '© Esri World Imagery'
        });

        // Add main layers
        itemsLayer.addTo(gisMap);
        shapesLayer.addTo(gisMap);
        zonesLayer.addTo(gisMap);

        // Water sub layers
        if (window.CURRENT_MODULE === 'water') {
            waterStationsLayer.addTo(gisMap);
            waterPipeNodesLayer.addTo(gisMap);
            waterMainPipesLayer.addTo(gisMap);
            waterSubPipesLayer.addTo(gisMap);
            waterMetersLayer.addTo(gisMap);
        }

        // Map Click Handler
        gisMap.on('click', function (e) {
            if (isDroppingPinMode) {
                handleMapClickDropPin(e.latlng);
            } else if (isDrawingLine) {
                const snapped = findSnapMarker(e.latlng, 26);
                if (snapped) {
                    handleDrawLinePoint([parseFloat(snapped.lat), parseFloat(snapped.lng)], snapped);
                } else {
                    handleDrawLinePoint([e.latlng.lat, e.latlng.lng], null);
                }
            } else if (isDrawingZone) {
                handleDrawZoneClick(e.latlng);
            }
        });

        // Double-click on map to finish drawing
        gisMap.on('dblclick', function (e) {
            if (isDrawingLine && linePoints.length >= 2) {
                toggleDrawLineMode();
            } else if (isDrawingZone && zonePoints.length >= 3) {
                toggleDrawZoneMode();
            }
        });

        // Mousemove for live snapping preview
        gisMap.on('mousemove', function (e) {
            if (isDrawingLine) {
                handleDrawingMouseMove(e.latlng);
            }
        });
    }

    function loadGISData() {
        fetch(`${window.API_BASE}/init`)
            .then(res => res.json())
            .then(data => {
                if (!data.ok) return;

                allItems = (data.items && data.items[window.CURRENT_MODULE]) ? data.items[window.CURRENT_MODULE] : [];
                allShapes = (data.shapes && data.shapes[window.CURRENT_MODULE]) ? data.shapes[window.CURRENT_MODULE] : { lines: [], zones: [], ann: [] };
                allTickets = (data.tickets || []).filter(t => t.module === window.CURRENT_MODULE);

                renderMapItems();
                renderMapShapes();
                renderMapZones();
                renderDrawerList();
                renderTicketDrawer();
                updateKPISummary();

                // If water module, render water networks
                if (window.CURRENT_MODULE === 'water' && data.system_water) {
                    renderWaterNetworkSystem(data.system_water);
                }
            })
            .catch(err => console.error('Error loading GIS data:', err));
    }

    /**
     * Render General and Custom Items with Filter by Station
     */
    function renderMapItems() {
        itemsLayer.clearLayers();

        allItems.forEach(item => {
            if (!item.lat || !item.lng) return;

            // Station filtering for Water module
            if (window.CURRENT_MODULE === 'water' && selectedStationId) {
                const itemStationId = item.meta && item.meta.station_id ? String(item.meta.station_id) : '';
                if (itemStationId && itemStationId !== String(selectedStationId)) {
                    return; // Skip items from other stations
                }
            }

            const isBad = (item.status === 'bad');
            const pinIcon = createSvgPin(MODULE_COLOR, item.item_type || window.CURRENT_MODULE, isBad, false);

            const marker = L.marker([parseFloat(item.lat), parseFloat(item.lng)], {
                icon: pinIcon,
                draggable: false
            });

            marker.itemId = item.id;
            marker.itemData = item;

            marker.on('click', function (e) {
                if (isDrawingLine) {
                    L.DomEvent.stopPropagation(e);
                    const markerCoord = [parseFloat(item.lat), parseFloat(item.lng)];
                    handleDrawLinePoint(markerCoord, item);
                    return;
                }
            });

            marker.bindPopup(() => getExistingItemPopupHtml(item, marker));

            marker.on('dragend', function (e) {
                handleExistingMarkerDragEnd(item, marker, e.target.getLatLng());
            });

            itemsLayer.addLayer(marker);
        });
    }

    function getExistingItemPopupHtml(item, marker) {
        let stationBadge = '';
        if (item.meta && item.meta.station_name) {
            stationBadge = `<span class="badge bg-indigo text-white ms-1" style="font-size:9.5px;"><i class="fas fa-industry"></i> ${item.meta.station_name}</span>`;
        }

        return `
            <div class="gis-popup">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <strong class="text-dark" style="font-size:14px;">${item.item_code}</strong>
                        ${stationBadge}
                    </div>
                    <span class="badge ${item.status === 'bad' ? 'bg-danger' : (item.status === 'wait' ? 'bg-warning' : 'bg-success')} text-white" style="font-size:10px;">
                        ${item.status === 'bad' ? 'ชำรุด / ดับ / รั่ว' : (item.status === 'wait' ? 'รอซ่อม' : 'ปกติ')}
                    </span>
                </div>
                <h6>${item.name || item.item_type || 'ไม่มีชื่อระบุ'}</h6>
                <p class="mb-1"><i class="fas fa-tag text-muted me-1"></i> ประเภท: <b>${item.item_type || 'อุปกรณ์ทั่วไป'}</b></p>
                <p class="mb-1"><i class="fas fa-map-marker-alt text-muted me-1"></i> ${item.village || 'ไม่ระบุหมู่บ้าน'}</p>
                <p class="mb-2 text-xxs text-muted">📍 พิกัด: ${parseFloat(item.lat).toFixed(6)}, ${parseFloat(item.lng).toFixed(6)}</p>

                <div class="d-flex flex-wrap gap-1 pt-1 border-top">
                    <button class="btn btn-xs btn-outline-warning mb-0 py-1" onclick="enableExistingMarkerMove(${item.id})">
                        <i class="fas fa-arrows-alt me-1"></i> ย้ายตำแหน่ง
                    </button>
                    <button class="btn btn-xs btn-outline-dark mb-0 py-1" onclick="openReportModalFor('${item.item_code}', '${item.village || ''}', ${item.lat}, ${item.lng})">
                        <i class="fas fa-wrench me-1"></i> แจ้งซ่อม
                    </button>
                    <button class="btn btn-xs btn-outline-danger mb-0 py-1" onclick="deleteAssetItem(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    }

    /* ======================================================== */
    /* 🏭 Render Water Network: Stations, Intakes, Pipes, Zones */
    /* ======================================================== */

    function renderWaterNetworkSystem(systemWater) {
        waterStationsLayer.clearLayers();
        waterPipeNodesLayer.clearLayers();
        waterMainPipesLayer.clearLayers();
        waterSubPipesLayer.clearLayers();
        waterMetersLayer.clearLayers();

        // 1. Water Treatment Plants & Floating Raw Water Pumps
        if (systemWater.stations && Array.isArray(systemWater.stations)) {
            systemWater.stations.forEach(st => {
                if (selectedStationId && String(st.id) !== String(selectedStationId)) return;

                const plantIcon = createSvgPin('#4338ca', 'plant', false, false);
                const m = L.marker([st.lat, st.lng], { icon: plantIcon });
                m.bindPopup(`
                    <div class="gis-popup">
                        <span class="badge bg-primary text-white mb-1">โรงผลิตน้ำประปา</span>
                        <h6>🏭 ${st.name}</h6>
                        <p class="mb-1">รหัสสถานี: <b>${st.code}</b></p>
                        <p class="mb-2">พิกัด: ${st.lat.toFixed(5)}, ${st.lng.toFixed(5)}</p>
                        <button class="btn btn-xs btn-primary w-100" onclick="filterByStation('${st.id}')">
                            <i class="fas fa-filter me-1"></i> กรองเฉพาะโครงข่ายสถานีนี้
                        </button>
                    </div>
                `);
                waterStationsLayer.addLayer(m);
            });
        }

        // 2. Pipe Nodes (จุดแยกท่อใหญ่, ประตูน้ำ)
        if (systemWater.pipe_nodes && Array.isArray(systemWater.pipe_nodes)) {
            systemWater.pipe_nodes.forEach(nd => {
                if (selectedStationId && String(nd.station_id) !== String(selectedStationId)) return;

                const nodeIcon = createSvgPin('#d97706', nd.type === 'valve' ? 'valve' : 'junction_major', false, false);
                const m = L.marker([nd.lat, nd.lng], { icon: nodeIcon });
                m.bindPopup(`
                    <div class="gis-popup">
                        <span class="badge bg-warning text-dark mb-1">จุดแยกท่อ / โหนด</span>
                        <h6>🔀 ${nd.name || nd.node_code}</h6>
                        <p class="mb-1">รหัส: <b>${nd.node_code}</b> | ประเภท: ${nd.type}</p>
                        <p class="text-xs text-muted mb-0">${nd.remark || ''}</p>
                    </div>
                `);
                waterPipeNodesLayer.addLayer(m);
            });
        }

        // 3. Tabwater Pipelines from DB
        if (systemWater.pipelines && Array.isArray(systemWater.pipelines)) {
            systemWater.pipelines.forEach(pipe => {
                if (selectedStationId && pipe.station_id && String(pipe.station_id) !== String(selectedStationId)) return;

                const isMain = (pipe.level === 'main');
                const lineColor = isMain ? '#0284c7' : '#0ea5e9';
                const lineWeight = isMain ? 6 : 3.5;

                const poly = L.polyline(pipe.coordinates, {
                    color: lineColor,
                    weight: lineWeight,
                    opacity: 0.9
                });

                const pipeLengthM = calculatePolylineLength(pipe.coordinates);

                // Tooltip showing name & size along the line
                poly.bindTooltip(`<b>${pipe.name || 'ท่อประปา'}</b> (${pipe.size_mm || '-'} มม.)`, {
                    sticky: true,
                    className: 'pipe-label-tooltip'
                });

                poly.bindPopup(`
                    <div class="gis-popup">
                        <span class="badge ${isMain ? 'bg-primary' : 'bg-info'} text-white mb-1">
                            ${isMain ? 'ท่อเมนหลัก (Main)' : 'ท่อจ่ายรอง (Sub)'}
                        </span>
                        <h6>💧 ${pipe.name || 'แนวท่อประปา'}</h6>
                        <p class="mb-1">ขนาด: <b>${pipe.size_mm || '-'} มม.</b> | ชนิด: <b>${pipe.material || 'HDPE'}</b></p>
                        <p class="mb-1">ความยาวประมาณการ: <b>${pipeLengthM.toLocaleString()} เมตร</b></p>
                    </div>
                `);

                if (isMain) {
                    waterMainPipesLayer.addLayer(poly);
                } else {
                    waterSubPipesLayer.addLayer(poly);
                }
            });
        }
    }

    /* ======================================================== */
    /* 📍 Interactive Drop Pin & Drag Placement Workflow */
    /* ======================================================== */

    function toggleDropPinMode() {
        isDroppingPinMode = !isDroppingPinMode;
        const btn = document.getElementById('btnTogglePinDrop');
        const banner = document.getElementById('modeGuideBanner');
        const wrapper = document.getElementById('gisWrapper');

        if (isDroppingPinMode) {
            if (isDrawingLine) toggleDrawLineMode();
            if (isDrawingZone) toggleDrawZoneMode();

            btn.classList.add('active-tool');
            document.getElementById('pinDropBtnText').textContent = 'กำลังปักหมุด...';
            wrapper.classList.add('is-dropping-mode');

            document.getElementById('modeGuideText').innerHTML = '👉 <b>คลิกบนแผนที่</b> เพื่อวางหมุด สามารถลากหมุดปรับตำแหน่งได้ทันทีก่อนบันทึก';
            banner.style.display = 'flex';
        } else {
            exitPinDropMode();
        }
    }

    function exitPinDropMode() {
        isDroppingPinMode = false;
        const btn = document.getElementById('btnTogglePinDrop');
        const banner = document.getElementById('modeGuideBanner');
        const wrapper = document.getElementById('gisWrapper');

        btn.classList.remove('active-tool');
        document.getElementById('pinDropBtnText').textContent = 'ปักหมุดลงแผนที่';
        wrapper.classList.remove('is-dropping-mode');
        banner.style.display = 'none';

        if (tempDropMarker) {
            gisMap.removeLayer(tempDropMarker);
            tempDropMarker = null;
        }
    }

    function handleMapClickDropPin(latlng) {
        if (tempDropMarker) {
            gisMap.removeLayer(tempDropMarker);
        }

        const nextNum = String(allItems.length + 1).padStart(4, '0');
        const autoCode = (window.CURRENT_MODULE.toUpperCase()) + '-' + nextNum;

        const activePinIcon = createSvgPin(MODULE_COLOR, window.CURRENT_MODULE, false, true);
        tempDropMarker = L.marker([latlng.lat, latlng.lng], {
            icon: activePinIcon,
            draggable: true
        }).addTo(gisMap);

        tempDropMarker.on('dragend', function (e) {
            openInlinePinPopup(e.target.getLatLng(), autoCode);
        });

        openInlinePinPopup(latlng, autoCode);
    }

    function openInlinePinPopup(latlng, autoCode) {
        const villageList = [...new Set(allItems.map(it => it.village).filter(Boolean))];
        const villageDatalist = villageList.map(v => `<option value="${v}">`).join('');

        let defaultTypeOptions = '';
        let stationDropdownHtml = '';

        if (window.CURRENT_MODULE === 'water') {
            defaultTypeOptions = `
                <option value="junction_major">🔀 จุดแยกท่อใหญ่ (Major Junction)</option>
                <option value="junction_sub">🔹 จุดแยกท่อย่อย (Sub Junction)</option>
                <option value="pump_raw">🚤 แพสูบน้ำดิบ (Raw Water Intake)</option>
                <option value="plant">🏭 โรงผลิตน้ำประปา (Treatment Plant)</option>
                <option value="valve">🚰 ประตูน้ำ (Gate Valve)</option>
                <option value="tank">🏢 ถังพักน้ำใส / หอถังสูง (Storage Tank)</option>
                <option value="meter">💧 มิเตอร์ผู้ใช้น้ำ (Water Meter)</option>
            `;

            let stationOpts = '<option value="">-- ไม่ระบุ / ภาพรวม --</option>';
            waterStations.forEach(st => {
                const isSel = (String(st.id) === String(selectedStationId)) ? 'selected' : '';
                stationOpts += `<option value="${st.id}" data-name="${st.name}" ${isSel}>🏭 ${st.name}</option>`;
            });

            stationDropdownHtml = `
                <div class="mb-2">
                    <label class="form-label text-xxs mb-0 font-weight-bold">สังกัดโรงผลิตน้ำ (Station)</label>
                    <select id="quickPinStation" class="form-select form-select-sm py-1 text-xs">
                        ${stationOpts}
                    </select>
                </div>
            `;
        } else if (window.CURRENT_MODULE === 'elec') {
            defaultTypeOptions = '<option value="เสาไฟคอนกรีต 8ม.">เสาไฟคอนกรีต 8ม.</option><option value="เสาไฟเหล็กกิ่งเดี่ยว">เสาไฟเหล็กกิ่งเดี่ยว</option><option value="โคมไฟถนน LED 50W">โคมไฟถนน LED 50W</option><option value="โคมไฟโซลาร์เซลล์">โคมไฟโซลาร์เซลล์</option><option value="หม้อแปลงไฟฟ้า">หม้อแปลงไฟฟ้า</option>';
        } else if (window.CURRENT_MODULE === 'road') {
            defaultTypeOptions = '<option value="ถนนคอนกรีต">ถนนคอนกรีต</option><option value="ถนนลาดยางแอสฟัลต์">ถนนลาดยางแอสฟัลต์</option><option value="ท่อระบายน้ำ คสล.">ท่อระบายน้ำ คสล.</option><option value="สะพาน / ท่อลอด">สะพาน / ท่อลอด</option>';
        } else {
            defaultTypeOptions = '<option value="อาคารสำนักงาน อปท.">อาคารสำนักงาน อปท.</option><option value="ศูนย์พัฒนาเด็กเล็ก">ศูนย์พัฒนาเด็กเล็ก</option><option value="โรงพยาบาลส่งเสริมสุขภาพ (รพ.สต.)">รพ.สต.</option><option value="สวนสาธารณะ / ลานกีฬา">สวนสาธารณะ / ลานกีฬา</option>';
        }

        const popupContent = `
            <div class="inline-pin-popup">
                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                    <strong class="text-xs text-primary"><i class="fas fa-map-pin"></i> ปักหมุด (${autoCode})</strong>
                    <span class="text-xxs text-muted"><i class="fas fa-arrows-alt"></i> ลากหมุดเพื่อปรับจุดได้</span>
                </div>

                <div class="mb-2">
                    <label class="form-label text-xxs mb-0 font-weight-bold">ชื่อจุด / สถานที่เรียก</label>
                    <input type="text" id="quickPinName" class="form-control form-control-sm py-1 text-xs" placeholder="เช่น จุดท่อใหญ่สี่แยก ม.1, แพสูบหนองบอน" autofocus />
                </div>

                <div class="row g-1 mb-2">
                    <div class="col-6">
                        <label class="form-label text-xxs mb-0 font-weight-bold">ประเภท</label>
                        <select id="quickPinType" class="form-select form-select-sm py-1 text-xs">
                            ${defaultTypeOptions}
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-xxs mb-0 font-weight-bold">หมู่บ้าน</label>
                        <input type="text" list="villageListQuick" id="quickPinVillage" class="form-control form-control-sm py-1 text-xs" placeholder="เช่น ม.1" />
                        <datalist id="villageListQuick">${villageDatalist}</datalist>
                    </div>
                </div>

                ${stationDropdownHtml}

                <div class="mb-2">
                    <label class="form-label text-xxs mb-0 font-weight-bold">สภาพการใช้งาน</label>
                    <select id="quickPinStatus" class="form-select form-select-sm py-1 text-xs">
                        <option value="good">🟢 ปกติ (พร้อมใช้งาน)</option>
                        <option value="bad">🔴 ชำรุด (ท่อแตก / ดับ)</option>
                        <option value="wait">🟡 รอตรวจสอบ / เฝ้าระวัง</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-1 border-top">
                    <button type="button" class="btn btn-xs btn-outline-secondary mb-0 py-1" onclick="cancelQuickPinDrop()">
                        ยกเลิก
                    </button>
                    <button type="button" class="btn btn-xs btn-primary mb-0 py-1 font-weight-bold" onclick="saveQuickPinDrop('${autoCode}')">
                        <i class="fas fa-check me-1"></i> บันทึกจุดนี้
                    </button>
                </div>
            </div>
        `;

        tempDropMarker.bindPopup(popupContent, { autoClose: false, closeOnClick: false }).openPopup();
    }

    window.cancelQuickPinDrop = function () {
        exitPinDropMode();
    };

    window.saveQuickPinDrop = function (autoCode) {
        if (!tempDropMarker) return;

        const pos = tempDropMarker.getLatLng();
        const nameVal = document.getElementById('quickPinName').value;
        const typeVal = document.getElementById('quickPinType').value;
        const villageVal = document.getElementById('quickPinVillage').value;
        const statusVal = document.getElementById('quickPinStatus').value;

        let metaObj = {};
        if (window.CURRENT_MODULE === 'water') {
            const stEl = document.getElementById('quickPinStation');
            if (stEl && stEl.value) {
                metaObj.station_id = stEl.value;
                metaObj.station_name = stEl.options[stEl.selectedIndex].getAttribute('data-name');
            }
        }

        const payload = {
            module: window.CURRENT_MODULE,
            item_code: autoCode,
            name: nameVal || typeVal,
            item_type: typeVal,
            village: villageVal,
            status: statusVal,
            lat: parseFloat(pos.lat.toFixed(6)),
            lng: parseFloat(pos.lng.toFixed(6)),
            meta: metaObj
        };

        fetch(`${window.API_BASE}/item`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                exitPinDropMode();
                loadGISData();
            } else {
                alert('ไม่สามารถบันทึกได้: ' + (data.message || 'Error'));
            }
        });
    };

    /* ======================================================== */
    /* 🔄 Move Existing Marker Directly by Dragging */
    /* ======================================================== */

    window.enableExistingMarkerMove = function (itemId) {
        const item = allItems.find(i => i.id == itemId);
        if (!item) return;

        let targetMarker = null;
        itemsLayer.eachLayer(layer => {
            if (layer.itemId == itemId) targetMarker = layer;
        });

        if (!targetMarker) return;

        targetMarker.closePopup();

        targetMarker.dragging.enable();
        movingMarkerId = itemId;
        movingMarkerOriginalLatLng = targetMarker.getLatLng();

        targetMarker.setIcon(createSvgPin(MODULE_COLOR, item.item_type || window.CURRENT_MODULE, (item.status === 'bad'), true));

        const banner = document.getElementById('modeGuideBanner');
        document.getElementById('modeGuideText').innerHTML = `🔄 <b>กำลังย้ายจุด ${item.item_code}</b>: ลากหมุดไปยังตำแหน่งใหม่ แล้วปล่อยเมาส์`;
        banner.style.display = 'flex';
    };

    function handleExistingMarkerDragEnd(item, marker, newLatLng) {
        if (!movingMarkerId || movingMarkerId != item.id) return;

        const confirmHtml = `
            <div class="gis-popup text-center">
                <h6 class="text-xs font-weight-bold mb-1">📍 ย้ายตำแหน่งจุด ${item.item_code}?</h6>
                <p class="text-xxs text-muted mb-2">พิกัดใหม่: ${newLatLng.lat.toFixed(6)}, ${newLatLng.lng.toFixed(6)}</p>
                <div class="d-flex justify-content-center gap-1">
                    <button class="btn btn-xs btn-secondary py-1 mb-0" onclick="cancelExistingMarkerMove(${item.id})">
                        ยกเลิก
                    </button>
                    <button class="btn btn-xs btn-success py-1 mb-0 font-weight-bold" onclick="confirmExistingMarkerMove(${item.id}, ${newLatLng.lat}, ${newLatLng.lng})">
                        <i class="fas fa-check me-1"></i> ยืนยันย้าย
                    </button>
                </div>
            </div>
        `;

        marker.bindPopup(confirmHtml, { autoClose: false, closeOnClick: false }).openPopup();
    }

    window.confirmExistingMarkerMove = function (itemId, newLat, newLng) {
        const item = allItems.find(i => i.id == itemId);
        if (!item) return;

        fetch(`${window.API_BASE}/item`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({
                id: item.id,
                module: item.module,
                item_code: item.item_code,
                name: item.name,
                item_type: item.item_type,
                village: item.village,
                status: item.status,
                lat: parseFloat(newLat.toFixed(6)),
                lng: parseFloat(newLng.toFixed(6)),
                meta: item.meta
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                movingMarkerId = null;
                movingMarkerOriginalLatLng = null;
                document.getElementById('modeGuideBanner').style.display = 'none';
                loadGISData();
            }
        });
    };

    window.cancelExistingMarkerMove = function (itemId) {
        if (movingMarkerOriginalLatLng) {
            itemsLayer.eachLayer(layer => {
                if (layer.itemId == itemId) {
                    layer.setLatLng(movingMarkerOriginalLatLng);
                    layer.dragging.disable();
                    layer.closePopup();
                }
            });
        }
        movingMarkerId = null;
        movingMarkerOriginalLatLng = null;
        document.getElementById('modeGuideBanner').style.display = 'none';
        renderMapItems();
    };

    /* ======================================================== */
    /* 🧲 Drawing Pipelines / Lines with Snapping Engine */
    /* ======================================================== */

    function findSnapMarker(latlng, tolerancePixels = 26) {
        if (!gisMap) return null;
        const clickPoint = gisMap.latLngToContainerPoint(latlng);
        let closestItem = null;
        let minDistance = tolerancePixels;

        allItems.forEach(item => {
            if (!item.lat || !item.lng) return;
            const itemPoint = gisMap.latLngToContainerPoint([parseFloat(item.lat), parseFloat(item.lng)]);
            const dist = clickPoint.distanceTo(itemPoint);
            if (dist < minDistance) {
                minDistance = dist;
                closestItem = item;
            }
        });

        return closestItem;
    }

    function toggleDrawLineMode() {
        isDrawingLine = !isDrawingLine;
        const btn = document.getElementById('btnToggleDrawLine');
        const btnText = document.getElementById('drawLineBtnText');
        const banner = document.getElementById('modeGuideBanner');
        const wrapper = document.getElementById('gisWrapper');

        if (isDrawingLine) {
            if (isDroppingPinMode) exitPinDropMode();
            if (isDrawingZone) toggleDrawZoneMode();

            btn.classList.add('active-tool');
            btnText.textContent = 'สิ้นสุดการวาด';
            wrapper.classList.add('is-drawing-mode');

            linePoints = [];
            connectedAssets = [];

            activeDrawingPolyline = L.polyline([], {
                color: (window.CURRENT_MODULE === 'water' ? '#0284c7' : '#eab308'),
                weight: (window.CURRENT_MODULE === 'water' ? 5 : 4),
                dashArray: '6, 8',
                opacity: 0.95
            }).addTo(gisMap);

            drawingPreviewLine = L.polyline([], {
                color: '#ef4444',
                weight: 2,
                dashArray: '3, 6',
                opacity: 0.7
            }).addTo(gisMap);

            updateDrawBannerText();
            banner.style.display = 'flex';
        } else {
            btn.classList.remove('active-tool');
            btnText.textContent = (window.CURRENT_MODULE === 'water' ? 'วาดแนวท่อ' : 'วาดแนวเส้น');
            wrapper.classList.remove('is-drawing-mode');
            banner.style.display = 'none';

            clearSnappingVisuals();

            if (linePoints.length >= 2) {
                promptSavePipeline(linePoints, connectedAssets);
            }
            if (activeDrawingPolyline) {
                gisMap.removeLayer(activeDrawingPolyline);
                activeDrawingPolyline = null;
            }
            if (drawingPreviewLine) {
                gisMap.removeLayer(drawingPreviewLine);
                drawingPreviewLine = null;
            }
        }
    }

    function handleDrawingMouseMove(latlng) {
        if (!isDrawingLine) return;

        const snapped = findSnapMarker(latlng, 26);
        let currentTargetCoord = [latlng.lat, latlng.lng];

        if (snapped) {
            currentTargetCoord = [parseFloat(snapped.lat), parseFloat(snapped.lng)];
            showSnappingVisual(snapped);
        } else {
            clearSnappingVisuals();
        }

        if (linePoints.length > 0 && drawingPreviewLine) {
            const lastPoint = linePoints[linePoints.length - 1];
            drawingPreviewLine.setLatLngs([lastPoint, currentTargetCoord]);
        }
    }

    function showSnappingVisual(item) {
        const itemCoord = [parseFloat(item.lat), parseFloat(item.lng)];

        if (!snapIndicatorCircle) {
            snapIndicatorCircle = L.circleMarker(itemCoord, {
                radius: 14,
                color: '#10b981',
                weight: 3,
                fillColor: '#6ee7b7',
                fillOpacity: 0.55
            }).addTo(gisMap);

            snapIndicatorCircle.bindTooltip(`🧲 เชื่อมกับ: ${item.item_code} (${item.name || item.item_type || ''})`, {
                permanent: true,
                direction: 'top',
                className: 'snap-tooltip',
                offset: [0, -14]
            }).openTooltip();
        } else {
            snapIndicatorCircle.setLatLng(itemCoord);
            snapIndicatorCircle.setTooltipContent(`🧲 เชื่อมกับ: ${item.item_code} (${item.name || item.item_type || ''})`);
        }
    }

    function clearSnappingVisuals() {
        if (snapIndicatorCircle) {
            gisMap.removeLayer(snapIndicatorCircle);
            snapIndicatorCircle = null;
        }
    }

    function handleDrawLinePoint(coord, snappedItem = null) {
        if (linePoints.length > 0) {
            const last = linePoints[linePoints.length - 1];
            if (Math.abs(last[0] - coord[0]) < 0.000001 && Math.abs(last[1] - coord[1]) < 0.000001) {
                return;
            }
        }

        linePoints.push(coord);
        if (snappedItem && snappedItem.item_code) {
            connectedAssets.push(snappedItem.item_code);
            const ripple = L.circleMarker(coord, { radius: 18, color: '#10b981', weight: 2, fill: false }).addTo(gisMap);
            setTimeout(() => gisMap.removeLayer(ripple), 600);
        }

        if (activeDrawingPolyline) {
            activeDrawingPolyline.setLatLngs(linePoints);
        }

        updateDrawBannerText();
    }

    function updateDrawBannerText() {
        const count = linePoints.length;
        let text = `✏️ <b>${window.CURRENT_MODULE === 'water' ? 'วาดแนวท่อประปา' : 'วาดแนวเส้น'}</b>: คลิกจุดเริ่มต้น (คลิกหมุดเพื่อเชื่อมต่อแบบแม่เหล็ก 🧲)`;

        if (count > 0) {
            let connectedInfo = '';
            if (connectedAssets.length > 0) {
                connectedInfo = ` <span class="badge bg-success ms-1">เชื่อมต่อ: ${connectedAssets.join(' ➔ ')}</span>`;
            }
            text = `✏️ กำหนดแล้ว <b>${count} จุด</b>${connectedInfo} | คลิกหมุดถัดไป หรือ <a href="javascript:;" onclick="toggleDrawLineMode()" class="text-warning text-decoration-underline ms-1">เสร็จสิ้น (บันทึก)</a>`;
        }

        document.getElementById('modeGuideText').innerHTML = text;
    }

    function promptSavePipeline(points, assets = []) {
        let suggestedName = (window.CURRENT_MODULE === 'water' ? 'แนวท่อเมนประปา' : 'แนวเส้นทางใหม่');
        if (assets.length >= 2) {
            suggestedName = (window.CURRENT_MODULE === 'water' ? `ท่อเมนเชื่อม ${assets[0]} - ${assets[assets.length - 1]}` : `แนวสายเชื่อม ${assets[0]} - ${assets[assets.length - 1]}`);
        }

        let pipeLevel = 'main';
        let pipeSize = '100mm (4 นิ้ว)';
        let pipeMaterial = 'HDPE';

        if (window.CURRENT_MODULE === 'water') {
            const levelPrompt = prompt("เลือกระดับท่อประปา:\n1 = ท่อเมนหลัก (Main Transmission)\n2 = ท่อจ่ายน้ำรอง/ท่อซอย (Distribution Sub)", "1");
            if (levelPrompt === "2") pipeLevel = 'sub';

            pipeSize = prompt("ระบุขนาดท่อ (เช่น 50mm, 100mm, 150mm, 2 นิ้ว, 4 นิ้ว):", (pipeLevel === 'main' ? '100mm (4 นิ้ว)' : '50mm (2 นิ้ว)'));
            pipeMaterial = prompt("ระบุชนิดท่อ (HDPE, PVC, เหล็กเหนียว, GI):", "HDPE");
        }

        const label = prompt('กรุณาระบุชื่อเรียกแนวท่อ / Label:', suggestedName);
        if (!label) return;

        const isMain = (pipeLevel === 'main');
        const lineColor = (window.CURRENT_MODULE === 'water' ? (isMain ? '#0284c7' : '#0ea5e9') : '#eab308');
        const lineWeight = (window.CURRENT_MODULE === 'water' ? (isMain ? 6 : 3.5) : 4);

        const payload = {
            module: window.CURRENT_MODULE,
            shape_type: 'line',
            label: label,
            color: lineColor,
            weight: lineWeight,
            coordinates: points,
            meta: {
                level: pipeLevel,
                size: pipeSize,
                material: pipeMaterial,
                station_id: selectedStationId || null,
                connected_assets: assets,
                total_points: points.length,
                length_m: calculatePolylineLength(points)
            }
        };

        fetch(`${window.API_BASE}/shape`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                loadGISData();
            }
        });
    }

    /* ======================================================== */
    /* ⬡ Area / Polygon (Zone) Drawing Workflow */
    /* ======================================================== */

    function toggleDrawZoneMode() {
        isDrawingZone = !isDrawingZone;
        const btn = document.getElementById('btnToggleDrawZone');
        const btnText = document.getElementById('drawZoneBtnText');
        const banner = document.getElementById('modeGuideBanner');
        const wrapper = document.getElementById('gisWrapper');

        if (isDrawingZone) {
            if (isDroppingPinMode) exitPinDropMode();
            if (isDrawingLine) toggleDrawLineMode();

            btn.classList.add('active-tool');
            btnText.textContent = 'เสร็จสิ้นพื้นที่';
            wrapper.classList.add('is-drawing-zone-mode');

            zonePoints = [];
            activeDrawingPolygon = L.polygon([], {
                color: '#10b981',
                fillColor: '#6ee7b7',
                fillOpacity: 0.35,
                weight: 3,
                dashArray: '4, 6'
            }).addTo(gisMap);

            document.getElementById('modeGuideText').innerHTML = '⬡ <b>วาดขอบเขตพื้นที่ (Polygon)</b>: คลิกตามมุมขอบเขตพื้นที่รอบๆ (ดับเบิ้ลคลิกเพื่อปิดและบันทึกพื้นที่)';
            banner.style.display = 'flex';
        } else {
            btn.classList.remove('active-tool');
            btnText.textContent = 'วาดพื้นที่ (Area)';
            wrapper.classList.remove('is-drawing-zone-mode');
            banner.style.display = 'none';

            if (zonePoints.length >= 3) {
                promptSaveZone(zonePoints);
            }
            if (activeDrawingPolygon) {
                gisMap.removeLayer(activeDrawingPolygon);
                activeDrawingPolygon = null;
            }
        }
    }

    function handleDrawZoneClick(latlng) {
        zonePoints.push([latlng.lat, latlng.lng]);
        if (activeDrawingPolygon) {
            activeDrawingPolygon.setLatLngs(zonePoints);
        }

        const areaSqm = calculatePolygonArea(zonePoints);
        document.getElementById('modeGuideText').innerHTML = `⬡ ขอบเขต: <b>${zonePoints.length} จุด</b> | ขนาดประมาณ: <b>${formatAreaThai(areaSqm)}</b> | ดับเบิ้ลคลิกหรือกด "เสร็จสิ้นพื้นที่"`;
    }

    function promptSaveZone(points) {
        const areaSqm = calculatePolygonArea(points);
        const areaFormatted = formatAreaThai(areaSqm);

        const label = prompt(`กรุณาระบุชื่อพื้นที่ (ขนาด ${areaFormatted}):`, 'เขตบริการจ่ายน้ำประปา');
        if (!label) return;

        const zoneType = prompt("ประเภทพื้นที่:\n1 = เขตบริการจ่ายน้ำ (Service Supply Zone)\n2 = สระพักน้ำดิบ/หนองน้ำ (Raw Water Reservoir)\n3 = บริเวณโรงผลิตน้ำ (Plant Compound)\n4 = พื้นที่แรงดันน้ำต่ำ/เฝ้าระวัง (Alert Zone)", "1");

        let color = '#0284c7';
        if (zoneType === '2') color = '#0d9488';
        else if (zoneType === '3') color = '#4338ca';
        else if (zoneType === '4') color = '#f59e0b';

        const payload = {
            module: window.CURRENT_MODULE,
            shape_type: 'zone',
            label: label,
            color: color,
            weight: 3,
            coordinates: points,
            meta: {
                zone_type: zoneType,
                area_sqm: areaSqm,
                area_formatted: areaFormatted,
                station_id: selectedStationId || null
            }
        };

        fetch(`${window.API_BASE}/shape`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                loadGISData();
            }
        });
    }

    /* ======================================================== */
    /* 📐 Area & Length Calculation Helpers */
    /* ======================================================== */

    function calculatePolylineLength(latLngs) {
        if (!latLngs || latLngs.length < 2) return 0;
        let total = 0;
        for (let i = 0; i < latLngs.length - 1; i++) {
            total += L.latLng(latLngs[i]).distanceTo(L.latLng(latLngs[i+1]));
        }
        return Math.round(total);
    }

    function calculatePolygonArea(latLngs) {
        if (!latLngs || latLngs.length < 3) return 0;
        const radius = 6378137;
        let total = 0;
        const len = latLngs.length;
        for (let i = 0; i < len; i++) {
            const p1 = latLngs[i];
            const p2 = latLngs[(i + 1) % len];
            const lat1 = (p1[0] || p1.lat) * Math.PI / 180;
            const lat2 = (p2[0] || p2.lat) * Math.PI / 180;
            const lng1 = (p1[1] || p1.lng) * Math.PI / 180;
            const lng2 = (p2[1] || p2.lng) * Math.PI / 180;
            total += (lng2 - lng1) * (2 + Math.sin(lat1) + Math.sin(lat2));
        }
        return Math.round(Math.abs(total * radius * radius / 2.0));
    }

    function formatAreaThai(sqm) {
        const rai = Math.floor(sqm / 1600);
        const remRai = sqm % 1600;
        const ngan = Math.floor(remRai / 400);
        const sqWah = Math.round((remRai % 400) / 4);
        let str = `${sqm.toLocaleString()} ตร.ม.`;
        if (rai > 0 || ngan > 0 || sqWah > 0) {
            str += ` (${rai} ไร่ ${ngan} งาน ${sqWah} ตร.ว.)`;
        }
        return str;
    }

    /* ======================================================== */
    /* Render Lines & Zones */
    /* ======================================================== */

    function renderMapShapes() {
        shapesLayer.clearLayers();
        const lines = allShapes.lines || [];
        lines.forEach(shape => {
            if (shape.coordinates && Array.isArray(shape.coordinates) && shape.coordinates.length > 1) {
                if (window.CURRENT_MODULE === 'water' && selectedStationId && shape.meta && shape.meta.station_id) {
                    if (String(shape.meta.station_id) !== String(selectedStationId)) return;
                }

                const color = shape.color || (window.CURRENT_MODULE === 'water' ? '#0284c7' : '#eab308');
                const poly = L.polyline(shape.coordinates, {
                    color: color,
                    weight: shape.weight || 4,
                    opacity: 0.88
                });

                const pipeLengthM = calculatePolylineLength(shape.coordinates);
                const sizeText = shape.meta && shape.meta.size ? ` (${shape.meta.size})` : '';

                poly.bindTooltip(`<b>${shape.label || 'แนวท่อ'}</b>${sizeText}`, {
                    sticky: true,
                    className: 'pipe-label-tooltip'
                });

                poly.bindPopup(`
                    <div class="gis-popup">
                        <h6>${shape.label || 'แนวเส้นทาง'}</h6>
                        <p class="mb-1">รหัส: <b>${shape.shape_code || '-'}</b></p>
                        <p class="mb-1">ความยาว: <b>${pipeLengthM.toLocaleString()} เมตร</b></p>
                        ${shape.meta && shape.meta.size ? `<p class="mb-1">ขนาด: <b>${shape.meta.size}</b> | วัสดุ: <b>${shape.meta.material || '-'}</b></p>` : ''}
                        <button class="btn btn-xs btn-danger mt-1" onclick="deleteShape(${shape.id})">ลบเส้นนี้</button>
                    </div>
                `);
                shapesLayer.addLayer(poly);
            }
        });
    }

    function renderMapZones() {
        zonesLayer.clearLayers();
        const zones = allShapes.zones || [];
        zones.forEach(zone => {
            if (zone.coordinates && Array.isArray(zone.coordinates) && zone.coordinates.length >= 3) {
                if (window.CURRENT_MODULE === 'water' && selectedStationId && zone.meta && zone.meta.station_id) {
                    if (String(zone.meta.station_id) !== String(selectedStationId)) return;
                }

                const color = zone.color || '#0284c7';
                const poly = L.polygon(zone.coordinates, {
                    color: color,
                    fillColor: color,
                    fillOpacity: 0.25,
                    weight: zone.weight || 2.5
                });

                const areaFormatted = zone.meta && zone.meta.area_formatted ? zone.meta.area_formatted : formatAreaThai(calculatePolygonArea(zone.coordinates));

                poly.bindTooltip(`⬡ <b>${zone.label}</b><br><small>${areaFormatted}</small>`, {
                    permanent: false,
                    className: 'zone-label-tooltip'
                });

                poly.bindPopup(`
                    <div class="gis-popup">
                        <span class="badge bg-success text-white mb-1">พื้นที่ / ขอบเขต (Zone)</span>
                        <h6>⬡ ${zone.label}</h6>
                        <p class="mb-1">ขนาดพื้นที่: <b>${areaFormatted}</b></p>
                        <button class="btn btn-xs btn-danger mt-1" onclick="deleteShape(${zone.id})">ลบพื้นที่นี้</button>
                    </div>
                `);

                zonesLayer.addLayer(poly);
            }
        });
        document.getElementById('zoneCountBadge').textContent = zones.length;
    }

    /* ======================================================== */
    /* 🏭 Water Station Filter Controls */
    /* ======================================================== */

    function filterByStation(stId) {
        selectedStationId = stId || "";
        const sel = document.getElementById('waterStationFilter');
        if (sel) sel.value = selectedStationId;

        if (selectedStationId) {
            const opt = sel.options[sel.selectedIndex];
            const lat = parseFloat(opt.getAttribute('data-lat'));
            const lng = parseFloat(opt.getAttribute('data-lng'));
            if (lat && lng) {
                gisMap.flyTo([lat, lng], 16);
            }
        }

        renderMapItems();
        renderMapShapes();
        renderMapZones();
        loadGISData(); // Re-render water system
    }

    window.toggleWaterSubLayer = function(layerName, isChecked) {
        if (layerName === 'stations') {
            isChecked ? gisMap.addLayer(waterStationsLayer) : gisMap.removeLayer(waterStationsLayer);
        } else if (layerName === 'nodes') {
            isChecked ? gisMap.addLayer(waterPipeNodesLayer) : gisMap.removeLayer(waterPipeNodesLayer);
        } else if (layerName === 'main_pipes') {
            isChecked ? gisMap.addLayer(waterMainPipesLayer) : gisMap.removeLayer(waterMainPipesLayer);
        } else if (layerName === 'sub_pipes') {
            isChecked ? gisMap.addLayer(waterSubPipesLayer) : gisMap.removeLayer(waterSubPipesLayer);
        } else if (layerName === 'meters') {
            isChecked ? gisMap.addLayer(itemsLayer) : gisMap.removeLayer(itemsLayer);
        } else if (layerName === 'zones') {
            isChecked ? gisMap.addLayer(zonesLayer) : gisMap.removeLayer(zonesLayer);
        }
    };

    /* ======================================================== */
    /* Drawers, Search & Standard Setup */
    /* ======================================================== */

    function renderDrawerList() {
        const container = document.getElementById('drawerAssetItems');
        const villageSelect = document.getElementById('drawerVillageFilter');
        if (!container) return;

        const villages = [...new Set(allItems.map(it => it.village).filter(Boolean))];
        villageSelect.innerHTML = '<option value="">-- ทุกหมู่บ้าน --</option>' + villages.map(v => `<option value="${v}">${v}</option>`).join('');

        filterDrawerItems();
    }

    function filterDrawerItems() {
        const container = document.getElementById('drawerAssetItems');
        const term = (document.getElementById('drawerSearchInput').value || '').toLowerCase();
        const selVillage = document.getElementById('drawerVillageFilter').value;
        const selStatus = document.getElementById('drawerStatusFilter').value;

        const filtered = allItems.filter(item => {
            const matchTerm = !term || (item.item_code || '').toLowerCase().includes(term) || (item.name || '').toLowerCase().includes(term);
            const matchVillage = !selVillage || item.village === selVillage;
            const matchStatus = !selStatus || item.status === selStatus;
            return matchTerm && matchVillage && matchStatus;
        });

        if (filtered.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-muted">ไม่พบข้อมูลตรงตามเงื่อนไข</div>';
            return;
        }

        container.innerHTML = filtered.map(item => `
            <div class="card shadow-none border mb-1">
                <div class="card-body p-2 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge ${item.status === 'bad' ? 'bg-danger' : (item.status === 'wait' ? 'bg-warning' : 'bg-success')} p-1" style="font-size:8px;">●</span>
                            <strong class="text-xs text-dark">${item.item_code}</strong>
                        </div>
                        <p class="mb-0 text-xs text-muted">${item.name || item.item_type || 'ไม่มีชื่อระบุ'}</p>
                        <small class="text-muted" style="font-size:10px;"><i class="fas fa-map-marker-alt"></i> ${item.village || '-'}</small>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-xs btn-outline-primary mb-0 px-2 py-1" onclick="flyToAsset(${item.lat}, ${item.lng})">
                            <i class="fas fa-crosshairs"></i>
                        </button>
                        <button class="btn btn-xs btn-outline-warning mb-0 px-2 py-1" onclick="enableExistingMarkerMove(${item.id})">
                            <i class="fas fa-arrows-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function renderTicketDrawer() {
        const container = document.getElementById('drawerTicketItems');
        if (!container) return;

        if (allTickets.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-muted">ไม่มีรายการแจ้งซ่อม</div>';
            return;
        }

        const statusLabels = {
            open: '<span class="badge bg-danger">รอรับเรื่อง</span>',
            in_progress: '<span class="badge bg-warning">กำลังซ่อม</span>',
            resolved: '<span class="badge bg-success">ซ่อมเสร็จแล้ว</span>',
            cancelled: '<span class="badge bg-secondary">ยกเลิก</span>'
        };

        container.innerHTML = allTickets.map(tk => `
            <div class="card shadow-none border mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-xs text-dark"><i class="fas fa-tag text-warning me-1"></i> ${tk.ticket_code}</strong>
                        ${statusLabels[tk.status] || tk.status}
                    </div>
                    <p class="text-xs text-dark mb-1 font-weight-bold">${tk.detail}</p>
                    <p class="text-xs text-muted mb-1"><i class="fas fa-map-marker-alt"></i> ${tk.location || tk.village || 'ไม่ระบุสถานที่'}</p>
                    <div class="d-flex justify-content-between align-items-center pt-1 border-top">
                        <small class="text-muted" style="font-size:10px;">${tk.created_at ? new Date(tk.created_at).toLocaleDateString('th-TH') : ''}</small>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-dark dropdown-toggle mb-0 py-1" data-bs-toggle="dropdown">
                                อัปเดตสถานะ
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end text-xs">
                                <li><a class="dropdown-item" href="javascript:;" onclick="updateTicketStatus('${tk.ticket_code}', 'open')">🔴 รอรับเรื่อง</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="updateTicketStatus('${tk.ticket_code}', 'in_progress')">🟡 กำลังดำเนินการ</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="updateTicketStatus('${tk.ticket_code}', 'resolved')">🟢 ซ่อมเสร็จเรียบร้อย</a></li>
                                <li><a class="dropdown-item text-danger" href="javascript:;" onclick="updateTicketStatus('${tk.ticket_code}', 'cancelled')">⚪ ยกเลิก</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function updateKPISummary() {
        const total = allItems.length;
        const good = allItems.filter(i => i.status === 'good').length;
        const bad = allItems.filter(i => i.status === 'bad').length;
        const pending = allTickets.filter(t => t.status === 'open' || t.status === 'in_progress').length;

        document.getElementById('kpiTotalItems').innerHTML = `${total} <small class="text-xs text-muted">จุด</small>`;
        document.getElementById('kpiGoodItems').innerHTML = `${good} <small class="text-xs text-muted">จุด</small>`;
        document.getElementById('kpiBadItems').innerHTML = `${bad} <small class="text-xs text-muted">จุด</small>`;
        document.getElementById('kpiPendingTickets').innerHTML = `${pending} <small class="text-xs text-muted">งาน</small>`;
        document.getElementById('assetCountBadge').textContent = total;
        document.getElementById('lineCountBadge').textContent = (allShapes.lines || []).length;
    }

    function setupEventListeners() {
        document.getElementById('btnTogglePinDrop').addEventListener('click', toggleDropPinMode);
        document.getElementById('btnToggleDrawLine').addEventListener('click', toggleDrawLineMode);
        document.getElementById('btnToggleDrawZone').addEventListener('click', toggleDrawZoneMode);

        // Water Station Filter
        const stFilter = document.getElementById('waterStationFilter');
        if (stFilter) {
            stFilter.addEventListener('change', function () {
                filterByStation(this.value);
            });
        }

        document.getElementById('btnCancelMode').addEventListener('click', function () {
            exitPinDropMode();
            if (isDrawingLine) toggleDrawLineMode();
            if (isDrawingZone) toggleDrawZoneMode();
            if (movingMarkerId) cancelExistingMarkerMove(movingMarkerId);
        });

        // Tile Switcher
        document.getElementById('btnTileStreet').addEventListener('click', function () {
            this.classList.add('active');
            document.getElementById('btnTileSat').classList.remove('active');
            gisMap.removeLayer(satelliteTile);
            gisMap.addLayer(streetTile);
        });

        document.getElementById('btnTileSat').addEventListener('click', function () {
            this.classList.add('active');
            document.getElementById('btnTileStreet').classList.remove('active');
            gisMap.removeLayer(streetTile);
            gisMap.addLayer(satelliteTile);
        });

        // GPS My Location
        document.getElementById('btnMyLoc').addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('เบราว์เซอร์ไม่รองรับ GPS');
                return;
            }
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                gisMap.flyTo([lat, lng], 17);
                L.circleMarker([lat, lng], { radius: 8, color: '#3b82f6', fillColor: '#60a5fa', fillOpacity: 0.8 }).addTo(gisMap)
                    .bindPopup('คุณอยู่ที่นี่ (ความแม่นยำ: ' + Math.round(pos.coords.accuracy) + 'm)').openPopup();
            }, err => {
                alert('ไม่สามารถระบุพิกัดได้: ' + err.message);
            });
        });

        // Search Input in map
        document.getElementById('mapSearchInput').addEventListener('input', function (e) {
            const term = (e.target.value || '').toLowerCase().trim();
            if (!term) return;

            const match = allItems.find(i => (i.item_code || '').toLowerCase().includes(term) || (i.name || '').toLowerCase().includes(term));
            if (match && match.lat && match.lng) {
                gisMap.flyTo([match.lat, match.lng], 18);
            }
        });

        // Fullscreen Toggle
        document.getElementById('btnToggleFullscreen').addEventListener('click', function () {
            const wrapper = document.getElementById('gisWrapper');
            wrapper.classList.toggle('is-fullscreen');
            gisMap.invalidateSize();
        });

        // Ticket Save Form
        document.getElementById('formTicketSave').addEventListener('submit', function (e) {
            e.preventDefault();
            const payload = {
                module: window.CURRENT_MODULE,
                ref_code: document.getElementById('ticketRefCode').value,
                village: document.getElementById('ticketVillage').value,
                location: document.getElementById('ticketLocation').value,
                detail: document.getElementById('ticketDetail').value,
                lat: parseFloat(document.getElementById('ticketLat').value) || null,
                lng: parseFloat(document.getElementById('ticketLng').value) || null,
            };

            fetch(`${window.API_BASE}/ticket`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('modalCreateTicket')).hide();
                    loadGISData();
                    alert('เปิดใบสั่งซ่อมเรียบร้อย รหัส: ' + data.ticket.ticket_code);
                }
            });
        });

        // Config Save Form
        document.getElementById('formConfigSave').addEventListener('submit', function (e) {
            e.preventDefault();
            const payload = {
                org_name: document.getElementById('cfgOrgName').value,
                area_name: document.getElementById('cfgAreaName').value,
                center_lat: parseFloat(document.getElementById('cfgCenterLat').value),
                center_lng: parseFloat(document.getElementById('cfgCenterLng').value),
            };

            fetch(`${window.API_BASE}/config`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.CSRF_TOKEN
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('modalConfig')).hide();
                    alert('บันทึกการตั้งค่าเรียบร้อย');
                    location.reload();
                }
            });
        });

        // Drawer Search & Filters
        document.getElementById('drawerSearchInput').addEventListener('input', filterDrawerItems);
        document.getElementById('drawerVillageFilter').addEventListener('change', filterDrawerItems);
        document.getElementById('drawerStatusFilter').addEventListener('change', filterDrawerItems);

        // Export JSON
        document.getElementById('btnExportJSON').addEventListener('click', function () {
            fetch(`${window.API_BASE}/init`)
                .then(res => res.json())
                .then(data => {
                    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `smart_gis_backup_${window.CURRENT_MODULE}_${new Date().toISOString().slice(0,10)}.json`;
                    a.click();
                    URL.revokeObjectURL(url);
                });
        });

        // Cloud Sync button
        document.getElementById('btnSyncCloud').addEventListener('click', function () {
            loadGISData();
            alert('ซิงค์ข้อมูลล่าสุดกับระบบฐานข้อมูลเรียบร้อย');
        });
    }

    window.flyToAsset = function (lat, lng) {
        gisMap.flyTo([lat, lng], 18);
        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasAssetList'));
        if (offcanvas) offcanvas.hide();
    };

    window.deleteAssetItem = function (id) {
        if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?')) return;

        fetch(`${window.API_BASE}/item/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN }
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) loadGISData();
        });
    };

    window.deleteShape = function (id) {
        if (!confirm('ยืนยันลบรายการนี้?')) return;

        fetch(`${window.API_BASE}/shape/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN }
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) loadGISData();
        });
    };

    window.openReportModalFor = function (refCode, village, lat, lng) {
        document.getElementById('ticketRefCode').value = refCode;
        document.getElementById('ticketVillage').value = village;
        document.getElementById('ticketLat').value = lat;
        document.getElementById('ticketLng').value = lng;
        document.getElementById('ticketDetail').value = 'แจ้งชำรุดจุด ' + refCode;
        new bootstrap.Modal(document.getElementById('modalCreateTicket')).show();
    };

    window.updateTicketStatus = function (code, status) {
        fetch(`${window.API_BASE}/ticket/${code}/staff-update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF_TOKEN
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) loadGISData();
        });
    };
</script>
@endsection
