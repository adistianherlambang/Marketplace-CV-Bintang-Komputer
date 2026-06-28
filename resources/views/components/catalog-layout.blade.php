<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Katalog - CV Bintang Jaya Komputer</title>

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Header -->
    <header class="guest-header">
        <div class="container guest-navbar">
            <a href="{{ route('catalog.index') }}" class="brand-logo">
                <i class="fa-solid fa-laptop-code"></i>
                <span>Bintang Komputer</span>
            </a>
            
            <div class="flex items-center gap-6">
                <a href="{{ route('catalog.index') }}" class="font-semibold hover:text-primary">
                    <i class="fa-solid fa-house mr-1"></i> Home
                </a>
                
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-gauge mr-1"></i> Admin Panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i> Login Admin
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
    <footer style="background-color: var(--white); border-top: 1px solid var(--border); padding: 40px 0; margin-top: 60px; text-align: center; color: var(--secondary); font-size: 0.875rem;">
        <div class="container">
            <p>&copy; {{ date('Y') }} CV Bintang Jaya Komputer. All rights reserved.</p>
            <p style="margin-top: 8px; font-size: 0.75rem; opacity: 0.8;">Jl. Ahmad Yani No.68, Iringmulyo, Kota Metro, Lampung</p>
        </div>
    </footer>
</body>
</html>
