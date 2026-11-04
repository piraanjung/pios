{{-- JS ร่วมของแผนที่ถังขยะ ต้องใส่ใน section script (หลัง jQuery/select2/bootstrap) --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (() => {
        const cfg = @json($mapCfg);
        const NONE = 'none';
        const NORMAL_COLOR = '#167d69';
        const byId = id => document.getElementById(id);
        const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[c]);

        const mapEl = byId('map');
        const lat = parseFloat(mapEl.dataset.latitude), lng = parseFloat(mapEl.dataset.longitude);
        const map = L.map(mapEl).setView(Number.isFinite(lat) && Number.isFinite(lng) ? [lat, lng] : [13.7563, 100.5018], 17);
        const imagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: 'Tiles &copy; Esri' }).addTo(map);
        const street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' });
        L.control.layers({ 'ภาพถ่ายดาวเทียม': imagery, 'ถนนและอาคาร': street }, null, { collapsed: true }).addTo(map);

        const stage = byId('mapStage'), hint = byId('mapHint');
        const placeTool = byId('placeBinTool');
        const fitBtn = byId('fitAllBinsButton');
        const modalEl = byId('binModal'), modal = new bootstrap.Modal(modalEl);

        function showHint(text) { hint.textContent = text; hint.hidden = !text; }

        // ---------- ตัวกรองตามเหตุแจ้ง ----------
        const types = new Map();   // key -> {name,color}
        types.set(NONE, { name: 'ปกติ (ไม่มีเหตุแจ้ง)', color: NORMAL_COLOR });
        (cfg.issueTypes || []).forEach(t => types.set('i' + t.id, { name: t.name, color: t.color || '#dc2626' }));
        const active = new Set(types.keys());
        let entries = [];          // {marker, key, point}

        const keyOf = bin => bin.issue ? 'i' + bin.issue.type_id : NONE;
        const colorIcon = color => L.divIcon({
            className: 'map-pin-icon',
            html: `<i class="fas fa-trash-alt" style="color:${esc(color)}" aria-hidden="true"></i>`,
            iconSize: [30, 34], iconAnchor: [15, 30], popupAnchor: [0, -28]
        });
        const newIcon = L.divIcon({ className: 'map-pin-icon is-new', html: '<i class="fas fa-map-pin" aria-hidden="true"></i>', iconSize: [30, 34], iconAnchor: [15, 30], popupAnchor: [0, -28] });

        const renderFilter = () => {
            const counts = {};
            entries.forEach(e => counts[e.key] = (counts[e.key] || 0) + 1);
            byId('issueFilter').innerHTML = [...types].map(([k, t]) => `
                <span class="map-chip ${active.has(k) ? '' : 'off'}" data-key="${esc(k)}" role="button" title="กดเพื่อแสดง/ซ่อน">
                    <span class="dot" style="background:${esc(t.color)}"></span>${esc(t.name)} <span class="cnt">${counts[k] || 0}</span>
                </span>`).join('');
        };
        const applyFilter = () => {
            entries.forEach(e => active.has(e.key) ? e.marker.addTo(map) : map.removeLayer(e.marker));
            renderFilter();
        };
        byId('issueFilter').addEventListener('click', ev => {
            const chip = ev.target.closest('.map-chip');
            if (!chip) return;
            const k = chip.dataset.key;
            active.has(k) ? active.delete(k) : active.add(k);
            applyFilter();
        });

        // ---------- โหลดถัง ----------
        const loadBins = () => fetch(cfg.mapUrl, { headers: { Accept: 'application/json' } })
            .then(r => { if (!r.ok) throw new Error('โหลดตำแหน่งถังไม่สำเร็จ'); return r.json(); })
            .then(bins => {
                entries.forEach(e => map.removeLayer(e.marker));
                entries = [];
                bins.forEach(bin => {
                    const p = [Number(bin.latitude), Number(bin.longitude)];
                    if (bin.latitude === null || bin.longitude === null || !Number.isFinite(p[0]) || !Number.isFinite(p[1])) return;
                    const key = keyOf(bin);
                    if (!types.has(key)) { types.set(key, { name: bin.issue.name, color: bin.issue.color || '#dc2626' }); active.add(key); }
                    const t = types.get(key);
                    const u = bin.user || {};
                    const name = `${u.firstname ?? ''} ${u.lastname ?? ''}`.trim() || 'ไม่พบข้อมูลสมาชิก';
                    const addr = [u.address, u.user_subzone?.subzone_name && 'บ้าน ' + u.user_subzone.subzone_name, u.user_zone?.zone_name && 'หมู่ ' + u.user_zone.zone_name].filter(Boolean).join(' ');
                    const issueHtml = bin.issue
                        ? `<div class="mt-1"><span class="badge" style="background:${esc(t.color)}">${esc(bin.issue.name)}</span> <small>(${esc(bin.issue.status)})</small>${bin.issue.description ? '<div><small>' + esc(bin.issue.description) + '</small></div>' : ''}</div>`
                        : '';
                    const extra = typeof window.binMapPopupExtra === 'function' ? window.binMapPopupExtra(bin, esc) : '';
                    const marker = L.marker(p, { icon: colorIcon(t.color) }).bindPopup(`
                        <strong>${esc(bin.bin_code)}</strong>
                        <div>สมาชิก: ${esc(name)}</div>
                        <div>ที่อยู่: ${esc(addr || '-')}</div>
                        <div>สถานะถัง: ${esc(bin.status)}</div>${extra}${issueHtml}`);
                    entries.push({ marker, key, point: p });
                });
                fitBtn.disabled = entries.length === 0;
                applyFilter();
            })
            .catch(e => showHint(e.message));
        fitBtn.addEventListener('click', () => {
            const pts = entries.filter(e => active.has(e.key)).map(e => e.point);
            if (pts.length) map.fitBounds(pts, { padding: [36, 36], maxZoom: 17 });
        });

        // ---------- ปักหมุด → modal ----------
        let placing = false, pin = null, saved = false;
        const setPlacing = on => {
            placing = on;
            placeTool.classList.toggle('active', on);
            placeTool.setAttribute('aria-pressed', String(on));
            stage.classList.toggle('placing', on);
            showHint(on ? 'คลิกบนแผนที่เพื่อปักหมุดตำแหน่งถัง' : '');
        };
        placeTool.addEventListener('click', () => setPlacing(!placing));

        const memberSel = $('#member_id'), binSel = $('#bin_ids');
        memberSel.select2({ dropdownParent: $('#binModal'), width: '100%', placeholder: 'ค้นหา / เลือกสมาชิก', data: cfg.members || [] });
        binSel.select2({ dropdownParent: $('#binModal'), width: '100%', placeholder: 'เลือกรหัสถัง', closeOnSelect: false, data: [] });
        byId('noMemberNote').hidden = (cfg.members || []).length > 0;

        const loadBinOptions = async () => {
            binSel.empty().trigger('change');
            if (cfg.optionsNeedMember && !memberSel.val()) return;
            const url = cfg.optionsUrl + (cfg.optionsNeedMember ? '?member_id=' + encodeURIComponent(memberSel.val()) : '');
            const res = await fetch(url, { headers: { Accept: 'application/json' } });
            const opts = res.ok ? await res.json() : [];
            const add = o => {
                if (o.children) {
                    const g = $('<optgroup>').attr('label', o.text);
                    o.children.forEach(c => g.append(new Option(c.text, c.id)));
                    binSel.append(g);
                } else binSel.append(new Option(o.text, o.id));
            };
            opts.forEach(add);
            binSel.trigger('change');
            byId('binOptEmpty')?.remove();
            if (!binSel.find('option').length) {
                byId('binOptNote').insertAdjacentHTML('beforebegin', '<div class="text-warning text-xs mb-1" id="binOptEmpty">ไม่มีถังที่ลงทะเบียนไว้และยังไม่ได้ผูก</div>');
            }
        };
        memberSel.on('change', () => { if (cfg.optionsNeedMember) loadBinOptions(); });

        const removePin = () => { if (pin) { map.removeLayer(pin); pin = null; } };
        map.on('click', ev => {
            if (!placing) return;
            removePin();
            pin = L.marker(ev.latlng, { icon: newIcon }).addTo(map);
            saved = false;
            setPlacing(false);
            byId('binFormError').hidden = true;
            memberSel.val('').trigger('change');
            byId('location_description').value = '';
            byId('pin_lat').value = ev.latlng.lat.toFixed(7);
            byId('pin_lng').value = ev.latlng.lng.toFixed(7);
            loadBinOptions();
            modal.show();
        });
        modalEl.addEventListener('shown.bs.modal', () => memberSel.select2('open'));
        modalEl.addEventListener('hidden.bs.modal', () => { if (!saved) removePin(); });

        byId('mapBinForm').addEventListener('submit', async () => {
            const err = byId('binFormError'), btn = byId('binSubmit');
            const refs = binSel.val() || [];
            if (!memberSel.val()) { err.textContent = 'กรุณาเลือกสมาชิก'; err.hidden = false; return; }
            if (!refs.length) { err.textContent = 'กรุณาเลือกถังอย่างน้อย 1 ใบ'; err.hidden = false; return; }
            btn.disabled = true;
            try {
                const res = await fetch(cfg.storeUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': cfg.csrf },
                    body: JSON.stringify({
                        member_id: memberSel.val(), refs,
                        latitude: byId('pin_lat').value, longitude: byId('pin_lng').value,
                        location_description: byId('location_description').value || null
                    })
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    const first = data.errors ? Object.values(data.errors).flat()[0] : null;
                    throw new Error(first || data.message || 'บันทึกไม่สำเร็จ');
                }
                saved = true;
                removePin();
                modal.hide();
                await loadBins();
                const pts = entries.filter(e => active.has(e.key)).slice(-refs.length).map(e => e.point);
                if (pts.length) map.setView(pts[0], Math.max(map.getZoom(), 19));
                showHint(data.message || 'บันทึกเรียบร้อย');
                setTimeout(() => !placing && showHint(''), 4000);
            } catch (e) {
                err.textContent = e.message; err.hidden = false;
            } finally {
                btn.disabled = false;
            }
        });
        // ---------- เต็มจอ ----------
        byId('fullscreenMapButton').addEventListener('click', async () => {
            try {
                document.fullscreenElement === stage ? await document.exitFullscreen() : await stage.requestFullscreen();
            } catch { showHint('เบราว์เซอร์นี้ไม่รองรับโหมดเต็มจอ'); }
        });
        document.addEventListener('fullscreenchange', () => {
            byId('fullscreenMapIcon').className = document.fullscreenElement === stage ? 'fas fa-compress' : 'fas fa-expand';
            map.invalidateSize();
        });

        renderFilter();
        loadBins();
    })();
</script>
