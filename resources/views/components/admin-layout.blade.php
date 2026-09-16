<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CV Bintang Jaya Komputer') }} - Admin Panel</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Core Stylesheet -->
        <link rel="stylesheet" href="{{ asset('css/global-utilities.css') }}">
        <link rel="stylesheet" href="{{ asset('css/modules/admin-layout.module.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom-select.css') }}">
        @stack('styles')
        
        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body>
        @php
            $pendingOrdersCount = \App\Models\Order::whereIn('status', ['Menunggu Konfirmasi', 'Belum Dibayar'])->count();
        @endphp

        <div class="admin-wrapper" x-data="{ sidebarOpen: false }">
            <!-- Sidebar Backdrop for mobile -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="sidebar-backdrop" x-cloak></div>
            
            <!-- Sidebar -->
            <aside class="admin-sidebar" :class="{ 'open': sidebarOpen }">
                <div class="admin-sidebar-header">
                    <a href="{{ route('admin.dashboard') }}" class="brand-logo sidebar-brand-logo" style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="height: 38px; width: 38px; object-fit: contain; border-radius: 8px; box-shadow: var(--shadow-sm);">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 800; font-size: 0.95rem; color: var(--dark); line-height: 1.2;">Bintang Jaya</span>
                            <span style="font-size: 0.68rem; color: var(--primary); font-weight: 600; letter-spacing: 0.5px;">KOMPUTER</span>
                        </div>
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
                    <a href="{{ route('admin.pesanan.index') }}" class="sidebar-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }}" style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-box-open text-primary"></i>
                            <span>Kelola Pesanan</span>
                        </div>
                        @if ($pendingOrdersCount > 0)
                            <span class="badge" style="background: #ef4444; color: white; font-size: 0.72rem; padding: 2px 7px; border-radius: 999px; font-weight: 700; box-shadow: 0 2px 4px rgba(239,68,68,0.35);">
                                {{ $pendingOrdersCount }}
                            </span>
                        @endif
                    </a>

                    <div class="sidebar-section-title sidebar-section-spacer">Manajemen Data</div>
                    <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-box"></i> Produk
                    </a>
                    <a href="{{ route('admin.suppliers.index') }}" class="sidebar-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-truck-field"></i> Supplier
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Pelanggan
                    </a>

                    <div class="sidebar-section-title sidebar-section-spacer">Logistik &amp; Laporan</div>
                    <a href="{{ route('admin.stocks.index') }}" class="sidebar-link {{ request()->routeIs('admin.stocks.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-warehouse"></i> Stok Barang
                    </a>
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
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger invoice-btn-full button" style="background-color: #ef4444; border: none; font-weight: 600;">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout Admin
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Panel -->
            <main class="admin-main">
                <header class="admin-navbar">
                    <div class="flex items-center gap-4">
                        <button type="button" @click="sidebarOpen = !sidebarOpen" class="sidebar-toggle-btn">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <h2 class="font-bold navbar-title">
                            @yield('header_title', 'CV Bintang Jaya Komputer')
                        </h2>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        @if ($pendingOrdersCount > 0)
                            <a href="{{ route('admin.pesanan.index') }}" title="{{ $pendingOrdersCount }} pesanan masuk baru" style="position: relative; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 50%; background: #fee2e2; color: #dc2626;">
                                <i class="fa-solid fa-bell"></i>
                                <span style="position: absolute; top: -2px; right: -2px; width: 18px; height: 18px; background: #ef4444; color: white; border-radius: 50%; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                                    {{ $pendingOrdersCount }}
                                </span>
                            </a>
                        @endif

                        <div class="td-right admin-name">
                            <div class="font-semibold text-sm">{{ Auth::user()->name ?? 'Administrator' }}</div>
                            <div class="text-xs text-secondary">Administrator Toko</div>
                        </div>
                        <div class="navbar-avatar" style="background: var(--primary); color: white; font-weight: 700;">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
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
    <!-- Custom Searchable Select JS -->
    <script src="{{ asset('js/custom-select.js') }}"></script>
</html>