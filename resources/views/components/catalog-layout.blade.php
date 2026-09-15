<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Katalog - CV Bintang Jaya Komputer</title>

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/global-utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/catalog-index.module.css') }}">
    @stack('styles')

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header class="guest-header">
        <div class="container guest-navbar">
            <a href="{{ route('catalog.index') }}" class="brand-logo">
                <span>Bintang Komputer</span>
            </a>
            
            <div class="flex items-center gap-3">
                @auth
                    @if(strtolower(trim(Auth::user()->email)) === 'admin@bintangkomputer.com')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-gauge-high mr-1"></i> Admin Panel
                        </a>
                    @else
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary btn-sm" style="margin-right: 5px;">
                            <i class="fa-solid fa-box-archive mr-1"></i> Riwayat Pesanan
                        </a>
                        <span class="text-sm text-secondary mr-1">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                    @endif

                    <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" style="border: none; background: #fee2e2; color: #dc2626;">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i> Login / Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="catalog-footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} CV Bintang Jaya Komputer. All rights reserved.</p>
            <p class="catalog-footer-address">Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung</p>
        </div>
    </footer>
</body>
</html>