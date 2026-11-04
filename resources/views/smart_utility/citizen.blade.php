<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
    <meta name="theme-color" content="#0284c7">
    <title>ระบบรับแจ้งเหตุสาธารณูปโภค — {{ $config->org_name }}</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --bg: #f8fafc;
            --card: #ffffff;
            --ink: #0f172a;
            --mute: #64748b;
            --line: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: "Sarabun", sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 540px;
            margin: 0 auto;
            padding: 16px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .app-header {
            background: linear-gradient(135deg, #0284c7, #1e3a8a);
            color: #ffffff;
            padding: 20px 18px;
            border-radius: 18px;
            box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.4);
            margin-bottom: 16px;
            text-align: center;
        }
        .app-header h1 {
            font-size: 19px;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .app-header p {
            font-size: 13px;
            color: #bae6fd;
            margin: 0;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: #e2e8f0;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 16px;
            gap: 4px;
        }
        .tab-btn {
            flex: 1;
            padding: 10px;
            border: 0;
            background: transparent;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        .tab-btn.active {
            background: #ffffff;
            color: #0284c7;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }

        /* Card */
        .card {
            background: var(--card);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            border: 1px solid var(--line);
            margin-bottom: 16px;
        }

        /* Form elements */
        .form-group { margin-bottom: 14px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: var(--ink);
            background: #fff;
            transition: border-color 0.2s;
        }
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Module Choice Buttons */
        .module-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 14px;
        }
        .mod-radio { display: none; }
        .mod-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 10px;
            border: 2px solid var(--line);
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: #fff;
        }
        .mod-radio:checked + .mod-label {
            border-color: var(--primary);
            background: #f0f9ff;
            color: var(--primary);
        }

        /* Map Pin Canvas */
        #pinMap {
            width: 100%;
            height: 220px;
            border-radius: 12px;
            margin-top: 8px;
            border: 1px solid var(--line);
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            border: 0;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(2, 132, 199, 0.35);
            font-family: inherit;
        }
        .btn-submit:hover {
            opacity: 0.95;
        }

        .btn-gps {
            background: #ffffff;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
        }

        /* Ticket Cards */
        .ticket-item {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
            background: #ffffff;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-open { background: #fee2e2; color: #b91c1c; }
        .badge-progress { background: #fef3c7; color: #b45309; }
        .badge-resolved { background: #dcfce7; color: #15803d; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="app-header">
            <h1><i class="fas fa-bullhorn me-1"></i> แจ้งเหตุสาธารณูปโภค</h1>
            <p>{{ $config->org_name }}</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="tabs">
            <button class="tab-btn active" id="tabReportBtn" onclick="switchTab('report')">
                <i class="fas fa-plus-circle me-1"></i> แจ้งเหตุใหม่
            </button>
            <button class="tab-btn" id="tabTrackBtn" onclick="switchTab('track')">
                <i class="fas fa-search me-1"></i> ติดตามสถานะ
            </button>
        </div>

        <!-- Tab 1: แจ้งเหตุใหม่ -->
        <div id="sectionReport">
            <div class="card">
                <form id="citizenForm">
                    <!-- Module Selection -->
                    <label class="form-label">เลือกประเภทสาธารณูปโภคที่ต้องการแจ้ง</label>
                    <div class="module-grid">
                        <input type="radio" name="citModule" id="modElec" value="elec" class="mod-radio" checked>
                        <label for="modElec" class="mod-label">💡 ไฟฟ้าดับ/เสาชำรุด</label>

                        <input type="radio" name="citModule" id="modWater" value="water" class="mod-radio">
                        <label for="modWater" class="mod-label">💧 ประปา/ท่อแตกรั่ว</label>

                        <input type="radio" name="citModule" id="modRoad" value="road" class="mod-radio">
                        <label for="modRoad" class="mod-label">🛣️ ถนนเป็นหลุม/ท่อตัน</label>

                        <input type="radio" name="citModule" id="modBldg" value="bldg" class="mod-radio">
                        <label for="modBldg" class="mod-label">🏢 อาคาร/สิ่งสาธารณะ</label>
                    </div>

                    <div class="form-group">
                        <label class="form-label">รายละเอียดปัญหา / สิ่งที่ต้องการให้แก้ไข *</label>
                        <textarea id="citDetail" class="form-control" rows="3" required placeholder="เช่น หลอดไฟทางไม่ติดมา 2 วันแล้ว มืดมากอันตราย..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">จุดสังเกต / สถานที่เกิดเหตุ</label>
                        <input type="text" id="citLocation" class="form-control" placeholder="เช่น ตรงข้ามบ้านเลขที่ 45 หรือ ปากซอย 3" />
                    </div>

                    <div class="form-group">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <label class="form-label" style="margin-bottom:0;">ตำแหน่งพิกัดแผนที่ (เลื่อนหมุดได้)</label>
                            <button type="button" class="btn-gps" id="btnGetGPS">
                                <i class="fas fa-crosshairs"></i> ตำแหน่งของฉัน
                            </button>
                        </div>
                        <div id="pinMap"></div>
                        <input type="hidden" id="citLat" value="{{ $config->center_lat }}" />
                        <input type="hidden" id="citLng" value="{{ $config->center_lng }}" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">ชื่อผู้แจ้ง (สำหรับติดต่อ)</label>
                        <input type="text" id="citName" class="form-control" placeholder="ชื่อ-นามสกุล หรือชื่อเล่น" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="tel" id="citTel" class="form-control" placeholder="08x-xxx-xxxx" />
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmitTicket">
                        <i class="fas fa-paper-plane me-1"></i> ส่งข้อมูลแจ้งเหตุให้กองช่าง
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab 2: ติดตามสถานะ -->
        <div id="sectionTrack" style="display: none;">
            <div class="card">
                <div class="form-group">
                    <label class="form-label">ค้นหาด้วยรหัสใบแจ้ง หรือเบอร์โทร</label>
                    <div style="display:flex; gap:6px;">
                        <input type="text" id="trackQuery" class="form-control" placeholder="เช่น E-0001 หรือเบอร์โทร" />
                        <button type="button" class="btn-gps" onclick="searchTicket()" style="white-space:nowrap;">ค้นหา</button>
                    </div>
                </div>

                <div id="trackResultList">
                    <div style="text-align:center; padding:30px 0; color:var(--mute); font-size:13px;">
                        <i class="fas fa-clipboard-list" style="font-size:28px; margin-bottom:8px; display:block;"></i>
                        กรุณาระบุรหัสใบแจ้งเพื่อติดตามความคืบหน้า
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="margin-top:auto; text-align:center; padding:16px 0; color:var(--mute); font-size:12px;">
            ระบบบริการสาธารณูปโภคอัจฉริยะ &copy; {{ date('Y') + 543 }} {{ $config->org_name }}
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
    <script>
        const API_BASE = "{{ url('smart-utility/api') }}";
        const centerLat = parseFloat("{{ $config->center_lat }}") || 13.690000;
        const centerLng = parseFloat("{{ $config->center_lng }}") || 101.200000;

        let pinMap = null;
        let dragMarker = null;

        document.addEventListener('DOMContentLoaded', function () {
            initPinMap();

            document.getElementById('btnGetGPS').addEventListener('click', function () {
                if (!navigator.geolocation) {
                    alert('เบราว์เซอร์ไม่รองรับ GPS');
                    return;
                }
                navigator.geolocation.getCurrentPosition(pos => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    updatePinPosition(lat, lng);
                    pinMap.flyTo([lat, lng], 17);
                }, err => {
                    alert('ไม่สามารถระบุพิกัดได้: ' + err.message);
                });
            });

            document.getElementById('citizenForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const selMod = document.querySelector('input[name="citModule"]:checked').value;
                const detail = document.getElementById('citDetail').value;
                const loc = document.getElementById('citLocation').value;
                const lat = parseFloat(document.getElementById('citLat').value);
                const lng = parseFloat(document.getElementById('citLng').value);
                const name = document.getElementById('citName').value;
                const tel = document.getElementById('citTel').value;

                let fullDetail = detail;
                if (name || tel) {
                    fullDetail += ` [ผู้แจ้ง: ${name || '-'} โทร: ${tel || '-'}]`;
                }

                const btn = document.getElementById('btnSubmitTicket');
                btn.disabled = true;
                btn.textContent = 'กำลังส่งข้อมูล...';

                fetch(`${API_BASE}/ticket`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        module: selMod,
                        location: loc,
                        detail: fullDetail,
                        lat: lat,
                        lng: lng
                    })
                })
                .then(res => res.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> ส่งข้อมูลแจ้งเหตุให้กองช่าง';

                    if (data.ok) {
                        alert(`ส่งข้อมูลเรียบร้อยแล้ว!\nรหัสติดตามเรื่องของคุณคือ: ${data.ticket.ticket_code}\nโปรดบันทึกรหัสไว้เพื่อติดตามผล`);
                        document.getElementById('citizenForm').reset();
                        switchTab('track');
                        document.getElementById('trackQuery').value = data.ticket.ticket_code;
                        searchTicket();
                    } else {
                        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> ส่งข้อมูลแจ้งเหตุให้กองช่าง';
                    alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
                });
            });
        });

        function initPinMap() {
            pinMap = L.map('pinMap').setView([centerLat, centerLng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(pinMap);

            dragMarker = L.marker([centerLat, centerLng], { draggable: true }).addTo(pinMap);
            dragMarker.on('dragend', function (e) {
                const pos = e.target.getLatLng();
                document.getElementById('citLat').value = pos.lat.toFixed(6);
                document.getElementById('citLng').value = pos.lng.toFixed(6);
            });
        }

        function updatePinPosition(lat, lng) {
            document.getElementById('citLat').value = lat.toFixed(6);
            document.getElementById('citLng').value = lng.toFixed(6);
            if (dragMarker) {
                dragMarker.setLatLng([lat, lng]);
            }
        }

        function switchTab(tab) {
            if (tab === 'report') {
                document.getElementById('tabReportBtn').classList.add('active');
                document.getElementById('tabTrackBtn').classList.remove('active');
                document.getElementById('sectionReport').style.display = 'block';
                document.getElementById('sectionTrack').style.display = 'none';
                setTimeout(() => pinMap.invalidateSize(), 150);
            } else {
                document.getElementById('tabTrackBtn').classList.add('active');
                document.getElementById('tabReportBtn').classList.remove('active');
                document.getElementById('sectionReport').style.display = 'none';
                document.getElementById('sectionTrack').style.display = 'block';
            }
        }

        function searchTicket() {
            const q = (document.getElementById('trackQuery').value || '').trim();
            const resultList = document.getElementById('trackResultList');
            if (!q) return;

            resultList.innerHTML = '<div style="text-align:center; padding:20px 0;">กำลังค้นหา...</div>';

            fetch(`${API_BASE}/init`)
                .then(res => res.json())
                .then(data => {
                    const tickets = (data.tickets || []).filter(t => 
                        (t.ticket_code && t.ticket_code.toLowerCase().includes(q.toLowerCase())) ||
                        (t.detail && t.detail.includes(q))
                    );

                    if (tickets.length === 0) {
                        resultList.innerHTML = '<div style="text-align:center; padding:20px 0; color:var(--mute);">ไม่พบข้อมูลที่ตรงกับคำค้นหา</div>';
                        return;
                    }

                    const statusMap = {
                        open: '<span class="badge badge-open">🔴 รอรับเรื่อง</span>',
                        in_progress: '<span class="badge badge-progress">🟡 กำลังดำเนินการ</span>',
                        resolved: '<span class="badge badge-resolved">🟢 ซ่อมเสร็จเรียบร้อย</span>',
                        cancelled: '<span class="badge">ยกเลิก</span>'
                    };

                    resultList.innerHTML = tickets.map(tk => `
                        <div class="ticket-item">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                                <strong style="font-size:14px; color:var(--ink);">${tk.ticket_code}</strong>
                                ${statusMap[tk.status] || tk.status}
                            </div>
                            <p style="font-size:13.5px; margin:0 0 6px; font-weight:600;">${tk.detail}</p>
                            <p style="font-size:12px; color:var(--mute); margin:0 0 8px;">
                                <i class="fas fa-map-marker-alt"></i> ${tk.location || 'ไม่ระบุสถานที่'}
                            </p>
                            ${tk.staff_note ? `
                                <div style="background:#f1f5f9; border-radius:8px; padding:8px 10px; font-size:12.5px; margin-top:8px;">
                                    <strong style="color:var(--primary);">บันทึกจากเจ้าหน้าที่:</strong> ${tk.staff_note}
                                </div>
                            ` : ''}
                        </div>
                    `).join('');
                });
        }
    </script>
</body>
</html>

