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
</head>
<body>
    <!-- Header -->
    <header class="guest-header">
        <div class="container guest-navbar">
            <a href="{{ route('catalog.index') }}" class="brand-logo">
                <span>Bintang Komputer</span>
            </a>
            
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                        Admin Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                        Login Admin
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

