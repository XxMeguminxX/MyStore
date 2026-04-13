<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — E Store ID</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/duotone/style.css">
</head>
<body>
    <div class="auth-page">

        <!-- Left Column: Hero / Branding -->
        <div class="auth-hero">
            <div class="hero-inner">
                <div class="hero-logo">
                    <i class="ph-bold ph-bag"></i>
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
