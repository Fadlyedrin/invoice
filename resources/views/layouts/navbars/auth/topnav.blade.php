<!-- Navbar -->
<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl
    {{ str_contains(Request::url(), 'virtual-reality') == true ? ' mt-3 mx-3 bg-primary' : '' }}"
    id="navbarBlur" data-scroll="false">
    
    <div class="container-fluid py-1 px-3 d-flex justify-content-between align-items-center">

        <!-- Left: Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            </ol>
        </nav>

        <!-- Right: Logout -->
        <ul class="navbar-nav d-flex align-items-center mb-0">
            <li class="nav-item d-flex align-items-center">
                <form role="form" method="post" action="{{ route('logout') }}" id="logout-form" class="mb-0">
                    @csrf
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link text-white font-weight-bold px-2">
                        <i class="fa fa-user me-1"></i>
                        <span class="d-inline">Log out</span>
                    </a>
                </form>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner" style="transform: scale(1.2);">
                        <i class="sidenav-toggler-line bg-white"></i>
                        <i class="sidenav-toggler-line bg-white"></i>
                        <i class="sidenav-toggler-line bg-white"></i>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- End Navbar -->
<!-- End Navbar -->
