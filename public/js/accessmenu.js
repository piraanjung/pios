// =========================================================================
//  📱 EnvSoGo Staff Mobile App - Unified Controller (public/js/accessmenu.js)
// =========================================================================

const API_BASE_URL = window.API_BASE_URL || '/api';

// Global State
let currentScreenGlobal = '';
let currentSubzonesData = [];
let membersSubzone = [];
let cur_meter = '';
let water_used = 0.0;
let paid_raw = 0.0;
let vat = 0.0;
let inv_id = 0.0;
let totalpaid = 0.0;
let membersInitStatusCount = 0;
let membersInitStatus = [];
let inv_period = '';

// Recycle Bank State
let allMembers = [];
let rawItemsData = [];
let currentSelectedTrash = null;
let purchaseCart = [];
let currentSelectedUnitId = null;
let currentActiveMember = null;
let currentTab = 'pending';
let staffInfo = null;
let html5QrCode = null;
let html5QrCodeForTabwater = null;
let cropper = null;

// Bluetooth Thermal Printer Constants & State
const PRINTER_SERVICE_UUID = '000018f0-0000-1000-8000-00805f9b34fb';
const PRINTER_CHARACTERISTIC_UUID = '00002af1-0000-1000-8000-00805f9b34fb';
const LAST_USED_DEVICE_ID_KEY = 'lastUsedBluetoothPrinterId';

let BluetoothSerial = null;
let bluetoothDevice = null;
let printCharacteristic = null;
let isBluethoothConnected = false;
let printer_name = '';
let isRecorded = false;
let inventoryIframeLoadPromise = null;

// =========================================================================
//  🔐 1. Authentication & Organization Selection
// =========================================================================

function hydrateStaffInfo() {
    try {
        const rawStaffInfo = sessionStorage.getItem('staffInfo') || localStorage.getItem('staffInfo');
        if (rawStaffInfo) {
            staffInfo = JSON.parse(rawStaffInfo);
        }
    } catch (e) {
        console.warn('Cannot parse staffInfo:', e);
    }

    const staffName = sessionStorage.getItem('staff_name') || localStorage.getItem('staff_name');
    if (staffName) {
        if (document.getElementById('staffName')) document.getElementById('staffName').innerText = staffName;
        if (document.getElementById('lblMainStaffName')) document.getElementById('lblMainStaffName').innerText = staffName;
        if (document.getElementById('lblActiveStaffName')) document.getElementById('lblActiveStaffName').innerText = staffName;
    }

    const orgName = sessionStorage.getItem('staff_org_name') || localStorage.getItem('staff_org_name');
    if (orgName && document.getElementById('lblActiveOrgName')) {
        document.getElementById('lblActiveOrgName').innerText = orgName;
    }
}

function setStaffActiveOrg(orgId, staffId, profileObj = null) {
    localStorage.setItem('staff_org_id', orgId);
    localStorage.setItem('staff_id', staffId);
    sessionStorage.setItem('staff_org_id', orgId);
    sessionStorage.setItem('staff_id', staffId);

    if (profileObj) {
        const org = profileObj.organization || profileObj.org || {};
        const normalizedStaffInfo = {
            id: staffId,
            org_id_fk: orgId,
            org: {
                id: orgId,
                org_name: org.org_name || `องค์กร #${orgId}`,
                org_type_name: org.org_type_name || '',
                org_logo_img: org.org_logo_img || 'ko_envsogo.png',
                org_address: org.org_address || '',
                org_zone: org.org_zone || '',
                org_tambon: org.org_tambon || '',
                org_district: org.org_district || '',
                org_province: org.org_province || '',
                org_phone: org.org_phone || ''
            }
        };
        staffInfo = normalizedStaffInfo;
        localStorage.setItem('staffInfo', JSON.stringify(normalizedStaffInfo));
        sessionStorage.setItem('staffInfo', JSON.stringify(normalizedStaffInfo));
        localStorage.setItem('staff_org_name', normalizedStaffInfo.org.org_name);
        sessionStorage.setItem('staff_org_name', normalizedStaffInfo.org.org_name);
    }

    hydrateStaffInfo();
}

function renderOrgSelectionList(profiles, fullName) {
    const container = document.getElementById('orgListContainer');
    if (!container) return;
    container.innerHTML = '';

    window._cachedStaffProfiles = profiles;

    profiles.forEach((staff, idx) => {
        const org = staff.organization || {};
        const orgName = org.org_name || `องค์กรรหัส #${staff.org_id_fk}`;

        const logoHtml = org.org_logo_img
            ? `<img src="${window.ASSET_URL || '/'}logo/${org.org_logo_img}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">`
            : `<div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 fw-bold text-center" style="width: 45px; height: 45px; line-height: 28px;">🏢</div>`;

        const cardHtml = `
            <div class="card border-0 shadow-sm rounded-4 mb-2" style="cursor: pointer;"
                 onclick="selectOrgAndContinue(${staff.org_id_fk}, ${staff.id}, '${fullName.replace(/'/g, "\\'")}', ${idx})">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div>${logoHtml}</div>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0 text-dark">${orgName}</h6>
                        <small class="text-muted">รหัสเจ้าหน้าที่: ${staff.id}</small>
                    </div>
                    <span class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">เลือก ➔</span>
                </div>
            </div>
        `;
        container.innerHTML += cardHtml;
    });
}

function selectOrgAndContinue(orgId, staffId, fullName, profileIndex = null) {
    const profileObj = (window._cachedStaffProfiles && profileIndex !== null)
        ? window._cachedStaffProfiles[profileIndex]
        : null;

    setStaffActiveOrg(orgId, staffId, profileObj);
    navigateTo('mainScreen');
}

async function checkExistingSession() {
    const staffToken = sessionStorage.getItem('staff_token') || localStorage.getItem('staff_token');
    const staffUserId = sessionStorage.getItem('staff_user_id') || localStorage.getItem('staff_user_id');
    const savedName = sessionStorage.getItem('staff_name') || localStorage.getItem('staff_name');
    currentScreenGlobal = sessionStorage.getItem('current_screen');

    if (!staffToken || !staffUserId) {
        sessionStorage.setItem('current_screen', 'loginScreen');
        currentScreenGlobal = 'loginScreen';
        navigateTo('loginScreen');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/users/staff/verify_token`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            },
            body: JSON.stringify({
                user_id: staffUserId,
                remember_token: staffToken
            })
        });

        const resData = await response.json();

        if (resData.code === 200 && resData.data && resData.data.valid === true) {
            hydrateStaffInfo();

            if (!currentScreenGlobal || currentScreenGlobal === 'loginScreen') {
                navigateTo('mainScreen');
            } else {
                navigateTo(currentScreenGlobal);
            }

        } else {
            handleForceLogout('เซสชันหมดอายุ หรือมีการเข้าสู่ระบบจากอุปกรณ์อื่น กรุณาเข้าสู่ระบบใหม่');
        }
    } catch (error) {
        console.error('API Verification Error:', error);
        handleForceLogout('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์เพื่อตรวจสอบสิทธิ์ได้');
    }
}

async function handleStaffLogin() {
    const username = $('#username').val()?.trim() || '';
    const password = $('#password').val()?.trim() || '';
    const $loginError = $('#loginError');
    const $btnSubmit = $('#btnLogin');

    $loginError.text('').hide();

    if (!username || !password) {
        $loginError.text('กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน').show();
        return;
    }

    $btnSubmit.prop('disabled', true).text('⏳ กำลังตรวจสอบข้อมูล...');

    try {
        const response = await fetch(`${API_BASE_URL}/users/staff_login_core`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            },
            body: JSON.stringify({
                username: username,
                passwords: password
            })
        });

        const resData = await response.json();

        if (resData.code === 200 && resData.data && resData.data.logged === true) {
            const staffUser = resData.data;
            const fullName = `${staffUser.prefix || ''}${staffUser.firstname || ''} ${staffUser.lastname || ''}`.trim();

            sessionStorage.setItem('staff_token', staffUser.token || '');
            sessionStorage.setItem('staff_name', fullName);
            sessionStorage.setItem('staff_user_id', staffUser.id);
            sessionStorage.setItem('twman', JSON.stringify(staffUser));

            localStorage.setItem('staff_token', staffUser.token || '');
            localStorage.setItem('staff_name', fullName);
            localStorage.setItem('staff_user_id', staffUser.id);

            const profiles = staffUser.staff_profiles || [];

            if (profiles.length === 1) {
                const singleOrg = profiles[0];
                setStaffActiveOrg(singleOrg.org_id_fk, singleOrg.id, singleOrg);
                navigateTo('mainScreen');
            } else if (profiles.length > 1) {
                renderOrgSelectionList(profiles, fullName);
                navigateTo('selectOrgScreen');
            } else {
                throw new Error('ไม่พบข้อมูลสิทธิ์เจ้าหน้าที่ในระบบ (Staff Profiles)');
            }
        } else {
            $loginError.text(resData.message || 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง').show();
        }
    } catch (error) {
        $loginError.text(error.message || 'เกิดข้อผิดพลาด: ไม่สามารถเชื่อมต่อกับฐานข้อมูลระบบได้').show();
        console.error('API Connection Error:', error);
    } finally {
        $btnSubmit.prop('disabled', false).text('🔓 เข้าสู่ระบบ');
    }
}

function handleForceLogout(message) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const iframe = document.getElementById('inventoryIframe');
    const endSessionUrl = iframe?.dataset.sessionEndUrl;
    if (csrfToken && endSessionUrl) {
        fetch(endSessionUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        }).catch(() => {});
    }

    sessionStorage.clear();
    localStorage.removeItem('staff_token');
    localStorage.removeItem('staff_name');
    localStorage.removeItem('staff_user_id');
    localStorage.removeItem('staff_id');
    localStorage.removeItem('staff_org_id');
    localStorage.removeItem('staffInfo');

    navigateTo('loginScreen');

    if (message) {
        $('#loginError').text(message).show();
    }
}

async function loadInventoryIframeOnce(forceReload = false) {
    const iframe = document.getElementById('inventoryIframe');
    const errorBox = document.getElementById('inventoryIframeError');
    const errorMessage = document.getElementById('inventoryIframeErrorMessage');
    if (!iframe) return;
    if (inventoryIframeLoadPromise) return inventoryIframeLoadPromise;
    if (iframe.dataset.loaded === 'true' && !forceReload) return;

    const showError = (message) => {
        if (errorMessage) errorMessage.textContent = message;
        if (errorBox) errorBox.hidden = false;
    };

    errorBox?.setAttribute('hidden', '');
    inventoryIframeLoadPromise = (async () => {
        const sessionUrl = iframe.dataset.sessionUrl;
        const inventoryUrl = iframe.dataset.src;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const userId = sessionStorage.getItem('staff_user_id') || localStorage.getItem('staff_user_id');
        const token = sessionStorage.getItem('staff_token') || localStorage.getItem('staff_token');
        const staffId = sessionStorage.getItem('staff_id') || localStorage.getItem('staff_id');
        const orgId = sessionStorage.getItem('staff_org_id') || localStorage.getItem('staff_org_id');

        if (!sessionUrl || !inventoryUrl || !csrfToken || !userId || !token || !staffId || !orgId) {
            showError('กรุณาเข้าสู่ระบบและเลือกองค์กรก่อนเปิดคลังพัสดุ');
            return;
        }

        try {
            const response = await fetch(sessionUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    user_id: userId,
                    remember_token: token,
                    staff_id: staffId,
                    org_id: orgId
                })
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'ยืนยันสิทธิ์เปิดคลังพัสดุไม่สำเร็จ');
            }

            iframe.onload = () => {
                iframe.dataset.loaded = 'true';
                if (errorBox) errorBox.hidden = true;
            };
            if (forceReload) {
                iframe.dataset.loaded = 'false';
                iframe.src = 'about:blank';
            }
            iframe.src = inventoryUrl;
        } catch (error) {
            showError(error.message || 'ไม่สามารถเชื่อมต่อคลังพัสดุได้');
            console.error('Inventory iframe session error:', error);
        }
    })();

    try {
        await inventoryIframeLoadPromise;
    } finally {
        inventoryIframeLoadPromise = null;
    }
}

// =========================================================================
//  🧭 2. Navigation, Floating Sidebar & Mobile Bottom Nav
// =========================================================================

const screenAliases = {
    'main': 'mainScreen',
    'login': 'loginScreen',
    'settings': 'settingsScreen',
    'recycle': 'recycleBankScreen',
    'recycleScreen': 'recycleBankScreen',
    'water': 'tabwaterScreen',
    'water-complain': 'tabwaterRecordScreen'
};

const authenticatedScreens = [
    'mainScreen',
    'tabwaterScreen',
    'tabwaterRecordScreen',
    'tabwaterRecordMemberSubzoneLists',
    'recycleBankScreen',
    'depositScreen',
    'settingsScreen',
    'inventoryScreen'
];

async function navigateTo(rawModuleName) {
    const moduleName = screenAliases[rawModuleName] || rawModuleName;

    sessionStorage.setItem('current_screen', moduleName);
    currentScreenGlobal = moduleName;

    // 1. ซ่อนทุกหน้าจอ (.screen)
    $('.screen').addClass('is-hidden');

    // 2. ปิดเมนูลอยทุกครั้งที่เปลี่ยนหน้า
    closeFullSidebar();

    // 3. จัดการการแสดงผล Floating Assistive Button & Bottom Navigation Bar
    const isAuthScreen = authenticatedScreens.includes(moduleName);
    if (isAuthScreen) {
        $('#appSidebar').removeClass('is-hidden');
        $('#assistiveBtn').removeClass('is-hidden');
        $('#mobileBottomNav').removeClass('is-hidden');
    } else {
        $('#appSidebar').addClass('is-hidden');
        $('#assistiveBtn').addClass('is-hidden');
        $('#mobileBottomNav').addClass('is-hidden');
    }

    // 4. อัปเดตสถานะปุ่ม Active บน Bottom Nav & Sidebar
    syncActiveNavigation(moduleName);

    // 5. โหลดข้อมูลเฉพาะของแต่ละหน้าจอ
    if (moduleName === 'mainScreen') {
        hydrateStaffInfo();
        updateGlobalPrinterStatus();
    } else if (moduleName === 'recycleBankScreen') {
        loadMembersFromServer();
    } else if (moduleName === 'tabwaterRecordScreen') {
        loadWaterRecordDashboard();
    } else if (moduleName === 'tabwaterRecordMemberSubzoneLists') {
        loadWaterMembersList();
        updateGlobalPrinterStatus();
    } else if (moduleName === 'inventoryScreen') {
        loadInventoryIframeOnce();
    } else if (moduleName === 'settingsScreen') {
        updateGlobalPrinterStatus();
    }

    // 6. แสดงหน้าจอเป้าหมาย
    $(`#${moduleName}`).removeClass('is-hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function syncActiveNavigation(moduleName) {
    let navGroup = moduleName;
    if (['tabwaterRecordScreen', 'tabwaterRecordMemberSubzoneLists'].includes(moduleName)) {
        navGroup = 'tabwaterScreen';
    } else if (moduleName === 'depositScreen') {
        navGroup = 'recycleBankScreen';
    }

    document.querySelectorAll('#mobileBottomNav .bottom-nav-item').forEach(btn => {
        if (btn.getAttribute('data-screen') === navGroup) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    document.querySelectorAll('#sidebarNavList .sidebar-item').forEach(item => {
        if (item.getAttribute('data-target') === navGroup) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

function backToMenu() {
    navigateTo('mainScreen');
}

function backToMenuFromSettings() {
    navigateTo('mainScreen');
}

function toggleIconOnly() {
    const sidebar = document.getElementById('sidebarMenu');
    if (!sidebar) return;
    sidebar.classList.toggle('icon-only');
    if (sidebar.classList.contains('icon-only')) {
        $('.menu-link div').addClass('is-hidden');
    } else {
        $('.menu-link div').removeClass('is-hidden');
    }
}

function toggleFullSidebar() {
    const sidebar = document.getElementById('sidebarMenu');
    const assistiveIcon = document.getElementById('assistiveIcon');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (!sidebar) return;

    sidebar.classList.toggle('hidden-sidebar');
    const isHidden = sidebar.classList.contains('hidden-sidebar');

    if (assistiveIcon) {
        assistiveIcon.className = isHidden ? 'fa-solid fa-bars-staggered' : 'fa-solid fa-xmark';
    }
    if (backdrop) {
        backdrop.classList.toggle('is-hidden', isHidden);
    }
}

function closeFullSidebar() {
    const sidebar = document.getElementById('sidebarMenu');
    const assistiveIcon = document.getElementById('assistiveIcon');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) sidebar.classList.add('hidden-sidebar');
    if (assistiveIcon) assistiveIcon.className = 'fa-solid fa-bars-staggered';
    if (backdrop) backdrop.classList.add('is-hidden');
}

function switchSidebarMenu(element, menuName) {
    document.querySelectorAll('.sidebar-nav .sidebar-item').forEach(item => {
        item.classList.remove('active');
    });
    if (element) element.classList.add('active');

    switch (menuName) {
        case 'tabwater':
            navigateTo('tabwaterScreen');
            break;
        case 'recycle_bank':
            navigateTo('recycleBankScreen');
            break;
        case 'inventory':
            navigateTo('inventoryScreen');
            break;
        case 'settings':
            navigateTo('settingsScreen');
            break;
        default:
            navigateTo('mainScreen');
    }
}

// =========================================================================
//  💧 3. Tabwater Module (จดมิเตอร์น้ำประปา, สแกน QR, AI OCR & พิมพ์ใบแจ้งหนี้)
// =========================================================================

async function loadWaterRecordDashboard() {
    const staffId = sessionStorage.getItem('staff_id') || localStorage.getItem('staff_id');
    const orgId = sessionStorage.getItem('staff_org_id') || localStorage.getItem('staff_org_id');
    const staffName = sessionStorage.getItem('staff_name') || localStorage.getItem('staff_name');

    if (!staffId || !orgId) {
        console.warn('ไม่พบข้อมูลเจ้าหน้าที่ หรือยังไม่ได้เลือกองค์กร');
        return;
    }

    if (staffName) {
        $('.twman_name').html(staffName);
    }

    $('#subzone').html(`
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted small">กำลังดึงข้อมูลเส้นทางจดมิเตอร์น้ำประปา...</p>
        </div>
    `);

    try {
        const response = await fetch(`${API_BASE_URL}/staff/tabwater/service-data`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            },
            body: JSON.stringify({
                staff_id: staffId,
                org_id_fk: orgId,
                service_type: 'water'
            })
        });

        const resData = await response.json();
        if (resData.code !== 200 || !resData.data) {
            $('#subzone').html(`<div class="col-12 text-center text-danger py-4">${resData.message || 'ดึงข้อมูลไม่สำเร็จ'}</div>`);
            return;
        }

        currentSubzonesData = resData.data.undertaker_subzone || [];

        if (resData.data.invoice_period && resData.data.invoice_period.inv_p_name) {
            $('.inv_period').html('รอบบิลที่ ' + resData.data.invoice_period.inv_p_name);
        }

        let text = '';
        if (currentSubzonesData.length === 0) {
            text = `<div class="col-12 text-center text-muted py-4">ไม่พบข้อมูลเขตรับผิดชอบ (Subzone)</div>`;
        } else {
            currentSubzonesData.forEach((element, i) => {
                const zoneName = element.subzone?.zone?.zone_name || 'ไม่ระบุโซน';
                const subzoneName = element.subzone?.subzone_name || 'ไม่ระบุสาย';

                text += `
                    <div class="col-12">
                        <div class="soft-card p-0 overflow-hidden mb-2 border-0 shadow-sm">
                            <div class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold text-white">${zoneName}</h6>
                                    <small class="text-white-50">${subzoneName}</small>
                                </div>
                                <span class="badge bg-white text-primary rounded-pill px-3 py-1 fw-bold">
                                    ทั้งหมด ${element.members || 0} ราย
                                </span>
                            </div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <span class="small text-muted">ชำระค่าน้ำประปาแล้ว</span>
                                    <span class="badge bg-success rounded-pill">${element.members_status_paid || 0} คน</span>
                                </div>

                                <div class="row g-2 align-items-center mb-2">
                                    <div class="col-7">
                                        <small class="d-block text-muted">บันทึกเลขมิเตอร์แล้ว</small>
                                        <span class="badge bg-success">${element.members_status_invoice || 0} คน</span>
                                    </div>
                                    <div class="col-5">
                                        <button type="button" data-subzone_id="${element.subzone_id}" data-undertaker_subzone_index="${i}"
                                            class="btn btn-sm btn-outline-success rounded-pill w-100 fw-bold goto_edit_subzone_selected_btn ${element.members_status_invoice === 0 ? 'd-none' : ''}">
                                            ✏️ แก้ไขข้อมูล
                                        </button>
                                    </div>
                                </div>

                                <div class="row g-2 align-items-center">
                                    <div class="col-7">
                                        <small class="d-block text-muted">ยังไม่บันทึกเลขมิเตอร์</small>
                                        <span class="badge bg-warning text-dark">${element.members_status_init || 0} คน</span>
                                    </div>
                                    <div class="col-5">
                                        <button type="button" data-subzone_id="${element.subzone_id}" data-undertaker_subzone_index="${i}"
                                            class="btn btn-sm btn-primary rounded-pill text-white w-100 fw-bold goto_members_subzone_selected_btn ${element.members_status_init === 0 ? 'd-none' : ''}">
                                            📝 จดมิเตอร์
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        $('#subzone').html(text);
    } catch (error) {
        console.error('API Error:', error);
        $('#subzone').html(`<div class="col-12 text-center text-danger py-4">เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์</div>`);
    }
}

$(document).on('click', '.goto_members_subzone_selected_btn, .goto_edit_subzone_selected_btn', function () {
    const subzone_id_selected = $(this).data('subzone_id');
    const index = $(this).data('undertaker_subzone_index');

    sessionStorage.setItem('subzone_id_selected', JSON.stringify(subzone_id_selected));
    sessionStorage.setItem('subzone_index_selected', index);
    localStorage.setItem('subzone_id_selected', JSON.stringify(subzone_id_selected));
    localStorage.setItem('subzone_index_selected', index);

    if (currentSubzonesData[index]) {
        const serialized = JSON.stringify(currentSubzonesData[index]);
        sessionStorage.setItem('subzone_selected', serialized);
        localStorage.setItem('subzone_selected', serialized);
    }

    navigateTo('tabwaterRecordMemberSubzoneLists');
});

async function loadWaterMembersList() {
    const container = document.getElementById('membersListContainer');
    const subzoneSelectedRaw = sessionStorage.getItem('subzone_selected') || localStorage.getItem('subzone_selected');
    if (!subzoneSelectedRaw) return;

    const subzoneSelected = JSON.parse(subzoneSelectedRaw);

    if (document.getElementById('currentSubzoneTitle')) {
        document.getElementById('currentSubzoneTitle').innerText = `สาย: ${subzoneSelected.subzone?.subzone_name || ''}`;
    }

    if (container) {
        container.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>`;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/staff/tabwater/members`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            },
            body: JSON.stringify({ subzone_id: subzoneSelected.subzone_id })
        });

        const resData = await response.json();
        if (resData.code === 200) {
            renderMembersCardList(resData.data);
            const totalMembers = subzoneSelected.members || resData.data.length;
            const recordedCount = Math.max(0, totalMembers - resData.data.length);
            if (document.getElementById('progressBadge')) {
                document.getElementById('progressBadge').innerText = `${recordedCount} / ${totalMembers}`;
            }
        } else {
            if (container) container.innerHTML = `<div class="text-center text-danger py-4">${resData.message}</div>`;
        }
    } catch (error) {
        console.error('Error loading members:', error);
        if (container) container.innerHTML = `<div class="text-center text-danger py-4">ไม่สามารถดึงข้อมูลสมาชิกได้</div>`;
    }
}

function renderMembersCardList(members) {
    const container = document.getElementById('membersListContainer');
    if (!container) return;

    container.innerHTML = '';

    if (!members || members.length === 0) {
        container.innerHTML = `<div class="text-center text-muted py-4">ไม่พบรายชื่อสมาชิกในสายนี้</div>`;
        return;
    }

    members.forEach((item) => {
        const user = item.user || {};
        const userName = `${user.firstname || ''} ${user.lastname || ''}`.trim() || 'ไม่ระบุชื่อ';
        const address = item.meter_address || 'ไม่ระบุบ้านเลขที่';
        const lastMeter = item.last_meter_recording || 0;

        const currentInvoice = (item.invoice_currrent_inv_period && Object.keys(item.invoice_currrent_inv_period).length > 0)
            ? item.invoice_currrent_inv_period
            : null;

        const invoiceId = currentInvoice ? currentInvoice.id : null;
        const isMeterRecorded = currentInvoice && currentInvoice.status !== 'init';

        const statusBadge = isMeterRecorded
            ? `<span class="badge bg-success rounded-pill">จดแล้ว (${currentInvoice.currentmeter})</span>`
            : `<span class="badge bg-warning text-dark rounded-pill">ยังไม่จด</span>`;

        const safeUserName = userName.replace(/'/g, "\\'");

        const cardHtml = `
            <div class="col-12 member-card-item mb-2">
                <div class="soft-card p-3 mb-0 border-0 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="fw-bold fs-6 text-primary">🏠 บ้านเลขที่ ${address}</span>
                            <h6 class="mb-0 text-dark fw-bold mt-1">${userName}</h6>
                        </div>
                        <div>${statusBadge}</div>
                    </div>

                    <div class="bg-light rounded-3 p-2 mb-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">เลขมิเตอร์เดิม: <strong class="text-dark">${lastMeter}</strong></small>
                        <small class="text-muted">รหัสมิเตอร์: <strong class="text-dark">${item.meternumber || '-'}</strong></small>
                    </div>

                    <button type="button" onclick="openRecordMeterModal(${item.id}, '${safeUserName}', ${lastMeter}, ${invoiceId})"
                            class="btn ${isMeterRecorded ? 'btn-outline-secondary' : 'btn-primary'} w-100 rounded-pill fw-bold">
                        ${isMeterRecorded ? '✏️ แก้ไขเลขมิเตอร์' : '📝 บันทึกเลขมิเตอร์'}
                    </button>
                </div>
            </div>
        `;
        container.innerHTML += cardHtml;
    });
}

function openQrScannerTabwaterModal() {
    const modalEl = document.getElementById('qrScannerTabwaterModal');
    if (!modalEl) return;
    const qrModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    qrModal.show();

    modalEl.addEventListener('shown.bs.modal', function () {
        if (!html5QrCodeForTabwater) {
            html5QrCodeForTabwater = new Html5Qrcode('qr-reader');
        }

        html5QrCodeForTabwater.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                const searchInput = document.getElementById('searchMemberInput');
                if (searchInput) {
                    searchInput.value = decodedText;
                    searchInput.dispatchEvent(new Event('input'));
                }
                closeQrScannerTabwaterModal();
            },
            () => {}
        ).catch(err => {
            console.error('ไม่สามารถเปิดกล้องได้:', err);
            alert('ไม่สามารถเปิดใช้งานกล้องได้ กรุณาตรวจสอบสิทธิ์การอนุญาตกล้อง');
        });
    }, { once: true });
}

function closeQrScannerTabwaterModal() {
    const modalEl = document.getElementById('qrScannerTabwaterModal');
    const modal = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;

    if (html5QrCodeForTabwater && html5QrCodeForTabwater.isScanning) {
        html5QrCodeForTabwater.stop().then(() => {
            if (modal) modal.hide();
        }).catch(err => console.error('Error stopping scanner:', err));
    } else if (modal) {
        modal.hide();
    }
}

function calculateWaterUsage() {
    const lastMeterVal = parseFloat(document.getElementById('modalLastMeterInput')?.value) || 0;
    const currentMeterVal = parseFloat(document.getElementById('modalCurrentMeterInput')?.value) || 0;

    let usage = currentMeterVal - lastMeterVal;
    if (usage < 0) usage = 0;

    const usageEl = document.getElementById('modalWaterUsageText');
    if (usageEl) {
        usageEl.innerText = usage.toFixed(0);
    }
    return usage;
}

function triggerMeterCamera() {
    const input = document.getElementById('meterCameraInput');
    if (input) input.click();
}

function handleImageCapture(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const image = document.getElementById('imageToCrop');
        if (!image) return;

        image.src = e.target.result;

        const cropContainer = document.getElementById('cropContainer');
        const btnProcessOcr = document.getElementById('btnProcessOcr');
        if (cropContainer) cropContainer.classList.remove('d-none');
        if (btnProcessOcr) btnProcessOcr.classList.remove('d-none');

        if (cropper) cropper.destroy();

        cropper = new Cropper(image, {
            viewMode: 1,
            autoCropArea: 0.6,
            movable: true,
            zoomable: true,
            rotatable: false,
            scalable: false
        });
    };
    reader.readAsDataURL(file);
}

function preprocessCanvasForOcr(sourceCanvas) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');

    canvas.width = sourceCanvas.width * 2;
    canvas.height = sourceCanvas.height * 2;

    ctx.drawImage(sourceCanvas, 0, 0, canvas.width, canvas.height);

    const imgData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const data = imgData.data;

    for (let i = 0; i < data.length; i += 4) {
        const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
        const color = avg > 130 ? 255 : 0;
        data[i] = color;
        data[i + 1] = color;
        data[i + 2] = color;
    }

    ctx.putImageData(imgData, 0, 0);
    return canvas;
}

async function processCroppedOcrWithClientSide() {
    if (!cropper) {
        alert('กรุณาเลือกพื้นที่ตัวเลขบนรูปภาพก่อนครับ');
        return;
    }

    const btn = document.getElementById('btnProcessOcr');
    const originalBtnText = btn ? btn.innerText : '🔍 อ่านตัวเลขจากพื้นที่ที่เลือก';

    const rawCanvas = cropper.getCroppedCanvas({ width: 400 });
    const processedCanvas = preprocessCanvasForOcr(rawCanvas);

    if (btn) {
        btn.innerText = '⏳ กำลังประมวลผลตัวเลข...';
        btn.disabled = true;
    }

    try {
        const worker = await Tesseract.createWorker('eng');
        await worker.setParameters({
            tessedit_char_whitelist: '0123456789',
            tessedit_pageseg_mode: Tesseract.PSM.SINGLE_LINE
        });

        const { data: { text } } = await worker.recognize(processedCanvas);
        await worker.terminate();

        const cleanedNumber = text.replace(/[^0-9]/g, '');
        if (cleanedNumber) {
            const padded = cleanedNumber.slice(-4).padStart(4, '0');
            ['digit1', 'digit2', 'digit3', 'digit4'].forEach((id, idx) => {
                const digitEl = document.getElementById(id);
                if (digitEl) digitEl.value = padded[idx] || '0';
            });
            updateMeterDigitsAndCalculate();
        } else {
            alert('อ่านตัวเลขไม่ชัดเจน กรุณากรอกตัวเลขด้วยตนเอง');
        }
    } catch (err) {
        console.error('Client-side OCR Error:', err);
        alert('เกิดข้อผิดพลาดในการประมวลผลรูปภาพ');
    } finally {
        if (btn) {
            btn.innerText = originalBtnText;
            btn.disabled = false;
        }
    }
}

function updateMeterDigitsAndCalculate() {
    const d1 = document.getElementById('digit1')?.value || '0';
    const d2 = document.getElementById('digit2')?.value || '0';
    const d3 = document.getElementById('digit3')?.value || '0';
    const d4 = document.getElementById('digit4')?.value || '0';

    const combinedValue = parseInt(`${d1}${d2}${d3}${d4}`, 10) || 0;
    const mainInput = document.getElementById('modalCurrentMeterInput');
    if (mainInput) {
        mainInput.value = combinedValue;
    }
    calculateWaterUsage();
}

function openRecordMeterModal(meterId, userName, lastMeter, invoiceId) {
    window.selectedMeterId = meterId;
    window.selectedInvoiceId = invoiceId;

    if (document.getElementById('modalMeterUserName')) {
        document.getElementById('modalMeterUserName').innerText = userName;
    }
    if (document.getElementById('modalLastMeterInput')) {
        document.getElementById('modalLastMeterInput').value = lastMeter;
    }
    ['digit1', 'digit2', 'digit3', 'digit4'].forEach(id => {
        const input = document.getElementById(id);
        if (input) input.value = '0';
    });
    if (document.getElementById('modalCurrentMeterInput')) {
        document.getElementById('modalCurrentMeterInput').value = 0;
    }
    if (document.getElementById('modalWaterUsageText')) {
        document.getElementById('modalWaterUsageText').innerText = '0';
    }

    const recordModalEl = document.getElementById('recordMeterModal');
    if (recordModalEl) {
        const modal = bootstrap.Modal.getOrCreateInstance(recordModalEl);
        modal.show();
    }
}

async function saveMeterRecord(isPrint = false) {
    const invoiceId = window.selectedInvoiceId;
    const currentMeter = document.getElementById('modalCurrentMeterInput')?.value;
    const lastMeter = document.getElementById('modalLastMeterInput')?.value;

    if (!invoiceId) {
        alert('ไม่พบข้อมูลใบแจ้งหนี้รอบปัจจุบัน');
        return;
    }
    if (currentMeter === '' || currentMeter === null) {
        alert('กรุณากรอกเลขมิเตอร์ครั้งนี้');
        return;
    }
    if (parseInt(currentMeter, 10) < parseInt(lastMeter || 0, 10)) {
        if (!confirm('เลขมิเตอร์ครั้งนี้น้อยกว่าครั้งก่อน คุณแน่ใจหรือไม่ที่จะบันทึก?')) {
            return;
        }
    }

    const btnSaveOnly = document.getElementById('btn-save-only');
    if (btnSaveOnly) btnSaveOnly.disabled = true;

    try {
        const response = await fetch(`${API_BASE_URL}/staff/tabwater/meter-records`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                invoice_id: invoiceId,
                current_reading: parseInt(currentMeter, 10)
            })
        });

        const resData = await response.json();

        if (response.ok && (resData.code === 200 || resData.success)) {
            generateWaterBillHTML(resData.data);

            await new Promise(resolve => setTimeout(resolve, 200));

            if (isPrint && typeof printReceipt === 'function') {
                await printReceipt();
            }

            const recordModalEl = document.getElementById('recordMeterModal');
            if (recordModalEl) {
                const modal = bootstrap.Modal.getInstance(recordModalEl);
                if (modal) modal.hide();
            }

            $('#card-reciept').html('');
            Swal.close();
            await loadWaterMembersList();
        } else {
            Swal.close();
            alert(resData.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
        }
    } catch (err) {
        console.error('Save Meter Record Error:', err);
        Swal.close();
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
    } finally {
        if (btnSaveOnly) btnSaveOnly.disabled = false;
    }
}

function generateWaterBillHTML(data) {
    if (!data) return;
    const recordDate = formatThaiDate(new Date(), 'short');
    const expireDate = formatThaiDate(data.expire_date || new Date(Date.now() + 15 * 24 * 60 * 60 * 1000));
    const org_img = window.ASSET_URL ? window.ASSET_URL + `logo/${data.org?.logo_img || 'ko_envsogo.png'}` : '/logo/hs_logo.png';

    let oweHtml = '';
    let increaseH = 1600;
    if (data.owes && data.owes.length > 0) {
        increaseH += (data.owes.length * 20);
        oweHtml = `
            <div style="border-top: 1px dotted #000; margin: 4px 0;"></div>
            <div style="font-weight: bold; font-size: 1.7rem;">รายการค้างชำระเดิม:</div>
            <ul style="padding-left:15px; margin:2px 0;font-size: 1.7rem">`;

        data.owes.forEach(item => {
            oweHtml += `<li>${item.invoice_period} <span style="float:right;">${parseFloat(item.totalpaid).toFixed(2)} <sup>บาท</sup></span></li>`;
        });
        oweHtml += `</ul>`;
    }

    const zero18 = '000000000000000000';
    const invIdStr = String(data.invoice_id || '0');
    const meterIdStr = String(data.meter_id || '0');
    const paddedInvId = zero18.slice(0, 18 - invIdStr.length) + invIdStr;
    const paddedMeterId = zero18.slice(0, 18 - meterIdStr.length) + meterIdStr;
    const qrPayload = `|099400035262000\n${paddedMeterId}\n${paddedInvId}\n${String(data.net_paid || 0).replace('.', '')}`;

    $('#card-reciept').css('height', `${increaseH}px`);
    $('#card-reciept').html(`
        <style>
            #card-reciept { font-family: "Kanit", sans-serif; }
            .style-title { font-size: 2.5rem; font-weight: bold; }
            .style-header { font-size: 1.8rem; font-weight: bold; }
            .style-subhead { font-size: 1.7rem; font-weight: bold; }
            .style-body { font-size: 1.7rem; }
            .style-small { font-size: 1.3rem; }
            .logo { width: 200px; height: 200px; }
            #qrcode img, #qrcode canvas { display: block; margin: 0 auto; }
        </style>
        <div class="receipt-container">
            <div class="text-center mb-1">
                <img src="${org_img}" class="img-fluid mb-1 logo" alt="Logo">
                <div class="style-title">${data.org?.name || ''}</div>
                <div class="style-small">${data.org?.address || ''} ${data.org?.zone || ''} ต.${data.org?.tambon || ''} อ.${data.org?.district || ''} จ.${data.org?.province || ''}</div>
                <div class="style-small">โทร. ${data.org?.phone || '-'}</div>
            </div>
            <div class="text-center my-1">
                <div class="style-header">ใบแจ้งหนี้ / ใบชำระค่าน้ำประปา</div>
                <div class="style-body">รอบบิล: ${data.inv_period_name || '-'} | เลขที่: ${data.invoice_id || '-'}</div>
            </div>
            <div class="border-top border-dark border-1 my-1"></div>
            <div class="style-body mb-1">
                <div class="d-flex justify-content-between">
                    <span class="text-nowrap">ผู้ใช้น้ำ:</span>
                    <span class="text-end">${data.user?.name || '-'} ${data.user?.subname || ''}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-nowrap">ที่อยู่:</span>
                    <span class="text-end">${data.user?.address || ''} ${data.user?.zone || ''}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-nowrap">เลขผู้ใช้:</span>
                    <span class="text-end">${data.meter_id || '-'}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-nowrap">เลขมิเตอร์:</span>
                    <span class="text-end">${data.meternumber || '-'}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-nowrap">วันที่จด:</span>
                    <span class="text-end">${recordDate}</span>
                </div>
            </div>
            <div class="border-top border-dark border-1 my-1"></div>
            <div class="style-body">
                <div class="d-flex justify-content-between">
                    <span>เลขครั้งนี้:</span>
                    <span>${data.currentmeter}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>เลขครั้งก่อน:</span>
                    <span>${data.lastmeter}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold style-subhead">
                    <span>ปริมาณที่ใช้:</span>
                    <span>${data.water_used} <sup>หน่วย</sup></span>
                </div>
            </div>
            <div class="border-top border-dark border-1 my-1 col-5" style="margin-left:63%"></div>
            <div class="style-body">
                <div class="d-flex justify-content-between">
                    <span>ค่าน้ำประปา:</span>
                    <span>${parseFloat(data.paid || 0).toFixed(2)}<sup>บาท</sup></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>ค่าบริการมิเตอร์:</span>
                    <span>${parseFloat(data.reserve_meter || 0).toFixed(2)}<sup>บาท</sup></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>ภาษี (VAT):</span>
                    <span>${parseFloat(data.vat || 0).toFixed(2)}<sup>บาท</sup></span>
                </div>
                <div class="d-flex justify-content-between fw-bold style-subhead pt-1">
                    <span>รวมเป็นเงิน:</span>
                    <span>${parseFloat(data.totalpaid || 0).toFixed(2)}<sup>บาท</sup></span>
                </div>
            </div>
            ${oweHtml}
            <div class="border-top border-dark border-2 my-1"></div>
            <div class="d-flex justify-content-between align-items-center fw-bold style-header my-1">
                <span>รวมเงินทั้งสิ้น:</span>
                <span>${parseFloat(data.net_paid || 0).toFixed(2)} <sup>บาท</sup></span>
            </div>
            <div class="text-center my-2">
                <div class="style-subheader">สแกนเพื่อชำระเงิน</div>
                <div class="fw-bold style-subheader">จำนวน ${parseFloat(data.net_paid || 0).toFixed(2)} บาท</div>
                <div id="qrcode" class="d-flex justify-content-center my-1"></div>
            </div>
            <div class="text-center p-1 border border-dark rounded my-1 style-body">
                <div class="fw-bold">กำหนดชำระภายใน:</div>
                ${expireDate}
            </div>
        </div>
    `);

    if (typeof QRCode !== 'undefined' && document.getElementById('qrcode')) {
        new QRCode(document.getElementById('qrcode'), {
            text: qrPayload,
            width: 230,
            height: 230,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
}

function formatThaiDate(dateInput) {
    if (!dateInput) return '-';
    const date = new Date(dateInput);
    if (isNaN(date.getTime())) return '-';
    const thaiMonthsShort = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    return `${date.getDate()} ${thaiMonthsShort[date.getMonth()]} ${date.getFullYear() + 543}`;
}

// =========================================================================
//  ♻️ 4. Recycle Bank & Deposit Cart Module (ธนาคารขยะรีไซเคิล)
// =========================================================================

async function loadMembersFromServer() {
    const org_id_fk = localStorage.getItem('staff_org_id') || sessionStorage.getItem('staff_org_id');
    if (!org_id_fk) return;

    try {
        const response = await fetch(`${API_BASE_URL}/keptkaya/members/${org_id_fk}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const resData = await response.json();
        if (resData.code === 200 && Array.isArray(resData.data)) {
            allMembers = resData.data;
            updateMemberListUI();
        }
    } catch (error) {
        console.error('เกิดข้อผิดพลาดในการโหลดข้อมูลสมาชิก:', error);
    }
}

function renderMemberList(members) {
    const container = document.getElementById('memberListContainer');
    const countSpan = document.getElementById('memberCount');
    if (!container || !countSpan) return;

    container.innerHTML = '';
    countSpan.innerText = members.length;

    if (members.length === 0) {
        container.innerHTML = `<p class="text-center text-muted my-4">ไม่พบรายชื่อสมาชิกในหมวดนี้</p>`;
        return;
    }

    members.forEach(member => {
        const account = member.waste_preference?.kp_bank_account?.account_no || 'ไม่มีบัญชี';
        const card = document.createElement('div');
        card.className = 'member-card';
        card.innerHTML = `
            <div class="member-info">
                <p class="fw-bold mb-1 text-dark" style="font-size: 15px;">${member.firstname || ''} ${member.lastname || ''}</p>
                <p class="mb-1 text-muted" style="font-size: 13px;">📞 เบอร์โทร: ${member.phone || 'ไม่มีข้อมูล'}</p>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1" style="font-size: 11px;">
                    เลขบัญชี: ${account}
                </span>
            </div>
            <div>
                <button type="button" class="btn btn-success btn-sm rounded-pill px-3 py-2 fw-bold shadow-sm" onclick="selectMemberToDeposit(${member.id})">
                    💰 รับซื้อขยะ
                </button>
            </div>
        `;
        container.appendChild(card);
    });
}

function filterMembers() {
    const searchInput = document.getElementById('searchMemberRecycleInput');
    const searchText = (searchInput?.value || '').toLowerCase();
    const filtered = allMembers.filter(member => {
        const fullName = `${member.firstname || ''} ${member.lastname || ''}`.toLowerCase();
        const phone = (member.phone || '').toLowerCase();
        return fullName.includes(searchText) || phone.includes(searchText);
    });
    renderMemberList(filtered);
}

function switchTab(tabName) {
    currentTab = tabName;
    const btnPending = document.getElementById('btnTabPending');
    const btnCompleted = document.getElementById('btnTabCompleted');

    if (btnPending && btnCompleted) {
        if (tabName === 'pending') {
            btnPending.style.background = '#007bff';
            btnPending.style.color = 'white';
            btnCompleted.style.background = 'transparent';
            btnCompleted.style.color = '#333';
        } else {
            btnCompleted.style.background = '#28a745';
            btnCompleted.style.color = 'white';
            btnPending.style.background = 'transparent';
            btnPending.style.color = '#333';
        }
    }
    updateMemberListUI();
}

function updateMemberListUI() {
    const pendingTotal = allMembers.filter(m => m.status !== 'completed').length;
    const completedTotal = allMembers.filter(m => m.status === 'completed').length;

    if (document.getElementById('countPending')) document.getElementById('countPending').innerText = pendingTotal;
    if (document.getElementById('countCompleted')) document.getElementById('countCompleted').innerText = completedTotal;

    const filteredMembers = currentTab === 'pending'
        ? allMembers.filter(m => m.status !== 'completed')
        : allMembers.filter(m => m.status === 'completed');

    renderMemberList(filteredMembers);
}

function onDepositSuccess(completedMemberId) {
    const member = allMembers.find(m => m.id === completedMemberId);
    if (member) {
        member.status = 'completed';
    }
    updateMemberListUI();
}

async function selectMemberToDeposit(memberId) {
    const member = allMembers.find(m => m.id === memberId);
    if (!member) return;

    currentActiveMember = member;
    const accountNo = member.waste_preference?.kp_bank_account?.account_no || '-';

    if (document.getElementById('lblDepositMemberName')) {
        document.getElementById('lblDepositMemberName').innerText = `คุณ ${member.firstname || ''} ${member.lastname || ''}`;
    }
    if (document.getElementById('lblDepositMemberAcc')) {
        document.getElementById('lblDepositMemberAcc').innerText = accountNo;
    }
    if (document.getElementById('lblActiveStaffName')) {
        document.getElementById('lblActiveStaffName').innerText = localStorage.getItem('staff_name') || sessionStorage.getItem('staff_name') || 'เจ้าหน้าที่';
    }

    resetSelection();

    try {
        const response = await fetch(`${API_BASE_URL}/keptkaya/kp_items_recycle_info`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            }
        });

        if (!response.ok) {
            throw new Error(`Server ตอบกลับผิดพลาด: ${response.status}`);
        }

        rawItemsData = await response.json();
    } catch (error) {
        console.error('เกิดข้อผิดพลาดตอนดึงข้อมูลขยะ:', error);
        rawItemsData = [
            { id: 10, kp_itemscode: 'P001', kp_itemsname: 'ขวดพลาสติกใส (PET)', group_id: 3, group_name: 'พลาสติก', price: 4.5, point: 5, unitname: 'กิโลกรัม', unit_name: 'กิโลกรัม', unit_short_name: 'กก.' },
            { id: 11, kp_itemscode: 'P002', kp_itemsname: 'ขวดพลาสติกขุ่น (HDPE)', group_id: 3, group_name: 'พลาสติก', price: 2.0, point: 2, unitname: 'กิโลกรัม', unit_name: 'กิโลกรัม', unit_short_name: 'กก.' },
            { id: 20, kp_itemscode: 'W001', kp_itemsname: 'กระดาษลัง', group_id: 1, group_name: 'กระดาษ', price: 7.5, point: 8, unitname: 'กิโลกรัม', unit_name: 'กิโลกรัม', unit_short_name: 'กก.' },
            { id: 30, kp_itemscode: 'G001', kp_itemsname: 'ขวดแก้วสีชา', group_id: 2, group_name: 'แก้ว/ขวด', price: 1.5, point: 2, unitname: 'กิโลกรัม', unit_name: 'กิโลกรัม', unit_short_name: 'กก.' },
            { id: 40, kp_itemscode: 'M001', kp_itemsname: 'กระป๋องอลูมิเนียม', group_id: 4, group_name: 'โลหะ', price: 48.0, point: 50, unitname: 'กิโลกรัม', unit_name: 'กิโลกรัม', unit_short_name: 'กก.' }
        ];
    }

    renderGroupFilters();
    renderFloatingCart();
    navigateTo('depositScreen');
}

function renderGroupFilters() {
    const container = document.getElementById('groupFiltersContainer');
    if (!container) return;
    container.innerHTML = '';

    const groups = [];
    const map = new Map();
    for (const item of rawItemsData) {
        if (!map.has(item.group_id)) {
            map.set(item.group_id, true);
            groups.push({ id: item.group_id, name: item.group_name });
        }
    }

    groups.forEach(g => {
        const filterDiv = document.createElement('div');
        filterDiv.className = 'col-3 filter-item';
        filterDiv.onclick = () => triggerFilter(g.id, filterDiv);

        let icon = '♻️';
        if ((g.name || '').includes('กระดาษ')) icon = '📦';
        else if ((g.name || '').includes('แก้ว')) icon = '🍾';
        else if ((g.name || '').includes('พลาสติก')) icon = '🥤';
        else if ((g.name || '').includes('โลหะ')) icon = '🥫';

        filterDiv.innerHTML = `
            <button type="button" class="btn btn-outline-success rounded-circle shadow-sm mx-auto d-flex align-items-center justify-content-center"
                style="width: 56px; height: 56px; font-size: 22px; background: #fff;">
                ${icon}
            </button>
            <small class="d-block mt-1 fw-bold text-dark" style="font-size: 12px;">${g.name}</small>
        `;
        container.appendChild(filterDiv);
    });
}

function triggerFilter(groupId, element) {
    const subContainer = document.getElementById('item-buttons-container');
    if (!subContainer) return;
    subContainer.innerHTML = '';

    const filteredItems = rawItemsData.filter(item => item.group_id === groupId);
    filteredItems.forEach(opt => {
        const gridCol = document.createElement('div');
        gridCol.className = 'trash-item-card-compact';
        gridCol.id = `card-${opt.kp_itemscode}`;
        gridCol.onclick = () => selectTrashItem(opt.kp_itemscode);
        gridCol.innerHTML = `
            <div class="item-title fw-bold text-dark">${opt.kp_itemsname}</div>
            <div class="item-price text-success fw-semibold small">${opt.price} บ. / ${opt.unitname || opt.unit_short_name || 'กก.'}</div>
        `;
        subContainer.appendChild(gridCol);
    });

    openCategoryModal();
}

function openCategoryModal() {
    const modal = document.getElementById('categoryItemsModal');
    if (modal) modal.classList.add('is-active');
}

function closeCategoryModal() {
    const modal = document.getElementById('categoryItemsModal');
    if (modal) modal.classList.remove('is-active');
}

function selectTrashItem(itemCode) {
    const trash = rawItemsData.find(item => item.kp_itemscode === itemCode);
    if (!trash) return;

    currentSelectedTrash = trash;
    closeCategoryModal();
    openWeightModal(trash.kp_itemsname);

    const mockupUnits = [{ id: 1, unitname: trash.unitname || trash.unit_short_name || 'กก.' }];
    renderUnitButtons(trash.units || mockupUnits);
}

function renderUnitButtons(unitsArray) {
    const container = document.getElementById('unit-buttons-container');
    if (!container) return;
    container.innerHTML = '';

    if (!unitsArray || unitsArray.length === 0) {
        unitsArray = [{ id: 1, unitname: 'กก.' }];
    }

    unitsArray.forEach((unit, index) => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.innerText = unit.unitname;
        btn.style.cssText = 'flex: 1; height: 56px; border: 1px solid #ced4da; background: #f8f9fa; border-radius: 12px; font-weight: bold; font-size: 15px; cursor: pointer;';

        if (index === 0) {
            selectUnitItem(unit.id, btn);
        }

        btn.onclick = () => selectUnitItem(unit.id, btn);
        container.appendChild(btn);
    });
}

function selectUnitItem(unitId, element) {
    document.querySelectorAll('#unit-buttons-container button').forEach(b => {
        b.style.background = '#f8f9fa';
        b.style.color = '#212529';
        b.style.borderColor = '#ced4da';
    });
    element.style.background = '#0d6efd';
    element.style.color = '#ffffff';
    element.style.borderColor = '#0d6efd';

    currentSelectedUnitId = unitId;
    const unitInput = document.getElementById('kp_units_idfk');
    if (unitInput) unitInput.value = unitId;
}

function resetSelection() {
    currentSelectedTrash = null;
    currentSelectedUnitId = null;
    const amountInput = document.getElementById('amount_in_units');
    const kpUnitsInput = document.getElementById('kp_units_idfk');
    if (amountInput) amountInput.value = '';
    if (kpUnitsInput) kpUnitsInput.value = '';

    const unitContainer = document.getElementById('unit-buttons-container');
    if (unitContainer) unitContainer.innerHTML = '';
}

function openWeightModal(itemName) {
    const modal = document.getElementById('weightInputModal');
    if (!modal) return;

    if (document.getElementById('modal-selected-item-name')) {
        document.getElementById('modal-selected-item-name').innerText = itemName;
    }
    const amountInput = document.getElementById('amount_in_units');
    if (amountInput) amountInput.value = '';

    modal.classList.add('is-active');
    setTimeout(() => {
        if (amountInput) amountInput.focus();
    }, 250);
}

function closeWeightModal() {
    const modal = document.getElementById('weightInputModal');
    if (modal) modal.classList.remove('is-active');
}

function renderFloatingCart() {
    const container = document.getElementById('floatingCartContainer');
    if (!container) return;
    container.innerHTML = '';
    if (purchaseCart.length === 0) return;

    let grandTotal = 0;
    purchaseCart.forEach(c => grandTotal += c.amount);

    const cartBtn = document.createElement('button');
    cartBtn.type = 'button';
    cartBtn.style.cssText = 'position:fixed; bottom:85px; right:24px; width:68px; height:68px; border-radius:50%; background:#28a745; color:#fff; border:3px solid #fff; box-shadow:0 8px 24px rgba(40, 167, 69, 0.45); font-size:28px; cursor:pointer; z-index:999; display:flex; align-items:center; justify-content:center;';
    cartBtn.innerHTML = `🛒<span style="position:absolute; top:-8px; right:-6px; background:#dc3545; font-size:14px; font-weight:bold; padding:2px 9px; border-radius:50px; border:2px solid #fff;">${purchaseCart.length}</span>`;
    cartBtn.onclick = () => openCartModal(grandTotal);
    container.appendChild(cartBtn);
}

function openCartModal(grandTotal) {
    const oldModal = document.getElementById('customCartModal');
    if (oldModal) oldModal.remove();

    const modal = document.createElement('div');
    modal.id = 'customCartModal';
    modal.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.55); display:flex; align-items:center; justify-content:center; z-index:10000; padding:15px; box-sizing:border-box;';

    let tableRows = '';
    purchaseCart.forEach((item, index) => {
        tableRows += `
            <tr style="border-bottom:1px solid #eee; height:48px;">
                <td style="padding:5px;"><b>${item.item_name}</b><br><small style="color:#888;">${item.price_per_unit.toFixed(2)} บ./${item.unit_short_name || 'กก.'}</small></td>
                <td style="text-align:right; padding:5px;">${item.amount_in_units.toFixed(2)} ${item.unit_short_name || 'กก.'}</td>
                <td style="text-align:right; padding:5px; color:#28a745; font-weight:bold;">${item.amount.toFixed(2)} บ.</td>
                <td style="text-align:center; padding:5px;"><button type="button" onclick="removeFromCart(${index})" style="background:none; border:none; color:#dc3545; font-size:16px; cursor:pointer;">🗑️</button></td>
            </tr>
        `;
    });

    modal.innerHTML = `
        <div style="background:#fff; width:100%; max-width:460px; border-radius:20px; padding:20px; box-shadow:0 10px 25px rgba(0,0,0,0.15);">
            <h5 class="fw-bold text-dark mb-3">🛒 รายการรับซื้อขยะในตะกร้า</h5>
            <div style="max-height:240px; overflow-y:auto; margin-bottom:15px;">
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <thead>
                        <tr style="background:#f8f9fa; height:35px; text-align:left; color:#6c757d;">
                            <th class="ps-2">รายการ</th><th style="text-align:right;">จำนวน</th><th style="text-align:right;">รวม</th><th style="text-align:center;">ลบ</th>
                        </tr>
                    </thead>
                    <tbody>${tableRows}</tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3 mb-3">
                <div>
                    <small class="text-muted d-block">ยอดสุทธิรวม</small>
                    <span class="fs-4 fw-bold text-success">${grandTotal.toFixed(2)} บาท</span>
                </div>
                <label class="d-flex align-items-center gap-2 fw-bold text-dark mb-0" style="cursor:pointer;">
                    <input type="checkbox" id="cashback" name="cashback" style="width:20px; height:20px;"> จ่ายเงินสด
                </label>
            </div>
            <div class="d-flex gap-2">
                <button type="button" onclick="closePurchaseModal()" class="btn btn-light border rounded-pill fw-bold flex-fill py-2">ปิด</button>
                <button type="button" onclick="submitFinalPurchase(0)" class="btn btn-primary rounded-pill fw-bold flex-fill py-2">📝 บันทึก</button>
                <button type="button" onclick="submitFinalPurchase(1)" id="submit_and_print" class="btn btn-success rounded-pill fw-bold flex-fill py-2">🖨️ บันทึก & พิมพ์</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

function removeFromCart(index) {
    purchaseCart.splice(index, 1);
    localStorage.setItem('current_purchase_cart', JSON.stringify(purchaseCart));
    closePurchaseModal();
    renderFloatingCart();
    if (purchaseCart.length > 0) {
        let grandTotal = 0;
        purchaseCart.forEach(c => grandTotal += c.amount);
        openCartModal(grandTotal);
    }
}

function closePurchaseModal() {
    const modal = document.getElementById('customCartModal');
    if (modal) modal.remove();
}

async function submitFinalPurchase(print_bill) {
    if (purchaseCart.length === 0) {
        alert('❌ ไม่มีรายการขยะในตะกร้า');
        return;
    }

    if (print_bill === 1 && !printCharacteristic) {
        BluethoothConnectedModal('recycle');
        return;
    }

    hydrateStaffInfo();

    const orgId = staffInfo?.org_id_fk || localStorage.getItem('staff_org_id') || sessionStorage.getItem('staff_org_id');
    const recorderId = staffInfo?.id || localStorage.getItem('staff_id') || sessionStorage.getItem('staff_id');

    const totalWeight = purchaseCart.reduce((sum, item) => sum + parseFloat(item.amount_in_units || 0), 0);
    const totalAmount = purchaseCart.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
    const totalPoints = purchaseCart.reduce((sum, item) => sum + parseInt(item.point || 0, 10), 0);

    const payload = {
        org_id_fk: orgId,
        kp_user_w_pref_id_fk: currentActiveMember?.waste_preference?.id,
        user_id: currentActiveMember?.id,
        recorder_id: recorderId,
        total_weight: totalWeight,
        cashback: document.getElementById('cashback')?.checked || false,
        total_amount: totalAmount,
        total_points: totalPoints,
        total_carbon_saved: 0.0000,
        cart_items: purchaseCart.map(item => ({
            kp_recycle_item_id: item.item_id,
            kp_tbank_items_pricepoint_id: item.item_prc_pnt_id || 1,
            amount_in_units: parseFloat(item.amount_in_units),
            kp_units_idfk: item.unit_id || 1,
            price_per_unit: parseFloat(item.price_per_unit),
            amount: parseFloat(item.price_per_unit * item.amount_in_units),
            points: parseInt((item.point || 0) * item.amount_in_units, 10),
            carbon_saved: 0.0000
        }))
    };

    try {
        Swal.fire({
            title: 'กำลังบันทึกข้อมูล...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const response = await fetch(`${API_BASE_URL}/keptkaya/store_purchase`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'ngrok-skip-browser-warning': 'true'
            },
            body: JSON.stringify(payload)
        });

        const resData = await response.json();

        if (resData.code === 200) {
            if (print_bill === 1 && typeof printReceipt === 'function') {
                await printReceipt();
            }

            purchaseCart = [];
            localStorage.removeItem('current_purchase_cart');
            closePurchaseModal();
            renderFloatingCart();
            $('#card-reciept').html('');

            if (currentActiveMember) {
                onDepositSuccess(currentActiveMember.id);
            }

            Swal.close();
            await Swal.fire({
                icon: 'success',
                title: 'บันทึกรับซื้อขยะสำเร็จ!',
                timer: 1400,
                showConfirmButton: false
            });

            backToRecycleScreen();
        } else {
            Swal.close();
            alert('❌ เซิร์ฟเวอร์ปฏิเสธการบันทึก: ' + (resData.message || ''));
        }
    } catch (error) {
        console.error('Submit Purchase Error:', error);
        Swal.close();
        alert('❌ ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาเช็กอินเทอร์เน็ตหน้างาน');
    }
}

function backToRecycleScreen() {
    currentActiveMember = null;
    navigateTo('recycleBankScreen');
}

function showSuccessToast(message) {
    const toast = document.getElementById('successToast');
    const toastText = document.getElementById('successToastText');
    if (!toast || !toastText) return;

    toastText.innerText = message;
    toast.classList.remove('is-hidden');
    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('is-hidden');
    }, 900);
}

function stopWebScanner() {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            const el = document.getElementById('qr-reader-container');
            if (el) el.style.display = 'none';
        }).catch(err => console.error('Failed to stop scanner', err));
    } else {
        const scannerContainer = document.getElementById('qr-reader-container');
        if (scannerContainer) scannerContainer.style.display = 'none';
    }
}

// =========================================================================
//  🖨️ 5. Bluetooth Thermal Printer (58mm)
// =========================================================================

function updateGlobalPrinterStatus() {
    const isConnected = Boolean(isBluethoothConnected || printCharacteristic);
    const printerName = printer_name || 'เครื่องพิมพ์ Bluetooth';

    const statusIds = [
        { box: 'global_printer_status', name: 'lbl_global_printer_name', dot: 'lbl_global_printer_indicator' },
        { box: 'globalPrinterStatus', name: 'lblGlobalPrinterName', dot: 'lblGlobalPrinterIndicator' }
    ];

    statusIds.forEach(cfg => {
        const box = document.getElementById(cfg.box);
        const lblName = document.getElementById(cfg.name);
        const lblDot = document.getElementById(cfg.dot);
        if (!box || !lblName || !lblDot) return;

        if (isConnected) {
            lblName.innerText = `🟢 เชื่อมต่ออยู่กับ (${printerName})`;
            lblName.style.color = '#28a745';
            lblDot.style.background = '#28a745';
            box.style.borderLeft = '5px solid #28a745';
        } else {
            lblName.innerText = '🔴 ยังไม่ได้เชื่อมต่อ (แตะเพื่อเชื่อมต่อ)';
            lblName.style.color = '#dc3545';
            lblDot.style.background = '#dc3545';
            box.style.borderLeft = '5px solid #dc3545';
        }
    });

    const homeStatus = document.getElementById('lblHomePrinterStatus');
    if (homeStatus) {
        homeStatus.innerText = isConnected ? `เชื่อมต่อแล้ว (${printerName})` : 'เช็คเครื่องพิมพ์';
        homeStatus.className = isConnected ? 'text-success fw-semibold' : 'text-danger fw-semibold';
    }
}

function updateStatus(message, type = 'info') {
    const statusTextEl = document.getElementById('status-text');
    const statusMainEl = document.getElementById('status');
    const statusCardEl = document.getElementById('status-card');
    const connectBtnEl = document.getElementById('connectButton');
    const printBtnEl = document.getElementById('printImageButton');

    if (statusTextEl) statusTextEl.textContent = message;
    if (statusMainEl) statusMainEl.textContent = message;

    if (!statusCardEl) return;

    if (type === 'success') {
        const printerName = printer_name || 'เครื่องพิมพ์';
        if (statusMainEl) {
            statusMainEl.innerText = `🟢 เชื่อมต่ออยู่กับ: ${printerName}`;
            statusMainEl.className = 'fw-bold text-success';
        }
        if (connectBtnEl) connectBtnEl.classList.replace('btn-info', 'btn-success');
        if (printBtnEl) printBtnEl.disabled = false;
    } else if (type === 'error') {
        if (statusMainEl) {
            statusMainEl.innerText = `🔴 ${message}`;
            statusMainEl.className = 'fw-bold text-danger';
        }
    }
}

async function connectToPrinter() {
    updateStatus('กำลังค้นหาเครื่องพิมพ์...', 'info');

    if (!navigator.bluetooth) {
        updateStatus('Browser ไม่รองรับ Web Bluetooth (แนะนำให้เปิดด้วย Chrome บน Android)', 'error');
        return;
    }

    try {
        let selectedDevice = null;
        const lastDeviceId = localStorage.getItem(LAST_USED_DEVICE_ID_KEY);

        if (lastDeviceId && navigator.bluetooth.getDevices) {
            try {
                const devices = await navigator.bluetooth.getDevices();
                selectedDevice = devices.find(d => d.id === lastDeviceId);
            } catch (e) {}
        }

        if (!selectedDevice) {
            selectedDevice = await navigator.bluetooth.requestDevice({
                filters: [{ services: [PRINTER_SERVICE_UUID] }],
                optionalServices: [PRINTER_SERVICE_UUID]
            });
            localStorage.setItem(LAST_USED_DEVICE_ID_KEY, selectedDevice.id);
        }

        bluetoothDevice = selectedDevice;
        bluetoothDevice.addEventListener('gattserverdisconnected', onDisconnected);

        const server = await bluetoothDevice.gatt.connect();
        const service = await server.getPrimaryService(PRINTER_SERVICE_UUID);
        printCharacteristic = await service.getCharacteristic(PRINTER_CHARACTERISTIC_UUID);

        isBluethoothConnected = true;
        printer_name = bluetoothDevice.name || 'Thermal Printer';
        updateStatus(`เชื่อมต่อ ${printer_name} สำเร็จ`, 'success');
        updateGlobalPrinterStatus();
    } catch (error) {
        updateStatus(`เชื่อมต่อไม่สำเร็จ: ${error.message}`, 'error');
    }
}

function onDisconnected() {
    isBluethoothConnected = false;
    printCharacteristic = null;
    updateStatus('เครื่องพิมพ์หลุดการเชื่อมต่อ', 'error');
    updateGlobalPrinterStatus();
}

function getMonochromeBitmapData(ctx, width, height) {
    const imageData = ctx.getImageData(0, 0, width, height);
    const data = imageData.data;
    const bitmap = new Uint8Array(Math.ceil(width / 8) * height);

    for (let y = 0; y < height; y++) {
        for (let x = 0; x < width; x++) {
            const i = (y * width + x) * 4;
            const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
            if (avg < 128) {
                const byteIndex = y * Math.ceil(width / 8) + Math.floor(x / 8);
                const bitIndex = 7 - (x % 8);
                bitmap[byteIndex] |= (1 << bitIndex);
            }
        }
    }
    return bitmap;
}

async function printReceipt() {
    if (!printCharacteristic) {
        updateStatus('กรุณาเชื่อมต่อเครื่องพิมพ์ก่อนสั่งพิมพ์', 'error');
        return false;
    }

    const printButton = document.getElementById('printImageButton');
    const printBtnText = document.getElementById('printBtnText');
    const originalText = printBtnText ? printBtnText.textContent : 'พิมพ์ใบเสร็จ';

    if (printButton) printButton.disabled = true;
    if (printBtnText) printBtnText.textContent = 'กำลังส่งข้อมูล...';

    try {
        const receiptElement = document.getElementById('card-reciept');
        if (!receiptElement) throw new Error('ไม่พบ Element #card-reciept');

        const canvas = await html2canvas(receiptElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        });

        const printerWidth = 384;
        const scaleFactor = printerWidth / canvas.width;
        const printerHeight = Math.floor(canvas.height * scaleFactor);

        const printCanvas = document.createElement('canvas');
        printCanvas.width = printerWidth;
        printCanvas.height = printerHeight;
        const ctx = printCanvas.getContext('2d');

        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, printerWidth, printerHeight);
        ctx.drawImage(canvas, 0, 0, printerWidth, printerHeight);

        const bitmapData = getMonochromeBitmapData(ctx, printerWidth, printerHeight);
        const bytesPerRow = Math.ceil(printerWidth / 8);

        const command = new Uint8Array([
            0x1D, 0x76, 0x30, 0x00,
            bytesPerRow & 0xFF, (bytesPerRow >> 8) & 0xFF,
            printerHeight & 0xFF, (printerHeight >> 8) & 0xFF
        ]);

        const dataToSend = new Uint8Array(command.length + bitmapData.length);
        dataToSend.set(command, 0);
        dataToSend.set(bitmapData, command.length);

        const CHUNK_SIZE = 170;
        for (let i = 0; i < dataToSend.length; i += CHUNK_SIZE) {
            const chunk = dataToSend.slice(i, i + CHUNK_SIZE);
            await printCharacteristic.writeValueWithoutResponse(chunk);
            await new Promise(r => setTimeout(r, 20));
        }

        await printCharacteristic.writeValueWithoutResponse(new Uint8Array([0x0A, 0x0A, 0x0A]));
        updateStatus('พิมพ์เสร็จสิ้น', 'success');
        return true;
    } catch (error) {
        updateStatus(`Error: ${error.message || error}`, 'error');
        return false;
    } finally {
        if (printButton) printButton.disabled = false;
        if (printBtnText) printBtnText.textContent = originalText;
    }
}

function BluethoothConnectedModal(from) {
    if (!printCharacteristic) {
        if (from === 'recycle') {
            closePurchaseModal();
        }

        Swal.fire({
            title: 'ยังไม่ได้เชื่อมต่อเครื่องพิมพ์!',
            text: 'กรุณากดเชื่อมต่อเครื่องพิมพ์บลูทูธก่อนทำการบันทึกและพิมพ์บิล',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '🔄 เชื่อมต่อเครื่องพิมพ์ตอนนี้',
            cancelButtonText: 'ยกเลิก'
        }).then(async (result) => {
            if (result.isConfirmed) {
                await connectToPrinter();
            }
        });
        return;
    }

    Swal.fire({
        title: 'กำลังบันทึกข้อมูล...',
        text: 'กรุณารอสักครู่ ห้ามปิดหน้าต่างนี้',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// =========================================================================
//  🚀 6. DOMContentLoaded Initialization & Event Listeners
// =========================================================================

document.addEventListener('DOMContentLoaded', () => {
    // 1. ตรวจสอบ Session ทันทีเมื่อเปิดแอป
    checkExistingSession();

    // 2. ผูกฟอร์ม Login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            handleStaffLogin();
        });
    }

    // 3. ปุ่ม Logout
    const btnLogout = document.getElementById('btnLogout');
    if (btnLogout) {
        btnLogout.addEventListener('click', () => {
            handleForceLogout();
        });
    }

    // 4. ช่องค้นหาผู้ใช้น้ำประปา
    document.getElementById('searchMemberInput')?.addEventListener('input', function (e) {
        const keyword = e.target.value.toLowerCase().trim();
        document.querySelectorAll('.member-card-item').forEach(card => {
            if (card.innerText.toLowerCase().includes(keyword)) {
                card.classList.remove('d-none');
            } else {
                card.classList.add('d-none');
            }
        });
    });

    // 5. ช่องกรอกเลขมิเตอร์ 4 หลัก
    const digits = document.querySelectorAll('.meter-digit-input');
    digits.forEach((digit, index) => {
        digit.addEventListener('focus', function () {
            this.select();
        });

        digit.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length >= 1) {
                this.value = this.value.slice(-1);
                if (index < digits.length - 1) {
                    setTimeout(() => {
                        digits[index + 1].focus();
                        digits[index + 1].select();
                    }, 10);
                }
            }
            updateMeterDigitsAndCalculate();
        });

        digit.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace') {
                if (!this.value && index > 0) {
                    digits[index - 1].focus();
                    digits[index - 1].select();
                } else {
                    this.value = '';
                    updateMeterDigitsAndCalculate();
                }
            }
        });
    });

    // 6. ปุ่มบันทึกเลขมิเตอร์น้ำ
    document.getElementById('btn-save-only')?.addEventListener('click', () => {
        saveMeterRecord(false);
    });

    document.getElementById('btn-save-and-print')?.addEventListener('click', () => {
        BluethoothConnectedModal('tabwater');
        if (printCharacteristic) {
            saveMeterRecord(true);
        }
    });

    // 7. ปุ่มเพิ่มรายการขยะลงตะกร้า
    document.getElementById('btnAddToCart')?.addEventListener('click', () => {
        if (!currentSelectedTrash) {
            alert('กรุณาเลือกรายการขยะก่อนครับ');
            return;
        }

        const weight = parseFloat(document.getElementById('amount_in_units')?.value) || 0;
        if (weight <= 0) {
            alert('กรุณากรอกจำนวน/น้ำหนักขยะที่รับซื้อ');
            return;
        }

        const itemTotalPrice = currentSelectedTrash.price * weight;
        const savedItemName = currentSelectedTrash.kp_itemsname;

        purchaseCart.push({
            item_prc_pnt_id: currentSelectedTrash.prc_pnt_id || 1,
            item_id: currentSelectedTrash.id,
            item_code: currentSelectedTrash.kp_itemscode,
            item_name: currentSelectedTrash.kp_itemsname,
            price_per_unit: parseFloat(currentSelectedTrash.price || 0),
            point: parseInt(currentSelectedTrash.point || 0, 10),
            amount_in_units: weight,
            unit_name: currentSelectedTrash.unit_name || currentSelectedTrash.unitname || 'กิโลกรัม',
            unit_short_name: currentSelectedTrash.unit_short_name || 'กก.',
            amount: itemTotalPrice
        });

        localStorage.setItem('current_purchase_cart', JSON.stringify(purchaseCart));
        showSuccessToast(`📥 เพิ่ม ${savedItemName} เรียบร้อย!`);
        closeWeightModal();
        resetSelection();
        renderFloatingCart();
    });

    // 8. ปุ่มสแกน QR ค้นหาสมาชิกธนาคารขยะ
    document.getElementById('btnScanQR')?.addEventListener('click', () => {
        const scannerContainer = document.getElementById('qr-reader-container');
        if (!scannerContainer) return;
        scannerContainer.style.display = 'flex';

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode('reader');
        }

        html5QrCode.start(
            { facingMode: 'environment' },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            (decodedText) => {
                const input = document.getElementById('searchMemberRecycleInput');
                if (input) input.value = decodedText;
                filterMembers();
                stopWebScanner();
            },
            () => {}
        ).catch(err => {
            console.error('ไม่สามารถเปิดกล้องบน Browser ได้:', err);
            alert('ไม่สามารถเปิดกล้องได้ กรุณาอนุญาตสิทธิ์เข้าถึงกล้องใน Browser');
            stopWebScanner();
        });
    });

    document.getElementById('btnCloseScanner')?.addEventListener('click', stopWebScanner);

    // 9. ปุ่มสแกน QR ขยะ
    document.getElementById('btnScanTrashQR')?.addEventListener('click', () => {
        selectTrashItem('P001');
    });

    // 10. ปุ่มเชื่อมต่อ Bluetooth Printer
    document.getElementById('connectButton')?.addEventListener('click', connectToPrinter);
    document.getElementById('global_printer_status')?.addEventListener('click', connectToPrinter);
    document.getElementById('globalPrinterStatus')?.addEventListener('click', connectToPrinter);
});
