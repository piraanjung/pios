<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $orgInfos['org_name'] ?? 'PIOS' }} - เมนูรวมระบบงาน (Access Menu)</title>

    <!-- Fonts & Icons (ADR-0009 Kanit) -->
    @include('layouts.partials.head-fonts')
    <link href="{{ asset('css/tokens.css') }}" rel="stylesheet" />

    <!-- Soft UI Dashboard CSS -->
    <link id="pagestyle" href="{{ asset('soft-ui/assets/css/soft-ui-dashboard.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('Applight/css/animate.css')}}">

    <style>
        body {
            font-family: 'Kanit', sans-serif !important;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Top Navigation Bar */
        .top-navbar-portal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            padding: 0.65rem 1.5rem;
        }

        /* Page Container */
        .portal-content {
            padding-top: 5.5rem;
            padding-bottom: 3rem;
            min-height: 100vh;
        }

        /* Card Mode Styling */
        .module-card {
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            background: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .module-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
            border-color: rgba(33, 82, 255, 0.3);
        }

        .module-card-icon {
            width: 52px;
            height: 52px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        /* Wheel Mode Styling */
        .wheel-section {
            min-height: calc(100vh - 7rem);
            display: flex;
            align-items: center;
        }

        .wheel-container {
            position: relative;
            width: 440px;
            height: 440px;
            margin: 0 auto;
        }

        .wheel-center-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #ffffff;
            border: 6px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), inset 0 2px 4px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 10;
        }

        .wheel-bubble-arm {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .wheel-bubble-arm a {
            pointer-events: auto;
            text-decoration: none;
        }

        .wheel-bubble {
            position: absolute;
            width: 120px;
            height: 120px;
            top: -60px;
            left: calc(50% - 60px);
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            padding: 8px;
            text-align: center;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .wheel-bubble:hover {
            transform: scale(1.15) !important;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.2) !important;
        }

        .wheel-bubble i {
            font-size: 1.6rem;
            margin-bottom: 4px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        .wheel-bubble span {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.15;
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Specific Gradients */
        .grad-tabwater { background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); }
        .grad-recycle { background: linear-gradient(135deg, #16a34a 0%, #4ade80 100%); }
        .grad-rvm { background: linear-gradient(135deg, #0891b2 0%, #22d3ee 100%); }
        .grad-foodwaste { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .grad-trash { background: linear-gradient(135deg, #d97706 0%, #fbbf24 100%); }
        .grad-welfare { background: linear-gradient(135deg, #7c3aed 0%, #c084fc 100%); }
        .grad-inventory { background: linear-gradient(135deg, #334155 0%, #64748b 100%); }
        .grad-admin { background: linear-gradient(135deg, #dc2626 0%, #f87171 100%); }

        @media (max-width: 991px) {
            .wheel-section {
                display: none !important;
            }
            #cardContainer {
                display: block !important;
            }
            #toggleViewModeBtn {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <!-- 1. Top Navigation Bar -->
    <header class="top-navbar-portal">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <!-- Brand & Org -->
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('logo/' . ($orgInfos['org_logo_img'] ?? 'ko_envsogo.png')) }}" alt="Logo" style="height: 38px; width: auto; object-fit: contain;">
                <div>
                    <h5 class="mb-0 font-weight-bolder text-dark lh-1">{{ $orgInfos['org_name'] ?? 'เทศบาลเมืองสมาร์ทซิตี้' }}</h5>
                    <small class="text-secondary">{{ $orgInfos['org_code'] ?? 'PIOS' }}: ระบบบริหารจัดการเมืองอัจฉริยะ</small>
                </div>
            </div>

            <!-- View Switcher & Action Controls -->
            <div class="d-flex align-items-center gap-2">
                <!-- Mode Switcher (Desktop only) -->
                <button id="toggleViewModeBtn" type="button" class="btn btn-sm btn-outline-primary mb-0 px-3 rounded-pill shadow-none">
                    <i class="fas fa-th-large me-1"></i> มุมมองการ์ด
                </button>

                <!-- Field Mode -->
                <a href="{{ route('staff_accessmenu') }}" class="btn btn-sm btn-outline-info mb-0 px-3 rounded-pill shadow-none" title="สำหรับเจ้าหน้าที่ภาคสนามบนมือถือ">
                    <i class="fas fa-mobile-alt me-1"></i> โหมดภาคสนาม
                </a>

                <!-- Super Admin Switcher -->
                @hasrole('Super Admin')
                <a href="{{ route('admin.org_selector') }}" class="btn btn-sm bg-gradient-secondary mb-0 px-3 rounded-pill" title="สลับองค์กร">
                    <i class="fas fa-exchange-alt me-1"></i> สลับ อปท.
                </a>
                @endhasrole

                <!-- User & Logout -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm bg-gradient-danger mb-0 px-3 rounded-pill shadow-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> ออกจากระบบ
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- 2. Portal Content Area -->
    <main class="portal-content">
        <div class="container-fluid px-md-5">

            <!-- Hero Banner Section -->
            <div class="text-center py-4 mb-3">
                <span class="badge bg-primary px-3 py-2 rounded-pill font-weight-bold text-uppercase mb-2 shadow-sm">
                    {{ $orgInfos['org_type_name'] ?? 'องค์กรปกครองส่วนท้องถิ่น' }}
                </span>
                <h1 class="font-weight-bolder text-dark mb-1 display-5">{{ $orgInfos['org_name'] ?? 'เทศบาลเมืองสมาร์ทซิตี้' }}</h1>
                <p class="text-secondary fs-6 mb-2">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    ตำบล{{ $orgInfos['org_tambon'] ?? '-' }} อำเภอ{{ $orgInfos['org_district'] ?? '-' }} จังหวัด{{ $orgInfos['org_province'] ?? '-' }}
                </p>
                <p class="text-muted mx-auto" style="max-width: 650px;">
                    ยินดีต้อนรับเจ้าหน้าที่ กรุณาเลือกระบบงานที่ต้องการปฏิบัติงานด้านล่าง
                </p>
            </div>

            <!-- VIEW 1: Card Mode (Default & Primary View) -->
            <div id="cardContainer">
                <div class="row g-4 justify-content-center">

                    <!-- 1. งานประปา -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary rounded-pill px-3 py-1 font-weight-bold">ลำดับที่ 1</span>
                                <div class="module-card-icon grad-tabwater">
                                    <i class="fas fa-tint text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">งานประปา</h4>
                            <p class="text-secondary small flex-grow-1">
                                จัดการระบบน้ำประปา จดบันทึกเลขมิเตอร์ คำนวณค่าน้ำ รับชำระเงิน ออกใบแจ้งหนี้และใบเสร็จ
                            </p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 rounded-pill mb-0 mt-3 shadow-sm">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 2. ธนาคารขยะรีไซเคิล -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-success rounded-pill px-3 py-1 font-weight-bold">ลำดับที่ 2</span>
                                <div class="module-card-icon grad-recycle">
                                    <i class="fas fa-recycle text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">ธนาคารขยะรีไซเคิล</h4>
                            <p class="text-secondary small flex-grow-1">
                                ออกหน่วยรับซื้อขยะ จัดการสมาชิก สมุดบัญชีเงินฝาก บันทึกแต้มสะสม และการถอนเงิน
                            </p>
                            <a href="{{ route('keptkayas.dashboard', 'recycle') }}" class="btn btn-success w-100 rounded-pill mb-0 mt-3 shadow-sm">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 3. ตู้คืนขวดอัตโนมัติ -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-info rounded-pill px-3 py-1 font-weight-bold">ลำดับที่ 3</span>
                                <div class="module-card-icon grad-rvm">
                                    <i class="fas fa-robot text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">ตู้คืนขวดอัตโนมัติ</h4>
                            <p class="text-secondary small flex-grow-1">
                                ตรวจสอบสถานะตู้ RVM สถิติการคืนขวดพลาสติก/กระป๋อง และจัดการข้อมูลแต้มสะสม
                            </p>
                            <a href="{{ route('keptkayas.kiosks.dashboard') }}" class="btn btn-info w-100 rounded-pill mb-0 mt-3 shadow-sm text-white">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 4. ถังขยะเปียกครัวเรือน -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-success rounded-pill px-3 py-1 font-weight-bold" style="background-color: #059669 !important;">ลำดับที่ 4</span>
                                <div class="module-card-icon grad-foodwaste">
                                    <i class="fas fa-leaf text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">ถังขยะเปียก</h4>
                            <p class="text-secondary small flex-grow-1">
                                ติดตามการจัดทำถังขยะเปียกลดโลกร้อน สถิติการลดคาร์บอนเครดิตระดับครัวเรือน
                            </p>
                            <a href="{{ auth()->user()->can('access food waste') || auth()->user()->hasRole('Super Admin') ? route('foodwaste.executive_dashboard') : 'javascript:void(0)' }}" class="btn btn-outline-success w-100 rounded-pill mb-0 mt-3">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 5. ค่าจัดการถังขยะรายปี -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 font-weight-bold">ลำดับที่ 5</span>
                                <div class="module-card-icon grad-trash">
                                    <i class="fas fa-trash-alt text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">ค่าจัดการถังขยะ</h4>
                            <p class="text-secondary small flex-grow-1">
                                จัดเก็บและบันทึกค่าธรรมเนียมการเก็บขยะมูลฝอยประจำปีของแต่ละครัวเรือน
                            </p>
                            <a href="{{ route('annual_trash.index') }}" class="btn btn-warning w-100 rounded-pill mb-0 mt-3 shadow-sm text-white">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 6. กองทุนฌาปนกิจสงเคราะห์ -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge rounded-pill px-3 py-1 font-weight-bold text-white" style="background-color: #7c3aed !important;">ลำดับที่ 6</span>
                                <div class="module-card-icon grad-welfare">
                                    <i class="fas fa-hands-helping text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">กองทุนฌาปนกิจ</h4>
                            <p class="text-secondary small flex-grow-1">
                                บริหารจัดการสมาชิกกองทุนฌาปนกิจสงเคราะห์ ตรวจสอบเงินสงเคราะห์และสิทธิประโยชน์
                            </p>
                            <a href="{{ route('admin.welfare.dashboard') }}" class="btn btn-outline-primary w-100 rounded-pill mb-0 mt-3" style="border-color: #7c3aed; color: #7c3aed;">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 7. คลังพัสดุ -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-secondary rounded-pill px-3 py-1 font-weight-bold">ลำดับที่ 7</span>
                                <div class="module-card-icon grad-inventory">
                                    <i class="fas fa-boxes text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">คลังพัสดุ</h4>
                            <p class="text-secondary small flex-grow-1">
                                บันทึกรับพัสดุ ทะเบียนคุมทรัพย์สิน การเบิกจ่าย และตรวจนับสต็อกพัสดุคงเหลือ
                            </p>
                            <a href="{{ route('inventory.dashboard') }}" class="btn btn-secondary w-100 rounded-pill mb-0 mt-3 shadow-sm">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 8. ผู้ดูแลระบบ -->
                    <div class="col-sm-6 col-md-4 col-xl-3">
                        <div class="card h-100 module-card p-3 d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-danger rounded-pill px-3 py-1 font-weight-bold">ลำดับที่ 8</span>
                                <div class="module-card-icon grad-admin">
                                    <i class="fas fa-user-shield text-white fs-4"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bolder text-dark mb-2">ตั้งค่าระบบ</h4>
                            <p class="text-secondary small flex-grow-1">
                                จัดการสิทธิ์เจ้าหน้าที่ ผู้ใช้งาน การตั้งค่าทั่วไป และการดูแลรักษาความปลอดภัย
                            </p>
                            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-danger w-100 rounded-pill mb-0 mt-3 shadow-sm">
                                เข้าสู่ระบบงาน <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- VIEW 2: Wheel Mode (Balanced 2-Column Desktop View) -->
            <div id="wheelContainer" class="wheel-section d-none">
                <div class="row align-items-center w-100">
                    <!-- Left: Info Column -->
                    <div class="col-lg-5 text-start pe-lg-4 mb-4 mb-lg-0">
                        <div class="card border-0 shadow-sm p-4 rounded-3" style="background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px);">
                            <span class="badge bg-primary px-3 py-2 rounded-pill font-weight-bold text-uppercase mb-2 align-self-start">
                                {{ $orgInfos['org_type_name'] ?? 'อปท.' }}
                            </span>
                            <h2 class="font-weight-bolder text-dark mb-2">{{ $orgInfos['org_name'] }}</h2>
                            <p class="text-secondary fs-6 mb-3">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                ตำบล{{ $orgInfos['org_tambon'] }} อำเภอ{{ $orgInfos['org_district'] }} จังหวัด{{ $orgInfos['org_province'] }}
                            </p>
                            <hr class="horizontal dark my-2">
                            <p class="text-muted small mb-3">
                                💡 <strong>คำแนะนำ:</strong> คลิกเลือกไอคอนรอบวงล้อด้านขวาเพื่อเข้าสู่ระบบงานที่ต้องการ หรือกดปุ่ม <strong>"มุมมองการ์ด"</strong> ด้านบนเพื่อดูรายละเอียดของทุกระบบ
                            </p>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill mb-0 px-3" onclick="$('#toggleViewModeBtn').click();">
                                    <i class="fas fa-th-large me-1"></i> สลับเป็นมุมมองการ์ด
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Balanced Orbital Wheel -->
                    <div class="col-lg-7 d-flex justify-content-center">
                        <div class="wheel-container">
                            <!-- Center Logo Circle -->
                            <div class="wheel-center-circle">
                                <img src="{{ asset('logo/' . ($orgInfos['org_logo_img'] ?? 'ko_envsogo.png')) }}" class="img-fluid" style="max-height: 140px; object-fit: contain;" alt="Org Logo">
                            </div>

                            <!-- 8 Orbital Bubbles -->
                            <!-- 1. งานประปา -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('dashboard') }}">
                                    <div class="wheel-bubble grad-tabwater">
                                        <i class="fas fa-tint"></i>
                                        <span>งานประปา</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 2. ธนาคารขยะรีไซเคิล -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('keptkayas.dashboard', 'recycle') }}">
                                    <div class="wheel-bubble grad-recycle">
                                        <i class="fas fa-recycle"></i>
                                        <span>ธนาคารขยะ</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 3. ตู้คืนขวดอัตโนมัติ -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('keptkayas.kiosks.dashboard') }}">
                                    <div class="wheel-bubble grad-rvm">
                                        <i class="fas fa-robot"></i>
                                        <span>ตู้คืนขวด</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 4. ถังขยะเปียกครัวเรือน -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ auth()->user()->can('access food waste') || auth()->user()->hasRole('Super Admin') ? route('foodwaste.executive_dashboard') : 'javascript:void(0)' }}">
                                    <div class="wheel-bubble grad-foodwaste">
                                        <i class="fas fa-leaf"></i>
                                        <span>ถังขยะเปียก</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 5. ค่าจัดการถังขยะรายปี -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('annual_trash.index') }}">
                                    <div class="wheel-bubble grad-trash">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>จัดการขยะ</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 6. กองทุนฌาปนกิจสงเคราะห์ -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('admin.welfare.dashboard') }}">
                                    <div class="wheel-bubble grad-welfare">
                                        <i class="fas fa-hands-helping"></i>
                                        <span>ฌาปนกิจ</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 7. คลังพัสดุ -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('inventory.dashboard') }}">
                                    <div class="wheel-bubble grad-inventory">
                                        <i class="fas fa-boxes"></i>
                                        <span>คลังพัสดุ</span>
                                    </div>
                                </a>
                            </div>

                            <!-- 8. ผู้ดูแลระบบ -->
                            <div class="wheel-bubble-arm">
                                <a href="{{ route('superadmin.dashboard') }}">
                                    <div class="wheel-bubble grad-admin">
                                        <i class="fas fa-user-shield"></i>
                                        <span>ตั้งค่าระบบ</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('soft-ui/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('soft-ui/assets/js/core/bootstrap.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            function renderWheel() {
                const arms = $('.wheel-bubble-arm');
                const count = arms.length;
                const startDeg = -90; // Start at top (12 o'clock)
                const step = 360 / count;

                arms.each(function (index) {
                    const deg = startDeg + (index * step);
                    const invertDeg = -deg;
                    $(this).css({
                        'transform': `rotate(${deg}deg)`,
                        'opacity': '1'
                    });
                    $(this).find('.wheel-bubble').css('transform', `rotate(${invertDeg}deg)`);
                });
            }

            function setMode(mode) {
                if (mode === 'wheel' && $(window).width() >= 992) {
                    $('#cardContainer').addClass('d-none');
                    $('#wheelContainer').removeClass('d-none');
                    $('#toggleViewModeBtn').html('<i class="fas fa-th-large me-1"></i> มุมมองการ์ด');
                    localStorage.setItem('pios_portal_mode', 'wheel');
                    setTimeout(renderWheel, 50);
                } else {
                    $('#cardContainer').removeClass('d-none');
                    $('#wheelContainer').addClass('d-none');
                    $('#toggleViewModeBtn').html('<i class="fas fa-circle-notch me-1"></i> มุมมองวงล้อ');
                    localStorage.setItem('pios_portal_mode', 'card');
                }
            }

            $('#toggleViewModeBtn').on('click', function () {
                const current = localStorage.getItem('pios_portal_mode') || 'card';
                setMode(current === 'card' ? 'wheel' : 'card');
            });

            // Default to 'card' mode as requested for better usability
            const initialMode = localStorage.getItem('pios_portal_mode') || 'card';
            setMode(initialMode);

            $(window).on('resize', function () {
                if ($(window).width() < 992) {
                    setMode('card');
                } else if (localStorage.getItem('pios_portal_mode') === 'wheel') {
                    renderWheel();
                }
            });
        });
    </script>
</body>

</html>
