<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('soft-ui/assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('soft-ui/assets/img/favicon.png') }}">
    <title>{{ $pageTitle ?? 'งานประปา - ระบบบันทึกเลขมิเตอร์น้ำ' }}</title>

    <!-- Fonts & Icons (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('css/tokens.css') }}" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.min.css') }}" rel="stylesheet" />

    <style>
        body {
            font-family: 'Kanit', sans-serif !important;
            background-color: #f8fafc;
        }

        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            display: block;
            overflow: auto;
            height: calc(100vh - 120px) !important;
        }

        @media (max-width: 1199.98px) {
            .g-sidenav-show .sidenav {
                transform: translateX(-19.125rem);
                transition: transform 0.25s ease-in-out;
            }
            .g-sidenav-show.g-sidenav-pinned .sidenav {
                transform: translateX(0);
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    @yield('style')
</head>

<body class="g-sidenav-show bg-gray-100">

    <!-- Sidenav Sidebar -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white shadow-xl"
        id="sidenav-main" style="z-index: 1050;">
        <div class="sidenav-header d-flex align-items-center justify-content-between p-3">
            <a class="navbar-brand m-0 d-flex align-items-center" href="{{ route('dashboard') }}">
                <div class="icon icon-shape icon-sm bg-gradient-primary shadow text-center border-radius-md me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-tint text-white"></i>
                </div>
                <span class="ms-1 font-weight-bold text-dark fs-6">งานประปา (Tabwater)</span>
            </a>
            <i class="fas fa-times cursor-pointer text-secondary opacity-8 d-xl-none fs-5 p-2"
                aria-hidden="true" id="iconSidenav" title="ปิดเมนู"></i>
        </div>
        <hr class="horizontal dark mt-0 mb-2">

        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('accessmenu') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-th-large text-primary text-gradient"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bold">เมนูรวมระบบ</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @yield('nav-dashboard')" href="{{ route('dashboard') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-pie text-info text-gradient"></i>
                        </div>
                        <span class="nav-link-text ms-1">แดชบอร์ดงานประปา</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('tabwater.staff.mobile.index') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-primary text-center me-2 d-flex align-items-center justify-content-center text-white">
                            <i class="fas fa-clipboard-check text-white"></i>
                        </div>
                        <span class="nav-link-text ms-1 font-weight-bold">จดมิเตอร์ภาคสนาม</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-water').submit();">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt text-danger text-gradient"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-danger font-weight-bold">ออกจากระบบ</span>
                    </a>
                    <form id="logout-form-water" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Mobile Sidenav Backdrop -->
    <div id="sidenavBackdrop" class="d-none" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.6); z-index: 1040; backdrop-filter: blur(4px);"></div>

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('layouts.partials.unified-header')

        <div class="container-fluid py-4">
            <!-- Offline Alert -->
            <div id="offline-alert" class="alert alert-danger text-white d-none shadow-sm mb-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-wifi-slash fa-2x me-3"></i>
                    <div>
                        <strong>สัญญาณอินเทอร์เน็ตขาดหาย:</strong> กรุณารอเชื่อมต่ออินเทอร์เน็ตก่อนบันทึกข้อมูล
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-white shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-white shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Core Scripts -->
    <script src="{{ asset('soft-ui/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/soft-ui-dashboard.min.js') }}"></script>

    @include('layouts.partials.sidenav-active')
    @yield('script')

    <script>
        $(document).ready(function () {
            const body = document.querySelector('body');
            const backdrop = document.getElementById('sidenavBackdrop');
            const hamburger = document.getElementById('iconNavbarSidenav');
            const closeBtn = document.getElementById('iconSidenav');

            function syncBackdrop() {
                if (body.classList.contains('g-sidenav-pinned')) {
                    backdrop.classList.remove('d-none');
                } else {
                    backdrop.classList.add('d-none');
                }
            }

            if (hamburger) {
                hamburger.addEventListener('click', function () {
                    setTimeout(syncBackdrop, 50);
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    body.classList.remove('g-sidenav-pinned');
                    syncBackdrop();
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    body.classList.remove('g-sidenav-pinned');
                    syncBackdrop();
                });
            }
        });
    </script>
</body>

</html>
