<div class="sub-header">
    <button type="button" onclick="backToMenu()" class="btn-back">⬅️ หน้าหลัก</button>
    <h5 class="fw-bold mb-0 text-white">♻️ ธนาคารขยะรีไซเคิล</h5>
    <div style="width: 40px;"></div>
</div>

<div class="white-sheet-container mt-2">
    <div class="container px-2" style="padding-bottom: 80px;">
        <!-- Search & Scan Bar -->
        <div class="card p-2 mb-3">
            <div class="search-wrapper d-flex gap-2">
                <input type="text" id="searchMemberRecycleInput" class="form-control form-control-sm rounded-pill px-3"
                    placeholder="🔍 ค้นหาชื่อ หรือเบอร์โทรสมาชิก..." oninput="filterMembers()">
                <button type="button" id="btnScanQR" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold text-nowrap">
                    📷 สแกน
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
            <span class="text-xs fw-bold text-secondary">รายชื่อสมาชิกธนาคารขยะ (<span id="memberCount">0</span> คน)</span>
        </div>

        <!-- Tabs: รอรับซื้อ vs รับซื้อแล้ว -->
        <div class="tab-container d-flex mb-3 bg-light p-1 rounded-pill">
            <button type="button" id="btnTabPending" onclick="switchTab('pending')"
                style="flex: 1; padding: 8px; border: none; border-radius: 50px; font-weight: bold; background: #007bff; color: white; cursor: pointer; font-size: 13px;">
                ⏳ รอรับซื้อ (<span id="countPending">0</span>)
            </button>
            <button type="button" id="btnTabCompleted" onclick="switchTab('completed')"
                style="flex: 1; padding: 8px; border: none; border-radius: 50px; font-weight: bold; background: transparent; color: #333; cursor: pointer; font-size: 13px;">
                ✓ รับซื้อแล้ว (<span id="countCompleted">0</span>)
            </button>
        </div>

        <!-- Member List Container (Render จาก JS) -->
        <div id="memberListContainer">
            <div class="text-center text-muted py-4 small">กำลังโหลดรายชื่อสมาชิก...</div>
        </div>
    </div>
</div>
