@php
    // view ของ keptkaya ตั้งหัวข้อหน้าด้วย section ต่างกัน (page-topic / nav-current / nav-header / ...)
    // รวมเป็นลำดับ fallback เดียวที่นี่ เพื่อให้ทุกหน้ามีหัวข้อแสดงและมี <title>
    $clean = fn (string $v) => trim(preg_replace('/\s+/', ' ', strip_tags($v)));
    $pageTitle = '';
    foreach (['page-topic', 'nav-topic', 'mainheader', 'topic', 'title_page', 'nav-current', 'nav-main', 'nav-header'] as $s) {
        $pageTitle = $clean($__env->yieldContent($s));
        if ($pageTitle !== '') {
            break;
        }
    }
    $menuTitle = \App\Support\PageTitle::for();
    if ($pageTitle === '') {
        $pageTitle = (string) $menuTitle;
    }
    $crumbParent = $clean($__env->yieldContent('nav-header')) ?: 'ธนาคารขยะ';
    $crumbCurrent = $clean($__env->yieldContent('nav-current')) ?: $clean($__env->yieldContent('nav-main')) ?: $pageTitle;
    $tabTitle = $menuTitle ?: $pageTitle;

    $me = Auth::user();
    $orgInfos = $me ? App\Models\Admin\Organization::getOrgName($me->org_id_fk) : [];
    $orgLogo = $orgInfos['org_logo_img'] ?? 'ko_envsogo.png';
    $meName = trim(($me->firstname ?? '') . ' ' . ($me->lastname ?? '')) ?: ($me->username ?? '');
@endphp
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('logo/ko_envsogo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/ko_envsogo.png') }}">
    <title>{{ $tabTitle !== '' ? $tabTitle . ' | ' : '' }}EnvSoGo::Kept Kaya</title>

    <!-- Fonts & Icons (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('soft-ui/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome 5 (soft-ui-dashboard.css อ้าง 'Font Awesome 5 Free'; ใช้ class fas/far/fab) -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" />
    <link id="pagestyle" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body, .btn, .form-control, .form-select, .dropdown-menu, .table, h1, h2, h3, h4, h5, h6 {
            font-family: 'Kanit', 'Open Sans', sans-serif;
        }
        /* Mobile-First Touch Targets for Officers (44px-48px min height) */
        .btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }
        .btn-lg {
            min-height: 52px;
        }
        .form-control, .form-select {
            min-height: 44px;
            font-size: 1rem;
        }
        .navbar-vertical .navbar-nav .nav-link {
            min-height: 44px;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
        }
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }
        /* sidebar ยาว: ให้เลื่อนในตัวเอง */
        .navbar-vertical.navbar-expand-xs .navbar-collapse { max-height: calc(100vh - 6rem); overflow-y: auto; }
        .navbar-vertical .navbar-nav .nav-item .collapse .nav .nav-item .nav-link,
        .navbar-vertical .navbar-nav .nav-item .collapsing .nav .nav-item .nav-link { color: #344767; font-size: .875rem; }
        /* DataTables */
        .dataTables_length, .dataTables_filter { display: inline-flex; }
        .dataTables_filter { margin-left: 1rem; }
        /* select2 ให้สูงเท่า .form-control ของ Soft UI */
        .select2-container .select2-selection--single { height: calc(1.4rem + 1rem + 2px); border: 1px solid #d2d6da; border-radius: .5rem; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: calc(1.4rem + 1rem); padding-left: .75rem; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.6/pagination/select.js"></script>
    @yield('style')
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="{{ route('keptkayas.dashboard') }}">
                <img src="{{ asset('logo/ko_envsogo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">EnvSoGo::Kept Kaya</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            @include('layouts.keptkaya_navigation')
        </div>
    </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('layouts.partials.unified-header')

        <div class="container-fluid py-4">
            <!-- Offline / Network Status Banner -->
            <div id="offline-alert" class="alert alert-danger text-white d-none shadow-sm mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-wifi-slash fa-2x me-3"></i>
                    <div>
                        <strong>ไม่สามารถเชื่อมต่ออินเทอร์เน็ตได้:</strong> สัญญาณอินเทอร์เน็ตขาดหาย ข้อมูลอาจยังไม่ถูกบันทึก กรุณารอเชื่อมต่ออินเทอร์เน็ตก่อนทำรายการต่อ
                    </div>
                </div>
            </div>
            {{-- success แต่ละหน้าแสดงเอง (layout เดิมแสดงเฉพาะ error) --}}
            {{-- @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span class="alert-text">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif --}}

            @yield('content')
        </div>
    </main>

    <!-- soft-ui-dashboard.js อ้าง [data-class] ของ configurator ตอน resize; ใส่ตัวแทนไว้เพื่อไม่ให้เกิด TypeError -->
    <span data-class="bg-white" class="d-none" aria-hidden="true"></span>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('soft-ui/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>

    @include('layouts.partials.sidenav-active')
    @yield('script')

    <script>
        $(function () {
            $('.js-example-basic-single').select2();

            window.addEventListener('offline', function () {
                var el = document.getElementById('offline-alert');
                if (el) el.classList.remove('d-none');
            });
            window.addEventListener('online', function () {
                var el = document.getElementById('offline-alert');
                if (el) el.classList.add('d-none');
            });
        });
    </script>

</body>

</html>
