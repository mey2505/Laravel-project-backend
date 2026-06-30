<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Admin') }} - @yield('title', 'Dashboard')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Theme style (AdminLTE v4 Beta) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/css/adminlte.min.css">
    
    @stack('styles')
    
    <style>
        /* Smooth transitions for interactive elements */
        .btn, .nav-link, .table-hover tbody tr {
            transition: all 0.2s ease-in-out;
        }
        .table-hover tbody tr:hover {
            transform: scale(1.002);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .main-sidebar .nav-sidebar .nav-item > .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body class="layout-fixed">
<div class="app-wrapper">

    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <!-- Logout Button -->
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!-- Brand Logo -->
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                <span class="brand-text fw-light">Admin Panel</span>
            </a>
        </div>

        <!-- Sidebar -->
        <div class="sidebar-wrapper">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-list"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Products</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Customers</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Orders</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-star"></i>
                            <p>Reviews</p>
                        </a>
                    </li>
                    <li class="nav-header">REPORTS & SYSTEM</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>Notifications
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge text-bg-warning float-end">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Audit Logs</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <main class="app-main">
        <!-- Content Header (Page header) -->
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">@yield('title', 'Dashboard')</h3>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="app-content">
            <div class="container-fluid">
                @include('layouts.admin.flash-messages')
                @yield('content')
            </div>
        </div>
        <!-- /.content -->
    </main>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">
            E-Commerce Platform
        </div>
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name') }}</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta2/dist/js/adminlte.min.js"></script>

<!-- Replace jQuery dependent logic with vanilla JS where possible, but if needed some plugins might require jQuery -->
<!-- For example, Select2, DataTables if they are used, but we stick to vanilla for now -->

@stack('scripts')
</body>
</html>
