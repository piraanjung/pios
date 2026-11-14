<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- เพิ่มบรรทัดนี้ -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('logo/ko_envsogo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('logo/ko_envsogo.png') }}">
    <title>
        EnvSoGo
    </title>



    <!-- Fonts & Icons (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('css/tokens.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css">


    <link href="{{ asset('soft-ui/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('soft-ui/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <link href="{{ asset('soft-ui/assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <link id="pagestyle" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        @media (max-width: 767px) {
            .navbar-vertical.navbar-expand-xs {
                max-width: 14.625rem !important;
            }
  
            .container,
            .container-fluid,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                padding-left: 5px;
                padding-right: 5px;
            }
        }
        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            display: block;
            overflow: auto;
            height: calc(100vh) !important;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 1050;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        input:read-only {
            background-color: #e9ecef !important;
        }

        .table thead th {
            padding: 0.2rem 0.2rem !important;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.2rem 0.2rem !important;
            font-size: .875rem;
            font-weight: 400;
            line-height: 1.4rem;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #d2d6da;
            appearance: none;
            transition: box-shadow .15s ease, border-color .15s ease;
        }

        .navbar-vertical.navbar-expand-xs .navbar-nav .nav .nav-link {
            padding-top: .417rem;
            padding-bottom: .417rem;
            padding-left: 0;
        }
        .btn-group-sm>.btn i, .btn.btn-sm i {
            font-size: 1rem
        }
        ul{
            list-style-type: none !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/plug-ins/1.13.6/pagination/select.js"></script>
    @yield('style')
</head>

<body class="g-sidenav-show  bg-gray-100" id="">
    <div id="overlay">
        <div class="cv-spinner">
            <button class="btn btn-primary btn-sm ml-2 mb-2" type="button" disabled>
                <span class="spinner-border spinner-border" role="status" aria-hidden="true"></span>
                <span class="h3 text-white">โปรดรอสักครู่! มีการสร้างข้อมูลจำนวนมาก...</span>
            </button>
        </div>
    </div>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="{{ route('keptkayas.dashboard') }}">
                <img src="{{ asset('logo/ko_envsogo.png') }}" class="navbar-brand-img h-100" alt="main_logo" style="max-height: 2rem;">
                <span class="ms-1 font-weight-bold">EnvSoGo::Kept Kaya</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            @include('layouts.keptkaya_mobile_navigation')
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

            @if ($message = Session::get('message'))
                <div class="alert alert-{{ Session::get('color') }} alert-block">
                    <strong>{{ $message }}</strong>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-1.12.4.js"
        integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('soft-ui/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/soft-ui-dashboard.min.js') }}"></script>

    @include('layouts.partials.sidenav-active')
    @yield('script')

    <script>
        $(document).ready(function () {
            setTimeout(() => {
                $('.alert').toggle('slow')
            }, 3000);

        })

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });
    </script>

</body>

</html>