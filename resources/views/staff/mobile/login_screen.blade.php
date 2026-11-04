<div class="mobile-auth-shell">
    <div class="card neu-card mx-auto">
        <div class="card-body p-4">
            <!-- โลโก้แอป -->
            <div class="text-center mb-3">
                <div class="neu-profile mb-3">
                    <img src="{{ asset('logo/ko_envsogo.png') }}" alt="EnvSoGo Logo">
                </div>
                <h5 class="fw-bold text-dark mb-0">EnvSoGo Staff</h5>
                <small class="text-muted">ระบบปฏิบัติงานเจ้าหน้าที่ภาคสนาม</small>
            </div>

            <!-- กล่องแจ้งเตือน Error -->
            <div id="loginError" class="alert alert-danger py-2 px-3 text-xs rounded-3 mb-3" style="display: none;"></div>

            <form id="loginForm" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label text-xs fw-bold text-secondary ps-2 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                    <div class="neu-input-group">
                        <span class="input-group-text bg-transparent border-0"><i class="fa-regular fa-user"></i></span>
                        <input type="text" id="username" placeholder="ระบุชื่อผู้ใช้งาน" value="" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-xs fw-bold text-secondary ps-2 mb-1">รหัสผ่าน (Password)</label>
                    <div class="neu-input-group">
                        <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="password" placeholder="ระบุรหัสผ่าน" value="" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" id="btnLogin" class="btn neu-button py-2">
                        🔓 เข้าสู่ระบบ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

