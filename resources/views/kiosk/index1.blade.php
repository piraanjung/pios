<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Smart Recycle (Student Theme)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    
    <!-- Fonts (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-color: #ff6b81; /* ชมพูน่ารัก */
            --bg-color: #f3f4f6;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
            --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        * { box-sizing: border-box; }
        
        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(45deg, #a18cd1 0%, #fbc2eb 100%); /* พื้นหลังพาสเทล */
            overflow: hidden;
            color: #4a4a4a;
        }

        /* --- Login Page --- */
        #login-page {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            background: rgba(255,255,255,0.9);
            border-radius: 30px 30px 0 0;
            margin-top: 20vh;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.1);
            animation: slideUp 0.6s ease-out;
        }

        h2 { color: #555; margin-bottom: 5px; }
        p.subtitle { color: #888; margin-bottom: 30px; font-size: 0.9rem; }

        input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 2px solid #eee;
            border-radius: 50px; /* ช่องกรอกแบบมน */
            font-size: 1rem;
            text-align: center;
            transition: 0.3s;
            background: #f9f9f9;
            font-family: 'Kanit', sans-serif;
        }
        input:focus { border-color: #a18cd1; outline: none; background: white; }

        button {
            width: 100%;
            padding: 15px;
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
            transition: transform 0.2s;
            font-family: 'Kanit', sans-serif;
        }
        button:active { transform: scale(0.95); }

        /* --- Scan Page Layout --- */
        #scan-page {
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        /* ⭐ 1. Futuristic Profile Card */
        .profile-card {
            margin: 15px;
            padding: 15px 20px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px); /* เอฟเฟกต์กระจก */
            -webkit-backdrop-filter: blur(10px);
            border-radius: 25px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 10;
        }

        .avatar-wrapper {
            position: relative;
        }

        #user-img-large {
            width: 60px; height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .online-dot {
            position: absolute; bottom: 5px; right: 0;
            width: 15px; height: 15px;
            background: #2ecc71;
            border: 2px solid white;
            border-radius: 50%;
        }

        .user-details h3 { margin: 0; font-size: 1.1rem; color: #333; }
        
        .score-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(to right, #f6d365 0%, #fda085 100%);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: white;
            font-weight: bold;
            margin-top: 4px;
            box-shadow: 0 2px 5px rgba(253, 160, 133, 0.4);
        }

        .btn-logout-mini {
            margin-left: auto;
            background: white;
            color: #ff6b81;
            border: 1px solid #ff6b81;
            width: 35px; height: 35px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: none;
            padding: 0;
        }

        /* ⭐ 2. Camera Frame (Sci-Fi Scanner) */
        .camera-wrapper {
            flex-shrink: 0;
            margin: 0 15px;
            height: 35vh; /* ความสูงกล้อง */
            border-radius: 25px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            background: black;
        }

        #cam-box { width: 100%; height: 100%; position: relative; }
        video { width: 100%; height: 100%; object-fit: cover; opacity: 0.9; }

        /* Animation เส้นสแกน */
        .scan-line {
            position: absolute;
            width: 100%; height: 4px;
            background: #00ffcc;
            box-shadow: 0 0 10px #00ffcc;
            top: 0;
            z-index: 5;
            animation: scanAnim 3s infinite linear;
            display: none; /* ซ่อนก่อน จะโชว์ตอน Process */
        }
        .scan-line.active { display: block; }

        /* กรอบมุม 4 ด้าน (Corner HUD) */
        .hud-corner {
            position: absolute; width: 30px; height: 30px;
            border: 3px solid rgba(255,255,255,0.7);
            z-index: 4;
        }
        .tl { top: 15px; left: 15px; border-right: none; border-bottom: none; border-radius: 10px 0 0 0; }
        .tr { top: 15px; right: 15px; border-left: none; border-bottom: none; border-radius: 0 10px 0 0; }
        .bl { bottom: 15px; left: 15px; border-right: none; border-top: none; border-radius: 0 0 0 10px; }
        .br { bottom: 15px; right: 15px; border-left: none; border-top: none; border-radius: 0 0 10px 0; }

        /* Status Text Floating */
        .status-pill {
            position: absolute;
            bottom: 20px; left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            white-space: nowrap;
            backdrop-filter: blur(4px);
            z-index: 6;
            transition: all 0.3s;
        }
        .st-found { background: #2ecc71; color: white; box-shadow: 0 0 15px rgba(46, 204, 113, 0.6); }
        .st-open { background: #3498db; color: white; }

        /* ⭐ 3. Bottom Sheet Table */
        .stats-sheet {
            flex-grow: 1;
            background: white;
            margin-top: 15px;
            border-radius: 30px 30px 0 0; /* มนแค่ข้างบน */
            box-shadow: 0 -5px 20px rgba(0,0,0,0.05);
            display: flex; flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        .sheet-header {
            padding: 20px 25px;
            display: flex; justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
        }
        .sheet-header h4 { margin: 0; font-size: 1.1rem; color: #444; }

        .table-scroll { flex-grow: 1; overflow-y: auto; padding: 0 10px; }
        
        table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        tr { transition: 0.2s; }
        td { padding: 15px; background: #f9f9fb; first-child: border-radius: 15px 0 0 15px; last-child: border-radius: 0 15px 15px 0; }
        td:first-child { border-radius: 15px 0 0 15px; }
        td:last-child { border-radius: 0 15px 15px 0; font-size: 0.8rem; color: #999; }
        
        /* Highlight Row */
        .row-highlight td { background: #e0f7fa; color: #006064; font-weight: bold; transform: scale(1.02); }

        /* Helpers */
        .hidden { display: none !important; }

        @keyframes scanAnim {
            0% { top: 0; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        @keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    </style>
</head>
<body>

    <div id="login-page">
        <div style="font-size: 3rem; margin-bottom: 10px;">♻️</div>
        <h2>Smart Recycle</h2>
        <p class="subtitle">ระบบคัดแยกขวดอัจฉริยะ</p>
        
        <input type="tel" id="phone" value="0993392334" placeholder="เบอร์โทรศัพท์">
        <button onclick="doLogin()">🚀 เริ่มใช้งาน</button>
    </div>

    <div id="scan-page" class="hidden">
        
        <div class="profile-card">
            <div class="avatar-wrapper">
                <img id="user-img-large" src="https://via.placeholder.com/60" onerror="this.src='https://cdn-icons-png.flaticon.com/512/847/847969.png'">
                <div class="online-dot"></div>
            </div>
            <div class="user-details">
                <h3><span class="result">Guest</span></h3>
                <div class="score-badge">⭐ <span id="user-score">0</span> แต้ม</div>
            </div>
            <button class="btn-logout-mini" onclick="location.reload()">↩</button>
        </div>

        <div class="camera-wrapper">
            <div id="cam-box">
                <video id="video" autoplay playsinline muted></video>
                
                <div class="hud-corner tl"></div>
                <div class="hud-corner tr"></div>
                <div class="hud-corner bl"></div>
                <div class="hud-corner br"></div>
                
                <div class="scan-line" id="scanner-fx"></div>

                <div id="status-pill" class="status-pill">📷 เล็งขวดให้อยู่ในกรอบ</div>
            </div>
        </div>

        <div class="stats-sheet">
            <div class="sheet-header">
                <h4>📜 ประวัติวันนี้</h4>
                <span style="font-size:0.8rem; color:#aaa;" id="show-ip">IP: ...</span>
            </div>
            <div class="table-scroll">
                <table width="100%">
                    <tbody id="stats-body">
                        </tbody>
                </table>
                <div id="empty-state" style="text-align:center; padding:30px; color:#ccc;">
                    <span style="font-size:2rem; display:block; margin-bottom:10px;">🗑️</span>
                    ยังไม่มีรายการ
                </div>
            </div>
        </div>

    </div>

    <video id="webcamVideo" autoplay playsinline width="640" height="480" style="display:none;"></video>
    <canvas id="captureCanvas" width="640" height="480" style="display:none;"></canvas>


    <script>
        // --- CONFIG & VARIABLES ---
        const NODE_MCU_IP = "http://10.255.156.96";
        const URL = "https://teachablemachine.withgoogle.com/models/n_4Cu6X1N/";
        let model, maxPredictions, isModelLoaded = false, isProcessing = false;
        let labelCounts = {};
        let totalScore = 0;

        // --- 1. LOGIN LOGIC ---
        function doLogin() {
            const p = $('#phone').val();
            // Mock Login (ใช้ Ajax จริงของคุณแทนที่นี่)
             $.post( "/api/check-member",{ 
                 _token: $('meta[name="csrf-token"]').attr('content'),
                 phone: p
             },
             function( data ) {
                 if (data.status === "found") {
                     setupUI(data.name, data.picture, data.score || 0);
                 } else {
                     alert("ไม่พบข้อมูลสมาชิก");
                 }
             }).fail(function() {
                 // Fallback for demo
                 // setupUI("Nadech K.", "https://via.placeholder.com/100", 120);
                 alert("Connection Failed");
             });
        }

        function setupUI(name, picture, score) {
            $('.result').text(name);
            $('#user-score').text(score);
            totalScore = score;
            if(picture) $('#user-img-large').attr('src', picture);

            $('#login-page').addClass('hidden');
            $('#scan-page').removeClass('hidden');
            $('#show-ip').text(NODE_MCU_IP.replace("http://", ""));
            
            initModel();
            pollSensor();
        }

        // --- 2. CAMERA & AI ---
        // (ฟังก์ชัน startCamera, initModel เหมือนเดิม แต่ปรับปรุง UX ใน processObject)
        async function initModel() {
            try {
                const modelURL = URL + "model.json";
                const metadataURL = URL + "metadata.json";
                model = await tmImage.load(modelURL, metadataURL);
                maxPredictions = model.getTotalClasses();
                isModelLoaded = true;
                
                // Init counts
                const labels = model.getClassLabels();
                labels.forEach(l => labelCounts[l] = 0);
                
                initCamera();
            } catch (e) { console.error(e); }
        }

        async function initCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "environment", width: { ideal: 640 }, height: { ideal: 480 } }
                });
                const video = document.getElementById('webcamVideo');
                video.srcObject = stream;
                document.getElementById('video').srcObject = stream; // UI Video

                video.onloadedmetadata = () => {
                    video.play();
                    const canvas = document.getElementById('captureCanvas');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                };
            } catch (err) { console.error(err); }
        }

        function pollSensor() {
            if ($('#scan-page').hasClass('hidden') || isProcessing) return; 

            $.ajax({
                url: NODE_MCU_IP + "/check-sensor",
                type: "GET",
                timeout: 10000, 
                success: function(res) {
                    if(isProcessing) return;
                    if (res.includes("YES")) processObject(); 
                    else pollSensor();
                },
                error: function() {
                    if(!isProcessing) setTimeout(pollSensor, 2000);
                }
            });
        }

        async function processObject() {
            if (!isModelLoaded || isProcessing) return;
            isProcessing = true;

            // ✨ UX: Start Scanning Animation
            updateStatus("⚡ กำลังวิเคราะห์...", "processing");
            $('#scanner-fx').addClass('active'); // เปิดเลเซอร์สแกน

            try {
                await new Promise(r => setTimeout(r, 600)); // หน่วงนิดนึงให้ดูเหมือนคิด
                const video = document.getElementById('webcamVideo');
                const canvas = document.getElementById('captureCanvas');
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const prediction = await model.predict(canvas);
                let bestResult = prediction.reduce((prev, current) => 
                    (prev.probability > current.probability) ? prev : current
                );

                $('#scanner-fx').removeClass('active'); // ปิดเลเซอร์

                if (bestResult.probability > 0.85) {
                    const labelName = bestResult.className;
                    
                    // Logic เดิม
                    if(labelCounts[labelName] !== undefined) labelCounts[labelName]++;
                    
                    // ✨ UX: Update UI
                    totalScore += 10; // แต้มสมมติ
                    $('#user-score').text(totalScore);
                    updateStatus(`✨ เย้! พบ ${labelName}`, "success");
                    updateTable(labelName);

                    if (labelName === "btmc_PET600") openGate();
                    else setTimeout(finishProcess, 2000);

                } else {
                    updateStatus("❌ ไม่รู้จักวัตถุนี้", "error");
                    setTimeout(finishProcess, 1500);
                }

            } catch (error) {
                console.error(error);
                isProcessing = false;
                $('#scanner-fx').removeClass('active');
            }
        }

        function openGate() {
            updateStatus("🚪 ประตูกำลังเปิด...", "open");
            $.ajax({
                url: NODE_MCU_IP + "/open-gate",
                type: "GET",
                success: function () { resetSystem(); },
                error: function () { resetSystem(); }
            });
        }

        function resetSystem() {
            // ไม่ต้องทำอะไรมาก แค่เรียก finishProcess แต่หน่วงเวลาให้ประตูทำงาน
            setTimeout(finishProcess, 4000); 
        }

        function finishProcess() {
            isProcessing = false;
            updateStatus("📷 รอขวดถัดไป...", "wait");
            pollSensor();
        }

        // --- UI HELPERS ---
        function updateStatus(msg, type) {
            const el = $('#status-pill');
            el.text(msg);
            el.removeClass('st-found st-open').css('background', 'rgba(0,0,0,0.7)');
            
            if (type === 'success') el.addClass('st-found');
            if (type === 'open') el.addClass('st-open');
            if (type === 'error') el.css('background', '#e74c3c');
            if (type === 'processing') el.css('background', '#f1c40f').css('color', 'black');
        }

        function updateTable(latestLabel) {
            $('#empty-state').hide();
            const tbody = document.getElementById('stats-body');
            const timeStr = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
            
            // เพิ่มแถวใหม่ไว้บนสุด (Prepend)
            const rowHtml = `
                <tr class="row-highlight">
                    <td>
                        <div style="font-weight:bold; color:#333;">${latestLabel}</div>
                        <div style="font-size:0.75rem; color:gray;">ขวดพลาสติก</div>
                    </td>
                    <td style="text-align:right;">
                        <span style="background:#2ecc71; color:white; padding:2px 8px; border-radius:10px; font-size:0.8rem;">+10 แต้ม</span>
                    </td>
                    <td style="text-align:right;">${timeStr}</td>
                </tr>
            `;
            
            $(tbody).prepend(rowHtml);

            // ลบ class highlight ออกหลังจากผ่านไปแป๊บนึง
            setTimeout(() => {
                $('.row-highlight').removeClass('row-highlight');
            }, 1000);
        }
    </script>
</body>
</html>