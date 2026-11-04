<!-- ปุ่มลัด Floating Menu Button (ซ่อนตอนอยู่หน้า Login) -->
<div class="iphone-assistive-btn is-hidden" id="assistiveBtn" onclick="toggleFullSidebar()" title="เมนูนำทาง">
    <i class="fa-solid fa-bars-staggered" id="assistiveIcon"></i>
</div>

<!-- ฉากหลังมืดเวลาเปิดเมนูลอยบนมือถือ -->
<div class="sidebar-backdrop is-hidden" id="sidebarBackdrop" onclick="closeFullSidebar()"></div>

<!-- Floating Mobile Sidebar -->
<div id="sidebarMenu" class="sidebar-menu hidden-sidebar">
    <div class="sidebar-header text-center py-3">
        <div class="sidebar-logo-icon" onclick="toggleIconOnly()" title="ย่อ/ขยาย เมนู">
            <i class="fa-solid fa-mobile-screen-button"></i>
        </div>
        <div class="sidebar-logo-text text-white fw-bold small mt-2">EnvSoGo Staff</div>
    </div>

    <div class="sidebar-nav" id="sidebarNavList">
        <div class="active-indicator" id="activeIndicator"></div>

        <div class="sidebar-item active" data-target="mainScreen" onclick="switchSidebarMenu(this, 'dashboard')">
            <div class="menu-link">
                <i class="fa-solid fa-house"></i>
                <div>หน้าหลัก</div>
            </div>
        </div>

        <div class="sidebar-item" data-target="tabwaterScreen" onclick="switchSidebarMenu(this, 'tabwater')">
            <div class="menu-link">
                <i class="fa-solid fa-droplet"></i>
                <div>งานประปา</div>
            </div>
        </div>

        <div class="sidebar-item" data-target="recycleBankScreen" onclick="switchSidebarMenu(this, 'recycle_bank')">
            <div class="menu-link">
                <i class="fa-solid fa-recycle"></i>
                <div>ธนาคารขยะ</div>
            </div>
        </div>

        <div class="sidebar-item" data-target="inventoryScreen" onclick="switchSidebarMenu(this, 'inventory')">
            <div class="menu-link">
                <i class="fa-solid fa-boxes-stacked"></i>
                <div>คลังพัสดุ</div>
            </div>
        </div>

        <div class="sidebar-item" data-target="settingsScreen" onclick="switchSidebarMenu(this, 'settings')">
            <div class="menu-link">
                <i class="fa-solid fa-print"></i>
                <div>เครื่องพิมพ์</div>
            </div>
        </div>
    </div>

    <div class="sidebar-footer p-2 text-center">
        <button type="button" id="btnLogout" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold shadow-sm">
            <i class="fa-solid fa-right-from-bracket me-1"></i> ออกจากระบบ
        </button>
    </div>
</div>

