@if(auth()->user()->can('access waste bank mobile'))
    @php $layout = 'layouts.keptkaya_mobile'; @endphp
@else
    @php $layout = 'layouts.keptkaya'; @endphp
@endif

@extends($layout)

@section('nav-header', 'ธนาคารขยะ')
@section('route-header', route('keptkayas.dashboard'))
@section('route-back', route('keptkayas.dashboard'))
@section('nav-current', 'เลือกเขตรับซื้อ')
@section('page-topic', 'ออกหน่วยรับซื้อขยะ - เลือกเขต')

@section('content')
<div class="container-fluid px-0 px-md-2">

    @if (session('success'))
        <div class="alert alert-success text-white py-2 px-3 mb-3 d-flex align-items-center rounded-3 shadow-xs">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- 4-Step Wizard Stepper (Full Width Desktop) -->
    <div class="card border-0 shadow-sm border-radius-xl mb-4 p-3 bg-white">
        @include('keptkayas.purchase.partials.stepper', ['currentStep' => 1])
    </div>

    @php
        $activeRoute = $routes->firstWhere('id', $currentRouteId);
    @endphp

    <!-- 2-Column Responsive Dashboard Layout for Full Desktop Experience -->
    <div class="row g-4 align-items-start">
        
        <!-- Left Column: Active Route Overview & Next Step Action -->
        <div class="col-12 col-lg-5 col-xl-4">
            <div class="card border-0 shadow-sm border-radius-xl overflow-hidden bg-gradient-info text-white">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-white text-info font-weight-bold px-3 py-2 rounded-pill shadow-xs">
                            <i class="fas fa-map-marker-alt me-1"></i> เขตรับซื้อปัจจุบัน
                        </span>
                        <span class="text-sm text-white opacity-8">
                            <i class="far fa-calendar-alt me-1"></i> {{ now()->format('d/m/Y') }}
                        </span>
                    </div>

                    @if($currentRouteId === 'all' || !$activeRoute)
                        <h3 class="text-white font-weight-bolder mb-2">ทุกเขต / ไม่จำกัดโซน</h3>
                        <p class="text-white opacity-9 text-sm mb-4">
                            ดึงรายชื่อสมาชิกทั้งหมดในองค์กร เหมาะสำหรับการรับซื้อ ณ จุดรับซื้อถาวร หรือจุดรวมหลัก
                        </p>
                    @else
                        <h3 class="text-white font-weight-bolder mb-2">{{ $activeRoute->route_name }}</h3>
                        <p class="text-white opacity-9 text-sm mb-4">
                            ครอบคลุมพื้นที่:
                            @forelse($activeRoute->zones as $zone)
                                <span class="badge bg-white text-dark me-1 my-1">{{ $zone->zone_name }}</span>
                            @empty
                                <span class="badge bg-white text-dark">ทุกโซนในเขต</span>
                            @endforelse
                        </p>
                    @endif

                    <div class="d-flex flex-column flex-sm-row gap-2 mt-4 pt-2 border-top border-white opacity-9">
                        <a href="{{ route('keptkayas.purchase.select_user') }}" class="btn btn-white text-info font-weight-bold mb-0 w-100 py-3 rounded-pill shadow-md">
                            <i class="fas fa-arrow-circle-right me-2"></i> ไปหน้าเลือกสมาชิก
                        </a>
                        <button type="button" class="btn btn-outline-white mb-0 d-md-none w-100 rounded-pill py-2" data-bs-toggle="offcanvas" data-bs-target="#routeOffcanvas">
                            <i class="fas fa-sliders-h me-1"></i> เปลี่ยนเขต
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Tips Card -->
            <div class="card border-0 shadow-sm border-radius-xl p-3 bg-white mt-3 d-none d-lg-block">
                <div class="d-flex align-items-center gap-2 text-secondary small">
                    <i class="fas fa-info-circle text-info fs-5"></i>
                    <span><strong>ข้อแนะนำ:</strong> เลือกเขตรับซื้อให้ตรงกับเส้นทางของรถรับซื้อ เพื่อความรวดเร็วในการค้นหารายชื่อสมาชิกตามโซน</span>
                </div>
            </div>
        </div>

        <!-- Right Column: All Selectable Routes Grid/List -->
        <div class="col-12 col-lg-7 col-xl-8">
            <div class="card border-0 shadow-sm border-radius-xl overflow-hidden bg-white">
                <div class="card-header bg-white pb-3 pt-4 px-4 d-flex align-items-center justify-content-between border-bottom">
                    <div>
                        <h5 class="font-weight-bolder text-dark mb-0">
                            <i class="fas fa-map-marked-alt text-primary me-2"></i> รายชื่อเขตรับซื้อทั้งหมด
                        </h5>
                        <p class="text-secondary text-xs mb-0">คลิกที่เขตที่ต้องการ เพื่อเปลี่ยนเขตการรับซื้อประจำวัน</p>
                    </div>
                    <span class="badge bg-light text-secondary rounded-pill px-3 py-2">
                        ทั้งหมด {{ $routes->count() + 1 }} ตัวเลือก
                    </span>
                </div>

                <div class="list-group list-group-flush p-3">
                    
                    <!-- 1. ทุกเขต / ไม่จำกัดโซน -->
                    <form action="{{ route('keptkayas.purchase.select_route') }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="route_id" value="all">
                        <button type="submit" class="list-group-item list-group-item-action p-3 rounded-3 d-flex align-items-center justify-content-between border transition-all {{ $currentRouteId === 'all' ? 'bg-light border-primary shadow-xs' : 'border-gray-200 hover-elevate' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="icon icon-shape icon-md rounded-circle text-center d-flex align-items-center justify-content-center {{ $currentRouteId === 'all' ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 text-secondary' }}">
                                    <i class="fas fa-globe-asia fs-5"></i>
                                </div>
                                <div class="text-start">
                                    <div class="text-dark font-weight-bold fs-6 mb-0">ทุกเขต / ไม่จำกัดโซน</div>
                                    <small class="text-secondary text-xs">ดึงรายชื่อสมาชิกทั้งหมดในองค์กร (รับซื้อ ณ จุดรับซื้อถาวร)</small>
                                </div>
                            </div>
                            @if($currentRouteId === 'all')
                                <span class="badge bg-primary text-white rounded-pill px-3 py-2 font-weight-bold">
                                    <i class="fas fa-check-circle me-1"></i> เลือกอยู่
                                </span>
                            @else
                                <span class="btn btn-sm btn-outline-secondary rounded-pill mb-0 px-3">
                                    เลือกเขตนี้
                                </span>
                            @endif
                        </button>
                    </form>

                    <!-- 2. Routes List -->
                    @foreach ($routes as $route)
                        <form action="{{ route('keptkayas.purchase.select_route') }}" method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" name="route_id" value="{{ $route->id }}">
                            <button type="submit" class="list-group-item list-group-item-action p-3 rounded-3 d-flex align-items-center justify-content-between border transition-all {{ $currentRouteId == $route->id ? 'bg-light border-primary shadow-xs' : 'border-gray-200 hover-elevate' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon icon-shape icon-md rounded-circle text-center d-flex align-items-center justify-content-center {{ $currentRouteId == $route->id ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 text-primary' }}">
                                        <i class="fas fa-map-marker-alt fs-5"></i>
                                    </div>
                                    <div class="text-start">
                                        <div class="text-dark font-weight-bold fs-6 mb-0">{{ $route->route_name }}</div>
                                        <small class="text-secondary text-xs">
                                            โซนที่รับผิดชอบ: 
                                            @forelse($route->zones as $z)
                                                <span class="badge bg-secondary text-white me-1">{{ $z->zone_name }}</span>
                                            @empty
                                                <span class="text-muted">ไม่ระบุโซน</span>
                                            @endforelse
                                        </small>
                                    </div>
                                </div>
                                @if($currentRouteId == $route->id)
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-2 font-weight-bold">
                                        <i class="fas fa-check-circle me-1"></i> เลือกอยู่
                                    </span>
                                @else
                                    <span class="btn btn-sm btn-outline-secondary rounded-pill mb-0 px-3">
                                        เลือกเขตนี้
                                    </span>
                                @endif
                            </button>
                        </form>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

</div>

{{-- Mobile Bottom Sheet (Offcanvas) --}}
<div class="offcanvas offcanvas-bottom rounded-top-5 h-auto d-md-none" tabindex="-1" id="routeOffcanvas" aria-labelledby="routeOffcanvasLabel" style="max-height: 80vh;">
    <div class="offcanvas-header border-bottom py-3">
        <h6 class="offcanvas-title fw-bold text-dark" id="routeOffcanvasLabel">
            <i class="fas fa-map-marker-alt text-primary me-2"></i>เลือกเขตการรับซื้อประจำวัน
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
            <form action="{{ route('keptkayas.purchase.select_route') }}" method="POST">
                @csrf
                <input type="hidden" name="route_id" value="all">
                <button type="submit" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center justify-content-between border-0 {{ $currentRouteId === 'all' ? 'bg-gray-100 font-weight-bold text-info' : '' }}">
                    <div>
                        <div class="text-dark"><i class="fas fa-globe-asia me-2"></i>ทุกเขต / ไม่จำกัดโซน</div>
                        <small class="text-secondary text-xs d-block">ดึงสมาชิกทั้งหมด (ไม่กรองโซน)</small>
                    </div>
                    @if($currentRouteId === 'all')
                        <i class="fas fa-check fs-4 text-primary"></i>
                    @endif
                </button>
            </form>

            @foreach ($routes as $route)
                <form action="{{ route('keptkayas.purchase.select_route') }}" method="POST">
                    @csrf
                    <input type="hidden" name="route_id" value="{{ $route->id }}">
                    <button type="submit" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center justify-content-between border-top {{ $currentRouteId == $route->id ? 'bg-gray-100 font-weight-bold text-info' : '' }}">
                    <div>
                        <div class="text-dark"><i class="fas fa-map-marker-alt me-2"></i>{{ $route->route_name }}</div>
                        <small class="text-secondary text-xs d-block">
                            โซน: {{ $route->zones->pluck('zone_name')->implode(', ') }}
                        </small>
                    </div>
                    @if($currentRouteId == $route->id)
                        <i class="fas fa-check fs-4 text-primary"></i>
                    @endif
                </button>
            </form>
            @endforeach
        </div>
    </div>
</div>
@endsection
