<ul class="navbar-nav">
    <li class="nav-item">
        <a href="{{ route('accessmenu') }}" class="nav-link">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-th-large text-primary text-gradient"></i>
            </div>
            <span class="nav-link-text ms-1 font-weight-bold">เมนูรวมระบบ</span>
        </a>
    </li>

    <li class="nav-item">
        <a data-bs-toggle="collapse" href="#cart" class="nav-link active" aria-controls="cart" role="button" aria-expanded="true">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-recycle text-success text-gradient"></i>
            </div>
            <span class="nav-link-text ms-1 font-weight-bold">ธนาคารขยะรีไซเคิล</span>
        </a>
        <div class="collapse show" id="cart">
            <ul class="nav ms-3 ps-2">
                <li class="nav-item">
                    <a class="nav-link @yield('nav-cart')" href="{{ route('keptkayas.purchase.select_route') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-truck-loading text-success text-gradient"></i>
                        </div>
                        <span class="sidenav-normal">ออกหน่วยรับซื้อขยะ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('keptkayas.sell.form') }}">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-warning text-gradient"></i>
                        </div>
                        <span class="sidenav-normal">บันทึกการขายขยะ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-out-alt text-danger text-gradient"></i>
                        </div>
                        <span class="sidenav-normal font-weight-bold">ออกจากระบบ</span>
                    </a>
                    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </li>
</ul>
