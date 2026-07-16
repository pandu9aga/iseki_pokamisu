<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Iseki Pokamisu')</title>

    <!-- <link rel="preconnect" href="https://fonts.gstatic.com"> -->
    <link href="{{ asset('assets/fonts/nunito/fonts.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/dataTables.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <style>
        :root { --pink: #d63384; --pink-light: #f8e8f0; --pink-border: #c060a0; --pink-hover: #c5277a; }
        .btn-primary { background-color: var(--pink) !important; border-color: var(--pink) !important; }
        .btn-primary:hover, .btn-primary:focus { background-color: var(--pink-hover) !important; border-color: var(--pink-hover) !important; }
        .btn-outline-primary { color: var(--pink) !important; border-color: var(--pink) !important; }
        .btn-outline-primary:hover { background-color: var(--pink) !important; border-color: var(--pink) !important; color: #fff !important; }
        a:not(.btn):not(.page-link) { color: var(--pink); }
        a:not(.btn):not(.page-link):hover { color: var(--pink-hover); }
        .text-primary { color: var(--pink) !important; }
        .bg-primary { background-color: var(--pink) !important; }
        .border-primary { border-color: var(--pink) !important; }
        .page-item.active .page-link { background-color: var(--pink) !important; border-color: var(--pink) !important; }
        .page-link:focus { box-shadow: 0 0 0 .25rem rgba(214, 51, 132, .25) !important; }
        .nav-link.active { color: var(--pink) !important; }
        .sidebar-item.active .sidebar-link { background-color: var(--pink) !important; }
        .pagination.pagination-primary .page-item.active .page-link { background-color: var(--pink) !important; border-color: var(--pink) !important; box-shadow: 0 2px 5px rgba(214,51,132,.3) !important; }
        .form-check-input:checked { background-color: var(--pink) !important; border-color: var(--pink) !important; }
        .nav-tabs .nav-link.active { color: var(--pink) !important; }
        .nav-tabs .nav-link.active:after { background-color: var(--pink) !important; }
        .btn-light-primary { background-color: var(--pink-light) !important; color: #a03070 !important; }
        .form-control:focus, .dataTable-input:focus { border-color: var(--pink) !important; box-shadow: 0 0 0 .25rem rgba(214,51,132,.25) !important; }
        .form-group.with-title .form-control:focus~label { border-color: var(--pink) !important; }
        .badge.bg-primary { background-color: var(--pink) !important; }
        .alert-primary { background-color: var(--pink) !important; }
        .dataTable-table thead th.dt-orderable-asc, .dataTable-table thead th.dt-orderable-desc { color: var(--pink) !important; }
        .spinner-border.text-primary { color: var(--pink) !important; }
        .page-item:not(.active) .page-link:hover { color: var(--pink) !important; }
        .dropdown-item:hover, .dropdown-item:focus { background-color: var(--pink-light) !important; color: var(--pink) !important; }
        .preset-batch.selected { border-color: var(--pink) !important; }
        #table1 .cell-wrap .edit-input { border-color: var(--pink) !important; }
        #table1 .edit-options { border-color: var(--pink) !important; }
        #table1 .edit-options .edit-option.selected { background: var(--pink-light) !important; font-weight: bold; }
        .batch-toolbar { background: var(--pink-light) !important; border-color: var(--pink-border) !important; }
    </style>
    <!-- Dynamic Favicon -->
    <script src="/iseki_pro_app/js/dynamic-favicon.js"></script>
    <script>document.addEventListener("DOMContentLoaded", function() { setDynamicFavicon("assignment", "Pokamisu"); });</script>

    <!-- Dynamic Favicon Assets -->
    <link rel="stylesheet" href="/iseki_pro_app/css/icon.css">
    <script src="/iseki_pro_app/js/dynamic-favicon.js"></script>
    <script>document.addEventListener("DOMContentLoaded", function() { setDynamicFavicon("assignment", "Pokamisu"); });</script>
    @stack('styles')
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="{{ route('data.index') }}">Pokamisu</a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>
                        <li class="sidebar-item {{ request()->routeIs('data.index') ? 'active' : '' }}">
                            <a href="{{ route('data.index') }}" class='sidebar-link'>
                                <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                                <span>Data Table</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('data.import.form') ? 'active' : '' }}">
                            <a href="{{ route('data.import.form') }}" class='sidebar-link'>
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                <span>Import Excel</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>Manage Users</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main" class='layout-navbar'>
            <header class='mb-3'>
                <nav class="navbar navbar-expand navbar-light">
                    <div class="container-fluid">
                        <a href="#" class="burger-btn d-block">
                            <i class="bi bi-justify fs-3"></i>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <!-- <li class="nav-item dropdown me-1">
                                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class='bi bi-envelope bi-sub fs-4 text-gray-600'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">Mail</h6></li>
                                        <li><a class="dropdown-item" href="#">No new mail</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown me-3">
                                    <a class="nav-link active dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                        <i class='bi bi-bell bi-sub fs-4 text-gray-600'></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><h6 class="dropdown-header">Notifications</h6></li>
                                        <li><a class="dropdown-item">No notification available</a></li>
                                    </ul>
                                </li> -->
                            </ul>
                            <div class="dropdown">
                                <a href="#" data-bs-toggle="dropdown">
                                    <div class="user-menu d-flex">
                                        <div class="user-name text-end me-3">
                                            <h6 class="mb-0 text-gray-600">{{ Session::get('username', 'Administrator') }}</h6>
                                            <p class="mb-0 text-sm text-gray-600">Iseki Pokamisu</p>
                                        </div>
                                        <div class="user-img d-flex align-items-center">
                                            <div class="avatar avatar-md">
                                                <img src="{{ asset('assets/images/faces/1.jpg') }}">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <div id="main-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible show fade">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @yield('content')
            </div>
            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start ms-2">
                        <p>Iseki Pokamisu</p>
                    </div>
                    <div class="float-end me-2">
                        <p>Iseki - 
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
