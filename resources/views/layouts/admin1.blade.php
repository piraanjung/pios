@php
    // view ต่าง ๆ ตั้งชื่อ section หัวข้อหน้าไม่เหมือนกัน (nav-topic / mainheader / page-topic / topic / title_page / nav-current)
    // รวมเป็นลำดับ fallback เดียวที่นี่ เพื่อให้ทุกหน้ามีหัวข้อแสดงและมี <title>
    $clean = fn (string $v) => trim(preg_replace('/\s+/', ' ', strip_tags($v)));
    $pageTitle = '';
    foreach (['nav-topic', 'mainheader', 'page-topic', 'topic', 'title_page', 'nav-current', 'nav-main'] as $s) {
        $pageTitle = $clean($__env->yieldContent($s));
        if ($pageTitle !== '') {
            break;
        }
    }
    // หน้าที่ view ไม่ตั้งหัวข้อเอง ใช้ชื่อเมนูจาก config/page_titles.php
    $menuTitle = \App\Support\PageTitle::for();
    if ($pageTitle === '') {
        $pageTitle = (string) $menuTitle;
    }
    $crumbParent = $clean($__env->yieldContent('nav-header')) ?: 'หน้าหลัก';
    $crumbCurrent = $clean($__env->yieldContent('nav-main')) ?: $clean($__env->yieldContent('nav-current')) ?: $pageTitle;
    // <title> ใช้ชื่อเมนูที่เปิดอยู่ (config/page_titles.php) ถ้าไม่มีให้ใช้หัวข้อหน้า
    $tabTitle = $menuTitle ?: $pageTitle;

    $orgInfos = $orgInfos ?? [];
    $rawLogo = $orgInfos['org_logo_img'] ?? 'ko_envsogo.png';
    $orgLogo = (!empty($rawLogo) && file_exists(public_path('logo/' . $rawLogo))) ? $rawLogo : 'ko_envsogo.png';
    $orgName = trim(($orgInfos['org_short_type_name'] ?? '') . ($orgInfos['org_name'] ?? '')) ?: 'Envsogo';
    $me = Auth::user();
    $meName = trim(($me->firstname ?? '') . ' ' . ($me->lastname ?? '')) ?: ($me->username ?? '');
@endphp
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('soft-ui/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/ko_envsogo.png') }}">
    <title>{{ $tabTitle !== '' ? $tabTitle . ' | ' : '' }}{{ $orgName }}</title>

    <!-- Fonts & Icons (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('soft-ui/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome 5 (soft-ui-dashboard.css อ้าง 'Font Awesome 5 Free'; ใช้ class fas/far/fab ไม่ใช่ fa-solid) -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" />
    <link id="pagestyle" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <style>
        body, .btn, .form-control, .form-select, .dropdown-menu, .table, h1, h2, h3, h4, h5, h6 {
            font-family: 'Kanit', 'Open Sans', sans-serif;
        }
        .hidden { display: none !important; }
        .padding4px { padding: 4px !important; }
        .selected { background: lightblue; }
        /* sidebar ยาว: ให้เลื่อนในตัวเอง */
        .navbar-vertical.navbar-expand-xs .navbar-collapse { max-height: calc(100vh - 6rem); overflow-y: auto; }
        .navbar-vertical .navbar-nav .nav-item .collapse .nav .nav-item .nav-link,
        .navbar-vertical .navbar-nav .nav-item .collapsing .nav .nav-item .nav-link { color: #344767; font-size: .875rem; }
        /* DataTables */
        .dataTables_length, .dataTables_filter { display: inline-flex; }
        .dataTables_filter { margin-left: 1rem; }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.6/pagination/select.js"></script>

    @yield('style')
    @yield('styles')

    <script>
        window.ASSET_URL = "{{ asset('') }}";
    </script>
</head>

<body class="g-sidenav-show bg-gray-100">

    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="{{ route('accessmenu') }}">
                <img src="{{ asset('logo/' . $orgLogo) }}" class="navbar-brand-img h-100" alt="main_logo" onerror="this.onerror=null; this.src='{{ asset('logo/ko_envsogo.png') }}';">
                <span class="ms-1 font-weight-bold">{{ $orgName }}</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            @hasSection('navigation')
                @yield('navigation')
            @else
                @include('layouts.admin1_navigation')
            @endif
        </div>
    </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('layouts.partials.unified-header')

        <div class="container-fluid py-4">
            {{-- @include('layouts.partials.flash') --}}

            @yield('content')
        </div>
    </main>

    <!-- soft-ui-dashboard.js อ้าง [data-class] ของ configurator ตอน resize; ใส่ตัวแทนไว้เพื่อไม่ให้เกิด TypeError -->
    <span data-class="bg-white" class="d-none" aria-hidden="true"></span>

    <!-- Core JS: ต้องโหลดก่อน section script เพื่อให้หน้าเรียกใช้ bootstrap ได้ -->
    <script src="{{ asset('soft-ui/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>

    @include('layouts.partials.sidenav-active')
    @yield('script')
    @yield('scripts')

    <script>
        // ข้อความแจ้งผลปิดเองหลัง 6 วินาที
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                document.querySelectorAll('.alert-dismissible').forEach(function (el) {
                    if (window.bootstrap) { bootstrap.Alert.getOrCreateInstance(el).close(); }
                });
            }, 6000);
        });
    </script>
</body>

</html>
