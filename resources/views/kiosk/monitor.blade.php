<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Eco-Scan Neumorphism</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    <!-- Fonts (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <style>
        /* ... (CSS เดิมทั้งหมดของคุณ ไม่ต้องแก้) ... */

        :root { --bg-light: #f0f2f5; --primary-light: #8dc1c7; --primary-dark: #64a6a8; --text-dark: #333; --text-light: #666; --shadow-light: #ffffff; --shadow-dark: #d6d9df; }
        body { font-family: 'Kanit', sans-serif; background: var(--bg-light); color: var(--text-dark); margin: 0; padding: 0; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; min-height: 100vh; overflow: hidden; }
        .neumo-card, .neumo-btn { background: var(--bg-light); border-radius: 25px; box-shadow: 8px 8px 15px var(--shadow-dark), -8px -8px 15px var(--shadow-light); transition: all 0.2s ease-in-out; }
        .neumo-btn { display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; color: var(--text-light); font-size: 1.1rem; font-weight: 500; padding: 15px 25px; text-decoration: none; }
        .neumo-btn:active { box-shadow: inset 5px 5px 10px var(--shadow-dark), inset -5px -5px 10px var(--shadow-light); color: var(--primary-dark); }
        .top-bar { width: 100%; max-width: 400px; padding: 20px 0; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 10; }
        .top-bar .icon-btn { width: 50px; height: 50px; border-radius: 50%; font-size: 1.2rem; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light); }
        .top-bar .icon-btn:active { box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); }
        .app-title { font-size: 1.3rem; font-weight: 600; color: var(--text-dark); }
        .camera-circle-container { width: 250px; height: 250px; border-radius: 50%; overflow: hidden; position: relative; margin: 30px auto 20px auto; background: var(--bg-light); box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light); display: flex; justify-content: center; align-items: center; border: 4px solid var(--bg-light); }
        #monitor-image { width: 100%; height: 100%; object-fit: cover; position: absolute; }
        .scan-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; border: 3px solid var(--primary-light); box-shadow: 0 0 15px rgba(141, 193, 199, 0.5); }
        .scan-focus-mark { position: absolute; width: 30px; height: 30px; border: 2px solid var(--primary-light); opacity: 0.7; }
        .scan-focus-mark.top-left { top: 15%; left: 15%; border-right: none; border-bottom: none; }
        .scan-focus-mark.bottom-right { bottom: 15%; right: 15%; border-left: none; border-top: none; }
        .status-text { color: var(--text-light); font-size: 1rem; margin-bottom: 25px; }
        .result-card { max-width: 300px; margin: 0 auto 30px auto; padding: 20px; background: var(--bg-light); border-radius: 20px; box-shadow: inset 5px 5px 10px var(--shadow-dark), inset -5px -5px 10px var(--shadow-light); color: var(--text-dark); display: none; transition: all 0.3s ease-in-out; }
        .label-text { font-size: 1.4rem; font-weight: 600; margin-bottom: 5px; }
        .confidence-text { font-size: 0.9rem; color: var(--primary-dark); }
        .action-buttons { display: flex; justify-content: space-around; width: 100%; max-width: 300px; padding-bottom: 30px; }
        .action-buttons .neumo-btn { width: 80px; height: 80px; border-radius: 50%; font-size: 1.5rem; position: relative; }
        .action-buttons .neumo-btn.primary { background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-dark) 100%); color: white; box-shadow: 8px 8px 15px rgba(100, 166, 168, 0.4), -8px -8px 15px var(--shadow-light); }
        .action-buttons .neumo-btn.primary:active { box-shadow: inset 5px 5px 10px var(--primary-dark), inset -5px -5px 10px var(--primary-light); }
        .item-count-badge { position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold; border: 2px solid var(--bg-light); }
        .summary-panel { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 350px; padding: 25px; background: var(--bg-light); border-radius: 25px; box-shadow: 10px 10px 20px var(--shadow-dark), -10px -10px 20px var(--shadow-light); z-index: 1000; display: none; flex-direction: column; gap: 15px; }
        .summary-header { font-size: 1.3rem; font-weight: 600; color: var(--primary-dark); text-align: center; }
        .summary-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid var(--shadow-dark); color: var(--text-dark); }
        .summary-item:last-child { border-bottom: none; }
        .summary-close-btn { width: 100%; padding: 12px; font-size: 1rem; color: var(--text-light); border-radius: 15px; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light); }
        .summary-close-btn:active { box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); }
        .slip-container { background: white; width: 320px; padding: 0; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1); position: relative; font-family: 'Kanit', sans-serif; }
        .slip-header { background: #4E9A9A; color: white; padding: 20px; text-align: center; }
        .slip-body { padding: 20px; background-image: radial-gradient(#eee 1px, transparent 1px); background-size: 20px 20px; }
        .success-icon { width: 60px; height: 60px; background: #27ae60; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: -50px auto 10px auto; border: 5px solid white; }
        .slip-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 0.9rem; color: #666; }
        .slip-total { border-top: 1px dashed #ccc; margin-top: 15px; padding-top: 15px; display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; color: #2C3E50; }
        .qr-mock { width: 100px; height: 100px; margin: 20px auto; background: #f8f8f8; border: 1px solid #eee; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 0.7rem; }
        .slip-footer { text-align: center; font-size: 0.7rem; color: #aaa; padding-bottom: 20px; }
        .user-chip { display: flex; align-items: center; gap: 12px; background: var(--bg-light); padding: 6px 20px 6px 6px; border-radius: 40px; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light); text-decoration: none; }
        .user-chip img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--bg-light); box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1); }
        .user-info { display: flex; flex-direction: column; line-height: 1.2; text-align: left; }
        .user-name { font-size: 0.95rem; font-weight: 600; color: var(--primary-dark); }
        .user-id { font-size: 0.75rem; color: var(--text-light); }
        .top-bar { width: 90%; max-width: 400px; padding: 20px 0; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>

<body>

    <div class="top-bar">
        <div class="user-chip">
            <img src="{{ !auth()->user()->image ? asset('storage/' . auth()->user()->image) : 'https://profile.line-scdn.net/' . auth()->user()->image }}"
                alt="Profile">
            <div class="user-info">
                <span class="user-name">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</span>
                <span class="user-id">ID: {{ auth()->user()->code ?? auth()->user()->id }}</span>
            </div>
        </div>
        <button class="neumo-btn icon-btn" onclick="toggleLog()" style="width:45px; height:45px; font-size:1rem;">📋</button>
    </div>

    <div class="camera-circle-container" id="camera-display">
        <img id="monitor-image" src="https://via.placeholder.com/250x250?text=Scan+Item" crossorigin="anonymous" />
        <div class="scan-overlay">
            <div class="scan-focus-mark top-left"></div>
            <div class="scan-focus-mark bottom-right"></div>
        </div>
    </div>

    <div class="status-text" id="main-status-text">แตะปุ่ม "สแกน" เพื่อเริ่ม</div>

    <div class="result-card" id="result-box">
        <div class="label-text" id="label-result">OBJECT DETECTED</div>
        <div class="confidence-text" id="conf-result">0% Confidence</div>
    </div>

    <div class="action-buttons">
        <button class="neumo-btn" onclick="toggleCart()">
            🛒<div class="item-count-badge" id="total-badge">0</div>
        </button>
        <button class="neumo-btn primary" id="scan-button" onclick="startScanProcess()">
            ♻️
        </button>
        <button class="neumo-btn">
            💰
        </button>
    </div>

    <div class="summary-panel" id="summary-panel">
        <div class="summary-header">📦 รายการสะสมของคุณ</div>
        <div id="summary-items">
            <div style="text-align:center; color:var(--text-light);">ยังไม่มีข้อมูล...</div>
        </div>
        <button class="neumo-btn summary-close-btn" onclick="toggleCart()">ปิด</button>
    </div>

    <div id="log-box" style="position:fixed; bottom:0; left:0; width:100%; height:150px; background:rgba(0,0,0,0.8); color:#00ff00; overflow-y:scroll; font-family:monospace; font-size:0.7em; padding:10px; display:none; z-index:1001;">
        <div id="log-content">--- ระบบพร้อมทำงาน ---</div>
    </div>

    <button class="neumo-btn" onclick="finishSession()" style="background: #e74c3c; color: white; margin-top: 20px;">
        🏁 จบการทำงาน (Finish)
    </button>

    <button class="neumo-btn" onclick="injectMockData()" style="background: #95a5a6; color: white; margin-top: 10px; font-size: 0.8rem; padding: 10px;">
        🧪 เพิ่มข้อมูลจำลอง (Test Data)
    </button>

    <div id="summary-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(254, 245, 245, 0.95); z-index:200; flex-direction:column; align-items:center; justify-content:center; backdrop-filter: blur(10px);">
        <div class="slip-container">
            <div class="slip-header">
                <h3 style="margin:0">ECO-SCAN SLIP</h3>
                <p style="margin:5px 0 0 0; font-size:0.7rem; opacity:0.8;">Transaction ID: #TEST-SESSION</p>
            </div>
            <div class="slip-body">
                <div class="success-icon">✓</div>
                <h4 style="text-align:center; margin:10px 0 20px 0; color:#27ae60;">บันทึกข้อมูลสำเร็จ</h4>
                <div id="slip-items"></div>
                <div class="slip-total">
                    <span>ยอดเงินสุทธิ</span>
                    <span id="slip-total-amount" style="color:#4E9A9A;">0.00 THB</span>
                </div>
                <div class="qr-mock">[ QR CODE ]</div>
                <div class="slip-footer">
                    ขอบคุณที่ช่วยรักษาความสะอาดให้มหาวิทยาลัย<br>
                    วันที่: <span id="slip-date"></span>
                </div>
            </div>
        </div>
        <button class="neumo-btn primary" onclick="saveAndReset()" style="width:280px; margin-top:30px; border-radius:15px; background:#4E9A9A; color:white; font-weight:bold;">
            บันทึกข้อมูลและจบงาน
        </button>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

    <script>
        const AI_URL = "https://teachablemachine.withgoogle.com/models/n_4Cu6X1N/";
        const kioskID = new URLSearchParams(window.location.search).get('kiosk') || 'SLAVE01';
        const API_BASE = "http://10.108.175.80:8081/api/kiosk";

        let model, inventory = {};
        let isScanning = false;

        // ... functions addLog, toggleLog, toggleCart ... (เหมือนเดิม)
        function addLog(message, type = 'info') {
            const logContent = document.getElementById('log-content');
            const now = new Date();
            const timeStr = now.toLocaleTimeString();
            const div = document.createElement('div');
            div.style.color = (type === 'error' ? "#ff4d4d" : (type === 'warn' ? "#ffcc00" : (type === 'process' ? "#00d4ff" : "#00ff00")));
            div.innerHTML = `[${timeStr}] > ${message}`;
            logContent.insertBefore(div, logContent.firstChild);
        }
        function toggleLog() {
            const box = document.getElementById('log-box');
            box.style.display = box.style.display === 'none' ? 'flex' : 'none';
        }
        function toggleCart() {
            const panel = document.getElementById('summary-panel');
            panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
            if (panel.style.display === 'flex') updateSummaryPanel();
        }

        async function startScanProcess() {
            if (isScanning) return;
            isScanning = true;
            document.getElementById('main-status-text').innerText = "กำลังเริ่มต้น AI...";
            addLog("🧠 กำลังโหลด AI Model...");
            try {
                model = await tmImage.load(AI_URL + "model.json", AI_URL + "metadata.json");
                addLog("✅ AI พร้อมใช้งาน!");
                document.getElementById('main-status-text').innerText = "วางวัตถุในวงกลมสแกน";
                setupEcho();
            } catch (err) {
                addLog("❌ โหลด AI ไม่สำเร็จ: " + err, "error");
                document.getElementById('main-status-text').innerText = "ข้อผิดพลาด AI";
                isScanning = false;
            }
        }

        function setupEcho() {
            addLog("📡 เชื่อมต่อ Server...");
            // เช็คว่า Echo โหลดมาหรือยัง
            if(window.Echo){
                window.Echo.channel('kiosk.' + kioskID)
                .listen('.image.sent', (e) => {
                    addLog("📸 ได้รับรูปภาพใหม่!");
                    document.getElementById('result-box').style.display = 'none';
                    document.getElementById('main-status-text').innerText = "กำลังวิเคราะห์วัตถุ...";
                    const img = document.getElementById('monitor-image');
                    img.src = e.image + "?t=" + new Date().getTime();
                    img.onload = () => predict();
                });
            } else {
                 addLog("⚠️ Echo not loaded (Mock Mode?)", "warn");
            }
        }

        async function predict() {
            // ... (Code เดิม) ...
        }

        // ... functions updateInventory, updateSummaryPanel ... (เหมือนเดิม)
        function updateInventory(label) {
            inventory[label] = (inventory[label] || 0) + 1;
            let total = 0;
            for (let key in inventory) { total += inventory[key]; }
            document.getElementById('total-badge').innerText = total;
            const cartBtn = document.querySelector('.action-buttons .neumo-btn:first-child');
            cartBtn.style.transform = "scale(1.1)";
            setTimeout(() => cartBtn.style.transform = "scale(1)", 200);
        }

        function updateSummaryPanel() {
            const summaryItemsDiv = document.getElementById('summary-items');
            if (Object.keys(inventory).length === 0) {
                summaryItemsDiv.innerHTML = '<div style="text-align:center; color:var(--text-light);">ยังไม่มีข้อมูล...</div>';
                return;
            }
            let html = '';
            for (let item in inventory) {
                let [name,code] = item.split('/');
                html += `<div class="summary-item"><span>${name}</span><span>x ${inventory[item]} ชิ้น</span></div>`;
            }
            summaryItemsDiv.innerHTML = html;
        }

        // 🧪 FUNCTION สำหรับ TEST DATA (เพิ่มใหม่)
        function injectMockData() {
            // 1. จำลองข้อมูล (Mock Data)
            inventory = {
                'ขวดพลาสติก/PET0250': 3,
                'กระป๋อง/CAN001': 2,
                'ขวดแก้ว/OT0001': 1
            };

            // 2. อัปเดต UI หน้าจอ
            let total = 0;
            for (let key in inventory) {
                total += inventory[key];
            }
            document.getElementById('total-badge').innerText = total;

            // 3. แจ้งเตือน
            addLog("🧪 Mock Data injected: 3 bottles, 2 cans, 1 glass", "warn");
            // alert("เพิ่มข้อมูลจำลองแล้ว! กดปุ่ม 'Finish' สีแดงเพื่อดูหน้าสรุปและทดสอบการบันทึก");
        }

        function finishSession() {
            // 1. สั่ง ESP Sleep (ข้ามได้ถ้าเทส)
            // fetch(`${API_BASE}/sleep?kiosk=${kioskID}`);

            let html = '';
            let totalValue = 0;
            const priceList = { 'ขวดพลาสติก/PET0250': 0.5, 'กระป๋อง/CAN001': 0.7, 'ขวดแก้ว/OT0001': 1.0 };

            for (let item in inventory) {
                let price = priceList[item] || 0.25;
                let subtotal = inventory[item] * price;
                totalValue += subtotal;
                let [name,code] = item.split("/");
                console.log(item)
                html += `<div class="slip-row"><span>${name} (x${inventory[item]})</span><span>${subtotal.toFixed(2)}</span></div>`;
            }

            document.getElementById('slip-items').innerHTML = html;
            document.getElementById('slip-total-amount').innerText = `${totalValue.toFixed(2)} THB`;
            document.getElementById('slip-date').innerText = new Date().toLocaleString('th-TH');
            document.getElementById('summary-overlay').style.display = 'flex';
        }

        async function saveAndReset() {
            if (Object.keys(inventory).length === 0) {
                alert("ไม่มีรายการขยะให้บันทึก");
                return;
            }

            const saveBtn = document.querySelector('.slip-container + .neumo-btn');
            saveBtn.innerText = "⏳ กำลังบันทึก...";
            saveBtn.disabled = true;

            try {
                const response = await fetch('/keptkayas/kiosks/noscreen/save-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        // ✅ ดึง CSRF Token จาก meta tag ที่เราเพิ่มไป
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        kioskId: kioskID,
                        inventory: inventory
                    })
                });

                const result = await response.json();
                console.log('result',result);
                if (result.status === 'success') {
                    alert("✅ บันทึกสำเร็จ! ขอบคุณที่ใช้บริการ\nเลขที่รายการ: " + result.trans_no);
                    // fetch(`${API_BASE}/sleep?kiosk=${kioskID}`);
                    // window.location.href = "/kiosk/scan"; // หรือ URL หน้าแรกที่คุณต้องการ
                } else {
                    alert("❌ ผิดพลาด: " + result.message);
                    saveBtn.innerText = "ลองใหม่อีกครั้ง";
                    saveBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert("เกิดข้อผิดพลาดในการเชื่อมต่อ: " + error);
                saveBtn.innerText = "ลองใหม่อีกครั้ง";
                saveBtn.disabled = false;
            }
        }
    </script>
</body>
</html>
