<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - CV Bintang Jaya Komputer</title>

    <!-- FontAwesome & Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Global Utilities & Admin Layout -->
    <link rel="stylesheet" href="{{ asset('css/global-utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/admin-layout.module.css') }}">
    @stack('styles')
    
    <!-- Alpine.js (Optional but handy for modals) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="brand-logo sidebar-brand-logo">
                    <span>Bintang Jaya</span>
                </a>
            </div>
            
            <div class="admin-sidebar-menu">
                <div class="sidebar-section-title">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="{{ route('admin.transactions.create') }}" class="sidebar-link {{ request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-cash-register text-success"></i> POS Kasir
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="sidebar-link {{ request()->routeIs('admin.transactions.index') && !request()->routeIs('admin.transactions.create') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Transaksi / Invoice
                </a>

                <div class="sidebar-section-title sidebar-section-spacer">Manajemen Data</div>
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i> Produk
                </a>
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Kategori
                </a>
                <a href="{{ route('admin.brands.index') }}" class="sidebar-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-copyright"></i> Merk / Brand
                </a>
                <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-field"></i> Supplier
                </a>
                <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Pelanggan
                </a>

                <div class="sidebar-section-title sidebar-section-spacer">Logistik & Laporan</div>
                <a href="{{ route('admin.returns.index') }}" class="sidebar-link {{ request()->routeIs('admin.returns.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-rotate-left"></i> Retur Barang
                </a>
                <a href="{{ route('admin.complaints.index') }}" class="sidebar-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i> Komplain Pelanggan
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-lines"></i> Laporan Bulanan
                </a>
            </div>
            
            <div class="admin-sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm logout-btn">
                        Logout Admin
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Panel -->
        <main class="admin-main">
            <!-- Navbar -->
            <header class="admin-navbar">
                <div class="flex items-center">
                    <h2 class="font-bold navbar-title">
                        @yield('header_title', 'Admin Panel')
                    </h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="td-right">
                        <div class="font-semibold text-sm">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-secondary">Administrator Store</div>
                    </div>
                    <div class="navbar-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="admin-content">
                <!-- Session Alerts -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-validation">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <strong>Terdapat kesalahan input:</strong>
                        </div>
                        <ul class="validation-error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
