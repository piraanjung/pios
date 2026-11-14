@php
    $clean = fn (string $v) => trim(preg_replace('/\s+/', ' ', strip_tags($v)));
    $pageTitle = '';
    foreach (['page-topic', 'nav-topic', 'mainheader', 'topic', 'title_page', 'nav-current', 'nav-main', 'nav-header'] as $s) {
        $pageTitle = $clean($__env->yieldContent($s));
        if ($pageTitle !== '') {
            break;
        }
    }
    if ($pageTitle === '' && class_exists(\App\Support\PageTitle::class)) {
        $pageTitle = (string) \App\Support\PageTitle::for();
    }
    $crumbParent = $clean($__env->yieldContent('nav-header')) ?: 'หน้าหลัก';
    $crumbCurrent = $clean($__env->yieldContent('nav-current')) ?: $clean($__env->yieldContent('nav-main')) ?: ($pageTitle ?: 'ระบบงาน');
    $backRoute = $__env->yieldContent('route-back') ?: null;

    $me = Auth::user();
    $orgInfos = $me ? App\Models\Admin\Organization::getOrgName($me->org_id_fk) : [];
    $orgLogo = $orgInfos['org_logo_img'] ?? 'ko_envsogo.png';
    $orgName = $orgInfos['org_name'] ?? 'KU:PI-OS';
    $meName = trim(($me->firstname ?? '') . ' ' . ($me->lastname ?? '')) ?: ($me->username ?? 'เจ้าหน้าที่');
@endphp

<!-- Unified Soft UI Header -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-2 mx-md-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-2 px-2 px-md-3 d-flex align-items-center justify-content-between flex-wrap">

        <!-- Left: Hamburger (Mobile) + Back Button + Breadcrumbs -->
        <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
            
            <!-- Mobile/Tablet Hamburger Sidenav Toggler (ALWAYS OUTSIDE COLLAPSE) -->
            <a href="javascript:;" class="nav-link text-body p-2 d-xl-none d-flex align-items-center justify-content-center border-radius-md hover-elevate" 
               id="iconNavbarSidenav" 
               aria-label="เปิดเมนูด้านข้าง"
               style="min-width: 44px; min-height: 44px; background: rgba(255, 255, 255, 0.85); box-shadow: 0 2px 5px rgba(0,0,0,0.06);">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-dark"></i>
                    <i class="sidenav-toggler-line bg-dark"></i>
                    <i class="sidenav-toggler-line bg-dark"></i>
                </div>
            </a>

            @if($backRoute)
            <!-- Back Button for Step / Sub-views -->
            <a href="{{ $backRoute }}" class="btn btn-sm btn-white mb-0 px-2 py-2 d-flex align-items-center rounded-pill shadow-xs" title="ย้อนกลับ">
                <i class="fas fa-chevron-left me-1 text-primary"></i> <span class="d-none d-sm-inline">ย้อนกลับ</span>
            </a>
            @endif

            <!-- Breadcrumbs & Page Topic -->
            <nav aria-label="breadcrumb" class="d-flex flex-column justify-content-center text-truncate ms-1">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-0 px-0 d-none d-sm-flex text-xs">
                    <li class="breadcrumb-item text-muted">
                        <a class="opacity-6 text-dark" href="{{ $__env->yieldContent('route-header') ?: route('accessmenu') }}">{{ $crumbParent }}</a>
                    </li>
                    <li class="breadcrumb-item text-dark font-weight-bold active" aria-current="page">{{ $crumbCurrent }}</li>
                </ol>
                <h5 class="font-weight-bolder text-dark mb-0 text-truncate" style="letter-spacing: -0.3px;">
                    {{ $pageTitle ?: $crumbCurrent }}
                </h5>
            </nav>
        </div>

        <!-- Right: Portal Shortcut + User Profile Dropdown -->
        <div class="d-flex align-items-center gap-2 mt-sm-0">
            
            <!-- Quick Link to Portal / Accessmenu -->
            <a href="{{ route('accessmenu') }}" class="btn btn-sm btn-outline-primary mb-0 d-flex align-items-center rounded-pill px-3 py-2 shadow-xs" title="กลับไปหน้าเมนูรวม">
                <i class="fas fa-th-large me-1"></i>
                <span class="d-none d-md-inline">เมนูรวมระบบ</span>
            </a>

            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <a href="javascript:;" class="nav-link text-body font-weight-bold px-2 py-1 d-flex align-items-center bg-white border-radius-pill shadow-xs" 
                   id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="min-height: 40px;">
                    <img src="{{ asset('logo/' . $orgLogo) }}" class="avatar avatar-xs rounded-circle me-md-2" alt="org_logo" style="width: 28px; height: 28px; object-fit: cover;">
                    <div class="d-none d-md-flex flex-column text-start me-1" style="line-height: 1.1;">
                        <span class="text-xs font-weight-bolder text-dark">{{ $meName }}</span>
                        <span class="text-xxs text-secondary">{{ Str::limit($orgName, 18) }}</span>
                    </div>
                    <i class="fas fa-chevron-down text-xxs text-secondary ms-1"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end px-2 py-3 shadow-lg border-0 border-radius-xl" aria-labelledby="userMenuButton" style="min-width: 220px; z-index: 1060;">
                    <li class="px-3 py-2 border-bottom mb-2">
                        <div class="font-weight-bold text-dark text-sm">{{ $meName }}</div>
                        <div class="text-xs text-muted">{{ $orgName }}</div>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md py-2 d-flex align-items-center" href="{{ route('accessmenu') }}">
                            <i class="fas fa-th-large me-2 text-primary"></i> กลับไปหน้าเมนูหลัก
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item border-radius-md py-2 d-flex align-items-center" href="{{ route('staff_accessmenu') }}">
                            <i class="fas fa-mobile-alt me-2 text-info"></i> สลับโหมดพนักงานภาคสนาม
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item border-radius-md py-2 text-danger d-flex align-items-center w-100 bg-transparent border-0">
                                <i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</nav>
