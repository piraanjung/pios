@extends('layouts.keptkaya_mobile2')

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@300;400;500;600;700&family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <link rel="stylesheet" href="{{ asset('css/accessmenu.css') }}">

    <script>
        window.ASSET_URL = "{{ asset('') }}";
        window.API_BASE_URL = "{{ url('/api') }}";
        @if(Auth::check())
        window.INITIAL_STAFF_SESSION = {
            token: "{{ session('_token') }}",
            user: {
                id: {{ Auth::id() }},
                firstname: "{{ Auth::user()->firstname }}",
                lastname: "{{ Auth::user()->lastname }}",
                username: "{{ Auth::user()->username }}",
                org_id_fk: {{ Auth::user()->org_id_fk ?? 'null' }},
                role: "{{ Auth::user()->role ?? 'staff' }}",
                prefix: "{{ Auth::user()->prefix ?? '' }}"
            },
            org_id: {{ Auth::user()->org_id_fk ?? 'null' }},
            org_name: "{{ Auth::user()->organization?->org_name ?? '' }}"
        };
        @else
        window.INITIAL_STAFF_SESSION = null;
        @endif
    </script>
@endsection

@section('content')
    <div class="mobile-app-shell" id="mobileAppShell">

        <!-- Offline Warning Banner -->
        <div id="offlineAlertBanner" class="alert alert-warning text-center py-2 px-3 m-0 shadow-sm sticky-top d-none" style="z-index: 1050; font-size: 0.9rem;">
            <i class="fas fa-wifi-slash me-1"></i> <strong>ไม่มีสัญญาณอินเทอร์เน็ต!</strong> ระบบกำลังทำงานในโหมดออฟไลน์ กรุณาเชื่อมต่อเครือข่ายก่อนทำการบันทึกข้อมูล
        </div>

        <!-- 1. Floating Sidebar Navigation -->
        <div id="appSidebar" class="mobile-sidebar-wrapper is-hidden">
            @include('staff.mobile.sidebar')
        </div>

        <!-- 2. หน้า Login -->
        <div id="loginScreen" class="screen is-hidden">
            @include('staff.mobile.login_screen')
        </div>

        <!-- 3. หน้าเลือกองค์กร (กรณีมีสิทธิ์หลายองค์กร) -->
        <div id="selectOrgScreen" class="screen is-hidden">
            @include('staff.mobile.screen_select_org')
        </div>

        <!-- 4. หน้าหลัก (Home / Main Dashboard) -->
        <div id="mainScreen" class="screen is-hidden">
            @include('staff.mobile.main_screen')
        </div>

        <!-- 5. หน้าเมนูหลักงานประปา -->
        <div id="tabwaterScreen" class="screen is-hidden">
            @include('staff.mobile.tabwater_main_screen')
        </div>

        <!-- 6. หน้าเลือกเส้นทางจดมิเตอร์น้ำ (Subzones) -->
        <div id="tabwaterRecordScreen" class="screen is-hidden">
            @include('staff.mobile.tabwater_record_screen')
        </div>

        <!-- 7. หน้ารายชื่อผู้ใช้น้ำในสาย & บันทึกเลขมิเตอร์ -->
        <div id="tabwaterRecordMemberSubzoneLists" class="screen is-hidden">
            @include('staff.mobile.screen_water_members_list')
        </div>

        <!-- 8. หน้าธนาคารขยะรีไซเคิล (ค้นหาสมาชิก) -->
        <div id="recycleBankScreen" class="screen is-hidden">
            @include('staff.mobile.recycle_bank_screen')
        </div>

        <!-- 9. หน้ารับซื้อ/ฝากขยะรีไซเคิล & ตะกร้า -->
        <div id="depositScreen" class="screen is-hidden">
            @include('staff.mobile.deposit_screen')
        </div>

        <!-- 10. หน้าตั้งค่าเครื่องพิมพ์ใบเสร็จ Bluetooth -->
        <div id="settingsScreen" class="screen is-hidden">
            @include('staff.mobile.settings_screen')
        </div>

        <!-- 11. หน้าคลังพัสดุ (Inventory Iframe) -->
        <div id="inventoryScreen" class="screen is-hidden">
            <div class="mobile-sub-header bg-primary text-white p-3 d-flex align-items-center justify-content-between rounded-bottom-4 shadow-sm">
                <button type="button" onclick="navigateTo('mainScreen')" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                    ⬅️ หน้าหลัก
                </button>
                <h6 class="fw-bold mb-0 text-white">📦 ระบบคลังพัสดุ</h6>
                <div style="width: 40px;"></div>
            </div>
            <div id="inventoryIframeError" class="alert alert-danger m-3" role="alert" hidden>
                <span id="inventoryIframeErrorMessage">ไม่สามารถเชื่อมต่อคลังพัสดุได้</span>
                <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="loadInventoryIframeOnce(true)">ลองใหม่</button>
            </div>
            <iframe src="" data-src="{{ url('/inventory/items/iframe') }}"
                data-session-url="{{ route('inventory.items.iframe.session') }}"
                data-session-end-url="{{ route('inventory.items.iframe.session.end') }}"
                style="width: 100%; height: calc(100vh - 130px); border: none;" id="inventoryIframe">
            </iframe>
        </div>

        <!-- 12. Bottom Navigation Bar สไตล์ Mobile App -->
        <nav id="mobileBottomNav" class="mobile-bottom-nav is-hidden">
            <button type="button" class="bottom-nav-item active" data-screen="mainScreen" onclick="navigateTo('mainScreen')">
                <i class="fa-solid fa-house"></i>
                <span>หน้าหลัก</span>
            </button>
            <button type="button" class="bottom-nav-item" data-screen="tabwaterScreen" onclick="navigateTo('tabwaterScreen')">
                <i class="fa-solid fa-droplet"></i>
                <span>งานประปา</span>
            </button>
            <button type="button" class="bottom-nav-item" data-screen="recycleBankScreen" onclick="navigateTo('recycleBankScreen')">
                <i class="fa-solid fa-recycle"></i>
                <span>ธนาคารขยะ</span>
            </button>
            <button type="button" class="bottom-nav-item" data-screen="inventoryScreen" onclick="navigateTo('inventoryScreen')">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>คลังพัสดุ</span>
            </button>
            <button type="button" class="bottom-nav-item" data-screen="settingsScreen" onclick="navigateTo('settingsScreen')">
                <i class="fa-solid fa-print"></i>
                <span>เครื่องพิมพ์</span>
            </button>
        </nav>

    </div>

    <!-- Toast แจ้งเตือนเมื่อเพิ่มลงตะกร้า -->
    <div id="successToast" class="success-toast-overlay is-hidden">
        <div class="toast-icon">✅</div>
        <div id="successToastText" class="toast-text">เพิ่มลงตะกร้าเรียบร้อย</div>
    </div>

    <!-- กล้องสแกน QR Code เต็มจอสำหรับธนาคารขยะ -->
    <div id="qr-reader-container"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; justify-content: center; align-items: center; flex-direction: column;">
        <div id="reader" style="width: 90%; max-width: 400px; background: #fff; padding: 10px; border-radius: 16px;"></div>
        <button type="button" id="btnCloseScanner"
            style="margin-top: 15px; padding: 10px 28px; background: #ff4d4d; color: #fff; border: none; border-radius: 50px; font-weight: bold; cursor: pointer;">
            ✕ ปิดกล้องสแกน
        </button>
    </div>

    <!-- Modal สำหรับแสดงหน้า Dashboard/Staff เพิ่มเติม -->
    <div class="modal fade" id="staffDashboardModal" tabindex="-1" aria-labelledby="staffDashboardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffDashboardModalLabel">ระบบจัดการงาน Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-staff-content">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">กำลังโหลดข้อมูล...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- พื้นที่ซ่อนสำหรับ Render ใบเสร็จความร้อน 58mm (384px) ก่อนแปลงเป็นภาพส่งเข้า Bluetooth Printer -->
    <div id="card-reciept" style="position: fixed; left: -9999px; top: 0; width: 384px; background: #ffffff; color: #000000; font-size: 1.4rem !important;"></div>
@endsection

@section('script')
    <script src="{{ asset('js/jquery-3.7.1.slim.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="{{ asset('js/accessmenu.js') }}?v={{ filemtime(public_path('js/accessmenu.js')) }}"></script>
@endsection
