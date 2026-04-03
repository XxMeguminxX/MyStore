<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} — E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/product-detail.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body>

    <!-- ===== HEADER ===== -->
    <div class="header-bar">
        <a href="{{ url('/') }}" class="header-brand">
            <div class="brand-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span>E Store ID</span>
        </a>
        <div class="header-icons">
            @auth
                <a href="{{ route('cart.index') }}" class="icon-btn cart-icon" title="Keranjang" id="cartBtn">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                    </svg>
                    <span class="cart-count" id="cartCount" style="display:none;">0</span>
                </a>
                <div class="user-menu-dropdown">
                    <button type="button" class="icon-btn user-menu-btn" id="userMenuBtn" title="Menu Akun">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="2" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
                        </svg>
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
                <a href="{{ route('login') }}" class="icon-btn" title="Login">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
            @endauth
        </div>
    </div>

    <!-- ===== BREADCRUMB ===== -->
    <div class="breadcrumb-bar">
        <a href="{{ route('dashboard') }}" class="breadcrumb-link">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Beranda
        </a>
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="breadcrumb-sep"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="breadcrumb-current">{{ $product->name }}</span>
    </div>

    @if(session('success'))
        <div class="flash-alert flash-success">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash-alert flash-error">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="pd-page">

        <!-- ===== HERO: TWO COLUMN ===== -->
        <div class="pd-hero">

            <!-- LEFT: Image Gallery -->
            <div class="pd-gallery">
                <div class="pd-img-wrap">
                    <span class="pd-sku-badge">SKU #{{ $product->id }}</span>
                    <img class="pd-main-img" id="pd-main-img"
                         src="{{ asset($product->image) }}"
                         alt="{{ $product->name }}">
                    <div class="pd-img-zoom-hint">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        Zoom
                    </div>
                </div>
            </div>

            <!-- RIGHT: Product Info -->
            <div class="pd-info">

                <!-- Social proof bar -->
                <div class="pd-social-row">
                    <span class="pd-badge pd-badge-fire">🔥 200+ terjual minggu ini</span>
                    <span class="pd-badge pd-badge-eye">👀 <span id="viewers-count">5</span> orang sedang melihat</span>
                </div>

                <!-- Title -->
                <h1 class="pd-title">{{ $product->name }}</h1>

                <!-- Rating row -->
                <div class="pd-rating-row">
                    <div class="pd-stars">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b" style="clip-path:inset(0 20% 0 0)"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <span class="pd-rating-score">4.8</span>
                    <span class="pd-rating-count">(1.200 ulasan)</span>
                    <span class="pd-divider-dot">·</span>
                    <span class="pd-sold-count">2.500+ terjual</span>
                </div>

                <!-- Price -->
                <div class="pd-price-wrap">
                    <span class="pd-price">Rp {{ number_format($product->price, 0, '', '.') }}</span>
                    <span class="pd-price-note">Harga sudah termasuk pajak</span>
                </div>

                <!-- Stock status -->
                <div class="pd-stock-wrap">
                    @if($product->stock > 10)
                        <div class="pd-stock pd-stock-ok">
                            <span class="pd-stock-dot"></span>
                            Ready Stock — {{ $product->stock }} unit tersedia
                        </div>
                    @elseif($product->stock > 0)
                        <div class="pd-stock pd-stock-low">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            Tersisa <strong>{{ $product->stock }} unit</strong> — Segera habis!
                        </div>
                        <div class="pd-urgency-bar">
                            <div class="pd-urgency-fill" style="width: {{ min(100, ($product->stock / 20) * 100) }}%"></div>
                        </div>
                    @else
                        <div class="pd-stock pd-stock-out">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Stok Habis
                        </div>
                    @endif
                </div>

                @if($product->isInStock())
                <!-- Quantity -->
                <div class="pd-qty-row" id="product-qty-container" data-max-qty="{{ min($product->stock, 100) }}">
                    <span class="pd-qty-label">Jumlah:</span>
                    <div class="pd-qty-selector">
                        <button type="button" id="qty-decrease" class="pd-qty-btn" data-action="decrease" aria-label="Kurangi">−</button>
                        <input type="number" id="product-detail-quantity" class="pd-qty-input" value="1" min="1" max="{{ min($product->stock, 100) }}" readonly>
                        <button type="button" id="qty-increase" class="pd-qty-btn" data-action="increase" aria-label="Tambah">+</button>
                    </div>
                    <span class="pd-qty-hint">Maks. {{ min($product->stock, 100) }}/transaksi</span>
                </div>
                @endif

                <!-- CTA Buttons -->
                <div class="pd-actions">
                    @auth
                        @if($product->isInStock())
                            <a href="{{ route('beli', ['id' => $product->id]) }}" id="btn-beli-sekarang" class="pd-btn-primary">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Beli Sekarang
                            </a>
                            <p class="pd-microcopy">⚡ Proses instan, tanpa ribet</p>
                            <button type="button" class="pd-btn-secondary" id="btn-tambah-keranjang">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5"/></svg>
                                Masukkan Keranjang
                            </button>
                        @else
                            <button type="button" class="pd-btn-disabled" disabled>
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Stok Habis
                            </button>
                        @endif
                    @else
                        @if($product->isInStock())
                            <a href="{{ route('login') }}" class="pd-btn-primary">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                Login untuk Membeli
                            </a>
                            <p class="pd-microcopy">⚡ Proses instan setelah login</p>
                        @else
                            <button type="button" class="pd-btn-disabled" disabled>Stok Habis</button>
                        @endif
                    @endauth
                </div>

                <!-- Trust Badges -->
                <div class="pd-trust-grid">
                    <div class="pd-trust-item">
                        <div class="pd-trust-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <p class="pd-trust-title">Pembayaran Aman</p>
                            <p class="pd-trust-sub">SSL & enkripsi penuh</p>
                        </div>
                    </div>
                    <div class="pd-trust-item">
                        <div class="pd-trust-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="pd-trust-title">Garansi 100%</p>
                            <p class="pd-trust-sub">Refund jika gagal</p>
                        </div>
                    </div>
                    <div class="pd-trust-item">
                        <div class="pd-trust-icon">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <p class="pd-trust-title">Aktivasi Instan</p>
                            <p class="pd-trust-sub">Otomatis setelah bayar</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ===== DESCRIPTION ===== -->
        <div class="pd-desc-section">
            <div class="pd-section-header">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Deskripsi Produk
            </div>
            <div class="pd-desc-content">{!! nl2br(e($product->description)) !!}</div>
        </div>

        <!-- ===== RELATED PRODUCTS ===== -->
        @if($relatedProducts->isNotEmpty())
        <div class="pd-related-section">
            <div class="pd-related-header">
                <h2 class="pd-related-title">Produk Lainnya</h2>
                <a href="{{ route('dashboard') }}" class="pd-related-see-all">
                    Lihat Semua
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="pd-related-grid">
                @foreach($relatedProducts as $related)
                <a href="{{ route('product.show', $related->id) }}" class="pd-related-card">
                    <div class="pd-related-img-wrap">
                        <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="pd-related-img">
                        @if(!$related->isInStock())
                        <div class="pd-related-soldout">Habis</div>
                        @endif
                    </div>
                    <div class="pd-related-info">
                        <p class="pd-related-name">{{ $related->name }}</p>
                        <p class="pd-related-price">Rp {{ number_format($related->price, 0, '', '.') }}</p>
                        <div class="pd-related-stock-row">
                            @if($related->stock > 10)
                                <span class="pd-related-stock-ok">Ready</span>
                            @elseif($related->stock > 0)
                                <span class="pd-related-stock-low">Sisa {{ $related->stock }}</span>
                            @else
                                <span class="pd-related-stock-out">Habis</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    <!-- ===== WHATSAPP ===== -->
    <a href="https://wa.me/6281234567890" class="wa-float" target="_blank" rel="noopener" title="Hubungi via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    <!-- ===== IMAGE ZOOM OVERLAY ===== -->
    <div class="pd-zoom-overlay" id="pdZoomOverlay">
        <button class="pd-zoom-close" id="pdZoomClose" aria-label="Tutup">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img class="pd-zoom-img" id="pdZoomImg" src="" alt="">
    </div>

    <script>
    (function() {
        var productId = {{ $product->id }};
        var productName = {!! json_encode($product->name) !!};
        var maxQty = {{ $product->isInStock() ? min($product->stock, 100) : 1 }};

        /* ---- Quantity ---- */
        function getQtyInput() { return document.getElementById('product-detail-quantity'); }
        function getQty() {
            var el = getQtyInput();
            return el ? Math.min(maxQty, Math.max(1, parseInt(el.value, 10) || 1)) : 1;
        }
        function setQty(val) {
            var el = getQtyInput();
            if (!el) return;
            val = Math.min(maxQty, Math.max(1, val));
            el.value = val;
            var dec = document.getElementById('qty-decrease');
            var inc = document.getElementById('qty-increase');
            if (dec) dec.disabled = (val <= 1);
            if (inc) inc.disabled = (val >= maxQty);
        }

        document.addEventListener('click', function(ev) {
            var btn = ev.target && ev.target.closest && ev.target.closest('.pd-qty-row button.pd-qty-btn[data-action]');
            if (!btn) return;
            ev.preventDefault();
            var action = btn.getAttribute('data-action');
            var v = parseInt((getQtyInput() || {}).value, 10) || 1;
            setQty(action === 'increase' ? v + 1 : v - 1);
        }, true);

        /* ---- Beli Sekarang ---- */
        var btnBeli = document.getElementById('btn-beli-sekarang');
        if (btnBeli) {
            btnBeli.addEventListener('click', function(e) {
                e.preventDefault();
                var href = btnBeli.getAttribute('href') || '';
                window.location.href = href + (href.indexOf('?') >= 0 ? '&' : '?') + 'quantity=' + getQty();
            });
        }

        /* ---- Add to Cart ---- */
        async function addToCart() {
            var btn = document.getElementById('btn-tambah-keranjang');
            if (btn) { btn.disabled = true; btn.textContent = 'Menambahkan...'; }
            var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
            try {
                var response = await fetch('/cart/add/' + productId, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ quantity: getQty() })
                });
                var result = await response.json();
                if (response.ok && result.success) {
                    updateCartCount();
                    showNotification(productName + ' berhasil ditambahkan ke keranjang!', 'success');
                } else {
                    showNotification(result.message || 'Gagal menambahkan ke keranjang', 'error');
                }
            } catch (err) {
                showNotification('Terjadi kesalahan koneksi', 'error');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5"/></svg> Masukkan Keranjang';
                }
            }
        }

        var btnCart = document.getElementById('btn-tambah-keranjang');
        if (btnCart) btnCart.addEventListener('click', addToCart);

        /* ---- Cart Count ---- */
        async function updateCartCount() {
            try {
                var r = await fetch('/cart/count');
                var res = await r.json();
                var el = document.getElementById('cartCount');
                if (el) { el.textContent = res.count; el.style.display = res.count > 0 ? 'flex' : 'none'; }
            } catch(e) {}
        }

        /* ---- Notification ---- */
        function showNotification(msg, type) {
            var old = document.querySelector('.pd-notification');
            if (old) old.remove();
            var n = document.createElement('div');
            n.className = 'pd-notification pd-notification-' + type;
            n.innerHTML = (type === 'success'
                ? '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                : '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            ) + ' ' + msg;
            document.body.appendChild(n);
            setTimeout(function() { n.classList.add('show'); }, 10);
            setTimeout(function() { n.classList.remove('show'); setTimeout(function() { n.remove(); }, 300); }, 3500);
        }

        /* ---- Image Zoom ---- */
        var mainImg = document.getElementById('pd-main-img');
        var overlay = document.getElementById('pdZoomOverlay');
        var zoomImg = document.getElementById('pdZoomImg');
        var closeBtn = document.getElementById('pdZoomClose');

        if (mainImg && overlay && zoomImg) {
            mainImg.addEventListener('click', function() {
                zoomImg.src = mainImg.src;
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            closeBtn && closeBtn.addEventListener('click', closeZoom);
            overlay.addEventListener('click', function(e) { if (e.target === overlay) closeZoom(); });
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeZoom(); });
        }
        function closeZoom() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        /* ---- User Menu ---- */
        var userMenuBtn = document.getElementById('userMenuBtn');
        var userMenu = document.getElementById('userMenu');
        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener('click', function(e) { e.stopPropagation(); userMenu.classList.toggle('open'); });
            document.addEventListener('click', function(e) { if (!userMenu.contains(e.target) && e.target !== userMenuBtn) userMenu.classList.remove('open'); });
        }

        /* ---- Viewers randomize ---- */
        var vEl = document.getElementById('viewers-count');
        if (vEl) {
            var count = Math.floor(Math.random() * 8) + 3;
            vEl.textContent = count;
            setInterval(function() {
                var delta = Math.random() > 0.5 ? 1 : -1;
                count = Math.max(2, Math.min(15, count + delta));
                vEl.textContent = count;
            }, 8000);
        }

        /* ---- Init ---- */
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            setQty(1);
        });
        if (document.readyState !== 'loading') { updateCartCount(); setQty(1); }

    })();
    </script>

</body>
</html>
