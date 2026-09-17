<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CV Bintang Jaya Komputer' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('catalog.index') }}">
                <img src="{{ asset('img/logo/logoKesamping.jpg') }}" alt="CV Bintang Jaya Komputer" style="height: 38px; width: auto; object-fit: contain; border-radius: 6px;">
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary btn-sm fw-bold me-1">
                        <i class="fa-solid fa-box-archive me-1"></i> Riwayat Pesanan
                    </a>
                    <span class="small text-muted me-2">Halo, <strong>{{ Auth::user()->name }}</strong></span>
                    <form action="{{ route('customer.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm fw-bold px-3">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Login
                    </a>
                    <a href="{{ route('customer.register') }}" class="btn btn-primary btn-sm fw-bold px-3 text-white">
                        <i class="fa-solid fa-user-plus me-1"></i> Daftar Akun
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-4">
        {{ $slot }}
    </main>

    <footer class="bg-white text-center py-3 text-muted small border-top mt-5">
        <div class="container">
            &copy; {{ date('Y') }} CV Bintang Jaya Komputer. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>