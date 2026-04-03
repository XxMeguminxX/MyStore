<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — E Store ID</title>
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

                <h1 class="hero-title">Selamat<br>Datang Kembali</h1>
                <p class="hero-desc">Masuk ke akun Anda dan temukan ribuan produk digital pilihan dengan harga terbaik.</p>

                <div class="hero-divider"></div>

                <ul class="hero-features">
                    <li>
                        <div class="feature-check">✦</div>
                        Produk digital berkualitas tinggi
                    </li>
                    <li>
                        <div class="feature-check">✦</div>
                        Pembayaran aman & terpercaya
                    </li>
                    <li>
                        <div class="feature-check">✦</div>
                        Pengiriman & aktivasi instan
                    </li>
                </ul>

                <p class="hero-tagline">Dipercaya oleh ribuan pengguna di seluruh Indonesia.</p>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="auth-form-panel">
            <div class="login-container">
                <h2>Masuk</h2>
                <span class="login-subtitle">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></span>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               placeholder="nama@email.com" required autofocus>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                               placeholder="Masukkan password" required>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary">Masuk ke Akun</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
