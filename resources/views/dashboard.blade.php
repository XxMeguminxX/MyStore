<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E Store ID — Produk Digital Terpercaya</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body>
    <!-- ===== HEADER ===== -->
    <header class="header-bar">
        <a href="{{ url('/') }}" class="header-brand">
            <div class="brand-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span>E Store ID</span>
        </a>

        <nav class="header-nav">
            <a href="{{ url('/') }}" class="header-nav-link active">Beranda</a>
            <a href="#produk" class="header-nav-link">Produk</a>
            <a href="{{ url('/halaman/cara-beli') }}" class="header-nav-link">Cara Beli</a>
        </nav>

        <div class="search-container">
            <div class="search-wrapper">
                <svg class="search-icon-svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="productSearch" onkeyup="filterProducts()" placeholder="Cari produk digital..." class="search-input">
            </div>
        </div>

        <div class="header-icons">
            @auth
                <a href="{{ route('cart.index') }}" class="icon-btn cart-icon" title="Keranjang" id="cartBtn">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                    </svg>
                    <span class="cart-count" id="cartCount">0</span>
                </a>

                <div class="user-menu-dropdown">
                    <button type="button" class="icon-btn user-menu-btn" id="userMenuBtn" title="Menu Akun">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="2" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
                        </svg>
                        <span class="user-greeting">{{ Str::limit(auth()->user()->name, 12) }}</span>
                    </button>
                    <div class="user-menu" id="userMenu">
                        <a href="{{ url('/profile') }}" class="user-menu-item">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="8" r="4" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/></svg>
                            Profil
                        </a>
                        <a href="{{ route('transaction.history') }}" class="user-menu-item">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Histori Transaksi
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-menu-item user-menu-logout">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-header-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-header-register">Daftar Gratis</a>
            @endauth
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- ===== HERO SECTION ===== -->
    <section class="dashboard-hero">
        <!-- LEFT: Copy & CTA -->
        <div class="hero-content-card">
            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>

            <span class="hero-badge">
                <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Toko Digital Pilihan
            </span>

            <h2 class="hero-headline">
                Produk Digital,<br>
                <span class="hero-headline-accent">Aktivasi Instan</span><br>
                Harga Terbaik
            </h2>

            <p class="hero-sub">
                Lebih hemat dari marketplace. Aktif dalam hitungan detik. Garansi <strong>100% uang kembali</strong> jika gagal.
            </p>

            <div class="hero-trust-row">
                <div class="trust-item">
                    <span class="trust-icon">⭐</span>
                    <div>
                        <strong>4.9</strong>
                        <span>Rating</span>
                    </div>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <span class="trust-icon">🛒</span>
                    <div>
                        <strong>1.2K+</strong>
                        <span>Pembeli</span>
                    </div>
                </div>
                <div class="trust-divider"></div>
                <div class="trust-item">
                    <span class="trust-icon">🛡️</span>
                    <div>
                        <strong>100%</strong>
                        <span>Aman</span>
                    </div>
                </div>
            </div>

            <a href="#produk" class="hero-cta">
                Lihat Produk Sekarang
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <!-- RIGHT: Visual Stats Card -->
        <div class="hero-visual-card">
            <div class="hero-visual-orb"></div>

            <div class="visual-card-header">
                <span class="visual-card-title">Kenapa Pilih Kami?</span>
                <span class="visual-card-badge">Terpercaya</span>
            </div>

            <div class="hero-stats-grid">
                <div class="hero-stat-item">
                    <div class="stat-icon stat-icon-blue">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="stat-text">
                        <strong>&lt; 1 Menit</strong>
                        <span>Waktu Aktivasi</span>
                    </div>
                </div>
                <div class="hero-stat-item">
                    <div class="stat-icon stat-icon-indigo">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="stat-text">
                        <strong>Garansi Penuh</strong>
                        <span>Refund jika gagal</span>
                    </div>
                </div>
                <div class="hero-stat-item">
                    <div class="stat-icon stat-icon-mint">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="stat-text">
                        <strong>Harga Terbaik</strong>
                        <span>Lebih murah 20–40%</span>
                    </div>
                </div>
                <div class="hero-stat-item">
                    <div class="stat-icon stat-icon-peach">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="stat-text">
                        <strong>Multi Pembayaran</strong>
                        <span>QRIS, Transfer Bank</span>
                    </div>
                </div>
            </div>

            <div class="hero-checklist">
                <div class="check-item">
                    <span class="check-dot"></span>
                    Produk dikurasi langsung oleh tim kami
                </div>
                <div class="check-item">
                    <span class="check-dot"></span>
                    Notifikasi otomatis setelah pembayaran
                </div>
                <div class="check-item">
                    <span class="check-dot"></span>
                    Support responsif via chat
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRODUCT SECTION ===== -->
    <div class="section-header" id="produk">
        <div class="section-header-inner">
            <h1 class="section-title">Produk Digital</h1>
            <p class="section-subtitle">Temukan produk terbaik untuk kebutuhanmu — aktivasi instan, harga transparan</p>
            <div class="section-accent"></div>
        </div>
    </div>

    <div id="noResults" class="no-results-message">
        <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="margin:0 auto 12px;display:block;opacity:0.3">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        Produk tidak ditemukan.
    </div>

    <!-- Filter -->
    <div class="filter-container">
        <div class="filter-dropdown">
            <button type="button" class="filter-btn" id="filterBtn">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <span id="filterText">Urutkan</span>
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="filter-arrow">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="filter-options" id="filterOptions">
                <a href="{{ url()->current() }}?sort=newest" class="filter-option {{ $sortBy == 'newest' ? 'active' : '' }}" data-sort="newest">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Terbaru
                </a>
                <a href="{{ url()->current() }}?sort=price_high" class="filter-option {{ $sortBy == 'price_high' ? 'active' : '' }}" data-sort="price_high">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Harga Tertinggi
                </a>
                <a href="{{ url()->current() }}?sort=price_low" class="filter-option {{ $sortBy == 'price_low' ? 'active' : '' }}" data-sort="price_low">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                    Harga Terendah
                </a>
                <a href="{{ url()->current() }}?sort=bestseller" class="filter-option {{ $sortBy == 'bestseller' ? 'active' : '' }}" data-sort="bestseller">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Terlaris
                </a>
                <a href="{{ url()->current() }}?sort=stock_high" class="filter-option {{ $sortBy == 'stock_high' ? 'active' : '' }}" data-sort="stock_high">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Stok Terbanyak
                </a>
                <a href="{{ url()->current() }}?sort=stock_low" class="filter-option {{ $sortBy == 'stock_low' ? 'active' : '' }}" data-sort="stock_low">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    Stok Tersedikit
                </a>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="product-section">
        <div class="product-grid">
            @foreach ($products as $data)
            @php
                $badgeType = '';
                $badgeText = '';
                if ($loop->index % 4 === 0) { $badgeType = 'bestseller'; $badgeText = '🔥 Terlaris'; }
                elseif ($loop->index % 4 === 1) { $badgeType = 'hot'; $badgeText = '⚡ Hot'; }
                elseif ($loop->index % 4 === 2) { $badgeType = 'new'; $badgeText = '✨ Baru'; }
            @endphp
            <div class="product-card product-card-clickable" data-url="{{ route('product.show', $data->id) }}" role="button" tabindex="0">
                @if($badgeText)
                <div class="product-badge product-badge-{{ $badgeType }}">{{ $badgeText }}</div>
                @endif

                <div class="product-img-wrapper">
                    <img class="product-img" src="{{ $data->image }}" alt="{{ $data->name }}">
                    <div class="product-img-overlay">
                        <span class="overlay-cta">Lihat Detail</span>
                    </div>
                </div>

                <div class="product-body">
                    <div class="product-title">{{ $data->name }}</div>

                    <div class="product-desc">
                        <span class="desc-short">{{ substr($data->description, 0, 72) }}{{ strlen($data->description) > 72 ? '...' : '' }}</span>
                    </div>

                    <div class="product-stock">
                        <span class="stock-badge
                            @if($data->stock > 10) stock-badge-high
                            @elseif($data->stock > 0) stock-badge-low
                            @else stock-badge-empty
                            @endif">
                            @if($data->stock > 10)
                                <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Tersedia
                            @elseif($data->stock > 0)
                                <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Stok Terbatas
                            @else
                                <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Habis
                            @endif
                        </span>
                        @if($data->stock > 0)
                        <span class="stock-count">{{ $data->stock }} unit</span>
                        @endif
                    </div>
                </div>

                <div class="product-footer">
                    <div class="product-price">Rp {{ number_format($data->price, 0, '', '.') }}</div>
                    <div class="product-cta-btn">
                        Lihat Detail
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Desc Modal -->
    <div id="desc-modal" class="desc-modal" style="display:none;">
        <div class="desc-modal-content">
            <button class="desc-modal-close" onclick="closeDescModal()">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div id="desc-modal-title" class="desc-modal-title"></div>
            <div id="desc-modal-body" class="desc-modal-body"></div>
        </div>
    </div>

    <!-- Login Required Modal -->
    <div id="loginRequiredModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Login Diperlukan</h3>
                <button class="modal-close" onclick="closeLoginRequiredModal()">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="login-required-content">
                    <div class="login-icon">
                        <svg width="52" height="52" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h4>Masuk untuk melanjutkan</h4>
                    <p>Buat akun gratis atau masuk untuk membeli produk digital dengan aman.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeLoginRequiredModal()">Nanti Saja</button>
                <a href="{{ route('login') }}" class="btn-confirm">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register">Daftar Gratis</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-brand-group">
                <a href="{{ url('/') }}" class="footer-brand">
                    <div class="brand-icon brand-icon-sm">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    E Store ID
                </a>
                <span class="footer-tagline">Toko digital terpercaya, aktivasi instan.</span>
            </div>
            <nav class="footer-links">
                <a href="{{ route('static.page', 'kebijakan-privasi') }}">Kebijakan Privasi</a>
                <a href="{{ route('static.page', 'ketentuan-layanan') }}">Ketentuan Layanan</a>
                <a href="{{ route('static.page', 'tentang-kami') }}">Tentang Kami</a>
                <a href="{{ route('static.page', 'kontak') }}">Kontak</a>
            </nav>
            <span class="footer-copy">&copy; {{ date('Y') }} E Store ID</span>
        </div>
    </footer>

    <script>
    // ===== MODAL =====
    function openDescModal(btn) {
        const card = btn.closest('.product-card');
        const title = card.querySelector('.product-title').textContent;
        const fullDesc = card.querySelector('.desc-full').innerHTML;
        document.getElementById('desc-modal-title').textContent = title;
        document.getElementById('desc-modal-body').innerHTML = fullDesc;
        document.getElementById('desc-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeDescModal() {
        document.getElementById('desc-modal').style.display = 'none';
        document.body.style.overflow = '';
    }
    function showLoginRequiredModal() {
        document.getElementById('loginRequiredModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeLoginRequiredModal() {
        document.getElementById('loginRequiredModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    window.onclick = function(event) {
        const descModal = document.getElementById('desc-modal');
        const loginModal = document.getElementById('loginRequiredModal');
        if (event.target == descModal) { descModal.style.display = 'none'; document.body.style.overflow = ''; }
        if (event.target == loginModal) { closeLoginRequiredModal(); }
    };

    // ===== SEARCH =====
    function filterProducts() {
        const q = document.getElementById('productSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        let count = 0;
        cards.forEach(card => {
            const match = card.querySelector('.product-title').textContent.toLowerCase().includes(q);
            card.style.display = match ? '' : 'none';
            if (match) count++;
        });
        document.getElementById('noResults').style.display = count === 0 ? 'block' : 'none';
    }
    window.onload = filterProducts;

    // ===== CART =====
    async function updateCartCount() {
        try {
            const res = await fetch('/cart/count');
            const data = await res.json();
            const el = document.getElementById('cartCount');
            if (el) {
                el.textContent = data.count > 9 ? '9+' : data.count;
                el.style.display = data.count > 0 ? 'flex' : 'none';
            }
        } catch(e) {}
    }

    function showNotification(msg, type = 'info') {
        const existing = document.querySelector('.cart-notification');
        if (existing) existing.remove();
        const n = document.createElement('div');
        n.className = `cart-notification ${type}`;
        n.textContent = msg;
        document.body.appendChild(n);
        setTimeout(() => n.classList.add('show'), 10);
        setTimeout(() => { n.classList.remove('show'); setTimeout(() => n.remove(), 300); }, 3000);
    }

    function animateCartButton() {
        const btn = document.getElementById('cartBtn');
        if (btn) { btn.style.transform = 'scale(1.2)'; setTimeout(() => btn.style.transform = '', 200); }
    }

    // ===== FILTER DROPDOWN =====
    function initFilterDropdown() {
        const btn = document.getElementById('filterBtn');
        const dropdown = document.querySelector('.filter-dropdown');
        const opts = document.getElementById('filterOptions');
        if (!btn || !dropdown) return;
        btn.addEventListener('click', e => { e.stopPropagation(); dropdown.classList.toggle('open'); });
        document.addEventListener('click', e => { if (!dropdown.contains(e.target)) dropdown.classList.remove('open'); });
        const active = opts.querySelector('.filter-option.active');
        if (active) {
            const labels = { newest:'Terbaru', price_high:'Harga Tertinggi', price_low:'Harga Terendah', stock_high:'Stok Terbanyak', stock_low:'Stok Tersedikit', bestseller:'Terlaris' };
            const t = active.getAttribute('data-sort');
            if (labels[t]) document.getElementById('filterText').textContent = labels[t];
        }
    }

    // ===== PRODUCT CARD CLICK =====
    function initProductCardClicks() {
        document.querySelectorAll('.product-card-clickable').forEach(card => {
            card.addEventListener('click', e => {
                if (e.target.closest('.product-card-no-click')) return;
                const url = card.getAttribute('data-url');
                if (url) window.location.href = url;
            });
            card.addEventListener('keydown', e => {
                if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.product-card-no-click')) {
                    e.preventDefault();
                    const url = card.getAttribute('data-url');
                    if (url) window.location.href = url;
                }
            });
        });
    }

    // ===== STICKY NAVBAR SCROLL SHADOW =====
    (function() {
        const header = document.querySelector('.header-bar');
        if (!header) return;
        window.addEventListener('scroll', () => {
            header.classList.toggle('scrolled', window.scrollY > 10);
        }, { passive: true });
    })();

    // ===== USER MENU =====
    document.addEventListener('DOMContentLoaded', () => {
        initFilterDropdown();
        initProductCardClicks();
        updateCartCount();

        const menuBtn = document.getElementById('userMenuBtn');
        const menu = document.getElementById('userMenu');
        if (menuBtn && menu) {
            menuBtn.addEventListener('click', e => { e.stopPropagation(); menu.classList.toggle('open'); });
            document.addEventListener('click', e => { if (!menu.contains(e.target) && e.target !== menuBtn) menu.classList.remove('open'); });
        }
    });
    </script>

    <!-- WhatsApp CS Floating Button -->
    <a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat Customer Service via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>
</html>
