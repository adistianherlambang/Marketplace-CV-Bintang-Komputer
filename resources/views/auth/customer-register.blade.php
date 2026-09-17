<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Pelanggan - Bintang Komputer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container" style="max-width: 450px;">
        <div class="card shadow-sm border-0 p-4 rounded-4">
            <div class="text-center mb-4">
                <a href="{{ route('catalog.index') }}">
                    <img src="{{ asset('img/logo/logoKesamping.jpg') }}" alt="CV Bintang Jaya Komputer" style="height: 48px; width: auto; max-width: 100%; object-fit: contain;" class="mb-2">
                </a>
                <p class="text-muted small">Buat akun untuk mulai berbelanja di Bintang Komputer.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger small py-2">
                    {{ $errors->first() }}
                </div>
            @endif

           <form action="{{ route('customer.register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nama Lengkap Anda" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold mb-3 shadow-sm">
                    Daftar & Masuk Sekarang
                </button>
            </form>

            <div class="text-center">
                <!-- DIPERBAIKI: Menggunakan route('login') -->
                <p class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login di sini</a></p>
                <a href="{{ route('catalog.index') }}" class="text-decoration-none small text-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</body>
</html>