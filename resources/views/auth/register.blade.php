<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>
<body>
    <div class="auth-page">

        <!-- Left Column: Hero / Branding -->
        <div class="auth-hero">
            <div class="hero-inner">
                <div class="hero-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    E Store ID
                </div>

                <h1 class="hero-title">Bergabung<br>Bersama Kami</h1>
                <p class="hero-desc">Buat akun gratis dan mulai belanja produk digital impian Anda hari ini.</p>

                <div class="hero-divider"></div>

                <ul class="hero-features">
                    <li>
                        <div class="feature-check">✦</div>
                        Daftar gratis, tanpa biaya apapun
                    </li>
                    <li>
                        <div class="feature-check">✦</div>
                        Akses ribuan produk digital pilihan
                    </li>
                    <li>
                        <div class="feature-check">✦</div>
                        Riwayat transaksi tersimpan aman
                    </li>
                </ul>

                <p class="hero-tagline">Bergabung dengan komunitas pembeli cerdas Indonesia.</p>
            </div>
        </div>

        <!-- Right Column: Register Form -->
        <div class="auth-form-panel">
            <div class="login-container">
                <h2>Buat Akun</h2>
                <span class="login-subtitle">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></span>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               placeholder="Nama lengkap Anda" required autofocus>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="nama@email.com" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                               placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               placeholder="Ulangi password" required>
                        @error('password_confirmation')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary">Buat Akun Sekarang</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
