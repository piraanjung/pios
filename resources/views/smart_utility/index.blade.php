<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#1e293b">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $config->org_name ?? 'ระบบบริหารจัดการไฟฟ้าสาธารณะและประปาอัจฉริยะ' }} — Smart Utility GIS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<!-- Fonts (ADR-0009 Kanit) -->
@include('layouts.partials.head-fonts')
<link href="{{ asset('css/tokens.css') }}" rel="stylesheet" />
<style>
:root{
  --bg:#f1f5f9;--card:#ffffff;--ink:#0f172a;--mute:#64748b;--line:#cbd5e1;
  --elec:#eab308;--elec-dark:#ca8a04;
  --water:#0284c7;--water-dark:#0369a1;
  --bad:#ef4444;--ok:#10b981;--map:#141d38;
}
*{box-sizing:border-box}
html{width:100%;height:100%;}
body{margin:0;width:100%;min-height:100%;background:var(--bg);color:var(--ink);font:400 15px/1.5 'Kanit', sans-serif;overflow-x:hidden}
main{max-width:1280px;margin:0 auto;padding:16px 16px 40px}

/* Header & Brand */
.app-header{background:#1e293b;color:#fff;padding:12px 20px;border-radius:12px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
.brand-title{font-size:18px;font-weight:700;display:flex;align-items:center;gap:8px}
.brand-sub{font-size:12px;color:#94a3b8}
.brand-org{font-size:15px;font-weight:700;color:#fde68a;margin:2px 0}
.print-head{display:none;font-size:16pt;font-weight:700;margin-bottom:6px}
@media print{.print-head{display:block!important}}
.cfg-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:8px}

/* Top System Toolbar */
.sys-bar{background:#ffffff;border:1px solid var(--line);border-radius:10px;padding:10px 14px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
.sys-group{display:flex;align-items:center;gap:6px;flex-wrap:wrap}

/* 2 งานหลัก */
.main-tabs{display:flex;gap:8px;margin-bottom:14px}
.tab-main-btn{flex:1;padding:12px 16px;border:2px solid transparent;background:#e2e8f0;color:#334155;border-radius:10px;font-size:16px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s}
.tab-main-btn.active-elec{background:#fef08a;color:#854d0e;border-color:var(--elec)}
.tab-main-btn.active-water{background:#bae6fd;color:#075985;border-color:var(--water)}
.tab-main-btn.active-road{background:#fde68a;color:#92400e;border-color:#d97706}
.tab-main-btn.active-bldg{background:#ddd6fe;color:#5b21b6;border-color:#7c3aed}
.tab-main-btn.active-report{background:#e0e7ff;color:#3730a3;border-color:#6366f1}

/* Mode Switch */
.sub-bar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:14px}
.modes{display:flex;border:1px solid var(--line);border-radius:8px;overflow:hidden;background:#fff}
.modes button{border:0;padding:8px 16px;background:transparent;cursor:pointer;font-weight:600;font-size:13.5px;color:var(--mute)}
.modes button.on{background:#0f172a;color:#fff}

.grid{display:grid;grid-template-columns:1.55fr 1fr;gap:14px}
.card{background:var(--card);border:1px solid var(--line);border-radius:10px;padding:14px}
.stack{display:grid;gap:14px;align-content:start}
.kpis{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
.kpi b{display:block;font-size:24px;line-height:1.1}
.kpi span{color:var(--mute);font-size:12px}

/* Search Bar */
.search-bar{display:flex;gap:6px;margin-bottom:10px;align-items:center}
.search-wrap{flex:1;position:relative}
.search-wrap input{width:100%;padding:8px 12px;border:1px solid var(--line);border-radius:6px;font:inherit}
.btn-search{background:#1e293b;color:#fff;border:0;padding:8px 14px;border-radius:6px;cursor:pointer;font-weight:600;display:flex;align-items:center;gap:4px}
.btn-myloc{background:#fff;border:1px solid var(--line);color:#1e293b;padding:8px 12px;border-radius:6px;cursor:pointer;font-weight:600;display:flex;align-items:center;gap:4px}

/* Floating Toolbar */
.map-wrap{position:relative}
.map-canvas{width:100%;height:600px;background:var(--map);border-radius:8px;z-index:0}
.floating-toolbar{
  position:absolute;top:12px;right:12px;z-index:1000;
  background:#1e293b;padding:8px;border-radius:10px;
  display:flex;flex-direction:column;gap:6px;
  box-shadow:0 6px 18px rgba(0,0,0,0.35);border:1px solid #334155;
  width:165px;max-height:calc(100% - 24px);overflow-y:auto;
}
.tb-btn{
  background:#ffffff;color:#1e293b;border:1px solid #cbd5e1;
  padding:7px 10px;font-size:13px;border-radius:6px;
  text-align:left;cursor:pointer;font-weight:600;
  display:flex;align-items:center;gap:6px;transition:all .15s;
}
.tb-btn:hover{background:#f1f5f9;transform:translateX(-1px)}
.tb-btn.btn-save{background:#10b981;color:#fff;border-color:#059669}
.tb-btn.btn-save:hover{background:#059669}
.tb-btn.btn-print{background:#0284c7;color:#fff;border-color:#0369a1}
.tb-btn.btn-print:hover{background:#0369a1}
.tb-btn.btn-action{background:#f59e0b;color:#fff;border-color:#d97706}

.legend{display:flex;flex-wrap:wrap;gap:10px;font-size:12px;color:var(--mute);margin-top:8px}
.legend i{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px;vertical-align:middle}

.form-input{width:100%;padding:6px 9px;border:1px solid var(--line);border-radius:6px;font:inherit;margin-top:3px;margin-bottom:8px}
.form-label{font-size:12.5px;font-weight:600;color:var(--mute)}
.note{font-size:12.5px;color:var(--mute);margin-top:6px}
.pill{display:inline-block;padding:2px 8px;border-radius:12px;font-size:11px;color:#fff}
.btn-sm{padding:6px 10px;font-size:12.5px;border-radius:6px;border:1px solid var(--line);cursor:pointer;font-weight:600}
.btn-primary{background:#0f172a;color:#fff;border-color:#0f172a}
.btn-outline{background:#fff;color:#0f172a}
.btn-file{position:relative;overflow:hidden;display:inline-block}
.btn-file input{position:absolute;top:0;right:0;margin:0;opacity:0;font-size:100px;direction:ltr;cursor:pointer}

/* Tickets & Reports */
.tk-card{border:1px solid var(--line);border-left:6px solid #94a3b8;border-radius:8px;padding:9px 11px;margin-bottom:8px;background:#fff;font-size:13px}
.tk-head{display:flex;justify-content:space-between;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:3px}
.tk-card .meta{color:var(--mute);font-size:12px}
.tk-ctrl{display:grid;gap:6px;margin-top:8px;padding-top:8px;border-top:1px dashed var(--line)}
.tk-ctrl select,.tk-ctrl input{padding:5px 8px;border:1px solid var(--line);border-radius:6px;font:inherit;width:100%}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin:0 0 10px}
.chip{border:1px solid var(--line);background:#fff;border-radius:16px;padding:3px 10px;font-size:12.5px;cursor:pointer;font-weight:600;color:#334155}
.chip.on{background:#0f172a;color:#fff;border-color:#0f172a}
.tk-ok{background:#d1fae5;border:1px solid #10b981;color:#065f46;border-radius:8px;padding:9px 12px;margin-top:10px;font-size:14px}
.tk-ok b{font-size:17px;letter-spacing:.5px}
.hp{position:absolute;left:-9999px;opacity:0;height:0;width:0}
.sync-note{font-size:12px;font-weight:600}
.pick{background:#f1f5f9;border:2px dashed #94a3b8;border-radius:8px;padding:10px 12px;margin-bottom:10px;font-size:14px}
.pick.sel{background:#fef9c3;border:2px solid #eab308}
.pick.sel.w{background:#e0f2fe;border-color:#0284c7}
.bub{border-radius:8px;padding:5px 9px;margin-top:5px;font-size:13px;max-width:92%}
.bub small{display:block;color:var(--mute);font-size:11px}
.bub.staff{margin-right:auto}
.bub.citizen{margin-left:auto;background:#f1f5f9;border:1px solid var(--line)}
.bub.sys{margin:5px auto 0;background:none;color:var(--mute);font-size:11.5px;text-align:center}
.tk-ctrl .row{display:flex;gap:6px}
.tk-ctrl .row button{white-space:nowrap}
body.mode-citizen .sys-bar{display:none!important}
body.mode-citizen .staff-only{display:none!important}
body.mode-staff .citizen-only{display:none!important}
.work-module{display:none}
.work-module.active{display:block}

/* Gate */
#gate{position:fixed;inset:0;z-index:99999;background:linear-gradient(135deg,#0f172a,#1e3a8a);display:flex;align-items:center;justify-content:center;padding:16px}
#gate[hidden]{display:none}
.gate-box{background:#fff;border-radius:16px;padding:26px 22px;width:100%;max-width:400px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,.4)}
.gate-box h1{font-size:19px;margin:8px 0 2px}
.gate-org{color:var(--mute);font-size:13px;margin-bottom:18px}
.gate-btn{display:block;width:100%;border:0;border-radius:12px;padding:14px 12px;font:600 17px/1.3 inherit;font-family:inherit;cursor:pointer;margin-bottom:10px;color:#fff}
.gate-btn small{display:block;font-weight:400;font-size:12px;opacity:.9;margin-top:3px}
.gate-btn.cit{background:#0284c7}
.gate-btn.stf{background:#0f172a}
.gate-btn.small{padding:10px;font-size:15px;margin:0;background:#e2e8f0;color:#0f172a}
.gate-btn.small.stf{background:#0f172a;color:#fff}
#gatePass{width:100%;padding:11px 12px;border:2px solid var(--line);border-radius:10px;font:inherit;font-size:17px;text-align:center;letter-spacing:3px}
.gate-edit-link{background:none;border:0;color:#0284c7;font-size:12px;cursor:pointer;margin-bottom:14px;text-decoration:underline;font-family:inherit}
.gate-edit{display:flex;gap:6px;margin:0 0 14px}
.gate-edit input{flex:1;min-width:0;padding:8px 10px;border:1px solid #cbd5e1;border-radius:8px;font-size:14px;font-family:inherit}
.gate-edit button{padding:8px 12px;border:0;border-radius:8px;background:#10b981;color:#fff;font-weight:600;cursor:pointer;font-family:inherit}
.gate-err{color:var(--bad);font-size:13px;min-height:18px;margin-top:6px}

/* Report module */
.report-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:14px}
.report-kpi{background:#fff;border:1px solid var(--line);border-radius:10px;padding:12px;text-align:center}
.report-kpi b{display:block;font-size:26px;line-height:1.1}
.report-kpi span{color:var(--mute);font-size:12px}
.report-table-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;margin-top:10px}
.report-table{width:100%;border-collapse:collapse;font-size:13px;min-width:640px}
.report-table th,.report-table td{border:1px solid var(--line);padding:8px 10px;text-align:left;vertical-align:top}
.report-table th{background:#1e293b;color:#fff;font-weight:600;white-space:nowrap}
.report-table tr:nth-child(even){background:#f8fafc}
.report-print-only{display:none}
.report-actions{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;align-items:center}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:10px;margin-bottom:14px}
.stat-card{background:#fff;border:1px solid var(--line);border-radius:10px;padding:12px;text-align:center}
.stat-card b{display:block;font-size:22px;line-height:1.15}
.stat-card span{color:var(--mute);font-size:12px}
.hotspot-table{width:100%;border-collapse:collapse;font-size:13px;margin-top:8px}
.hotspot-table th,.hotspot-table td{border:1px solid var(--line);padding:7px 9px;text-align:left}
.hotspot-table th{background:#1e293b;color:#fff;font-weight:600}
.hotspot-table tr:nth-child(even){background:#f8fafc}
.cost-tag{display:inline-block;background:#ecfdf5;color:#065f46;border:1px solid #10b981;border-radius:6px;padding:1px 7px;font-size:12px;font-weight:600}

/* Photo */
.photo-row{display:flex;flex-wrap:wrap;gap:8px;margin:8px 0;align-items:flex-start}
.photo-thumb{width:72px;height:72px;object-fit:cover;border-radius:8px;border:1px solid var(--line);cursor:pointer;background:#f1f5f9}
.photo-preview{display:flex;flex-wrap:wrap;gap:6px;min-height:0}
.photo-preview .pv{position:relative;width:80px;height:80px}
.photo-preview .pv img{width:100%;height:100%;object-fit:cover;border-radius:8px;border:1px solid var(--line)}
.photo-preview .pv button{position:absolute;top:-6px;right:-6px;width:22px;height:22px;border-radius:50%;border:0;background:#ef4444;color:#fff;font-size:12px;cursor:pointer;line-height:1;font-weight:700}
.photo-lightbox{position:fixed;inset:0;z-index:100001;background:rgba(0,0,0,.85);display:flex;align-items:center;justify-content:center;padding:16px}
.photo-lightbox[hidden]{display:none}
.photo-lightbox img{max-width:100%;max-height:90vh;border-radius:8px}
.photo-lightbox button{position:absolute;top:16px;right:16px;background:#fff;border:0;border-radius:8px;padding:8px 14px;font-weight:700;cursor:pointer}

/* Draw Modal & Help Modal */
#drawModal,#helpModal{position:fixed;inset:0;z-index:100000;background:rgba(15,23,42,.6);display:flex;align-items:center;justify-content:center;padding:12px}
#drawModal[hidden],#helpModal[hidden]{display:none}
.dm-box,.help-box{background:#fff;border-radius:12px;max-width:760px;width:100%;max-height:90vh;display:flex;flex-direction:column;overflow:hidden}
.help-box{max-width:820px}
.dm-head,.help-head{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;background:#1e293b;color:#fff}
.dm-head h2,.help-head h2{margin:0;font-size:16px}
.help-close{background:#334155;color:#fff;border:0;width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:18px;font-weight:700}
.help-close:hover{background:#ef4444}
.dm-body,.help-body{padding:14px 16px;overflow:auto;font-size:13.5px}
.dm-t{width:100%;border-collapse:collapse}
.dm-t th,.dm-t td{border-bottom:1px solid #e2e8f0;padding:6px;text-align:left}
.dm-t button{border:1px solid #cbd5e1;background:#fff;border-radius:5px;cursor:pointer;padding:2px 7px;margin-right:3px}
.dm-t button.del{color:#dc2626;border-color:#fca5a5}
.dm-foot{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}
.dm-foot button{border:1px solid #fca5a5;color:#dc2626;background:#fff;border-radius:6px;padding:6px 10px;cursor:pointer;font-weight:600}
.help-btn{background:#6366f1;color:#fff;border:0;padding:6px 14px;border-radius:6px;cursor:pointer;font-weight:600;font-size:13px}
.ln-label{background:rgba(15,23,42,.85)!important;color:#fff!important;border:1px solid #94a3b8!important;border-radius:3px!important;padding:1px 5px!important;font-size:11px!important;font-weight:700!important}

/* System Live Water Meter Badge */
.sys-meter-badge{background:#e0f2fe;border:1px solid #0284c7;color:#0369a1;border-radius:4px;padding:2px 6px;font-size:11px;font-weight:700;display:inline-block}
.sys-meter-owe{background:#fee2e2;border:1px solid #ef4444;color:#991b1b;border-radius:4px;padding:2px 6px;font-size:11px;font-weight:700;display:inline-block}

/* Responsive & Print */
@media (max-width:900px){
  .grid{grid-template-columns:1fr}
  .map-canvas{height:min(440px,55vh)}
  .sys-bar,.sub-bar{flex-direction:column;align-items:stretch}
}
@media (max-width:560px){
  .map-canvas{height:min(360px,48vh)}
  .floating-toolbar{top:auto;bottom:10px;right:8px;left:8px;width:auto;flex-direction:row;flex-wrap:wrap;justify-content:center}
  .tb-btn{flex:1 1 calc(33% - 6px);min-width:90px;text-align:center;justify-content:center;font-size:11px}
}
@media print{
  .no-print,.app-header,.sys-bar,.main-tabs,.sub-bar,.floating-toolbar,.search-bar,.modes,.row-tools,#gate{display:none!important}
  main{max-width:none!important;margin:0!important;padding:0!important}
  .grid{display:block!important}
  .card{border:none!important;padding:0!important}
  .map-canvas{height:780px!important;border:1px solid #000;page-break-after:always}
  .print-head,.report-print-only{display:block!important}
  body.print-report .map-canvas{display:none!important}
}
</style>
</head>
<body class="mode-citizen">

<!-- ===== หน้าจอเลือกสถานะผู้ใช้งาน ===== -->
<div id="gate" role="dialog" aria-modal="true">
  <div class="gate-box">
    <div style="font-size:42px;line-height:1">⚙️</div>
    <h1>ระบบบริหารจัดการสาธารณูปโภค</h1>
    <div class="gate-org" id="gateOrg">{{ $config->org_name ?? 'อบต.เสม็ดใต้' }}</div>
    <button type="button" class="gate-edit-link" id="gateEditLink" onclick="gateEditOrg()">✏️ เปลี่ยนชื่อหน่วยงาน</button>
    <div class="gate-edit" id="gateEditBox" hidden>
      <input type="text" id="gateOrgInput" maxlength="80" placeholder="ชื่อหน่วยงาน" onkeydown="if(event.key==='Enter')gateSaveOrg()">
      <button type="button" onclick="gateSaveOrg()">บันทึก</button>
    </div>
    <div id="gateStep1">
      <p style="margin:0 0 14px;font-weight:600">คุณเป็นใคร?</p>
      <button type="button" class="gate-btn cit" onclick="gateCitizen()">👤 ประชาชน<small>แจ้งเหตุไฟฟ้า/ประปา/ถนน/อาคาร และติดตามเรื่อง</small></button>
      <button type="button" class="gate-btn stf" onclick="gateStaffStep()">🔧 เจ้าหน้าที่ / กองช่าง<small>จัดการแผนที่ เปลี่ยนสถานะ ตอบกลับประชาชน</small></button>
    </div>
    <div id="gateStep2" hidden>
      <p style="margin:0 0 10px;font-weight:600">🔐 เจ้าหน้าที่: ใส่รหัสผ่าน</p>
      <input type="password" id="gatePass" inputmode="numeric" autocomplete="off" placeholder="รหัสผ่านเจ้าหน้าที่" onkeydown="if(event.key==='Enter')gateLogin()">
      <div id="gateErr" class="gate-err"></div>
      <div style="display:flex;gap:8px;margin-top:10px">
        <button type="button" class="gate-btn small" onclick="gateBack()">← กลับ</button>
        <button type="button" class="gate-btn small stf" onclick="gateLogin()">เข้าสู่ระบบ</button>
      </div>
    </div>
  </div>
</div>

<header class="app-header no-print">
  <div>
    <div class="brand-title">⚙️ ระบบบริหารจัดการสาธารณูปโภคอัจฉริยะ (Smart Utility GIS)</div>
    <div class="brand-org" id="brandOrg">🏛️ {{ $config->org_name }} @if(!empty($config->area_name)) · {{ $config->area_name }} @endif</div>
    <div class="brand-sub">เชื่อมโยงระบบฐานข้อมูล MySQL & โครงข่ายประปา (Tabwater) | ONGART’S FUTURO CONCEPT</div>
  </div>
  <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
    @if(Auth::check())
      <a href="{{ route('dashboard') }}" style="background:#334155;color:#fff;border:0;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:600;font-size:13px">🏠 แดชบอร์ด</a>
    @endif
    <button type="button" class="help-btn" onclick="openHelp()">📖 คู่มือการใช้งาน</button>
    <button onclick="window.print()" style="background:#10b981;color:#fff;border:0;padding:6px 14px;border-radius:6px;cursor:pointer;font-weight:600;font-family:inherit">🖨️ พิมพ์แผนที่ (A4)</button>
  </div>
</header>

<main>
  <div class="print-head" id="printOrg"></div>

  <!-- แถบระบบหลัก (สำหรับเจ้าหน้าที่) -->
  <div class="sys-bar no-print">
    <div class="sys-group">
      <button class="btn-sm" style="background:#10b981;color:#fff;border-color:#059669" onclick="saveAllSystem()">💾 บันทึกฐานข้อมูลระบบ</button>
      <button class="btn-sm btn-primary" onclick="exportFullSystemJSON()">📦 ส่งออก JSON ทั้งระบบ</button>
      <label class="btn-sm btn-outline btn-file">
        📥 นำเข้า JSON
        <input type="file" id="inpFullJson" accept=".json" onchange="importFullSystemJSON(event)">
      </label>
    </div>
    <div class="sys-group">
      <span style="font-size:13px;color:var(--mute)">หน่วยงาน:</span>
      <input type="text" id="inpOrgName" value="{{ $config->org_name ?? 'อบต.เสม็ดใต้' }}" style="padding:4px 8px;border:1px solid var(--line);border-radius:4px" onchange="saveOrg()">
      <button class="btn-sm btn-outline" onclick="toggleCfg()">⚙️ ตั้งค่าพื้นที่</button>
      <button class="btn-sm btn-outline" style="color:var(--bad)" onclick="clearAllData()">🗑️ ล้างข้อมูล</button>
    </div>
  </div>

  <!-- ตั้งค่าพื้นที่ -->
  <section class="card no-print staff-only" id="cfgPanel" hidden style="margin-bottom:14px">
    <h2 style="margin:0 0 4px;font-size:16px">⚙️ ตั้งค่าหน่วยงานและพื้นที่เริ่มต้น</h2>
    <div class="cfg-grid">
      <div><label class="form-label">ชื่อพื้นที่ / ตำบล</label><input type="text" id="cfg_area" class="form-input" value="{{ $config->area_name }}"></div>
      <div><label class="form-label">ตัวย่อรหัสเสาไฟ</label><input type="text" id="cfg_ep" class="form-input" maxlength="8" value="{{ $config->elec_prefix }}"></div>
      <div><label class="form-label">ตัวย่อรหัสมิเตอร์น้ำ</label><input type="text" id="cfg_wp" class="form-input" maxlength="8" value="{{ $config->water_prefix }}"></div>
    </div>
    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;margin:6px 0 10px">
      <button class="btn-sm btn-outline" onclick="setCenterHere()">📍 ใช้พิกัดกลางแผนที่ตอนนี้เป็นจุดเริ่มต้น</button>
      <span class="note" id="cfg_center" style="margin:0">พิกัดเริ่มต้น: {{ $config->center_lat }}, {{ $config->center_lng }}</span>
    </div>
    <div style="display:flex;gap:6px;flex-wrap:wrap">
      <button class="btn-sm" style="background:#10b981;color:#fff;border-color:#059669" onclick="applyCfgPanel()">💾 บันทึกการตั้งค่า</button>
      <button class="btn-sm btn-primary" onclick="makeCitizenLink()">🔗 สร้างลิงก์สำหรับประชาชน</button>
    </div>
    <div id="cfg_linkbox" hidden style="margin-top:8px">
      <input type="text" id="cfg_link" class="form-input" readonly onclick="this.select()">
      <div class="note">คัดลอกลิงก์นี้เพื่อนำไปประชาสัมพันธ์ให้ประชาชนใช้งานได้ทันที</div>
    </div>
  </section>

  <!-- แท็บหมวดหลัก 5 หมวด -->
  <div class="main-tabs no-print">
    <button class="tab-main-btn active-elec" id="btnTabElec" onclick="switchWorkModule('elec')">💡 ไฟฟ้า</button>
    <button class="tab-main-btn" id="btnTabWater" onclick="switchWorkModule('water')">💧 ประปา</button>
    <button class="tab-main-btn" id="btnTabRoad" onclick="switchWorkModule('road')">🛣️ ถนน / ทางเท้า / ท่อระบาย</button>
    <button class="tab-main-btn" id="btnTabBldg" onclick="switchWorkModule('bldg')">🏢 อาคาร / สิ่งปลูกสร้าง</button>
    <button class="tab-main-btn" id="btnTabReport" onclick="switchWorkModule('report')">📊 สรุป / รายงาน</button>
  </div>

  <div class="sub-bar no-print">
    <div class="modes">
      <button type="button" id="btnStaffMode" class="on" onclick="setAppMode('staff')">🔧 โหมดกองช่าง/เจ้าหน้าที่</button>
      <button type="button" id="btnCitizenMode" onclick="setAppMode('citizen')">👤 โหมดประชาชนแจ้งเหตุ</button>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <span class="sync-note" id="syncNote" style="color:#059669">🟢 เชื่อมต่อฐานข้อมูลระบบแล้ว</span>
    </div>
  </div>

  <!-- ==================== 1. หมวดไฟฟ้า ==================== -->
  <div id="module-elec" class="work-module active">
    <!-- ฟอร์มประชาชน: ไฟฟ้า -->
    <section class="card citizen-only" id="e_c_form" style="margin-bottom:14px">
      <h2 style="margin:0 0 8px;color:var(--elec-dark)">แจ้งซ่อมไฟฟ้าสาธารณะ (สำหรับประชาชน)</h2>
      <div class="pick" id="e_c_pick">👆 <b>ขั้นที่ 1:</b> แตะ <b>จุดเสาไฟ</b> บนแผนที่ด้านล่าง แล้วกด «เลือกเสานี้เพื่อแจ้งซ่อม»</div>
      <input type="hidden" id="e_c_pole"><input type="hidden" id="e_c_vil">
      <label class="form-label">ขั้นที่ 2: อาการที่พบ</label>
      <select id="e_c_detail" class="form-input">
        <option>ไฟดับ</option><option>ไฟกระพริบ</option><option>หลอดไฟเสีย/ไม่สว่าง</option>
        <option>เสาเอียง/ล้ม</option><option>สายไฟหลุด/ห้อยต่ำ</option><option>อื่น ๆ (ระบุด้านล่าง)</option>
      </select>
      <label class="form-label">รายละเอียดเพิ่มเติม / จุดสังเกต</label>
      <input type="text" id="e_c_house" class="form-input" placeholder="เช่น ดับมา 2 คืนแล้ว หน้าบ้านเลขที่ 45">
      <input type="text" id="e_c_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <label class="form-label">แนบรูปถ่าย (สูงสุด 2 รูป)</label>
      <div class="photo-input-wrap">
        <input type="file" id="e_c_photos" accept="image/*" multiple capture="environment" onchange="onPhotoPick('elec', event)">
        <div class="photo-preview" id="e_c_photoPrev"></div>
      </div>
      <button type="button" id="e_c_btn" disabled onclick="submitElecCitizen()" style="background:var(--elec-dark);color:#fff;border:0;padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:600;opacity:.5">ส่งแจ้งซ่อมเสาไฟ</button>
      <div id="e_c_result"></div>
    </section>

    <div class="grid">
      <section class="card">
        <h2 style="margin:0 0 8px;font-size:16px;color:#1e3a8a">แผนที่เสาไฟ — กองช่าง (แตะเสา/ป้ายหมู่เพื่อจัดการ)</h2>
        <div class="search-bar no-print">
          <div class="search-wrap"><input type="text" id="e_search" placeholder="ค้นหาสถานที่ เช่น ตำบล หมู่บ้าน อบต."></div>
          <button class="btn-search" onclick="searchGeo('e')">🔍 ค้นหา</button>
          <button class="btn-myloc" onclick="myLoc('e')">📍 ตำแหน่งฉัน</button>
        </div>
        <div class="map-wrap">
          <div id="map-elec" class="map-canvas"></div>
          <div class="floating-toolbar staff-only">
            <button class="tb-btn btn-save" onclick="elecSave()">💾 บันทึก</button>
            <button class="tb-btn btn-print" onclick="window.print()">🖨️ พิมพ์</button>
            <button class="tb-btn" onclick="elecSetMode('pole')">📍 วางเสาไฟ</button>
            <button class="tb-btn" onclick="elecSetMode('road')">🛣️ วาดถนน</button>
            <button class="tb-btn" onclick="elecSetMode('alley')">〰️ วาดซอย</button>
            <select class="tb-btn tb-sel" data-mod="elec" onchange="lineSel(this)"></select>
            <button class="tb-btn btn-action" id="e_finLine" hidden onclick="elecFinishLine()">✅ เสร็จเส้น</button>
            <button class="tb-btn" id="e_undoPt" hidden onclick="elecUndoPt()">↩️ ถอยจุด</button>
            <button class="tb-btn" onclick="elecSetMode('zone')">⭕ เพิ่มโซน</button>
            <button class="tb-btn" onclick="elecSetMode('label')">🏷️ เพิ่มป้าย</button>
            <button class="tb-btn" onclick="elecSetMode('poi')">⭐ จุดสนใจ</button>
            <button class="tb-btn" onclick="elecSetMode('village')">🏡 เพิ่มหมู่</button>
            <button class="tb-btn" onclick="openDrawMgr('elec')">📋 จัดการสายทาง</button>
          </div>
        </div>
        <div class="legend">
          <span><i style="background:#eab308"></i>เสาไฟปกติ</span>
          <span><i style="background:#ef4444"></i>ไฟดับ/ชำรุด</span>
          <span><i style="background:#f97316"></i>รับเรื่องแล้ว</span>
          <span><i style="background:#0ea5e9"></i>กำลังดำเนินการ</span>
          <span><i style="background:#10b981"></i>แก้ไขเสร็จแล้ว</span>
        </div>
        <div class="row-tools no-print" style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;align-items:center">
          <button class="btn-sm btn-outline" onclick="exportElecCSV()">📤 ส่งออก CSV</button>
          <label class="btn-sm btn-outline btn-file">📥 นำเข้า CSV<input type="file" accept=".csv" onchange="importElecCSV(event)"></label>
          <button class="btn-sm btn-outline" style="color:var(--bad)" onclick="elecSimFail()">⚠️ จำลองไฟดับ</button>
          <button class="btn-sm btn-outline staff-only" onclick="openDrawMgr('elec')">📋 จัดการสายทาง/รูปวาด</button>
          <button class="btn-sm btn-outline" onclick="openHelp('u2')">📖 วิธีใช้</button>
        </div>
        <p class="note" id="e_hint">คลิกเสาไฟ / เส้นสายทาง เพื่อแก้ไขหรือลบ หรือใช้แถบเครื่องมือมุมขวาเพื่อวาด</p>
      </section>

      <div class="stack">
        <section class="card kpis staff-only">
          <div class="kpi"><b id="e_k1" style="color:var(--elec-dark)">0</b><span>เสาไฟทั้งหมด</span></div>
          <div class="kpi"><b id="e_k2" style="color:var(--bad)">0</b><span>งานแจ้งซ่อมไฟ</span></div>
        </section>
        <section class="card">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px">
            <h2 style="margin:0">รายการแจ้งซ่อมไฟฟ้า</h2>
            <button class="btn-sm btn-outline" onclick="loadTickets()">🔄 รีเฟรช</button>
          </div>
          <div class="chips" id="e_chips"></div>
          <div id="e_repairList"><div class="note">ไม่มีรายการค้าง</div></div>
        </section>
        <section class="card">
          <h2 style="margin:0 0 8px">🔎 ติดตามเรื่องที่แจ้ง</h2>
          <div style="display:flex;gap:6px">
            <input type="text" id="e_track" class="form-input" style="margin:0" placeholder="ใส่เลขเรื่อง เช่น E-0001" onkeydown="if(event.key==='Enter')trackTicket('elec')">
            <button class="btn-sm btn-primary" onclick="trackTicket('elec')">ค้นหา</button>
          </div>
          <div id="e_trackResult" style="margin-top:10px"></div>
        </section>
      </div>
    </div>
  </div>

  <!-- ==================== 2. หมวดประปา ==================== -->
  <div id="module-water" class="work-module">
    <!-- ฟอร์มประชาชน: ประปา -->
    <section class="card citizen-only" id="w_c_form" style="margin-bottom:14px">
      <h2 style="margin:0 0 8px;color:var(--water-dark)">แจ้งปัญหาประปา (สำหรับประชาชน)</h2>
      <div class="pick w" id="w_c_pick">👆 <b>ขั้นที่ 1:</b> แตะ <b>จุดมิเตอร์น้ำ</b> ใกล้บ้านหรือจุดเกิดเหตุบนแผนที่ แล้วกด «เลือกมิเตอร์นี้เพื่อแจ้งปัญหา»</div>
      <input type="hidden" id="w_c_meter"><input type="hidden" id="w_c_vil">
      <label class="form-label">ขั้นที่ 2: อาการที่พบ</label>
      <select id="w_c_detail" class="form-input">
        <option>น้ำไม่ไหล</option><option>น้ำไหลอ่อน</option><option>ท่อแตก/รั่ว</option>
        <option>น้ำขุ่น/มีกลิ่น</option><option>มิเตอร์ชำรุด</option><option>อื่น ๆ (ระบุด้านล่าง)</option>
      </select>
      <label class="form-label">รายละเอียดเพิ่มเติม / จุดสังเกต</label>
      <input type="text" id="w_c_loc" class="form-input" placeholder="เช่น ท่อแตกหน้าบ้านเลขที่ 45 น้ำไหลแรง">
      <input type="text" id="w_c_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <label class="form-label">แนบรูปถ่าย (สูงสุด 2 รูป)</label>
      <div class="photo-input-wrap">
        <input type="file" id="w_c_photos" accept="image/*" multiple capture="environment" onchange="onPhotoPick('water', event)">
        <div class="photo-preview" id="w_c_photoPrev"></div>
      </div>
      <button type="button" id="w_c_btn" disabled onclick="submitWaterCitizen()" style="background:var(--water);color:#fff;border:0;padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:600;opacity:.5">ส่งแจ้งปัญหาประปา</button>
      <div id="w_c_result"></div>
    </section>

    <div class="grid">
      <section class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:8px">
          <h2 style="margin:0;font-size:16px;color:#0369a1">แผนที่ระบบประปา — กองการประปา</h2>
          <div class="staff-only" style="display:flex;gap:6px">
            <button class="btn-sm btn-outline" id="btnToggleSysMeters" onclick="toggleSystemMeters()">💧 แสดงมิเตอร์จริงระบบประปา (<span id="sysMeterCount">0</span>)</button>
          </div>
        </div>
        <div class="search-bar no-print">
          <div class="search-wrap"><input type="text" id="w_search" placeholder="ค้นหาสถานที่ หรือรหัสมิเตอร์"></div>
          <button class="btn-search" onclick="searchGeo('w')">🔍 ค้นหา</button>
          <button class="btn-myloc" onclick="myLoc('w')">📍 ตำแหน่งฉัน</button>
        </div>
        <div class="map-wrap">
          <div id="map-water" class="map-canvas"></div>
          <div class="floating-toolbar staff-only">
            <button class="tb-btn btn-save" onclick="waterSave()">💾 บันทึก</button>
            <button class="tb-btn btn-print" onclick="window.print()">🖨️ พิมพ์</button>
            <button class="tb-btn" onclick="waterSetMode('meter')">💧 วางมิเตอร์น้ำ</button>
            <button class="tb-btn" onclick="waterSetMode('leak')">⚠️ จุดท่อแตก/รั่ว</button>
            <button class="tb-btn" onclick="waterSetMode('valve')">🔵 วางประตูน้ำ</button>
            <button class="tb-btn" onclick="waterSetMode('tank')">🏰 หอส่งน้ำ/ถังพัก</button>
            <button class="tb-btn" onclick="waterSetMode('pipeMain')">〰️ วาดท่อเมนหลัก</button>
            <button class="tb-btn" onclick="waterSetMode('pipeSub')">┈ วาดท่อแยก</button>
            <select class="tb-btn tb-sel" data-mod="water" onchange="lineSel(this)"></select>
            <button class="tb-btn btn-action" id="w_finLine" hidden onclick="waterFinishLine()">✅ เสร็จเส้น</button>
            <button class="tb-btn" id="w_undoPt" hidden onclick="waterUndoPt()">↩️ ถอยจุด</button>
            <button class="tb-btn" onclick="waterSetMode('zone')">⭕ เพิ่มโซนจ่ายน้ำ</button>
            <button class="tb-btn" onclick="waterSetMode('label')">🏷️ เพิ่มป้าย</button>
            <button class="tb-btn" onclick="waterSetMode('poi')">⭐ จุดสนใจ</button>
            <button class="tb-btn" onclick="waterSetMode('village')">🏡 เพิ่มหมู่</button>
            <button class="tb-btn" onclick="openDrawMgr('water')">📋 จัดการสายทาง</button>
          </div>
        </div>
        <div class="legend">
          <span><i style="background:#0284c7"></i>มิเตอร์น้ำ</span>
          <span><i style="background:#ef4444"></i>ท่อแตก/ไม่ไหล</span>
          <span><i style="background:#10b981"></i>ประตูน้ำ/วาล์ว</span>
          <span><i style="background:#7c3aed"></i>หอส่งน้ำ/สถานี</span>
          <span><i style="background:#dc2626"></i>มิเตอร์มีหนี้ค้างชำระ</span>
        </div>
        <div class="row-tools no-print" style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;align-items:center">
          <button class="btn-sm btn-outline" onclick="exportWaterCSV()">📤 ส่งออก CSV</button>
          <label class="btn-sm btn-outline btn-file">📥 นำเข้า CSV<input type="file" accept=".csv" onchange="importWaterCSV(event)"></label>
          <button class="btn-sm btn-outline" style="color:var(--bad)" onclick="waterSimLeak()">⚠️ จำลองท่อแตก</button>
          <button class="btn-sm btn-outline staff-only" onclick="openDrawMgr('water')">📋 จัดการสายทาง/รูปวาด</button>
          <button class="btn-sm btn-outline" onclick="openHelp('u3')">📖 วิธีใช้</button>
        </div>
        <p class="note" id="w_hint">คลิกจุดมิเตอร์ / วาล์ว / แนวท่อ เพื่อดูข้อมูลและจัดการ</p>
      </section>

      <div class="stack">
        <section class="card kpis staff-only">
          <div class="kpi"><b id="w_k1" style="color:var(--water-dark)">0</b><span>มิเตอร์น้ำทั้งหมด</span></div>
          <div class="kpi"><b id="w_k2" style="color:var(--bad)">0</b><span>จุดแจ้งท่อแตก</span></div>
        </section>
        <section class="card">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px">
            <h2 style="margin:0">รายการแจ้งซ่อมประปา</h2>
            <button class="btn-sm btn-outline" onclick="loadTickets()">🔄 รีเฟรช</button>
          </div>
          <div class="chips" id="w_chips"></div>
          <div id="w_repairList"><div class="note">ไม่มีรายการค้าง</div></div>
        </section>
        <section class="card">
          <h2 style="margin:0 0 8px">🔎 ติดตามเรื่องที่แจ้ง</h2>
          <div style="display:flex;gap:6px">
            <input type="text" id="w_track" class="form-input" style="margin:0" placeholder="ใส่เลขเรื่อง เช่น W-0001" onkeydown="if(event.key==='Enter')trackTicket('water')">
            <button class="btn-sm btn-primary" onclick="trackTicket('water')">ค้นหา</button>
          </div>
          <div id="w_trackResult" style="margin-top:10px"></div>
        </section>
      </div>
    </div>
  </div>

  <!-- ==================== 3. หมวดถนน / ทางเท้า / ท่อระบาย ==================== -->
  <div id="module-road" class="work-module">
    <section class="card citizen-only" id="r_c_form" style="margin-bottom:14px">
      <h2 style="margin:0 0 8px;color:#92400e">แจ้งซ่อมถนน / ทางเท้า / ท่อระบายน้ำ (สำหรับประชาชน)</h2>
      <div class="pick" id="r_c_pick" style="border-color:#d97706">👆 <b>ขั้นที่ 1:</b> แตะตำแหน่งบนแผนที่ด้านล่าง แล้วกด «ใช้จุดนี้เพื่อแจ้งซ่อม»</div>
      <input type="hidden" id="r_c_lat"><input type="hidden" id="r_c_lng"><input type="hidden" id="r_c_ref">
      <label class="form-label">ขั้นที่ 2: ประเภทปัญหา</label>
      <select id="r_c_detail" class="form-input">
        <option>ถนนชำรุด / หลุมบ่อ</option><option>ทางเท้าชำรุด / ทรุด</option>
        <option>ท่อระบายน้ำอุดตัน</option><option>ฝาท่อหาย / แตก</option>
        <option>น้ำท่วมขังบนถนน</option><option>ไหล่ทางพัง / ดินสไลด์</option>
        <option>ป้ายจราจรชำรุด</option><option>อื่น ๆ (ระบุด้านล่าง)</option>
      </select>
      <label class="form-label">รายละเอียดเพิ่มเติม / จุดสังเกต</label>
      <input type="text" id="r_c_loc" class="form-input" placeholder="เช่น หลุมลึกใกล้สะพาน">
      <input type="text" id="r_c_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <label class="form-label">แนบรูปถ่าย (สูงสุด 2 รูป)</label>
      <div class="photo-input-wrap">
        <input type="file" id="r_c_photos" accept="image/*" multiple capture="environment" onchange="onPhotoPick('road', event)">
        <div class="photo-preview" id="r_c_photoPrev"></div>
      </div>
      <button type="button" id="r_c_btn" disabled onclick="submitRoadCitizen()" style="background:#d97706;color:#fff;border:0;padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:600;opacity:.5">ส่งแจ้งซ่อมถนน/ทางเท้า/ท่อระบาย</button>
      <div id="r_c_result"></div>
    </section>

    <div class="grid">
      <section class="card">
        <h2 style="margin:0 0 8px;font-size:16px;color:#92400e">แผนที่ถนน / ทางเท้า / ท่อระบาย — กองช่าง</h2>
        <div class="search-bar no-print">
          <div class="search-wrap"><input type="text" id="r_search" placeholder="ค้นหาสถานที่"></div>
          <button class="btn-search" onclick="searchGeo('r')">🔍 ค้นหา</button>
          <button class="btn-myloc" onclick="myLoc('r')">📍 ตำแหน่งฉัน</button>
        </div>
        <div class="map-wrap">
          <div id="map-road" class="map-canvas"></div>
          <div class="floating-toolbar staff-only">
            <button class="tb-btn btn-save" onclick="roadSave()">💾 บันทึก</button>
            <button class="tb-btn btn-print" onclick="window.print()">🖨️ พิมพ์</button>
            <button class="tb-btn" onclick="roadSetMode('issue')">⚠️ จุดแจ้งซ่อม</button>
            <button class="tb-btn" onclick="roadSetMode('road')">🛣️ วาดถนน</button>
            <button class="tb-btn" onclick="roadSetMode('sidewalk')">🚶 วาดทางเท้า</button>
            <button class="tb-btn" onclick="roadSetMode('drain')">〰️ วาดท่อระบาย</button>
            <select class="tb-btn tb-sel" data-mod="road" onchange="lineSel(this)"></select>
            <button class="tb-btn btn-action" id="r_finLine" hidden onclick="roadFinishLine()">✅ เสร็จเส้น</button>
            <button class="tb-btn" id="r_undoPt" hidden onclick="roadUndoPt()">↩️ ถอยจุด</button>
            <button class="tb-btn" onclick="roadSetMode('label')">🏷️ เพิ่มป้าย</button>
            <button class="tb-btn" onclick="roadSetMode('village')">🏡 เพิ่มหมู่</button>
            <button class="tb-btn" onclick="openDrawMgr('road')">📋 จัดการสายทาง</button>
          </div>
        </div>
        <div class="legend">
          <span><i style="background:#d97706"></i>จุดแจ้งซ่อม</span>
          <span><i style="background:#f97316"></i>รับเรื่องแล้ว</span>
          <span><i style="background:#0ea5e9"></i>กำลังดำเนินการ</span>
          <span><i style="background:#10b981"></i>แก้ไขเสร็จแล้ว</span>
        </div>
        <div class="row-tools no-print" style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;align-items:center">
          <button class="btn-sm btn-outline staff-only" onclick="openDrawMgr('road')">📋 จัดการสายทาง/รูปวาด</button>
          <button class="btn-sm btn-outline" onclick="openHelp('u4')">📖 วิธีใช้</button>
        </div>
        <p class="note" id="r_hint">คลิกจุด / เส้นสายทาง บนแผนที่เพื่อจัดการ</p>
      </section>
      <div class="stack">
        <section class="card kpis staff-only">
          <div class="kpi"><b id="r_k1" style="color:#92400e">0</b><span>จุดแจ้งบนแผนที่</span></div>
          <div class="kpi"><b id="r_k2" style="color:var(--bad)">0</b><span>งานแจ้งค้าง</span></div>
        </section>
        <section class="card">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px">
            <h2 style="margin:0">รายการแจ้งซ่อมถนน/ทางเท้า/ท่อระบาย</h2>
            <button class="btn-sm btn-outline" onclick="loadTickets()">🔄 รีเฟรช</button>
          </div>
          <div class="chips" id="r_chips"></div>
          <div id="r_repairList"><div class="note">ไม่มีรายการค้าง</div></div>
        </section>
        <section class="card">
          <h2 style="margin:0 0 8px">🔎 ติดตามเรื่องที่แจ้ง</h2>
          <div style="display:flex;gap:6px">
            <input type="text" id="r_track" class="form-input" style="margin:0" placeholder="ใส่เลขเรื่อง เช่น R-0001" onkeydown="if(event.key==='Enter')trackTicket('road')">
            <button class="btn-sm btn-primary" onclick="trackTicket('road')">ค้นหา</button>
          </div>
          <div id="r_trackResult" style="margin-top:10px"></div>
        </section>
      </div>
    </div>
  </div>

  <!-- ==================== 4. หมวดอาคาร / สิ่งปลูกสร้าง ==================== -->
  <div id="module-bldg" class="work-module">
    <section class="card citizen-only" id="b_c_form" style="margin-bottom:14px">
      <h2 style="margin:0 0 8px;color:#5b21b6">แจ้งซ่อมอาคาร / สิ่งปลูกสร้าง (สำหรับประชาชน)</h2>
      <div class="pick" id="b_c_pick" style="border-color:#7c3aed">👆 <b>ขั้นที่ 1:</b> แตะ <b>จุดอาคาร</b> บนแผนที่ แล้วกด «ใช้จุดนี้เพื่อแจ้งซ่อม»</div>
      <input type="hidden" id="b_c_lat"><input type="hidden" id="b_c_lng"><input type="hidden" id="b_c_ref">
      <label class="form-label">ขั้นที่ 2: ประเภทปัญหา</label>
      <select id="b_c_detail" class="form-input">
        <option>หลังคารั่ว / ชำรุด</option><option>ผนัง / พื้น แตกร้าว ทรุดตัว</option>
        <option>ประตู / หน้าต่างชำรุด</option><option>ระบบไฟฟ้าในอาคารชำรุด</option>
        <option>ระบบน้ำ / สุขภัณฑ์ชำรุด</option><option>เสี่ยงพังถล่ม / เป็นอันตราย</option>
        <option>ทรุดโทรมทั่วไป ต้องปรับปรุง</option><option>อื่น ๆ (ระบุด้านล่าง)</option>
      </select>
      <label class="form-label">รายละเอียดเพิ่มเติม / จุดสังเกต</label>
      <input type="text" id="b_c_loc" class="form-input" placeholder="เช่น ศาลาประชาคม หลังคารั่ว">
      <input type="text" id="b_c_hp" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
      <label class="form-label">แนบรูปถ่าย (สูงสุด 2 รูป)</label>
      <div class="photo-input-wrap">
        <input type="file" id="b_c_photos" accept="image/*" multiple capture="environment" onchange="onPhotoPick('bldg', event)">
        <div class="photo-preview" id="b_c_photoPrev"></div>
      </div>
      <button type="button" id="b_c_btn" disabled onclick="submitBldgCitizen()" style="background:#7c3aed;color:#fff;border:0;padding:8px 16px;border-radius:6px;cursor:pointer;font-weight:600;opacity:.5">ส่งแจ้งซ่อมอาคาร/สิ่งปลูกสร้าง</button>
      <div id="b_c_result"></div>
    </section>

    <div class="grid">
      <section class="card">
        <h2 style="margin:0 0 8px;font-size:16px;color:#5b21b6">แผนที่อาคาร / สิ่งปลูกสร้าง — กองช่าง</h2>
        <div class="search-bar no-print">
          <div class="search-wrap"><input type="text" id="b_search" placeholder="ค้นหาสถานที่"></div>
          <button class="btn-search" onclick="searchGeo('b')">🔍 ค้นหา</button>
          <button class="btn-myloc" onclick="myLoc('b')">📍 ตำแหน่งฉัน</button>
        </div>
        <div class="map-wrap">
          <div id="map-bldg" class="map-canvas"></div>
          <div class="floating-toolbar staff-only">
            <button class="tb-btn btn-save" onclick="bldgSave()">💾 บันทึก</button>
            <button class="tb-btn btn-print" onclick="window.print()">🖨️ พิมพ์</button>
            <button class="tb-btn" onclick="bldgSetMode('building')">🏢 วางจุดอาคาร</button>
            <button class="tb-btn" onclick="bldgSetMode('fence')">🧱 วาดรั้ว/กำแพง</button>
            <select class="tb-btn tb-sel" data-mod="bldg" onchange="lineSel(this)"></select>
            <button class="tb-btn btn-action" id="b_finLine" hidden onclick="bldgFinishLine()">✅ เสร็จเส้น</button>
            <button class="tb-btn" id="b_undoPt" hidden onclick="bldgUndoPt()">↩️ ถอยจุด</button>
            <button class="tb-btn" onclick="bldgSetMode('label')">🏷️ เพิ่มป้าย</button>
            <button class="tb-btn" onclick="bldgSetMode('village')">🏡 เพิ่มหมู่</button>
            <button class="tb-btn" onclick="openDrawMgr('bldg')">📋 จัดการสายทาง</button>
          </div>
        </div>
        <div class="legend">
          <span><i style="background:#8b5cf6"></i>สภาพดี</span>
          <span><i style="background:#eab308"></i>พอใช้</span>
          <span><i style="background:#ec4899"></i>ทรุดโทรม</span>
          <span><i style="background:#7f1d1d"></i>เสี่ยงอันตราย</span>
        </div>
        <div class="row-tools no-print" style="display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;align-items:center">
          <button class="btn-sm btn-outline staff-only" onclick="exportBldgCSV()">📤 CSV ทะเบียนอาคาร</button>
          <label class="btn-sm btn-outline btn-file staff-only">📥 นำเข้า CSV<input type="file" accept=".csv" onchange="importBldgCSV(event)"></label>
          <button class="btn-sm btn-outline staff-only" onclick="openDrawMgr('bldg')">📋 จัดการสายทาง/รูปวาด</button>
          <button class="btn-sm btn-outline" onclick="openHelp('u4b')">📖 วิธีใช้</button>
        </div>
        <p class="note" id="b_hint">คลิกจุดอาคารเพื่อดูรายละเอียดหรือแจ้งซ่อม</p>
      </section>
      <div class="stack">
        <section class="card kpis staff-only">
          <div class="kpi"><b id="b_k1" style="color:#5b21b6">0</b><span>อาคารในทะเบียน</span></div>
          <div class="kpi"><b id="b_k2" style="color:var(--bad)">0</b><span>งานแจ้งค้าง</span></div>
          <div class="kpi"><b id="b_k3" style="color:#be185d">0</b><span>ทรุดโทรม/อันตราย</span></div>
          <div class="kpi"><b id="b_k4" style="color:#059669;font-size:18px">0</b><span>มูลค่ารวม (บาท)</span></div>
        </section>
        <section class="card staff-only">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px">
            <h2 style="margin:0">📑 ทะเบียนอาคาร</h2>
            <select id="b_regFilter" onchange="renderBldgRegistry()" style="padding:4px 8px;border:1px solid var(--line);border-radius:6px;font:inherit;font-size:13px">
              <option value="all">ทุกสภาพ</option><option value="good">สภาพดี</option>
              <option value="fair">พอใช้</option><option value="poor">ทรุดโทรม</option><option value="danger">เสี่ยงอันตราย</option>
            </select>
          </div>
          <div style="overflow:auto;max-height:220px">
            <table class="hotspot-table" id="b_regTable">
              <thead><tr><th>รหัส</th><th>ชื่อ</th><th>หมู่</th><th>สภาพ</th></tr></thead>
              <tbody id="b_regBody"></tbody>
            </table>
          </div>
        </section>
        <section class="card">
          <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:8px">
            <h2 style="margin:0">รายการแจ้งซ่อมอาคาร</h2>
            <button class="btn-sm btn-outline" onclick="loadTickets()">🔄 รีเฟรช</button>
          </div>
          <div class="chips" id="b_chips"></div>
          <div id="b_repairList"><div class="note">ไม่มีรายการค้าง</div></div>
        </section>
        <section class="card">
          <h2 style="margin:0 0 8px">🔎 ติดตามเรื่องที่แจ้ง</h2>
          <div style="display:flex;gap:6px">
            <input type="text" id="b_track" class="form-input" style="margin:0" placeholder="ใส่เลขเรื่อง เช่น B-0001" onkeydown="if(event.key==='Enter')trackTicket('bldg')">
            <button class="btn-sm btn-primary" onclick="trackTicket('bldg')">ค้นหา</button>
          </div>
          <div id="b_trackResult" style="margin-top:10px"></div>
        </section>
      </div>
    </div>
  </div>

  <!-- ==================== 5. หมวดสรุปคำร้องและรายงาน ==================== -->
  <div id="module-report" class="work-module">
    <section class="card" id="reportSection">
      <div class="print-head" id="printReportHead"></div>
      <h2 style="margin:0 0 6px;font-size:18px;color:#3730a3">📊 สรุปคำร้องแจ้งซ่อมและการแก้ไข</h2>
      <p class="note" style="margin:0 0 12px" id="reportOrgLine"></p>

      <div class="report-actions no-print">
        <button class="btn-sm" style="background:#10b981;color:#fff;border-color:#059669" onclick="refreshReport()">🔄 รีเฟรชข้อมูล</button>
        <button class="btn-sm btn-primary print-keep" onclick="printReport()">🖨️ พิมพ์รายงานสรุป (A4)</button>
        <button class="btn-sm btn-outline" onclick="exportReportCSV()">📤 ส่งออก CSV สรุป</button>
        <label style="font-size:13px;display:flex;align-items:center;gap:6px;margin-left:4px">
          <span>สถานะ:</span>
          <select id="rep_filter" onchange="renderReport()" style="padding:5px 8px;border:1px solid var(--line);border-radius:6px;font:inherit">
            <option value="all">ทั้งหมด</option>
            <option value="new">🟠 รับเรื่องแล้ว</option>
            <option value="doing">🔵 กำลังดำเนินการ</option>
            <option value="done">🟢 แก้ไขเสร็จแล้ว</option>
            <option value="rejected">⚪ ไม่สามารถดำเนินการได้</option>
          </select>
        </label>
        <label style="font-size:13px;display:flex;align-items:center;gap:6px">
          <span>ประเภท:</span>
          <select id="rep_type" onchange="renderReport()" style="padding:5px 8px;border:1px solid var(--line);border-radius:6px;font:inherit">
            <option value="all">ทั้งหมด</option>
            <option value="elec">💡 ไฟฟ้า</option>
            <option value="water">💧 ประปา</option>
            <option value="road">🛣️ ถนน/ทางเท้า</option>
            <option value="bldg">🏢 อาคาร</option>
          </select>
        </label>
      </div>

      <div class="report-kpis" id="reportKpis"></div>

      <div class="staff-only" id="repairStatsPanel" style="margin-top:16px">
        <h3 style="margin:0 0 8px;font-size:15px;color:#1e293b">📈 สถิติการซ่อมและงบประมาณ (เฉพาะเจ้าหน้าที่)</h3>
        <div class="stats-grid" id="budgetKpis"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px" class="stats-cols">
          <div>
            <h4 style="margin:10px 0 6px;font-size:14px">🔥 จุดที่แจ้งซ่อมบ่อยที่สุด</h4>
            <div style="overflow-x:auto">
              <table class="hotspot-table" id="hotspotFreqTable">
                <thead><tr><th>#</th><th>จุด / อ้างอิง</th><th>ประเภท</th><th>ครั้ง</th><th>งบรวม</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
          <div>
            <h4 style="margin:10px 0 6px;font-size:14px">💰 จุดที่ใช้งบซ่อมสูงสุด</h4>
            <div style="overflow-x:auto">
              <table class="hotspot-table" id="hotspotCostTable">
                <thead><tr><th>#</th><th>จุด / อ้างอิง</th><th>ประเภท</th><th>ครั้ง</th><th>งบรวม</th></tr></thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="report-print-only" style="margin:8px 0 12px;font-size:12pt">
        <div>วันที่พิมพ์: <span id="printDate"></span></div>
        <div>หน่วยงาน: <span id="printOrgReport"></span></div>
      </div>

      <h3 style="margin:14px 0 8px;font-size:15px">รายการคำร้องทั้งหมด</h3>
      <div class="report-table-wrap">
        <table class="report-table" id="reportTable">
          <thead>
            <tr>
              <th>เลขเรื่อง</th><th>ประเภท</th><th>จุดอ้างอิง</th><th>รายละเอียด</th>
              <th>สถานะ</th><th>แจ้งเมื่อ</th><th>อัปเดต</th><th>ข้อความล่าสุด</th>
              <th class="staff-only">งบซ่อม</th>
            </tr>
          </thead>
          <tbody id="reportTbody"><tr><td colspan="9" class="note">ไม่มีข้อมูล</td></tr></tbody>
        </table>
      </div>

      <div style="margin-top:16px" class="no-print">
        <h3 style="margin:0 0 8px;font-size:15px">การ์ดรายละเอียด (สำหรับดูบนหน้าจอ)</h3>
        <div id="reportCards"><div class="note">ไม่มีรายการ</div></div>
      </div>
    </section>
  </div>
</main>

<!-- Modals -->
<div id="drawModal" hidden role="dialog" aria-modal="true" onclick="if(event.target===this)closeDrawMgr()">
  <div class="dm-box">
    <div class="dm-head"><h2 id="drawModalTitle">📋 จัดการสายทาง/รูปวาด</h2><button type="button" class="help-close" onclick="closeDrawMgr()">×</button></div>
    <div class="dm-body" id="drawModalBody"></div>
  </div>
</div>

<div id="helpModal" hidden role="dialog" aria-modal="true">
  <div class="help-box">
    <div class="help-head"><h2 id="helpTitle">📖 คู่มือระบบ Smart Utility</h2><button type="button" class="help-close" onclick="closeHelp()">×</button></div>
    <div class="help-body" id="helpBody">
      <h3>วิธีใช้งานระบบ Smart Utility GIS</h3>
      <p>ระบบนี้เชื่อมโยงฐานข้อมูล MySQL ของระบบส่วนกลาง เข้ากับแผนที่และการแจ้งเรื่องร้องเรียนของประชาชน</p>
      <ul>
        <li><b>หมวดไฟฟ้า:</b> วางเสาไฟ วาดแนวสายทาง แจ้งซ่อมเสาไฟ</li>
        <li><b>หมวดประปา:</b> แสดงตำแหน่งมิเตอร์น้ำ ประตูน้ำ หอส่งน้ำ จุดท่อแตก และสามารถดึงข้อมูลมิเตอร์จากระบบประปาเดิมได้</li>
        <li><b>หมวดถนน:</b> วางจุดแจ้งซ่อมถนน หลุมบ่อ ทางเท้า ท่อระบายน้ำ</li>
        <li><b>หมวดอาคาร:</b> สำรวจและขึ้นทะเบียนอาคาร/สิ่งปลูกสร้าง พร้อมบันทึกมูลค่าและสภาพการใช้งาน</li>
        <li><b>การซิงค์ข้อมูล:</b> ทุกการสร้างหรือแก้ไขจะบันทึกลงฐานข้อมูล MySQL ทันที และมีระบบสำรองในเครื่อง</li>
      </ul>
    </div>
  </div>
</div>

<div id="photoLightbox" class="photo-lightbox" hidden onclick="if(event.target===this)closePhoto()">
  <button type="button" onclick="closePhoto()">ปิด</button>
  <img id="photoLightboxImg" alt="รูปแนบ">
</div>

<script>
// ==========================================
// 🚀 LARAVEL API & CONFIGURATION INTEGRATION
// ==========================================
const API = {
  init: "{{ route('smart_utility.api.init') }}",
  config: "{{ route('smart_utility.api.config') }}",
  itemSave: "{{ route('smart_utility.api.item.save') }}",
  itemDelete: id => `{{ url('smart-utility/api/item') }}/${id}`,
  shapeSave: "{{ route('smart_utility.api.shape.save') }}",
  shapeDelete: id => `{{ url('smart-utility/api/shape') }}/${id}`,
  shapesClear: "{{ route('smart_utility.api.shapes.clear') }}",
  ticketCreate: "{{ route('smart_utility.api.ticket.create') }}",
  ticketStaffUpdate: code => `{{ url('smart-utility/api/ticket') }}/${code}/staff-update`,
  ticketCitizenReply: code => `{{ url('smart-utility/api/ticket') }}/${code}/citizen-reply`,
  syncAll: "{{ route('smart_utility.api.sync_all') }}",
  importJson: "{{ route('smart_utility.api.import_json') }}",
  clearAll: "{{ route('smart_utility.api.clear_all') }}",
  oweReport: "{{ route('reports.owe') }}",
  invoiceIndex: "{{ route('invoice.index') }}"
};

const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const IS_AUTHENTICATED = @json(Auth::check());
const INITIAL_MODE = @json($initialMode);
const SERVER_CFG = @json($config);

// Headers for AJAX requests
const reqHeaders = (isJson = true) => {
  const h = { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' };
  if(isJson) h['Content-Type'] = 'application/json';
  return h;
};

const $ = id => document.getElementById(id);
const esc = t => String(t||'').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

const CFG = {
  org: SERVER_CFG.org_name || 'อบต.เสม็ดใต้',
  area: SERVER_CFG.area_name || '',
  ep: SERVER_CFG.elec_prefix || 'SMT',
  wp: SERVER_CFG.water_prefix || 'WTR',
  lat: parseFloat(SERVER_CFG.center_lat) || 13.6900,
  lng: parseFloat(SERVER_CFG.center_lng) || 101.2000
};
const LAT0 = CFG.lat, LNG0 = CFG.lng;

let currentWork = 'elec';
let appRole = INITIAL_MODE === 'citizen' ? 'citizen' : 'staff';
let STAFF_OK = IS_AUTHENTICATED || sessionStorage.getItem('omni_staff_ok') === '1';

function setSyncStatus(msg, isSuccess = true){
  const el = $('syncNote');
  if(!el) return;
  el.textContent = msg;
  el.style.color = isSuccess ? '#059669' : '#dc2626';
}

function orgLabel(){ return CFG.org + (CFG.area ? ' · ' + CFG.area : ''); }
function applyBranding(){
  const l = orgLabel();
  $('brandOrg').textContent = '🏛️ ' + l;
  $('printOrg').textContent = l;
  $('gateOrg').textContent = l;
  document.title = CFG.org + ' — ระบบบริหารจัดการสาธารณูปโภคอัจฉริยะ';
}

async function saveOrg(){
  CFG.org = $('inpOrgName').value.trim() || 'อบต.เสม็ดใต้';
  applyBranding();
  try {
    await fetch(API.config, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ org_name: CFG.org, area_name: CFG.area, elec_prefix: CFG.ep, water_prefix: CFG.wp, center_lat: CFG.lat, center_lng: CFG.lng })
    });
    setSyncStatus('🟢 บันทึกชื่อหน่วยงานลงฐานข้อมูลแล้ว');
  } catch(e){}
}

function fillCfgPanel(){
  $('cfg_area').value = CFG.area;
  $('cfg_ep').value = CFG.ep;
  $('cfg_wp').value = CFG.wp;
  $('cfg_center').textContent = `จุดเริ่มต้นแผนที่: ${(+CFG.lat).toFixed(5)}, ${(+CFG.lng).toFixed(5)}`;
}
function loadOrg(){ $('inpOrgName').value = CFG.org; fillCfgPanel(); applyBranding(); }
function toggleCfg(){ $('cfgPanel').hidden = !$('cfgPanel').hidden; }

async function applyCfgPanel(){
  CFG.org = $('inpOrgName').value.trim() || 'อบต.เสม็ดใต้';
  CFG.area = $('cfg_area').value.trim();
  CFG.ep = ($('cfg_ep').value || 'SMT').toUpperCase().slice(0, 8);
  CFG.wp = ($('cfg_wp').value || 'WTR').toUpperCase().slice(0, 8);
  applyBranding();
  fillCfgPanel();
  try {
    await fetch(API.config, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ org_name: CFG.org, area_name: CFG.area, elec_prefix: CFG.ep, water_prefix: CFG.wp, center_lat: CFG.lat, center_lng: CFG.lng })
    });
    alert('✅ บันทึกการตั้งค่าลงฐานข้อมูลระบบแล้ว');
  } catch(e){ alert('บันทึกลงฐานข้อมูลล้มเหลว'); }
}

function setCenterHere(){
  const m = currentWork === 'water' ? mapWater : currentWork === 'road' ? mapRoad : currentWork === 'bldg' ? mapBldg : mapElec;
  const c = m.getCenter();
  CFG.lat = +c.lat.toFixed(6); CFG.lng = +c.lng.toFixed(6);
  fillCfgPanel();
}

function makeCitizenLink(){
  const u = new URL(location.href);
  u.pathname = '/smart-utility/citizen';
  u.search = '';
  $('cfg_linkbox').hidden = false;
  $('cfg_link').value = u.toString();
  $('cfg_link').select();
  try{ navigator.clipboard.writeText(u.toString()); }catch(e){}
}

function switchWorkModule(m){
  currentWork = m;
  ['elec','water','road','bldg','report'].forEach(k => {
    const btn = $('btnTab' + k.charAt(0).toUpperCase() + k.slice(1));
    if(btn) btn.className = 'tab-main-btn ' + (m === k ? 'active-' + k : '');
    const mod = $('module-' + k);
    if(mod) mod.classList.toggle('active', m === k);
  });
  if(m === 'report') refreshReport();
  setTimeout(() => {
    if(m === 'elec' && mapElec) mapElec.invalidateSize();
    if(m === 'water' && mapWater) mapWater.invalidateSize();
    if(m === 'road' && mapRoad) mapRoad.invalidateSize();
    if(m === 'bldg' && mapBldg) mapBldg.invalidateSize();
  }, 150);
}

function setAppMode(r){
  if(r === 'staff' && !STAFF_OK){ openGate(true); return; }
  appRole = r;
  document.body.classList.toggle('mode-citizen', r === 'citizen');
  document.body.classList.toggle('mode-staff', r !== 'citizen');
  $('btnStaffMode').classList.toggle('on', r === 'staff');
  $('btnCitizenMode').classList.toggle('on', r === 'citizen');
  refreshTicketViews();
  setTimeout(() => {
    if(mapElec) mapElec.invalidateSize();
    if(mapWater) mapWater.invalidateSize();
    if(mapRoad) mapRoad.invalidateSize();
    if(mapBldg) mapBldg.invalidateSize();
  }, 100);
}

async function searchGeo(type){
  const inp = $(type + '_search');
  const q = (inp.value || '').trim();
  if(!q) return;
  try{
    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&countrycodes=th&limit=1`);
    const data = await res.json();
    if(data && data.length > 0){
      const ll = [+data[0].lat, +data[0].lon];
      const m = (type === 'e' ? mapElec : type === 'w' ? mapWater : type === 'r' ? mapRoad : mapBldg);
      m.flyTo(ll, 16);
    } else { alert("ไม่พบสถานที่ที่ค้นหา"); }
  }catch(e){ alert("การค้นหาขัดข้อง"); }
}

function myLoc(type){
  if(!navigator.geolocation) return alert("เบราว์เซอร์ไม่รองรับ GPS");
  navigator.geolocation.getCurrentPosition(pos => {
    const ll = [pos.coords.latitude, pos.coords.longitude];
    const m = (type === 'e' ? mapElec : type === 'w' ? mapWater : type === 'r' ? mapRoad : mapBldg);
    m.flyTo(ll, 16);
  }, () => alert("ไม่สามารถดึงตำแหน่งปัจจุบันได้"));
}

// ==========================================
// 🎫 TICKETS & STATUS MANAGEMENT
// ==========================================
const STATUS = {
  new:      { label: 'รับเรื่องแล้ว',          icon: '🟠', color: '#f97316', bg: '#ffedd5' },
  doing:    { label: 'กำลังดำเนินการ',         icon: '🔵', color: '#0ea5e9', bg: '#e0f2fe' },
  done:     { label: 'แก้ไขเสร็จแล้ว',         icon: '🟢', color: '#10b981', bg: '#d1fae5' },
  rejected: { label: 'ไม่สามารถดำเนินการได้',  icon: '⚪', color: '#64748b', bg: '#f1f5f9' }
};
const TK = { items: [], filter: { elec: 'all', water: 'all', road: 'all', bldg: 'all' }, search: { elec: null, water: null, road: null, bldg: null } };
const tkSide = type => type === 'elec' ? 'e' : type === 'water' ? 'w' : type === 'road' ? 'r' : 'b';
const fmtTime = ms => ms ? new Date(+ms).toLocaleString('th-TH', { dateStyle: 'short', timeStyle: 'short' }) : '—';
const isActive = t => t && (t.status === 'new' || t.status === 'doing');
function myIds(){ try{ return JSON.parse(localStorage.getItem('omni_mine') || '[]'); }catch(e){ return []; } }
function rememberMine(id){ try{ localStorage.setItem('omni_mine', JSON.stringify([id].concat(myIds()).slice(0, 30))); }catch(e){} }

function ticketFor(type, id){
  return TK.items.filter(t => t.type === type && (t.ref === id || t.ref_code === id)).sort((a, b) => b.createdAt - a.createdAt)[0];
}
function tkColor(t){
  if(!t) return null;
  if(isActive(t)) return STATUS[t.status].color;
  if(t.status === 'done') return STATUS.done.color;
  return null;
}
function tkRadius(type, id){ return tkColor(ticketFor(type, id)) ? 9 : 6; }
function elecColor(p){ return tkColor(ticketFor('elec', p.id)) || (p.st === 'ok' ? '#eab308' : '#ef4444'); }
function waterColor(m){ return tkColor(ticketFor('water', m.id)) || (m.st === 'ok' ? '#0284c7' : '#ef4444'); }

function refreshMarkers(){
  e_poles.forEach(p => p.marker.setStyle({ fillColor: elecColor(p), radius: tkRadius('elec', p.id) }));
  w_meters.forEach(m => m.marker.setStyle({ fillColor: waterColor(m), radius: tkRadius('water', m.id) }));
  r_points.forEach(p => { if(p.marker) p.marker.setStyle({ fillColor: roadColor(p), radius: tkRadius('road', p.id) }); });
  b_points.forEach(p => { if(p.marker) p.marker.setStyle({ fillColor: bldgColor(p), radius: tkRadius('bldg', p.id) + 1 }); });
}

function refreshTicketViews(){
  elecUpdateUI();
  waterUpdateUI();
  roadUpdateUI();
  bldgUpdateUI();
}

async function createTicketAPI(type, data){
  try {
    const res = await fetch(API.ticketCreate, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({
        module: type,
        ref_code: data.ref || '',
        village: data.vil || '',
        location: data.loc || '',
        detail: data.detail || '',
        lat: data.lat,
        lng: data.lng,
        photos: data.photos || []
      })
    });
    const d = await res.json();
    if(d.ok && d.ticket){
      const t = formatTicketRecord(d.ticket);
      TK.items.unshift(t);
      refreshTicketViews();
      return t.id;
    }
  } catch(e){
    console.error(e);
  }
  // Fallback
  return null;
}

function formatTicketRecord(rec){
  return {
    id: rec.ticket_code || rec.id,
    type: rec.module,
    status: rec.status || 'new',
    createdAt: new Date(rec.created_at).getTime(),
    updatedAt: new Date(rec.updated_at).getTime(),
    ref: rec.ref_code || '',
    ref_code: rec.ref_code || '',
    vil: rec.village || '',
    loc: rec.location || '',
    detail: rec.detail || '',
    lat: rec.lat ? +rec.lat : null,
    lng: rec.lng ? +rec.lng : null,
    photos: Array.isArray(rec.photos) ? rec.photos : [],
    cost: rec.cost ? +rec.cost : 0,
    msgs: Array.isArray(rec.messages) ? rec.messages : []
  };
}

async function staffUpdate(id, status, text, cost){
  setSyncStatus('กำลังอัปเดตสถานะ...');
  try {
    const res = await fetch(API.ticketStaffUpdate(id), {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ status, reply: text, cost })
    });
    const d = await res.json();
    if(d.ok && d.ticket){
      const idx = TK.items.findIndex(x => x.id === id);
      if(idx >= 0) TK.items[idx] = formatTicketRecord(d.ticket);
      refreshTicketViews();
      setSyncStatus('🟢 บันทึกสถานะเรียบร้อยแล้ว');
      return;
    }
  } catch(e){}
  alert('ไม่สามารถอัปเดตข้อมูลได้ กรุณาลองใหม่');
}

async function citizenMsg(id, text){
  if(!text) return;
  setSyncStatus('กำลังส่งข้อความ...');
  try {
    const res = await fetch(API.ticketCitizenReply(id), {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ message: text })
    });
    const d = await res.json();
    if(d.ok && d.ticket){
      const idx = TK.items.findIndex(x => x.id === id);
      if(idx >= 0) TK.items[idx] = formatTicketRecord(d.ticket);
      refreshTicketViews();
      setSyncStatus('🟢 ส่งข้อความเรียบร้อย');
      return;
    }
  } catch(e){}
}

function saveTicket(id, ctx){
  const st = $(`st_${ctx}_${id}`), rp = $(`rp_${ctx}_${id}`), cs = $(`cs_${ctx}_${id}`);
  staffUpdate(id, st.value, rp.value.trim(), cs ? cs.value : null);
}
function sendCitizenMsg(id, ctx){
  const v = $(`cm_${ctx}_${id}`).value.trim();
  if(!v) return;
  citizenMsg(id, v);
}

function tkTitle(t){
  const ref = (t.ref || '').trim(), vil = (t.vil || '').trim();
  const v = vil && vil !== '—' ? ` (ม.${esc(String(vil).replace(/^(หมู่ที่|หมู่)\s*/, ''))})` : '';
  if(t.type === 'elec') return (ref ? 'เสาไฟ ' + esc(ref) : 'เสาไฟ') + v;
  if(t.type === 'water') return (ref ? 'มิเตอร์ ' + esc(ref) : 'ปัญหาประปา') + v;
  if(t.type === 'road') return (ref ? 'จุด ' + esc(ref) : 'ถนน/ทางเท้า') + v;
  return (ref ? 'อาคาร ' + esc(ref) : 'อาคาร/สิ่งปลูกสร้าง') + v;
}

function ticketCard(t, ctx){
  const s = STATUS[t.status] || STATUS.new;
  const where = t.loc ? ` — ${esc(t.loc)}` : '';
  const thread = (t.msgs || []).map(m => {
    const who = m.from === 'staff' ? '👷 เจ้าหน้าที่' : m.from === 'citizen' ? '👤 ประชาชน' : '';
    const style = m.from === 'staff' ? ` style="background:${s.bg}"` : '';
    return `<div class="bub ${m.from}"${style}>${who ? `<small>${who} · ${fmtTime(m.at)}</small>` : ''}${esc(m.text)}</div>`;
  }).join('');
  let ctrl = '';
  if(appRole === 'staff'){
    const costVal = t.cost > 0 ? +t.cost : '';
    ctrl = `<div class="tk-ctrl">
      <select id="st_${ctx}_${t.id}">${Object.keys(STATUS).map(k => `<option value="${k}" ${k === t.status ? 'selected' : ''}>${STATUS[k].icon} ${STATUS[k].label}</option>`).join('')}</select>
      <div style="display:flex;gap:6px;align-items:center"><label style="font-size:12px;color:var(--mute)">งบซ่อม (บาท)</label><input type="number" id="cs_${ctx}_${t.id}" min="0" placeholder="0" value="${costVal}"></div>
      <div class="row"><input type="text" id="rp_${ctx}_${t.id}" maxlength="300" placeholder="ตอบกลับประชาชน..."><button class="btn-sm btn-primary" onclick="saveTicket('${t.id}','${ctx}')">💬 ส่ง</button></div>
    </div>`;
  } else if(myIds().includes(t.id)){
    ctrl = `<div class="tk-ctrl"><div class="row"><input type="text" id="cm_${ctx}_${t.id}" maxlength="300" placeholder="พิมพ์ข้อความถึงเจ้าหน้าที่"><button class="btn-sm btn-primary" onclick="sendCitizenMsg('${t.id}','${ctx}')">ส่ง</button></div></div>`;
  }
  return `<div class="tk-card" style="border-left-color:${s.color}">
    <div class="tk-head"><b>#${esc(t.id)}</b><span class="pill" style="background:${s.color}">${s.icon} ${s.label}</span></div>
    <div><b>${tkTitle(t)}</b></div>
    <div class="meta">${esc(t.detail)}${where}</div>
    <div class="meta">แจ้งเมื่อ ${fmtTime(t.createdAt)} · อัปเดต ${fmtTime(t.updatedAt)}${t.cost > 0 ? ` · <span class="cost-tag">งบ ${t.cost.toLocaleString()} บ.</span>` : ''}</div>
    ${photoHtml(t)}
    ${thread}${ctrl}
  </div>`;
}

function setTkFilter(type, f){ TK.filter[type] = f; renderTickets(type); }

function renderTickets(type){
  const p = tkSide(type), box = $(p + '_repairList'), chips = $(p + '_chips');
  if(!box || !chips) return;
  const all = TK.items.filter(t => t.type === type).sort((a, b) => b.updatedAt - a.updatedAt);
  const f = TK.filter[type];
  const defs = [['all', '⚫ ทั้งหมด', all.length]].concat(Object.keys(STATUS).map(k => [k, STATUS[k].icon + ' ' + STATUS[k].label, all.filter(t => t.status === k).length]));
  chips.innerHTML = defs.map(d => `<button type="button" class="chip ${f === d[0] ? 'on' : ''}" onclick="setTkFilter('${type}','${d[0]}')">${d[1]} (${d[2]})</button>`).join('');
  const list = all.filter(t => f === 'all' || t.status === f).slice(0, 50);
  box.innerHTML = list.length ? list.map(t => ticketCard(t, 'l')).join('') : '<div class="note">ไม่มีรายการ</div>';
  renderTrack(type);
}

function renderTrack(type){
  const p = tkSide(type), box = $(p + '_trackResult');
  if(!box) return;
  const sid = TK.search[type];
  if(sid){
    const t = TK.items.find(x => x.id === sid);
    box.innerHTML = t ? ticketCard(t, 't') : `<div class="note">ไม่พบเลขเรื่อง ${esc(sid)}</div>`;
    return;
  }
  const mine = myIds().map(id => TK.items.find(t => t.id === id && t.type === type)).filter(Boolean);
  box.innerHTML = mine.length
    ? `<div class="note" style="margin:0 0 6px">เรื่องที่แจ้งไว้จากเครื่องนี้</div>` + mine.map(t => ticketCard(t, 't')).join('')
    : '<div class="note">ยังไม่มีเรื่องที่แจ้งจากเครื่องนี้</div>';
}

function trackTicket(type){
  const p = tkSide(type), prefix = type === 'elec' ? 'E-' : type === 'water' ? 'W-' : type === 'road' ? 'R-' : 'B-';
  let q = ($(p + '_track').value || '').trim().toUpperCase();
  if(/^\d+$/.test(q)) q = prefix + q.padStart(4, '0');
  TK.search[type] = q || null;
  renderTrack(type);
}

// ==========================================
// 💡 MODULE 1: ELEC
// ==========================================
const mapElec = L.map("map-elec").setView([LAT0, LNG0], 15);
L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {maxZoom:19}).addTo(mapElec);

let e_poles = [], e_lines = [];
let e_mode = null, e_drawPts = [], e_drawPreview = null;

function elecSetMode(m){
  e_mode = m; e_drawPts = [];
  if(e_drawPreview){ mapElec.removeLayer(e_drawPreview); e_drawPreview = null; }
  $('e_finLine').hidden = true; $('e_undoPt').hidden = true;
  mapElec.getContainer().style.cursor = m ? 'crosshair' : '';
  if(isLineKind('elec', m)){
    $('e_finLine').hidden = false; $('e_undoPt').hidden = false;
    $('e_hint').textContent = 'คลิกลากเส้นตามแนวทาง แล้วกด «เสร็จเส้น»';
  } else if(m){
    $('e_hint').textContent = 'คลิกบนแผนที่เพื่อวางตำแหน่ง';
  } else {
    $('e_hint').textContent = 'คลิกเสาไฟ / เส้นสายทาง เพื่อแก้ไขหรือลบ';
  }
}

async function addPole(ll, id, name, vil, st, syncToDb = true){
  const p = { id: id || CFG.ep + "-" + String(e_poles.length + 1).padStart(3,'0'), ll, name: name||"", vil: vil||1, st: st||"ok" };
  p.marker = L.circleMarker(ll, {
    radius: tkRadius('elec', p.id),
    fillColor: elecColor(p),
    color: "#fff", weight: 1.5, fillOpacity: 1
  }).addTo(mapElec);

  p.marker.bindPopup(() => {
    const d = document.createElement("div");
    if(appRole === 'citizen'){
      d.innerHTML = `<b>เสาไฟ: ${esc(p.id)}</b> (หมู่ ${esc(p.vil)})<br>${esc(p.name)}`;
      d.appendChild(pointAction('elec', p.id, p.vil, 'เลือกเสานี้เพื่อแจ้งซ่อม', '#ca8a04'));
      return d;
    }
    d.innerHTML = `<b>รหัสเสาไฟ: ${esc(p.id)}</b><br>
    ชื่อจุด: <input class="en" type="text" value="${esc(p.name)}" style="width:100%;margin:4px 0;padding:2px"><br>
    สถานะ: <select class="est">
      <option value="ok" ${p.st==="ok"?"selected":""}>ปกติ</option>
      <option value="fault" ${p.st==="fault"?"selected":""}>ไฟดับ</option>
    </select><br>
    <div style="margin-top:6px;display:flex;gap:4px">
      <button data-a="sv" style="background:#10b981;color:#fff;border:0;padding:3px 8px;border-radius:4px;cursor:pointer">บันทึก</button>
      <button data-a="del" style="background:#ef4444;color:#fff;border:0;padding:3px 8px;border-radius:4px;cursor:pointer">ลบ</button>
    </div>`;
    d.querySelector('[data-a="sv"]').onclick = async () => {
      p.name = d.querySelector('.en').value.trim();
      p.st = d.querySelector('.est').value;
      refreshMarkers();
      mapElec.closePopup();
      elecUpdateUI();
      await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({ module: 'elec', item_code: p.id, name: p.name, item_type: 'pole', village: String(p.vil), lat: p.ll[0], lng: p.ll[1], status: p.st })
      });
      setSyncStatus('🟢 บันทึกเสาไฟลงฐานข้อมูลแล้ว');
    };
    d.querySelector('[data-a="del"]').onclick = async () => {
      if(confirm("ลบเสาไฟนี้?")){
        mapElec.removeLayer(p.marker);
        e_poles = e_poles.filter(x => x !== p);
        mapElec.closePopup();
        elecUpdateUI();
        if(p.db_id) await fetch(API.itemDelete(p.db_id), { method: 'DELETE', headers: reqHeaders() });
      }
    };
    return d;
  });
  e_poles.push(p);
  elecUpdateUI();

  if(syncToDb){
    try {
      const res = await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({ module: 'elec', item_code: p.id, name: p.name, item_type: 'pole', village: String(p.vil), lat: p.ll[0], lng: p.ll[1], status: p.st })
      });
      const d = await res.json();
      if(d.ok && d.item) p.db_id = d.item.id;
    } catch(e){}
  }
}

mapElec.on('click', e => {
  const ll = [e.latlng.lat, e.latlng.lng];
  if(e_mode === 'pole'){ addPole(ll); elecSetMode(null); }
  else if(isLineKind('elec', e_mode)){
    e_drawPts.push(ll);
    if(e_drawPreview) mapElec.removeLayer(e_drawPreview);
    e_drawPreview = L.polyline(e_drawPts, lineOpt(e_mode, true)).addTo(mapElec);
  }
  else if(['zone','label','poi','village'].includes(e_mode)){ annFromClick('elec', e_mode, ll); }
});

function elecUndoPt(){ e_drawPts.pop(); if(e_drawPreview) e_drawPreview.setLatLngs(e_drawPts); }
function elecFinishLine(){
  if(!finishLine('elec', e_drawPts, e_mode)) return;
  elecSetMode(null);
}

function elecSimFail(){
  if(!e_poles.length) return alert("ยังไม่มีเสาไฟบนแผนที่");
  const p = e_poles[Math.floor(Math.random()*e_poles.length)];
  p.st = 'fault';
  p.marker.setStyle({ fillColor: '#ef4444' });
  createTicketAPI('elec', { ref: p.id, vil: p.vil, loc: '', detail: 'IoT ตรวจพบไฟดับอัตโนมัติ', lat: p.ll[0], lng: p.ll[1] });
  elecUpdateUI();
}

function elecUpdateUI(){
  $('e_k1').textContent = e_poles.length;
  $('e_k2').textContent = TK.items.filter(t => t.type === 'elec' && isActive(t)).length;
  renderTickets('elec');
  refreshMarkers();
}

async function elecSave(){
  setSyncStatus('กำลังบันทึกข้อมูล...');
  await fetch(API.syncAll, {
    method: 'POST',
    headers: reqHeaders(),
    body: JSON.stringify({
      cfg: CFG,
      elec: { items: e_poles.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], name: p.name, vil: p.vil, st: p.st })) }
    })
  });
  setSyncStatus('🟢 บันทึกข้อมูลไฟฟ้าลงฐานข้อมูลเรียบร้อย');
  alert("💾 บันทึกข้อมูลแผนที่ไฟฟ้าลงฐานข้อมูลเรียบร้อยแล้ว");
}

function exportElecCSV(){
  const csv = "id,lat,lng,village,name\n" + e_poles.map(p => [p.id, p.ll[0].toFixed(6), p.ll[1].toFixed(6), p.vil, `"${(p.name||'').replace(/"/g,'""')}"`].join(',')).join('\n');
  const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" });
  const a = document.createElement("a"); a.href = URL.createObjectURL(blob); a.download = `poles_${Date.now()}.csv`; a.click();
}

function importElecCSV(e){
  const file = e.target.files[0];
  if(!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    const lines = ev.target.result.replace(/^\uFEFF/,"").trim().split(/\r?\n/);
    lines.shift();
    lines.forEach(line => {
      const parts = line.split(',');
      if(parts.length >= 3){
        const id = parts[0].trim();
        const lat = parseFloat(parts[1]), lng = parseFloat(parts[2]);
        const vil = parseInt(parts[3]) || 1;
        const name = parts[4] ? parts[4].replace(/"/g,'').trim() : '';
        if(!isNaN(lat) && !isNaN(lng)) addPole([lat, lng], id, name, vil, 'ok');
      }
    });
    alert("นำเข้าเสาไฟเรียบร้อย");
  };
  reader.readAsText(file);
}

// ==========================================
// 💧 MODULE 2: WATER + TABWATER INTEGRATION
// ==========================================
const mapWater = L.map("map-water").setView([LAT0, LNG0], 15);
L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {maxZoom:19}).addTo(mapWater);

let w_meters = [], w_items = [], w_pipes = [];
let w_mode = null, w_drawPts = [], w_drawPreview = null;
let sys_meters_raw = [], sys_meter_markers = [];
let show_sys_meters = false;

function waterSetMode(m){
  w_mode = m; w_drawPts = [];
  if(w_drawPreview){ mapWater.removeLayer(w_drawPreview); w_drawPreview = null; }
  $('w_finLine').hidden = true; $('w_undoPt').hidden = true;
  mapWater.getContainer().style.cursor = m ? 'crosshair' : '';
  if(isLineKind('water', m)){
    $('w_finLine').hidden = false; $('w_undoPt').hidden = false;
    $('w_hint').textContent = 'คลิกลากเส้นตามแนวท่อ แล้วกด «เสร็จเส้น»';
  } else if(m){
    $('w_hint').textContent = 'คลิกบนแผนที่เพื่อวางตำแหน่งประปา';
  } else {
    $('w_hint').textContent = 'คลิกจุดมิเตอร์ / วาล์ว / แนวท่อ เพื่อจัดการ';
  }
}

async function addWaterMeter(ll, id, name, vil, st, syncToDb = true){
  const m = { id: id || CFG.wp + "-" + String(w_meters.length + 1).padStart(3,'0'), ll, name: name||"", vil: vil||1, st: st||"ok" };
  m.marker = L.circleMarker(ll, {
    radius: tkRadius('water', m.id),
    fillColor: waterColor(m),
    color: "#fff", weight: 1.5, fillOpacity: 1
  }).addTo(mapWater);

  m.marker.bindPopup(() => {
    const d = document.createElement("div");
    if(appRole === 'citizen'){
      d.innerHTML = `<b>มิเตอร์น้ำ: ${esc(m.id)}</b> (หมู่ ${esc(m.vil)})<br>${esc(m.name)}`;
      d.appendChild(pointAction('water', m.id, m.vil, 'เลือกมิเตอร์นี้เพื่อแจ้งปัญหา', '#0284c7'));
      return d;
    }
    d.innerHTML = `<b>มิเตอร์น้ำ: ${esc(m.id)}</b><br>
    ผู้ใช้น้ำ: <input class="wn" type="text" value="${esc(m.name)}" style="width:100%;margin:4px 0;padding:2px"><br>
    สถานะ: <select class="wst">
      <option value="ok" ${m.st==="ok"?"selected":""}>ปกติ</option>
      <option value="fault" ${m.st==="fault"?"selected":""}>น้ำไม่ไหล/ชำรุด</option>
    </select><br>
    <div style="margin-top:6px;display:flex;gap:4px">
      <button data-a="sv" style="background:#10b981;color:#fff;border:0;padding:3px 8px;border-radius:4px;cursor:pointer">บันทึก</button>
      <button data-a="del" style="background:#ef4444;color:#fff;border:0;padding:3px 8px;border-radius:4px;cursor:pointer">ลบ</button>
    </div>`;
    d.querySelector('[data-a="sv"]').onclick = async () => {
      m.name = d.querySelector('.wn').value.trim();
      m.st = d.querySelector('.wst').value;
      refreshMarkers();
      mapWater.closePopup();
      waterUpdateUI();
      await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({ module: 'water', item_code: m.id, name: m.name, item_type: 'meter', village: String(m.vil), lat: m.ll[0], lng: m.ll[1], status: m.st })
      });
      setSyncStatus('🟢 บันทึกมิเตอร์ลงฐานข้อมูลแล้ว');
    };
    d.querySelector('[data-a="del"]').onclick = async () => {
      if(confirm("ลบมิเตอร์นี้?")){
        mapWater.removeLayer(m.marker);
        w_meters = w_meters.filter(x => x !== m);
        mapWater.closePopup();
        waterUpdateUI();
        if(m.db_id) await fetch(API.itemDelete(m.db_id), { method: 'DELETE', headers: reqHeaders() });
      }
    };
    return d;
  });
  w_meters.push(m);
  waterUpdateUI();

  if(syncToDb){
    try {
      const res = await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({ module: 'water', item_code: m.id, name: m.name, item_type: 'meter', village: String(m.vil), lat: m.ll[0], lng: m.ll[1], status: m.st })
      });
      const d = await res.json();
      if(d.ok && d.item) m.db_id = d.item.id;
    } catch(e){}
  }
}

function addWaterAsset(ll, type, label, syncToDb = true){
  const color = type === 'valve' ? '#10b981' : type === 'tank' ? '#7c3aed' : '#ef4444';
  const radius = type === 'tank' ? 9 : 7;
  const item = { type, label, ll, marker: L.circleMarker(ll, { radius, fillColor: color, color: '#fff', weight: 1.5, fillOpacity: 0.95 }).addTo(mapWater) };
  item.marker.bindPopup(`<b>${type === 'valve' ? '🔵 ประตูน้ำ' : type === 'tank' ? '🏰 หอส่งน้ำ' : '⚠️ ท่อแตก/รั่ว'}</b><br>${esc(label)}`);
  w_items.push(item);
  waterUpdateUI();

  if(syncToDb){
    fetch(API.itemSave, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ module: 'water', item_code: 'W-ASSET-' + Date.now(), name: label, item_type: type, lat: ll[0], lng: ll[1] })
    });
  }
}

mapWater.on('click', e => {
  const ll = [e.latlng.lat, e.latlng.lng];
  if(w_mode === 'meter'){ addWaterMeter(ll); waterSetMode(null); }
  else if(w_mode === 'valve'){ const l = prompt("ชื่อหรือรหัสวาล์ว:","วาล์ว 01"); if(l) addWaterAsset(ll, 'valve', l); waterSetMode(null); }
  else if(w_mode === 'tank'){ const l = prompt("ชื่อหอส่งน้ำ/ถังพัก:","หอจ่ายน้ำหลัก"); if(l) addWaterAsset(ll, 'tank', l); waterSetMode(null); }
  else if(w_mode === 'leak'){ const l = prompt("ระบุจุดท่อแตก:","ท่อเมนแตกรั่ว"); if(l){ addWaterAsset(ll, 'leak', l); createTicketAPI('water', { ref: '', vil: '', loc: '', detail: l, lat: ll[0], lng: ll[1] }); } waterSetMode(null); }
  else if(isLineKind('water', w_mode)){
    w_drawPts.push(ll);
    if(w_drawPreview) mapWater.removeLayer(w_drawPreview);
    w_drawPreview = L.polyline(w_drawPts, lineOpt(w_mode, true)).addTo(mapWater);
  }
  else if(['zone','label','poi','village'].includes(w_mode)){ annFromClick('water', w_mode, ll); }
});

function waterUndoPt(){ w_drawPts.pop(); if(w_drawPreview) w_drawPreview.setLatLngs(w_drawPts); }
function waterFinishLine(){
  if(!finishLine('water', w_drawPts, w_mode)) return;
  waterSetMode(null);
}

function waterSimLeak(){
  const ll = [LAT0 + (Math.random() - 0.5) * 0.003, LNG0 + (Math.random() - 0.5) * 0.003];
  addWaterAsset(ll, 'leak', 'ท่อเมนแตกแรงดันลดฮวบ (จำลอง)');
  createTicketAPI('water', { ref: '', vil: '', loc: '', detail: 'ระบบตรวจวัดแรงดันแจ้งเตือนอัตโนมัติ', lat: ll[0], lng: ll[1] });
  waterUpdateUI();
}

function waterUpdateUI(){
  $('w_k1').textContent = w_meters.length;
  $('w_k2').textContent = w_items.filter(x => x.type === 'leak').length + TK.items.filter(t => t.type === 'water' && isActive(t)).length;
  renderTickets('water');
  refreshMarkers();
}

async function waterSave(){
  setSyncStatus('กำลังบันทึกข้อมูล...');
  await fetch(API.syncAll, {
    method: 'POST',
    headers: reqHeaders(),
    body: JSON.stringify({
      cfg: CFG,
      water: { items: w_meters.map(m => ({ id: m.id, lat: m.ll[0], lng: m.ll[1], name: m.name, vil: m.vil, st: m.st })) }
    })
  });
  setSyncStatus('🟢 บันทึกข้อมูลประปาลงฐานข้อมูลเรียบร้อย');
  alert("💾 บันทึกข้อมูลแผนที่ประปาลงฐานข้อมูลเรียบร้อยแล้ว");
}

function exportWaterCSV(){
  const csv = "id,lat,lng,village,name\n" + w_meters.map(m => [m.id, m.ll[0].toFixed(6), m.ll[1].toFixed(6), m.vil, `"${(m.name||'').replace(/"/g,'""')}"`].join(',')).join('\n');
  const blob = new Blob(["\uFEFF" + csv], { type: "text/csv;charset=utf-8;" });
  const a = document.createElement("a"); a.href = URL.createObjectURL(blob); a.download = `water_meters_${Date.now()}.csv`; a.click();
}

function importWaterCSV(e){
  const file = e.target.files[0];
  if(!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    const lines = ev.target.result.replace(/^\uFEFF/,"").trim().split(/\r?\n/);
    lines.shift();
    lines.forEach(line => {
      const parts = line.split(',');
      if(parts.length >= 3){
        const id = parts[0].trim();
        const lat = parseFloat(parts[1]), lng = parseFloat(parts[2]);
        const vil = parseInt(parts[3]) || 1;
        const name = parts[4] ? parts[4].replace(/"/g,'').trim() : '';
        if(!isNaN(lat) && !isNaN(lng)) addWaterMeter([lat, lng], id, name, vil, 'ok');
      }
    });
    alert("นำเข้ามิเตอร์น้ำเรียบร้อย");
  };
  reader.readAsText(file);
}

// ---- Overlay System Water Meters from Tabwater ----
function toggleSystemMeters(){
  show_sys_meters = !show_sys_meters;
  const btn = $('btnToggleSysMeters');
  if(!show_sys_meters){
    sys_meter_markers.forEach(m => mapWater.removeLayer(m));
    sys_meter_markers = [];
    btn.style.background = '#fff';
    btn.style.color = '#0f172a';
    return;
  }
  btn.style.background = '#0284c7';
  btn.style.color = '#fff';

  // Render markers around area
  sys_meters_raw.forEach((sm, idx) => {
    // Offset slightly for visualization if no lat/lng
    const offsetLat = LAT0 + ((idx % 10) - 5) * 0.0006 + (Math.random() - 0.5) * 0.0002;
    const offsetLng = LNG0 + (Math.floor(idx / 10) - 5) * 0.0006 + (Math.random() - 0.5) * 0.0002;
    const isOwe = sm.unpaid_count > 0;
    const m = L.circleMarker([offsetLat, offsetLng], {
      radius: 7,
      fillColor: isOwe ? '#dc2626' : '#0284c7',
      color: '#fff',
      weight: 1.5,
      fillOpacity: 0.95
    }).addTo(mapWater);

    const oweBadge = isOwe ? `<span class="sys-meter-owe">⚠️ ค้าง ${sm.unpaid_count} งวด (${sm.total_debt.toLocaleString()} บ.)</span>` : `<span class="sys-meter-badge">ปกติ</span>`;
    m.bindPopup(`
      <b>💧 มิเตอร์จริง: ${esc(sm.meternumber)}</b><br>
      ผู้ใช้น้ำ: <b>${esc(sm.user_name)}</b><br>
      ที่อยู่: ${esc(sm.meter_address)}<br>
      สถานะ: ${oweBadge}<br>
      <div style="margin-top:6px;display:flex;gap:4px;flex-wrap:wrap">
        <a href="${API.invoiceIndex}" target="_blank" style="background:#0284c7;color:#fff;padding:3px 8px;border-radius:4px;text-decoration:none;font-size:12px">📄 ใบแจ้งหนี้</a>
        ${isOwe ? `<a href="${API.oweReport}" target="_blank" style="background:#dc2626;color:#fff;padding:3px 8px;border-radius:4px;text-decoration:none;font-size:12px">🖨️ พิมพ์เตือนตัดน้ำ</a>` : ''}
      </div>
    `);
    sys_meter_markers.push(m);
  });
}

// ==========================================
// 🛣️ MODULE 3: ROAD / SIDEWALK / DRAIN
// ==========================================
const mapRoad = L.map("map-road").setView([LAT0, LNG0], 15);
L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {maxZoom:19}).addTo(mapRoad);

let r_points = [], r_lines = [];
let r_mode = null, r_drawPts = [], r_drawPreview = null;

function roadSetMode(m){
  r_mode = m; r_drawPts = [];
  if(r_drawPreview){ mapRoad.removeLayer(r_drawPreview); r_drawPreview = null; }
  $('r_finLine').hidden = true; $('r_undoPt').hidden = true;
  mapRoad.getContainer().style.cursor = m ? 'crosshair' : '';
  if(isLineKind('road', m)){
    $('r_finLine').hidden = false; $('r_undoPt').hidden = false;
    $('r_hint').textContent = 'คลิกลากเส้น แล้วกด «เสร็จเส้น»';
  } else if(m === 'issue'){
    $('r_hint').textContent = 'คลิกบนแผนที่เพื่อวางจุดแจ้งซ่อม';
  } else {
    $('r_hint').textContent = 'คลิกจุด / เส้น เพื่อจัดการ';
  }
}

function roadColor(pt){ return tkColor(ticketFor('road', pt.id)) || '#d97706'; }

async function addRoadPoint(ll, id, label, st, syncToDb = true){
  const pt = { id: id || ('RD-' + String(r_points.length + 1).padStart(3, '0')), ll, label: label || '', st: st || 'ok' };
  pt.marker = L.circleMarker(ll, {
    radius: tkRadius('road', pt.id),
    fillColor: roadColor(pt),
    color: '#fff', weight: 1.5, fillOpacity: 1
  }).addTo(mapRoad);

  pt.marker.bindPopup(() => {
    const d = document.createElement('div');
    if(appRole === 'citizen'){
      d.innerHTML = `<b>จุด: ${esc(pt.id)}</b><br>${esc(pt.label || 'จุดแจ้งซ่อมถนน/ทางเท้า/ท่อระบาย')}`;
      const btn = document.createElement('button');
      btn.textContent = 'ใช้จุดนี้เพื่อแจ้งซ่อม';
      btn.onclick = () => selectRoadPoint(pt.ll[0], pt.ll[1], pt.id);
      btn.style.cssText = 'background:#d97706;color:#fff;border:0;padding:4px 8px;border-radius:4px;cursor:pointer;margin-top:6px';
      d.appendChild(btn);
      return d;
    }
    d.innerHTML = `<b>จุด: ${esc(pt.id)}</b><br>
      รายละเอียด: <input class="rn" type="text" value="${esc(pt.label)}" style="width:100%;margin:4px 0;padding:2px"><br>
      <div style="margin-top:6px;display:flex;gap:4px">
        <button data-a="sv" style="background:#10b981;color:#fff;border:0;padding:3px 8px;border-radius:4px;cursor:pointer">บันทึก</button>
        <button data-a="del" style="background:#ef4444;color:#fff;border:0;padding:3px 8px;border-radius:4px;cursor:pointer">ลบ</button>
      </div>`;
    d.querySelector('[data-a="sv"]').onclick = async () => {
      pt.label = d.querySelector('.rn').value.trim();
      mapRoad.closePopup();
      roadUpdateUI();
      await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({ module: 'road', item_code: pt.id, name: pt.label, item_type: 'issue', lat: pt.ll[0], lng: pt.ll[1], status: pt.st })
      });
      setSyncStatus('🟢 บันทึกจุดถนนลงฐานข้อมูลแล้ว');
    };
    d.querySelector('[data-a="del"]').onclick = async () => {
      if(confirm('ลบจุดนี้?')){
        mapRoad.removeLayer(pt.marker);
        r_points = r_points.filter(x => x !== pt);
        mapRoad.closePopup();
        roadUpdateUI();
        if(pt.db_id) await fetch(API.itemDelete(pt.db_id), { method: 'DELETE', headers: reqHeaders() });
      }
    };
    return d;
  });
  r_points.push(pt);
  roadUpdateUI();

  if(syncToDb){
    try {
      const res = await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({ module: 'road', item_code: pt.id, name: pt.label, item_type: 'issue', lat: pt.ll[0], lng: pt.ll[1], status: pt.st })
      });
      const d = await res.json();
      if(d.ok && d.item) pt.db_id = d.item.id;
    } catch(e){}
  }
  return pt;
}

mapRoad.on('click', e => {
  const ll = [e.latlng.lat, e.latlng.lng];
  if(appRole === 'citizen' && !r_mode){
    selectRoadPoint(ll[0], ll[1], '');
    return;
  }
  if(r_mode === 'issue'){
    const label = prompt('รายละเอียดจุดแจ้งซ่อม:', 'ถนนชำรุด');
    if(label){
      const pt = addRoadPoint(ll, null, label, 'ok');
      createTicketAPI('road', { ref: pt.id, vil: '', loc: '', detail: label, lat: ll[0], lng: ll[1] });
    }
    roadSetMode(null);
  } else if(isLineKind('road', r_mode)){
    r_drawPts.push(ll);
    if(r_drawPreview) mapRoad.removeLayer(r_drawPreview);
    r_drawPreview = L.polyline(r_drawPts, lineOpt(r_mode, true)).addTo(mapRoad);
  } else if(['label','village'].includes(r_mode)){
    annFromClick('road', r_mode, ll);
  }
});

function roadUndoPt(){ r_drawPts.pop(); if(r_drawPreview) r_drawPreview.setLatLngs(r_drawPts); }
function roadFinishLine(){
  if(!finishLine('road', r_drawPts, r_mode)) return;
  roadSetMode(null);
}

function selectRoadPoint(lat, lng, ref){
  $('r_c_lat').value = lat; $('r_c_lng').value = lng; $('r_c_ref').value = ref || '';
  const box = $('r_c_pick');
  box.classList.add('sel');
  box.style.background = '#fef3c7'; box.style.borderColor = '#d97706';
  box.innerHTML = `✅ เลือกจุดแล้ว (${(+lat).toFixed(5)}, ${(+lng).toFixed(5)}) — ขั้นที่ 2: เลือกประเภทปัญหา แล้วกดส่ง`;
  const btn = $('r_c_btn'); btn.disabled = false; btn.style.opacity = 1;
  mapRoad.closePopup();
  $('r_c_form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

async function submitRoadCitizen(){
  if($('r_c_hp').value) return;
  const lat = parseFloat($('r_c_lat').value), lng = parseFloat($('r_c_lng').value);
  if(!isFinite(lat) || !isFinite(lng)) return alert('กรุณาแตะเลือกจุดบนแผนที่ก่อน');
  let ref = $('r_c_ref').value;
  if(!ref){
    const pt = await addRoadPoint([lat, lng], null, $('r_c_detail').value, 'ok');
    ref = pt.id;
  }
  const photos = (pendingPhotos.road || []).slice(0, 2);
  const id = await createTicketAPI('road', {
    ref, vil: '', loc: $('r_c_loc').value, detail: $('r_c_detail').value,
    lat, lng, photos: photos.map(p => p.dataUrl)
  });
  rememberMine(id);
  $('r_c_loc').value = '';
  clearPhotos('road');
  $('r_c_result').innerHTML = `<div class="tk-ok">✅ ได้รับเรื่องแล้ว เลขติดตามของคุณคือ <b>${esc(id)}</b></div>`;
}

function roadUpdateUI(){
  $('r_k1').textContent = r_points.length;
  $('r_k2').textContent = TK.items.filter(t => t.type === 'road' && isActive(t)).length;
  renderTickets('road');
  r_points.forEach(p => { if(p.marker) p.marker.setStyle({ fillColor: roadColor(p), radius: tkRadius('road', p.id) }); });
}

async function roadSave(){
  setSyncStatus('กำลังบันทึกข้อมูล...');
  await fetch(API.syncAll, {
    method: 'POST',
    headers: reqHeaders(),
    body: JSON.stringify({
      cfg: CFG,
      road: { items: r_points.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], label: p.label, st: p.st })) }
    })
  });
  setSyncStatus('🟢 บันทึกข้อมูลถนนลงฐานข้อมูลเรียบร้อย');
  alert('💾 บันทึกข้อมูลแผนที่ถนนลงฐานข้อมูลเรียบร้อยแล้ว');
}

// ==========================================
// 🏢 MODULE 4: BUILDING
// ==========================================
const mapBldg = L.map("map-bldg").setView([LAT0, LNG0], 15);
L.tileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}", {maxZoom:19}).addTo(mapBldg);

const BLDG_CATS = [
  '🏢 อาคารสำนักงาน/ที่ทำการ', '🏫 โรงเรียน/ศูนย์เด็กเล็ก', '🏥 สถานพยาบาล/รพ.สต.',
  '🛖 ศาลาประชาคม/ศาลาที่พัก', '🛕 วัด/ศาสนสถาน', '🏟️ สนามกีฬา/ลานกีฬา', '🏪 ตลาด/อาคารพาณิชย์',
  '🚻 ห้องน้ำสาธารณะ', '🏛️ หอประชุม', '🚒 โรงจอดรถ/บรรเทาสาธารณภัย', '🏠 อื่น ๆ'
];
const BLDG_COND = {
  good:   { label: 'ดี / ใช้งานได้ปกติ', color: '#8b5cf6', rank: 0 },
  fair:   { label: 'พอใช้ / ควรบำรุงรักษา', color: '#eab308', rank: 1 },
  poor:   { label: 'ทรุดโทรม / ต้องซ่อม', color: '#ec4899', rank: 2 },
  danger: { label: 'เสี่ยงอันตราย', color: '#7f1d1d', rank: 3 }
};
const condOf = c => BLDG_COND[c] || BLDG_COND.good;

let b_points = [], b_lines = [];
let b_mode = null, b_drawPts = [], b_drawPreview = null;

function bldgSetMode(m){
  b_mode = m; b_drawPts = [];
  if(b_drawPreview){ mapBldg.removeLayer(b_drawPreview); b_drawPreview = null; }
  $('b_finLine').hidden = true; $('b_undoPt').hidden = true;
  mapBldg.getContainer().style.cursor = m ? 'crosshair' : '';
  if(isLineKind('bldg', m)){
    $('b_finLine').hidden = false; $('b_undoPt').hidden = false;
    $('b_hint').textContent = 'คลิกลากเส้น แล้วกด «เสร็จเส้น»';
  } else if(m === 'building'){
    $('b_hint').textContent = 'คลิกบนแผนที่เพื่อวางจุดอาคาร';
  } else {
    $('b_hint').textContent = 'คลิกจุดอาคารเพื่อดูข้อมูล';
  }
}

function bldgColor(pt){ return tkColor(ticketFor('bldg', pt.id)) || condOf(pt.cond).color; }

async function addBldgPoint(ll, d, syncToDb = true){
  d = d || {};
  const pt = {
    id: d.id || ('BD-' + String(b_points.length + 1).padStart(3, '0')),
    ll, name: d.name || '', cat: d.cat || BLDG_CATS[0],
    vil: d.vil || '', year: d.year || '', area: d.area || '',
    cond: BLDG_COND[d.cond] ? d.cond : 'good',
    value: d.value || 0, owner: d.owner || '', note: d.note || ''
  };
  pt.marker = L.circleMarker(ll, {
    radius: tkRadius('bldg', pt.id) + 1, fillColor: bldgColor(pt),
    color: '#fff', weight: 2, fillOpacity: 1
  }).addTo(mapBldg);

  pt.marker.bindPopup(() => {
    const div = document.createElement('div');
    div.innerHTML = `<b>🏢 ${esc(pt.name || pt.id)}</b> (${esc(pt.id)})<br>
      ประเภท: ${esc(pt.cat)}<br>
      สภาพ: <span class="pill" style="background:${condOf(pt.cond).color}">${condOf(pt.cond).label}</span><br>
      ${pt.value ? `มูลค่า: ${(+pt.value).toLocaleString()} บาท<br>` : ''}
      <button onclick="selectBldgPoint(${pt.ll[0]}, ${pt.ll[1]}, '${pt.id}')" style="background:#7c3aed;color:#fff;border:0;padding:4px 8px;border-radius:4px;cursor:pointer;margin-top:6px">ใช้จุดนี้เพื่อแจ้งซ่อม</button>`;
    return div;
  });
  b_points.push(pt);
  bldgUpdateUI();

  if(syncToDb){
    try {
      const res = await fetch(API.itemSave, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify({
          module: 'bldg', item_code: pt.id, name: pt.name, item_type: 'building', category: pt.cat,
          village: pt.vil, lat: pt.ll[0], lng: pt.ll[1], condition: pt.cond, value_baht: pt.value, note: pt.note
        })
      });
      const resData = await res.json();
      if(resData.ok && resData.item) pt.db_id = resData.item.id;
    } catch(e){}
  }
  return pt;
}

mapBldg.on('click', e => {
  const ll = [e.latlng.lat, e.latlng.lng];
  if(appRole === 'citizen' && !b_mode){
    selectBldgPoint(ll[0], ll[1], '');
    return;
  }
  if(b_mode === 'building'){
    const nm = prompt('ชื่ออาคาร/สิ่งปลูกสร้าง:', '');
    if(nm){ addBldgPoint(ll, { name: nm.trim() }); }
    bldgSetMode(null);
  } else if(isLineKind('bldg', b_mode)){
    b_drawPts.push(ll);
    if(b_drawPreview) mapBldg.removeLayer(b_drawPreview);
    b_drawPreview = L.polyline(b_drawPts, lineOpt(b_mode, true)).addTo(mapBldg);
  } else if(['label','village'].includes(b_mode)){
    annFromClick('bldg', b_mode, ll);
  }
});

function bldgUndoPt(){ b_drawPts.pop(); if(b_drawPreview) b_drawPreview.setLatLngs(b_drawPts); }
function bldgFinishLine(){
  if(!finishLine('bldg', b_drawPts, b_mode)) return;
  bldgSetMode(null);
}

function selectBldgPoint(lat, lng, ref){
  $('b_c_lat').value = lat; $('b_c_lng').value = lng; $('b_c_ref').value = ref || '';
  const box = $('b_c_pick');
  box.classList.add('sel');
  box.style.background = '#ede9fe'; box.style.borderColor = '#7c3aed';
  box.innerHTML = `✅ เลือกจุดแล้ว (${(+lat).toFixed(5)}, ${(+lng).toFixed(5)}) — ขั้นที่ 2: เลือกประเภทปัญหา แล้วกดส่ง`;
  const btn = $('b_c_btn'); btn.disabled = false; btn.style.opacity = 1;
  mapBldg.closePopup();
  $('b_c_form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

async function submitBldgCitizen(){
  if($('b_c_hp').value) return;
  const lat = parseFloat($('b_c_lat').value), lng = parseFloat($('b_c_lng').value);
  if(!isFinite(lat) || !isFinite(lng)) return alert('กรุณาแตะเลือกจุดบนแผนที่ก่อน');
  let ref = $('b_c_ref').value;
  if(!ref){
    const pt = await addBldgPoint([lat, lng], { name: 'จุดอาคารทั่วไป' });
    ref = pt.id;
  }
  const photos = (pendingPhotos.bldg || []).slice(0, 2);
  const id = await createTicketAPI('bldg', {
    ref, vil: '', loc: $('b_c_loc').value, detail: $('b_c_detail').value,
    lat, lng, photos: photos.map(p => p.dataUrl)
  });
  rememberMine(id);
  $('b_c_loc').value = '';
  clearPhotos('bldg');
  $('b_c_result').innerHTML = `<div class="tk-ok">✅ ได้รับเรื่องแล้ว เลขติดตามของคุณคือ <b>${esc(id)}</b></div>`;
}

function renderBldgRegistry(){
  const tb = $('b_regBody'); if(!tb) return;
  const f = $('b_regFilter').value || 'all';
  const rows = b_points.filter(p => f === 'all' || p.cond === f);
  tb.innerHTML = rows.length ? rows.map(p => `<tr>
    <td><b>${esc(p.id)}</b></td><td>${esc(p.name || '—')}</td>
    <td>${esc(p.vil || '—')}</td>
    <td><span class="pill" style="background:${condOf(p.cond).color}">${condOf(p.cond).label}</span></td></tr>`).join('')
    : '<tr><td colspan="4" style="text-align:center;color:#64748b">ยังไม่มีข้อมูล</td></tr>';
}

function bldgUpdateUI(){
  $('b_k1').textContent = b_points.length;
  $('b_k2').textContent = TK.items.filter(t => t.type === 'bldg' && isActive(t)).length;
  $('b_k3').textContent = b_points.filter(p => p.cond === 'poor' || p.cond === 'danger').length;
  $('b_k4').textContent = b_points.reduce((s, p) => s + (+p.value || 0), 0).toLocaleString();
  renderTickets('bldg');
  renderBldgRegistry();
  b_points.forEach(p => { if(p.marker) p.marker.setStyle({ fillColor: bldgColor(p), radius: tkRadius('bldg', p.id) + 1 }); });
}

async function bldgSave(){
  setSyncStatus('กำลังบันทึกข้อมูล...');
  await fetch(API.syncAll, {
    method: 'POST',
    headers: reqHeaders(),
    body: JSON.stringify({
      cfg: CFG,
      bldg: { items: b_points.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], name: p.name, cat: p.cat, cond: p.cond, value: p.value })) }
    })
  });
  setSyncStatus('🟢 บันทึกข้อมูลอาคารลงฐานข้อมูลเรียบร้อย');
  alert('💾 บันทึกข้อมูลแผนที่อาคารลงฐานข้อมูลเรียบร้อยแล้ว');
}

function exportBldgCSV(){
  const head = 'id,name,category,village,condition,value,lat,lng';
  const rows = b_points.map(p => [p.id, `"${p.name}"`, `"${p.cat}"`, p.vil, p.cond, p.value, p.ll[0], p.ll[1]].join(','));
  const blob = new Blob(['\uFEFF' + head + '\n' + rows.join('\n')], { type: 'text/csv;charset=utf-8;' });
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `bldg_${Date.now()}.csv`; a.click();
}

function importBldgCSV(e){
  const file = e.target.files[0]; if(!file) return;
  const reader = new FileReader();
  reader.onload = ev => {
    const lines = ev.target.result.replace(/^\uFEFF/,"").trim().split(/\r?\n/);
    lines.shift();
    lines.forEach(line => {
      const parts = line.split(',');
      if(parts.length >= 7){
        const lat = parseFloat(parts[6]), lng = parseFloat(parts[7]);
        if(!isNaN(lat) && !isNaN(lng)){
          addBldgPoint([lat, lng], { id: parts[0], name: parts[1].replace(/"/g,''), cat: parts[2].replace(/"/g,''), vil: parts[3], cond: parts[4], value: +parts[5] });
        }
      }
    });
    alert('นำเข้าทะเบียนอาคารเรียบร้อย');
  };
  reader.readAsText(file);
}

// ==========================================
// 🧭 LINES, SHAPES & ANNOTATIONS SYSTEM
// ==========================================
const LT = {
  road:    { label:'🛣️ ถนนหลัก', color:'#ffffff', w:3.5 },
  alley:   { label:'〰️ ซอย', color:'#eab308', w:2, dash:'6 4' },
  pipeMain:{ label:'〰️ ท่อเมนหลัก', color:'#0284c7', w:4 },
  pipeSub: { label:'┈ ท่อแยก', color:'#38bdf8', w:2.5, dash:'6 4' },
  sidewalk:{ label:'🚶 ทางเท้า', color:'#a78bfa', w:2.5, dash:'6 4' },
  drain:   { label:'〰️ ท่อระบายน้ำ', color:'#78716c', w:2.5, dash:'6 4' },
  fence:   { label:'🧱 รั้ว/กำแพง', color:'#a3e635', w:3, dash:'8 3' },
  other:   { label:'📌 อื่น ๆ', color:'#f43f5e', w:2.5 }
};
const LT_SET = {
  elec:  ['road','alley','other'],
  water: ['pipeMain','pipeSub','road','alley','other'],
  road:  ['road','sidewalk','drain','alley','other'],
  bldg:  ['fence','road','alley','other']
};
const isLineKind = (mod, k) => !!k && (LT_SET[mod]||[]).includes(k);
const ltText = k => (LT[k] || LT.other).label;
function lineOpt(kind, preview){
  const t = LT[kind] || LT.other;
  return { color:t.color, weight: preview ? 3 : t.w, dashArray: preview ? '4 4' : (t.dash || null) };
}
function fmtLen(pts){
  let m = 0; for(let i = 1; i < pts.length; i++) m += L.latLng(pts[i-1]).distanceTo(L.latLng(pts[i]));
  return m >= 1000 ? (m/1000).toFixed(2) + ' กม.' : Math.round(m) + ' ม.';
}

const MOD = {
  elec:  { name:'💡 ไฟฟ้า', map:()=>mapElec, get:()=>e_lines, set:v=>{e_lines=v}, ann:[], tag:'#eab308' },
  water: { name:'💧 ประปา', map:()=>mapWater, get:()=>w_pipes, set:v=>{w_pipes=v}, ann:[], tag:'#0284c7' },
  road:  { name:'🛣️ ถนน', map:()=>mapRoad, get:()=>r_lines, set:v=>{r_lines=v}, ann:[], tag:'#d97706' },
  bldg:  { name:'🏢 อาคาร', map:()=>mapBldg, get:()=>b_lines, set:v=>{b_lines=v}, ann:[], tag:'#7c3aed' }
};

function addLine(mod, pts, kind, name, syncToDb = true){
  const M = MOD[mod];
  const rec = { pts, kind: kind || 'other', name: name || '', layer: null };
  rec.layer = L.polyline(pts, lineOpt(rec.kind)).addTo(M.map());
  if(rec.name) rec.layer.bindTooltip(esc(rec.name), { permanent:true, direction:'center', className:'ln-label' });
  rec.layer.on('click', ev => {
    if(appRole === 'citizen') return;
    if(confirm(`ลบสายทาง "${rec.name || ltText(rec.kind)}" ?`)){
      M.map().removeLayer(rec.layer);
      M.set(M.get().filter(x => x !== rec));
      if(rec.db_id) fetch(API.shapeDelete(rec.db_id), { method: 'DELETE', headers: reqHeaders() });
    }
  });
  M.get().push(rec);

  if(syncToDb){
    fetch(API.shapeSave, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ module: mod, shape_type: 'line', kind: rec.kind, name: rec.name, coordinates: pts })
    }).then(r => r.json()).then(d => { if(d.ok && d.shape) rec.db_id = d.shape.id; });
  }
  return rec;
}

function finishLine(mod, pts, kind){
  if(pts.length < 2){ alert('ต้องมีอย่างน้อย 2 จุด'); return false; }
  const nm = prompt(`ตั้งชื่อสายทาง (${ltText(kind)}):`, '');
  addLine(mod, pts.slice(), kind, (nm || '').trim());
  return true;
}
function lineSel(sel){
  const mod = sel.dataset.mod, v = sel.value; sel.value = '';
  if(v){
    if(mod === 'elec') elecSetMode(v);
    else if(mod === 'water') waterSetMode(v);
    else if(mod === 'road') roadSetMode(v);
    else if(mod === 'bldg') bldgSetMode(v);
  }
}

function annAdd(mod, rec, syncToDb = true){
  const M = MOD[mod], c = M.tag; let ly;
  if(rec.t === 'zone') ly = L.circle(rec.ll, { radius: rec.r || 100, color:c, fillColor:c, fillOpacity:0.2, dashArray:'4 4' });
  else if(rec.t === 'poi') ly = L.circleMarker(rec.ll, { radius:8, fillColor:c, color:'#fff', weight:2, fillOpacity:0.9 });
  else ly = L.marker(rec.ll, { icon: L.divIcon({ className:'', html:`<div style="background:#1e293b;color:#fff;padding:2px 6px;border-radius:3px;font-size:11px;font-weight:700;border:1px solid ${c};white-space:nowrap;transform:translate(-50%,-50%)">${esc(rec.text)}</div>` }) });
  ly.addTo(M.map()); rec.layer = ly;
  M.ann.push(rec);

  if(syncToDb){
    fetch(API.shapeSave, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({ module: mod, shape_type: rec.t, name: rec.text, coordinates: rec.ll, radius: rec.r || null })
    }).then(r => r.json()).then(d => { if(d.ok && d.shape) rec.db_id = d.shape.id; });
  }
  return rec;
}

function annFromClick(mod, mode, ll){
  if(mode === 'zone'){
    const r = prompt('ระบุรัศมีโซน (เมตร):', '100');
    if(r){ const n = prompt('ชื่อโซน:', ''); annAdd(mod, { t:'zone', ll, r:+r||100, text:(n||'').trim() }); }
  } else {
    const t = prompt('ข้อความ/ชื่อ:', 'จุดสนใจ');
    if(t) annAdd(mod, { t:mode, ll, text:t });
  }
  if(mod === 'elec') elecSetMode(null);
  else if(mod === 'water') waterSetMode(null);
  else if(mod === 'road') roadSetMode(null);
  else if(mod === 'bldg') bldgSetMode(null);
}

// Draw manager
let dm_mod = 'elec';
function openDrawMgr(mod){
  dm_mod = mod || dm_mod;
  const M = MOD[dm_mod], lines = M.get();
  $('drawModalTitle').textContent = '📋 จัดการสายทาง — ' + M.name;
  $('drawModalBody').innerHTML = lines.length
    ? `<table class="dm-t"><tr><th>#</th><th>ชื่อ</th><th>ประเภท</th><th>ระยะทาง</th><th>ลบ</th></tr>` +
      lines.map((l, i) => `<tr><td>${i+1}</td><td>${esc(l.name||'—')}</td><td>${esc(ltText(l.kind))}</td><td>${fmtLen(l.pts)}</td><td><button class="del" onclick="dmDel(${i})">🗑️ ลบ</button></td></tr>`).join('') + `</table>`
    : '<div class="note">ยังไม่มีสายทางในหมวดนี้</div>';
  $('drawModal').hidden = false;
}
function closeDrawMgr(){ $('drawModal').hidden = true; }
function dmDel(i){
  const M = MOD[dm_mod], l = M.get()[i];
  if(!l || !confirm('ลบเส้นนี้?')) return;
  M.map().removeLayer(l.layer);
  M.set(M.get().filter(x => x !== l));
  if(l.db_id) fetch(API.shapeDelete(l.db_id), { method: 'DELETE', headers: reqHeaders() });
  openDrawMgr(dm_mod);
}

// ==========================================
// 📊 MODULE 5: REPORTS & KPIS
// ==========================================
function getFilteredTickets(){
  const st = ($('rep_filter') && $('rep_filter').value) || 'all';
  const tp = ($('rep_type') && $('rep_type').value) || 'all';
  return TK.items.filter(t => (tp === 'all' || t.type === tp) && (st === 'all' || t.status === st)).sort((a, b) => b.updatedAt - a.updatedAt);
}

function refreshReport(){ renderReport(); }

function renderReport(){
  const all = TK.items || [];
  const filtered = getFilteredTickets();
  const org = orgLabel();
  if($('reportOrgLine')) $('reportOrgLine').textContent = '🏛️ ' + org + ' · ข้อมูลซิงค์สดจากระบบฐานข้อมูล';
  if($('printOrgReport')) $('printOrgReport').textContent = org;
  if($('printReportHead')) $('printReportHead').textContent = 'รายงานสรุปคำร้องแจ้งซ่อม — ' + org;
  if($('printDate')) $('printDate').textContent = new Date().toLocaleString('th-TH');

  const count = fn => all.filter(fn).length;
  const kpis = [
    { n: all.length, l: 'คำร้องทั้งหมด', c: '#1e293b' },
    { n: count(t => t.type === 'elec'), l: 'ไฟฟ้า', c: 'var(--elec-dark)' },
    { n: count(t => t.type === 'water'), l: 'ประปา', c: 'var(--water-dark)' },
    { n: count(t => t.type === 'road'), l: 'ถนน/ทางเท้า', c: '#d97706' },
    { n: count(t => t.type === 'bldg'), l: 'อาคาร', c: '#7c3aed' },
    { n: count(t => t.status === 'new'), l: 'รับเรื่องแล้ว', c: '#f97316' },
    { n: count(t => t.status === 'doing'), l: 'กำลังดำเนินการ', c: '#0ea5e9' },
    { n: count(t => t.status === 'done'), l: 'แก้ไขเสร็จ', c: '#10b981' }
  ];
  if($('reportKpis')){
    $('reportKpis').innerHTML = kpis.map(k => `<div class="report-kpi"><b style="color:${k.c}">${k.n}</b><span>${k.l}</span></div>`).join('');
  }

  const tbody = $('reportTbody');
  if(!tbody) return;
  tbody.innerHTML = filtered.length
    ? filtered.map(t => {
        const s = STATUS[t.status] || STATUS.new;
        const lastStaff = (t.msgs || []).filter(m => m.from === 'staff').pop();
        return `<tr>
          <td><b>#${esc(t.id)}</b></td><td>${t.type}</td><td>${esc(t.ref||'—')}</td><td>${esc(t.detail)}</td>
          <td><span class="pill" style="background:${s.color}">${s.icon} ${s.label}</span></td>
          <td>${fmtTime(t.createdAt)}</td><td>${fmtTime(t.updatedAt)}</td><td>${lastStaff ? esc(lastStaff.text) : '—'}</td>
          <td class="staff-only">${t.cost > 0 ? t.cost.toLocaleString() + ' บ.' : '—'}</td>
        </tr>`;
      }).join('')
    : '<tr><td colspan="9" style="text-align:center;color:#64748b">ไม่มีรายการ</td></tr>';

  const cards = $('reportCards');
  if(cards){
    cards.innerHTML = filtered.length ? filtered.slice(0, 30).map(t => ticketCard(t, 'r')).join('') : '<div class="note">ไม่มีรายการ</div>';
  }
}

function printReport(){
  renderReport();
  document.body.classList.add('print-report');
  setTimeout(() => {
    window.print();
    document.body.classList.remove('print-report');
  }, 150);
}

function exportReportCSV(){
  const rows = getFilteredTickets();
  const header = 'id,type,ref,detail,status,cost,created';
  const lines = rows.map(t => [t.id, t.type, `"${t.ref||''}"`, `"${t.detail||''}"`, t.status, t.cost||0, fmtTime(t.createdAt)].join(','));
  const blob = new Blob(['\uFEFF' + header + '\n' + lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
  const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `report_${Date.now()}.csv`; a.click();
}

// Master Save / Export / Import
async function saveAllSystem(){
  setSyncStatus('กำลังบันทึกทั้งระบบ...');
  try {
    await fetch(API.syncAll, {
      method: 'POST',
      headers: reqHeaders(),
      body: JSON.stringify({
        cfg: CFG,
        elec: { items: e_poles.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], name: p.name, vil: p.vil, st: p.st })) },
        water: { items: w_meters.map(m => ({ id: m.id, lat: m.ll[0], lng: m.ll[1], name: m.name, vil: m.vil, st: m.st })) },
        road: { items: r_points.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], label: p.label, st: p.st })) },
        bldg: { items: b_points.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], name: p.name, cat: p.cat, cond: p.cond, value: p.value })) }
      })
    });
    setSyncStatus('🟢 บันทึกข้อมูลทั้งระบบเข้าสู่ MySQL แล้ว');
    alert("💾 บันทึกข้อมูลทั้งระบบ (ไฟฟ้า + ประปา + ถนน + อาคาร + ค่าหน่วยงาน) ลงฐานข้อมูล MySQL เรียบร้อยแล้ว!");
  } catch(e){
    alert("บันทึกข้อมูลล้มเหลว");
  }
}

function exportFullSystemJSON(){
  const data = {
    version: "3.0",
    appName: "SMART_UTILITY_GIS",
    orgName: CFG.org,
    cfg: CFG,
    elec: { poles: e_poles.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], name: p.name, vil: p.vil, st: p.st })) },
    water: { meters: w_meters.map(m => ({ id: m.id, lat: m.ll[0], lng: m.ll[1], name: m.name, vil: m.vil, st: m.st })) },
    road: { points: r_points.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], label: p.label, st: p.st })) },
    bldg: { points: b_points.map(p => ({ id: p.id, lat: p.ll[0], lng: p.ll[1], name: p.name, cat: p.cat, cond: p.cond, value: p.value })) },
    tickets: TK.items
  };
  const blob = new Blob([JSON.stringify(data, null, 2)], { type: "application/json" });
  const a = document.createElement("a"); a.href = URL.createObjectURL(blob); a.download = `Smart_Utility_Backup_${Date.now()}.json`; a.click();
}

async function importFullSystemJSON(e){
  const file = e.target.files[0]; if(!file) return;
  const reader = new FileReader();
  reader.onload = async ev => {
    try {
      const json = JSON.parse(ev.target.result);
      setSyncStatus('กำลังนำเข้าข้อมูล...');
      const res = await fetch(API.importJson, {
        method: 'POST',
        headers: reqHeaders(),
        body: JSON.stringify(json)
      });
      const d = await res.json();
      if(d.ok){
        alert(d.message);
        location.reload();
      } else {
        alert(d.message || 'นำเข้าล้มเหลว');
      }
    } catch(err){ alert('ไฟล์ JSON ไม่ถูกต้อง'); }
  };
  reader.readAsText(file);
}

async function clearAllData(){
  if(!confirm("⚠️ คุณต้องการล้างข้อมูลทั้งหมดในฐานข้อมูลหรือไม่?")) return;
  try {
    await fetch(API.clearAll, { method: 'POST', headers: reqHeaders() });
    alert("ล้างข้อมูลในฐานข้อมูลเรียบร้อยแล้ว");
    location.reload();
  } catch(e){ alert("ล้างข้อมูลล้มเหลว"); }
}

// Photos
const pendingPhotos = { elec: [], water: [], road: [], bldg: [] };
function photoHtml(t){
  const ph = t.photos || [];
  if(!ph.length) return '';
  return '<div class="photo-row">' + ph.map(p => `<img class="photo-thumb" src="${p.url || p.dataUrl || p}" onclick="openPhoto(this.src)">`).join('') + '</div>';
}
function openPhoto(src){ $('photoLightboxImg').src = src; $('photoLightbox').hidden = false; }
function closePhoto(){ $('photoLightbox').hidden = true; }
function clearPhotos(type){
  pendingPhotos[type] = [];
  const prev = $(tkSide(type) + '_c_photoPrev'); if(prev) prev.innerHTML = '';
  const inp = $(tkSide(type) + '_c_photos'); if(inp) inp.value = '';
}
async function onPhotoPick(type, ev){
  const files = Array.from(ev.target.files || []);
  for(const f of files.slice(0, 2)){
    const reader = new FileReader();
    reader.onload = () => {
      pendingPhotos[type].push({ dataUrl: reader.result, name: f.name });
      renderPhotoPrev(type);
    };
    reader.readAsDataURL(f);
  }
}
function renderPhotoPrev(type){
  const box = $(tkSide(type) + '_c_photoPrev');
  if(!box) return;
  box.innerHTML = pendingPhotos[type].map((p, i) => `<div class="pv"><img src="${p.dataUrl}"><button type="button" onclick="pendingPhotos['${type}'].splice(${i},1);renderPhotoPrev('${type}')">×</button></div>`).join('');
}

// Point action
function pointAction(type, id, vil, label, color){
  const wrap = document.createElement('div');
  wrap.style.marginTop = '6px';
  const t = ticketFor(type, id);
  const btn = document.createElement('button');
  if(isActive(t)){
    btn.textContent = '🔎 ดูความคืบหน้า';
    btn.onclick = () => { $(tkSide(type) + '_track').value = t.id; trackTicket(type); (type === 'elec' ? mapElec : mapWater).closePopup(); };
    btn.style.cssText = 'background:#334155;color:#fff;border:0;padding:4px 8px;border-radius:4px;cursor:pointer';
  } else {
    btn.textContent = label;
    btn.onclick = () => selectPoint(type, id, vil);
    btn.style.cssText = `background:${color};color:#fff;border:0;padding:4px 8px;border-radius:4px;cursor:pointer`;
  }
  wrap.appendChild(btn);
  return wrap;
}

function selectPoint(type, id, vil){
  const p = tkSide(type);
  $(p + '_c_' + (type === 'elec' ? 'pole' : 'meter')).value = id;
  $(p + '_c_vil').value = vil;
  const box = $(p + '_c_pick');
  box.classList.add('sel');
  box.innerHTML = `✅ เลือกแล้ว: <b>${id}</b> (หมู่ ${vil}) — <a href="#" onclick="clearPick('${type}');return false">เปลี่ยน</a>`;
  const btn = $(p + '_c_btn'); btn.disabled = false; btn.style.opacity = 1;
  (type === 'elec' ? mapElec : mapWater).closePopup();
  $(p + '_c_form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function clearPick(type){
  const p = tkSide(type);
  $(p + '_c_' + (type === 'elec' ? 'pole' : 'meter')).value = '';
  const box = $(p + '_c_pick'); box.classList.remove('sel');
  box.innerHTML = '👆 แตะเลือกจุดบนแผนที่';
  const btn = $(p + '_c_btn'); btn.disabled = true; btn.style.opacity = .5;
}

async function submitElecCitizen(){
  const ref = $('e_c_pole').value;
  if(!ref) return alert('กรุณาเลือกเสาไฟก่อน');
  const photos = (pendingPhotos.elec || []).slice(0, 2);
  const id = await createTicketAPI('elec', {
    ref, vil: $('e_c_vil').value, loc: $('e_c_house').value, detail: $('e_c_detail').value,
    photos: photos.map(p => p.dataUrl)
  });
  rememberMine(id);
  $('e_c_house').value = '';
  clearPhotos('elec');
  clearPick('elec');
  $('e_c_result').innerHTML = `<div class="tk-ok">✅ ได้รับเรื่องแล้ว เลขติดตามของคุณคือ <b>${esc(id)}</b></div>`;
}

async function submitWaterCitizen(){
  const ref = $('w_c_meter').value;
  if(!ref) return alert('กรุณาเลือกมิเตอร์น้ำก่อน');
  const photos = (pendingPhotos.water || []).slice(0, 2);
  const id = await createTicketAPI('water', {
    ref, vil: $('w_c_vil').value, loc: $('w_c_loc').value, detail: $('w_c_detail').value,
    photos: photos.map(p => p.dataUrl)
  });
  rememberMine(id);
  $('w_c_loc').value = '';
  clearPhotos('water');
  clearPick('water');
  $('w_c_result').innerHTML = `<div class="tk-ok">✅ ได้รับเรื่องแล้ว เลขติดตามของคุณคือ <b>${esc(id)}</b></div>`;
}

function loadTickets(){ renderTickets(currentWork); }

// Gate
function openGate(toStaff){
  $('gateOrg').textContent = orgLabel();
  $('gate').hidden = false;
  if(toStaff) gateStaffStep(); else gateBack();
}
function gateEditOrg(){ $('gateOrgInput').value = CFG.org; $('gateEditBox').hidden = false; $('gateEditLink').hidden = true; }
function gateSaveOrg(){
  CFG.org = $('gateOrgInput').value.trim() || 'อบต.เสม็ดใต้';
  saveOrg(); $('gateEditBox').hidden = true; $('gateEditLink').hidden = false;
}
function gateBack(){ $('gateStep1').hidden = false; $('gateStep2').hidden = true; }
function gateStaffStep(){ $('gateStep1').hidden = true; $('gateStep2').hidden = false; $('gatePass').focus(); }
function gateCitizen(){ $('gate').hidden = true; setAppMode('citizen'); }
function gateLogin(){
  const p = $('gatePass').value.trim();
  if(p === (SERVER_CFG.staff_pass || '3350553') || p === '3350553'){
    STAFF_OK = true;
    sessionStorage.setItem('omni_staff_ok', '1');
    $('gate').hidden = true;
    setAppMode('staff');
  } else {
    $('gateErr').textContent = '❌ รหัสผ่านไม่ถูกต้อง';
  }
}

function openHelp(){ $('helpModal').hidden = false; }
function closeHelp(){ $('helpModal').hidden = true; }

// ==========================================
// 🚀 INITIALIZE FROM SERVER DATABASE
// ==========================================
async function initApp(){
  loadOrg();
  if(INITIAL_MODE === 'citizen'){
    $('gate').hidden = true;
    setAppMode('citizen');
  } else if(IS_AUTHENTICATED || STAFF_OK){
    $('gate').hidden = true;
    setAppMode('staff');
  } else {
    openGate(false);
  }

  setSyncStatus('กำลังโหลดข้อมูลจากฐานข้อมูล...');
  try {
    const res = await fetch(API.init);
    const data = await res.json();
    if(data.ok){
      // Items
      (data.items.elec || []).forEach(p => addPole([+p.lat, +p.lng], p.item_code, p.name, p.village, p.status, false));
      (data.items.water || []).forEach(m => addWaterMeter([+m.lat, +m.lng], m.item_code, m.name, m.village, m.status, false));
      (data.items.road || []).forEach(r => addRoadPoint([+r.lat, +r.lng], r.item_code, r.name, r.status, false));
      (data.items.bldg || []).forEach(b => addBldgPoint([+b.lat, +b.lng], { id: b.item_code, name: b.name, cat: b.category, cond: b.condition, value: b.value_baht }, false));

      // Shapes
      ['elec','water','road','bldg'].forEach(mod => {
        (data.shapes[mod].lines || []).forEach(l => addLine(mod, l.coordinates, l.kind, l.name, false));
        (data.shapes[mod].ann || []).forEach(a => annAdd(mod, { t: a.shape_type, ll: a.coordinates, text: a.name, r: a.radius }, false));
      });

      // Tickets
      TK.items = (data.tickets || []).map(formatTicketRecord);

      // System Tabwater
      if(data.system_water && data.system_water.meters){
        sys_meters_raw = data.system_water.meters;
        $('sysMeterCount').textContent = sys_meters_raw.length;
      }
      if(data.system_water && data.system_water.stations){
        data.system_water.stations.forEach(st => {
          if(st.lat && st.lng){
            L.circleMarker([st.lat, st.lng], { radius: 10, fillColor: '#7c3aed', color: '#fff', weight: 2, fillOpacity: 0.95 })
              .addTo(mapWater)
              .bindPopup(`<b>🏭 สถานีผลิตน้ำ: ${esc(st.name)}</b> (${esc(st.code)})<br>อุปกรณ์: ${st.devices_count} ชิ้น`);
          }
        });
      }
      if(data.system_water && data.system_water.pipelines){
        data.system_water.pipelines.forEach(p => {
          if(Array.isArray(p.pts) && p.pts.length >= 2){
            L.polyline(p.pts, { color: p.level === 'main' ? '#0284c7' : '#38bdf8', weight: p.level === 'main' ? 4 : 2.5 })
              .addTo(mapWater)
              .bindTooltip(esc(p.name || p.code), { permanent: false });
          }
        });
      }

      refreshTicketViews();
      setSyncStatus('🟢 เชื่อมต่อฐานข้อมูลระบบแล้ว');
    }
  } catch(e){
    console.error("Init failed:", e);
    setSyncStatus('⚠️ ออฟไลน์ (ใช้งานโหมดแคชในเครื่อง)', false);
  }
}

window.onload = () => {
  // Populate line dropdowns
  document.querySelectorAll('.tb-sel').forEach(sel => {
    const mod = sel.dataset.mod;
    const opts = (LT_SET[mod]||[]).slice(1);
    sel.innerHTML = '<option value="">➕ วาดเส้นประเภทอื่น…</option>' + opts.map(k => `<option value="${k}">${esc(ltText(k))}</option>`).join('');
  });
  initApp();
};
</script>

</body>
</html>
