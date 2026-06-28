<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin - CV Bintang Jaya Komputer</title>

    <!-- FontAwesome & Modular CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/global-utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modules/auth.module.css') }}">
</head>
<body class="auth-body">
    <div class="auth-split-container">
        
        <!-- Left Side Pane -->
        <div class="auth-left-pane">
            <div class="auth-left-content">
                <div class="auth-star-icon">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h1 class="auth-heading">Hello Admin!</h1>
                <p class="auth-subheading">Kelola transaksi kasir, pantau persediaan stok barang, tangani retur/komplain, dan buat laporan keuangan bulanan dalam satu sistem informasi terintegrasi.</p>
            </div>
            
            <div class="auth-copyright">
                &copy; {{ date('Y') }} CV Bintang Jaya Komputer
            </div>
        </div>

        <!-- Right Side Pane (Form) -->
        <div class="auth-right-pane">
            <div class="auth-form-container">
                <!-- Store Name / Logo -->
                <div class="brand-logo auth-brand-logo-spacer">
                    <i class="fa-solid fa-laptop-code text-primary"></i>
                    <span class="auth-brand-name">Bintang Komputer</span>
                </div>

                <h2 class="auth-form-title">Selamat Datang</h2>
                <p class="auth-form-subtitle">Silakan login untuk mengakses dashboard administrator.</p>

                <!-- Session Status / Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger auth-error-alert">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Email atau password salah.</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Administrator</label>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@bintangkomputer.com">
                    </div>

                    <!-- Password -->
                    <div class="form-group auth-password-group">
                        <div class="flex justify-between items-center auth-password-label-row">
                            <label for="password" class="form-label auth-password-label-nomargin">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold auth-forgot-link">Lupa Password?</a>
                            @endif
                        </div>
                        <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group auth-remember-group">
                        <input id="remember_me" type="checkbox" name="remember" class="auth-remember-checkbox">
                        <label for="remember_me" class="text-xs text-secondary font-semibold auth-remember-label">Ingat saya di perangkat ini</label>
                    </div>

                    <!-- Actions -->
                    <button type="submit" class="btn btn-primary auth-login-btn">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="auth-back-link">
                    <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-secondary hover:text-primary">
                        <i class="fa-solid fa-arrow-left-long mr-1"></i> Kembali ke Katalog Guest
                    </a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
