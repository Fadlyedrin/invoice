<div class="container-fluid position-sticky z-index-sticky top-0 mb-5">
    <div class="row">
        <div class="col-12">
            <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid d-flex justify-content-between align-items-center">

                    <!-- Logo -->
                    <a class="navbar-brand font-weight-bolder ms-2" href="{{ route('home') }}">
                        Invoice System
                    </a>

                    <!-- Hamburger / Toggler Button -->
                    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarContent">
                        <!-- Tengah: Menu -->
                        <div class="d-flex justify-content-center flex-grow-1">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
                                        <i class="fa fa-chart-pie text-dark me-1"></i> Dashboard
                                    </a>
                                </li>

                                <!-- Transaksi Dropdown -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class="fa fa-folder-open text-dark me-1"></i> Transaksi
                                    </a>
                                    <ul class="dropdown-menu" style="margin-top: 0.3rem !important; top: 100% !important;">
                                        <li><a class="dropdown-item" href="{{ url('invoices') }}">Invoice</a></li>
                                        <li><a class="dropdown-item" href="{{ url('receipts') }}">Receipt</a></li>
                                        @can('approval invoices')
                                            <li><a class="dropdown-item" href="{{ route('approvalPage') }}">Approval</a></li>
                                        @endcan
                                    </ul>
                                </li>

                                <!-- User Management Dropdown -->
                                @can('roles')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class="fa fa-user-shield text-dark me-1"></i> User Management
                                    </a>
                                    <ul class="dropdown-menu" style="margin-top: 0.3rem !important; top: 100% !important;">
                                        @can('users')
                                            <li><a class="dropdown-item" href="{{ url('users') }}">User</a></li>
                                        @endcan
                                        <li><a class="dropdown-item" href="{{ url('roles') }}">Role</a></li>
                                        <li><a class="dropdown-item" href="{{ url('permissions') }}">Permissions</a></li>
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </div>

                        <!-- Kanan: Profile -->
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                    <i class="fa fa-user-circle text-dark me-1"></i> {{ auth()->user()->username ?? 'User' }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="margin-top: 0.3rem !important; top: 100% !important;">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
        </div>
    </div>
</div>
