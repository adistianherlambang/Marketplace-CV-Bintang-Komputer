<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Bintang Komputer</title>
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
                <p class="text-muted small">Silakan login untuk melanjutkan proses checkout pesanan Anda.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger small py-2">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Dev Quick Fill Helper --}}
            <div class="p-3 mb-4 rounded-3 border bg-light">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-bold text-secondary">
                        <i class="fa-solid fa-code text-primary me-1"></i> Quick Fill (Dev)
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary border">Dev Mode</span>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger btn-sm flex-fill d-flex align-items-center justify-content-center gap-1 py-1" onclick="fillLogin('admin@bintangkomputer.com', 'password')">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Admin</span>
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm flex-fill d-flex align-items-center justify-content-center gap-1 py-1" onclick="fillLogin('user@bintangkomputer.com', 'password')">
                        <i class="fa-solid fa-user"></i>
                        <span>User</span>
                    </button>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email Pelanggan</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="********" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small" for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3 shadow-sm">
                    Masuk ke Akun
                </button>
            </form>

            <div class="text-center">
                <p class="small text-muted">Belum punya akun? <a href="{{ route('customer.register') }}" class="text-decoration-none fw-bold">Buat Akun Baru di sini</a></p>
                <a href="{{ route('catalog.index') }}" class="text-decoration-none small text-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                passwordInput.focus();
            }
        }
    </script>
</body>
</html>