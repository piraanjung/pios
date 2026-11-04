@extends('layouts.admin1')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .gis-wrap { position: relative; height: 78vh; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.12); }
        #gisMap { width: 100%; height: 100%; }
        .gis-bar { position: absolute; z-index: 1000; display: flex; gap: 6px; padding: 6px; background: rgba(255,255,255,.96); border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.2); }
        .gis-bar.v { flex-direction: column; top: 12px; left: 56px; }
        .gis-bar.layers { top: 12px; right: 12px; }
        .gis-bar.stations { bottom: 24px; left: 56px; max-width: 70%; flex-wrap: wrap; }
        .gis-btn { width: 40px; height: 40px; border: 1px solid #e2e8f0; background: #fff; border-radius: 10px; font-size: 18px; cursor: pointer; line-height: 1; position: relative; }
        .gis-btn:hover { background: #f1f5f9; }
        .gis-btn.active { background: #0284c7; color: #fff !important; border-color: #0284c7; }
        .gis-btn.off { opacity: .35; }
        .gis-btn:disabled { opacity: .3; cursor: not-allowed; }
        .gis-btn.st { width: auto; padding: 0 12px; font-size: 13px; font-weight: 600; }
        .gis-btn[data-tip]:hover::after { content: attr(data-tip); position: absolute; left: 46px; top: 8px; background: #1e293b; color: #fff; font-size: 12px; padding: 3px 8px; border-radius: 6px; white-space: nowrap; z-index: 2000; font-weight: 400; }
        .gis-bar.layers .gis-btn[data-tip]:hover::after { left: auto; right: 0; top: 46px; }
        .gis-hint { position: absolute; z-index: 1000; top: 12px; left: 50%; transform: translateX(-50%); background: #1e293b; color: #fff; padding: 6px 14px; border-radius: 20px; font-size: 13px; display: none; }
        .gis-pin { font-size: 24px; text-align: center; line-height: 1; filter: drop-shadow(0 1px 2px rgba(0,0,0,.5)); }
        .gis-dot { border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 3px rgba(0,0,0,.6); }
        .gis-modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 100000; display: none; align-items: center; justify-content: center; }
        .gis-modal { background: #fff; border-radius: 12px; padding: 18px; width: 380px; max-width: 94vw; }
        .gis-modal label { font-size: 12px; font-weight: 600; margin: 8px 0 2px; display: block; }
        .gis-modal input, .gis-modal select { width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .gis-modal .acts { display: flex; gap: 8px; justify-content: flex-end; align-items: center; margin-top: 14px; }
        .gis-modal .acts button { border: 0; padding: 6px 14px; border-radius: 6px; color: #fff; }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">🗺️ แผนที่ระบบประปา (สถานี • อุปกรณ์ • จุดท่อ • แนวท่อ)</h5>
            <div>
                <a href="{{ route('tabwater.settings.stations.index') }}" class="btn btn-sm btn-outline-secondary">สถานี</a>
                <a href="{{ route('tabwater.settings.devices.index') }}" class="btn btn-sm btn-outline-secondary">อุปกรณ์</a>
                <a href="{{ route('tabwater.settings.pipe-nodes.index') }}" class="btn btn-sm btn-outline-secondary">จุดท่อ</a>
                <a href="{{ route('tabwater.settings.pipelines.index') }}" class="btn btn-sm btn-outline-secondary">แนวท่อ</a>
            </div>
        </div>

        <div class="gis-wrap">
            <div id="gisMap"></div>
            <div class="gis-hint" id="gisHint"></div>

            <div class="gis-bar v" id="toolBar">
                <button class="gis-btn active" data-mode="select" data-tip="เลือก / แก้ไข / ลากย้าย">🖐️</button>
                <button class="gis-btn" data-mode="station" data-tip="1. วางสถานีผลิตน้ำ">🏭</button>
                <button class="gis-btn need-st" data-mode="node" data-tip="2. วางจุดท่อ (ต้องเลือกสถานี)">⚪</button>
                <button class="gis-btn need-st" data-mode="pipe:main" data-tip="3. ท่อประธาน" style="color:#0284c7">━</button>
                <button class="gis-btn need-st" data-mode="pipe:sub_main" data-tip="3. ท่อเมนรอง" style="color:#16a34a">━</button>
                <button class="gis-btn need-st" data-mode="pipe:distribution" data-tip="3. ท่อจ่าย" style="color:#ca8a04">─</button>
                <button class="gis-btn" data-mode="delete" data-tip="ลบรายการ">🗑️</button>
                <button class="gis-btn" id="btnUndo" data-tip="ย้อนจุดล่าสุด (ขณะวาดท่อ)">↩️</button>
                <button class="gis-btn" id="btnCancel" data-tip="ยกเลิกการวาด">✖️</button>
            </div>

            <div class="gis-bar layers" id="layerBar">
                <button class="gis-btn" data-layer="stations" data-tip="แสดง/ซ่อน สถานี">🏭</button>
                <button class="gis-btn" data-layer="nodes" data-tip="แสดง/ซ่อน จุดท่อ">⚪</button>
                <button class="gis-btn" data-layer="main" data-tip="แสดง/ซ่อน ท่อประธาน" style="color:#0284c7">━</button>
                <button class="gis-btn" data-layer="sub_main" data-tip="แสดง/ซ่อน ท่อเมนรอง" style="color:#16a34a">━</button>
                <button class="gis-btn" data-layer="distribution" data-tip="แสดง/ซ่อน ท่อจ่าย" style="color:#ca8a04">─</button>
                <button class="gis-btn" id="btnFit" data-tip="ดูทั้งหมด">🎯</button>
            </div>

            <div class="gis-bar stations" id="stationBar"></div>
        </div>
    </div>

    <div class="gis-modal-bg" id="gisModalBg"><div class="gis-modal" id="gisModal"></div></div>

    <script>
        (function () {
            const URLS = {
                data: @json(route('tabwater.settings.gis.data')),
                base: @json(url('tabwater/settings/gis')),
                devices: @json(route('tabwater.settings.devices.index')),
            };
            const CSRF = @json(csrf_token());
            const COLORS = { main: '#0284c7', sub_main: '#16a34a', distribution: '#ca8a04' };
            const WEIGHTS = { main: 6, sub_main: 4, distribution: 2 };
            const LEVEL_TH = { main: 'ท่อประธาน', sub_main: 'ท่อเมนรอง', distribution: 'ท่อจ่าย' };
            const NODE_TYPES = { station: 'สถานี', main_junction: 'จุดแยกท่อประธาน', sub_junction: 'จุดแยกท่อย่อย', valve_point: 'จุดวาล์ว', tank: 'ถังเก็บน้ำ', end_point: 'ปลายท่อ' };
            const NODE_COLORS = { station: '#0f172a', main_junction: '#0284c7', sub_junction: '#16a34a', valve_point: '#dc2626', tank: '#7c3aed', end_point: '#ca8a04' };

            const map = L.map('gisMap').setView([13.75, 100.5], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);

            const groups = { stations: L.layerGroup().addTo(map), nodes: L.layerGroup().addTo(map), main: L.layerGroup().addTo(map), sub_main: L.layerGroup().addTo(map), distribution: L.layerGroup().addTo(map) };
            const draftLayer = L.layerGroup().addTo(map);

            let data = { stations: [], nodes: [], pipes: [] };
            let stationId = null;
            let mode = 'select';
            let draft = null;
            const visible = { stations: true, nodes: true, main: true, sub_main: true, distribution: true };
            let firstFit = true;

            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
            const $ = s => document.querySelector(s);
            const $$ = s => [...document.querySelectorAll(s)];

            async function api(method, path, body) {
                const res = await fetch(URLS.base + path, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: body ? JSON.stringify(body) : undefined,
                });
                const json = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const msg = json.message || Object.values(json.errors || {}).flat().join('\n') || 'เกิดข้อผิดพลาด';
                    alert(Object.values(json.errors || {}).flat().join('\n') || msg);
                    throw new Error(msg);
                }
                return json;
            }

            async function load() {
                const res = await fetch(URLS.data, { headers: { 'Accept': 'application/json' } });
                data = await res.json();
                if (stationId && !data.stations.some(s => s.id === stationId)) stationId = null;
                render();
            }

            function hint(text) {
                const h = $('#gisHint');
                h.style.display = text ? 'block' : 'none';
                h.textContent = text || '';
            }

            function setMode(m) {
                cancelDraft();
                mode = m;
                $$('#toolBar [data-mode]').forEach(b => b.classList.toggle('active', b.dataset.mode === m));
                map.getContainer().style.cursor = m === 'select' ? '' : 'crosshair';
                const hints = {
                    station: 'คลิกบนแผนที่เพื่อวางสถานีผลิตน้ำ',
                    node: 'คลิกบนแผนที่เพื่อวางจุดท่อของสถานีที่เลือก',
                    delete: 'คลิกที่รายการที่ต้องการลบ',
                };
                if (m.startsWith('pipe:')) hint('คลิกจุดท่อเริ่มต้น → คลิกแผนที่เพื่อวาดแนว → คลิกจุดท่อปลายทาง');
                else hint(hints[m] || '');
            }

            function cancelDraft() { draft = null; draftLayer.clearLayers(); }

            function updateToolState() {
                $$('.need-st').forEach(b => { b.disabled = !stationId; });
                if (!stationId && (mode === 'node' || mode.startsWith('pipe:'))) setMode('select');
            }

            function render() {
                Object.values(groups).forEach(g => g.clearLayers());
                const inScope = id => !stationId || id === stationId;

                data.stations.filter(s => s.lat != null && inScope(s.id)).forEach(s => {
                    const m = L.marker([s.lat, s.lng], {
                        draggable: mode === 'select',
                        icon: L.divIcon({ className: '', html: '<div class="gis-pin">🏭</div>', iconSize: [28, 28], iconAnchor: [14, 14] }),
                    }).bindTooltip(esc(s.name), { permanent: true, direction: 'bottom', offset: [0, 10] });
                    m.on('click', () => onItemClick('station', s));
                    m.on('dragend', async e => {
                        const p = e.target.getLatLng();
                        await api('PUT', '/station/' + s.id, { code: s.code, name: s.name, lat: p.lat, lng: p.lng });
                        load();
                    });
                    m.addTo(groups.stations);
                });

                data.nodes.filter(n => inScope(n.station_id)).forEach(n => {
                    const color = NODE_COLORS[n.type] || '#334155';
                    const m = L.marker([n.lat, n.lng], {
                        draggable: mode === 'select',
                        icon: L.divIcon({ className: '', html: `<div class="gis-dot" style="width:14px;height:14px;background:${color}"></div>`, iconSize: [14, 14], iconAnchor: [7, 7] }),
                    }).bindTooltip(`${esc(n.node_code)} · ${esc(n.name)}`);
                    m.on('click', () => onItemClick('node', n));
                    m.on('dragend', async e => {
                        const p = e.target.getLatLng();
                        await api('PUT', '/node/' + n.id, { node_code: n.node_code, name: n.name, type: n.type, remark: n.remark, lat: p.lat, lng: p.lng });
                        load();
                    });
                    m.addTo(groups.nodes);
                });

                data.pipes.filter(p => inScope(p.station_id) && Array.isArray(p.path_coordinates)).forEach(p => {
                    const line = L.polyline(p.path_coordinates, { color: COLORS[p.level], weight: WEIGHTS[p.level], opacity: .9 })
                        .bindTooltip(`${esc(p.pipe_code)} · ${esc(p.name)} (${p.size_mm} มม.)`, { sticky: true });
                    line.on('click', e => { L.DomEvent.stopPropagation(e); onItemClick('pipe', p); });
                    line.addTo(groups[p.level]);
                });

                Object.keys(groups).forEach(k => { if (!visible[k]) map.removeLayer(groups[k]); else if (!map.hasLayer(groups[k])) map.addLayer(groups[k]); });
                renderStationBar();
                updateToolState();

                if (firstFit) {
                    firstFit = false;
                    fitAll();
                }
            }

            function renderStationBar() {
                const bar = $('#stationBar');
                bar.innerHTML = '';
                const all = document.createElement('button');
                all.className = 'gis-btn st' + (stationId ? '' : ' active');
                all.textContent = 'ทุกสถานี';
                all.onclick = () => { stationId = null; render(); fitAll(); };
                bar.appendChild(all);
                data.stations.forEach(s => {
                    const b = document.createElement('button');
                    b.className = 'gis-btn st' + (stationId === s.id ? ' active' : '');
                    b.textContent = '🏭 ' + (s.code || s.name);
                    b.title = s.name;
                    b.onclick = () => { stationId = s.id; render(); fitAll(); };
                    bar.appendChild(b);
                });
            }

            function fitAll() {
                const pts = [];
                data.stations.filter(s => s.lat != null && (!stationId || s.id === stationId)).forEach(s => pts.push([s.lat, s.lng]));
                data.nodes.filter(n => !stationId || n.station_id === stationId).forEach(n => pts.push([n.lat, n.lng]));
                if (pts.length === 1) map.setView(pts[0], 16);
                else if (pts.length > 1) map.fitBounds(pts, { padding: [60, 60] });
            }

            function modal(title, fields, onSave, extraButtons = '') {
                const bg = $('#gisModalBg');
                let html = `<h6>${title}</h6>`;
                fields.forEach(f => {
                    html += `<label>${f.label}</label>`;
                    if (f.options) {
                        html += `<select name="${f.name}">` + Object.entries(f.options).map(([k, v]) => `<option value="${k}" ${String(f.value) === k ? 'selected' : ''}>${esc(v)}</option>`).join('') + '</select>';
                    } else {
                        html += `<input name="${f.name}" type="${f.type || 'text'}" value="${esc(f.value ?? '')}">`;
                    }
                });
                html += `<div class="acts">${extraButtons}<button type="button" style="background:#64748b" id="gmCancel">ยกเลิก</button><button type="button" style="background:#0284c7" id="gmSave">บันทึก</button></div>`;
                $('#gisModal').innerHTML = html;
                bg.style.display = 'flex';
                const close = () => { bg.style.display = 'none'; };
                $('#gmCancel').onclick = () => { close(); cancelDraft(); };
                $('#gmSave').onclick = async () => {
                    const v = {};
                    $$('#gisModal [name]').forEach(el => v[el.name] = el.value);
                    try { await onSave(v); close(); await load(); } catch (e) { /* alert shown in api() */ }
                };
            }

            function nextCode(prefix, list, key) {
                const n = list.filter(x => (x[key] || '').startsWith(prefix)).length + 1;
                return prefix + String(n).padStart(2, '0');
            }

            async function confirmDelete(type, item, label) {
                if (!confirm('ลบ ' + label + ' ?')) return;
                try { await api('DELETE', '/' + type + '/' + item.id); load(); } catch (e) { /* alert shown */ }
            }

            function onItemClick(type, item) {
                if (mode.startsWith('pipe:') && type === 'node') return pipeNodeClick(item);
                if (mode === 'delete') {
                    const label = type === 'station' ? item.name : type === 'node' ? item.node_code + ' ' + item.name : item.pipe_code + ' ' + item.name;
                    return confirmDelete(type, item, label);
                }
                if (mode !== 'select') return;
                if (type === 'station') editStation(item);
                else if (type === 'node') editNode(item);
                else editPipe(item);
            }

            function editStation(s) {
                const devs = (s.devices || []).map(d => esc(d.name)).join(', ') || 'ยังไม่มีอุปกรณ์';
                modal('🏭 สถานีผลิตน้ำ', [
                    { label: 'รหัส', name: 'code', value: s.code },
                    { label: 'ชื่อสถานี', name: 'name', value: s.name },
                ], v => api('PUT', '/station/' + s.id, { ...v, lat: s.lat, lng: s.lng }),
                    `<small style="margin-right:auto">อุปกรณ์: ${devs}<br><a href="${URLS.devices}?station_id=${s.id}">จัดการอุปกรณ์</a></small>`);
            }

            function nodeFields(n) {
                return [
                    { label: 'รหัสจุด', name: 'node_code', value: n.node_code },
                    { label: 'ชื่อ', name: 'name', value: n.name },
                    { label: 'ประเภท', name: 'type', value: n.type, options: NODE_TYPES },
                    { label: 'หมายเหตุ', name: 'remark', value: n.remark },
                ];
            }

            function editNode(n) {
                modal('⚪ จุดท่อ', nodeFields(n), v => api('PUT', '/node/' + n.id, { ...v, lat: n.lat, lng: n.lng }));
            }

            function pipeFields(p) {
                return [
                    { label: 'รหัสท่อ', name: 'pipe_code', value: p.pipe_code },
                    { label: 'ชื่อ', name: 'name', value: p.name },
                    { label: 'ขนาด (มม.)', name: 'size_mm', type: 'number', value: p.size_mm || 100 },
                    { label: 'วัสดุ', name: 'material', value: p.material || 'PVC' },
                ];
            }

            function editPipe(p) {
                modal(`➖ ${LEVEL_TH[p.level]}`, pipeFields(p), v => api('PUT', '/pipe/' + p.id, {
                    ...v, size_mm: +v.size_mm, from_node_id: p.from_node_id, to_node_id: p.to_node_id,
                    level: p.level, length_m: p.length_m, path_coordinates: p.path_coordinates,
                }));
            }

            function drawDraft() {
                draftLayer.clearLayers();
                if (!draft) return;
                const pts = [[draft.from.lat, draft.from.lng], ...draft.points];
                L.polyline(pts, { color: COLORS[draft.level], weight: WEIGHTS[draft.level], dashArray: '6 6' }).addTo(draftLayer);
            }

            function distance(path) {
                let d = 0;
                for (let i = 1; i < path.length; i++) d += L.latLng(path[i - 1]).distanceTo(L.latLng(path[i]));
                return Math.round(d);
            }

            function pipeNodeClick(n) {
                const level = mode.split(':')[1];
                if (!draft) {
                    draft = { level, from: n, points: [] };
                    hint('คลิกแผนที่เพื่อเพิ่มจุดโค้ง แล้วคลิกจุดท่อปลายทางเพื่อจบ');
                    return drawDraft();
                }
                if (n.id === draft.from.id) return;
                const path = [[draft.from.lat, draft.from.lng], ...draft.points, [n.lat, n.lng]];
                const d = draft;
                modal(`➖ บันทึก${LEVEL_TH[level]}`, pipeFields({ pipe_code: nextCode('P', data.pipes, 'pipe_code'), name: `${d.from.node_code} → ${n.node_code}` }),
                    async v => {
                        await api('POST', '/pipe', {
                            ...v, size_mm: +v.size_mm, station_id: stationId, level,
                            from_node_id: d.from.id, to_node_id: n.id, length_m: distance(path), path_coordinates: path,
                        });
                        cancelDraft();
                    });
            }

            map.on('click', e => {
                const { lat, lng } = e.latlng;
                if (mode === 'station') {
                    modal('🏭 เพิ่มสถานีผลิตน้ำ', [
                        { label: 'รหัส', name: 'code', value: nextCode('ST', data.stations, 'code') },
                        { label: 'ชื่อสถานี', name: 'name', value: '' },
                    ], async v => { const r = await api('POST', '/station', { ...v, lat, lng }); stationId = r.id; });
                } else if (mode === 'node' && stationId) {
                    modal('⚪ เพิ่มจุดท่อ', nodeFields({ node_code: nextCode('N', data.nodes, 'node_code'), name: '', type: 'sub_junction' }),
                        v => api('POST', '/node', { ...v, station_id: stationId, lat, lng }));
                } else if (mode.startsWith('pipe:') && draft) {
                    draft.points.push([lat, lng]);
                    drawDraft();
                }
            });

            $$('#toolBar [data-mode]').forEach(b => b.onclick = () => { setMode(b.dataset.mode); render(); });
            $('#btnUndo').onclick = () => { if (draft) { draft.points.pop(); drawDraft(); } };
            $('#btnCancel').onclick = () => { setMode('select'); render(); };
            $('#btnFit').onclick = fitAll;
            $$('#layerBar [data-layer]').forEach(b => b.onclick = () => {
                const k = b.dataset.layer;
                visible[k] = !visible[k];
                b.classList.toggle('off', !visible[k]);
                render();
            });
            $('#gisModalBg').addEventListener('mousedown', e => { if (e.target.id === 'gisModalBg') { e.target.style.display = 'none'; cancelDraft(); } });

            load();
        })();
    </script>
@endsection
