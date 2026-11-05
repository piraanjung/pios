@php
    $clean = fn (string $v) => trim(preg_replace('/\s+/', ' ', strip_tags($v)));
    $pageTitle = '';
    foreach (['nav-topic', 'mainheader', 'page-topic', 'topic', 'title_page', 'nav-current', 'nav-main'] as $s) {
        $pageTitle = $clean($__env->yieldContent($s));
        if ($pageTitle !== '') break;
    }
    if ($pageTitle === '' && class_exists('\App\Support\PageTitle')) {
        $pageTitle = (string) \App\Support\PageTitle::for();
    }
    $pageTitle = $pageTitle ?: 'ระบบบริหารจัดการท้องถิ่นอัจฉริยะ';
    $crumbParent = $clean($__env->yieldContent('nav-header')) ?: 'หน้าหลัก';
    $crumbCurrent = $clean($__env->yieldContent('nav-main')) ?: $clean($__env->yieldContent('nav-current')) ?: $pageTitle;

    $user = Auth::user();
    $userName = $user ? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: ($user->username ?? 'เจ้าหน้าที่') : 'ผู้ใช้งาน';
    $orgId = \App\Shared\Tenancy\TenantContext::currentOrgId();
    $org = \App\Shared\Tenancy\TenantContext::currentOrg();
    $orgName = $org ? trim(($org->org_short_type_name ?? '') . ($org->org_name ?? '')) : 'องค์กรปกครองส่วนท้องถิ่น';
@endphp
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/ko_envsogo.png') }}">
    <title>{{ $pageTitle }} | {{ $orgName }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}" />
    <link id="pagestyle" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <style>
        body, .btn, .form-control, .form-select, .dropdown-menu, .table, h1, h2, h3, h4, h5, h6 {
            font-family: 'Sarabun', 'Open Sans', sans-serif;
        }
        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            height: calc(100vh - 280px);
        }
        .tenant-badge {
            background: linear-gradient(310deg, #7928CA 0%, #FF0080 100%);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>
    @stack('styles')
</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar Navigation -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
        <div class="sidenav-header text-center">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex align-items-center justify-content-center" href="{{ url('/home') }}">
                <img src="{{ asset('logo/ko_envsogo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-2 font-weight-bold text-dark">PIOS Smart City</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="px-3 pb-2 text-center">
            <span class="tenant-badge"><i class="fas fa-landmark me-1"></i> {{ $orgName }}</span>
        </div>
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            @if (View::exists('layouts.admin1_navigation'))
                @include('layouts.admin1_navigation')
            @elseif (View::exists('layouts.navigation'))
                @include('layouts.navigation')
            @endif
        </div>
        <div class="sidenav-footer mx-3 mt-3 pt-3 border-top">
            <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
                <div class="card-body text-start p-3 w-100">
                    <div class="docs-info">
                        <h6 class="text-white up mb-0">{{ $userName }}</h6>
                        <p class="text-xs font-weight-bold text-white opacity-8 mb-2">{{ $user?->email ?? 'เจ้าหน้าที่ปฏิบัติการ' }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-white btn-sm w-100 mb-0">
                                <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Top Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{ $crumbParent }}</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $crumbCurrent }}</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">{{ $pageTitle }}</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center"></div>
                    <ul class="navbar-nav justify-content-end">
                        <li class="nav-item d-flex align-items-center me-3">
                            <span class="badge bg-gradient-info text-white">
                                <i class="fas fa-building me-1"></i> Org #{{ $orgId ?? '-' }}
                            </span>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">{{ $userName }}</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Body Container -->
        <div class="container-fluid py-4">
            @include('layouts.partials.flash')

            @yield('content')

            <footer class="footer pt-3 mt-4">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                © {{ date('Y') }} PIOS Local Government Service Platform. Powered by Creative Tim Soft UI PRO.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <!-- Core Scripts -->
    <script src="{{ asset('soft-ui/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('soft-ui/assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>

    @stack('scripts')
</body>
</html>
