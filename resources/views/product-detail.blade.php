<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/product-detail.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body class="body-background-3d">
    <div class="header-bar">
        <div class="search-container" style="max-width: 200px;">
            <a href="{{ route('dashboard') }}" class="back-to-dashboard">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Beranda
            </a>
        </div>
        <div class="header-icons">
            @auth
                <a href="{{ route('cart.index') }}" class="icon-btn cart-icon" title="Keranjang" id="cartBtn">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                    </svg>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <div class="user-menu-dropdown">
                    <button type="button" class="icon-btn user-menu-btn" id="userMenuBtn" title="Menu Akun">
                        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="2" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
                        </svg>
                    </button>
                    <div class="user-menu" id="userMenu">
                        <a href="{{ url('/profile') }}" class="user-menu-item">Profil</a>
                        <a href="{{ route('transaction.history') }}" class="user-menu-item">Histori Transaksi</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-menu-item user-menu-logout">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="icon-btn" title="Login">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
                <a href="{{ route('register') }}" class="icon-btn" title="Register">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </a>
            @endauth
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="product-detail-page">
        <div class="product-detail-card">
            <div class="product-detail-id">ID: {{ $product->id }}</div>
            <img class="product-detail-img" src="{{ $product->image }}" alt="{{ $product->name }}">
            <h1 class="product-detail-title">{{ $product->name }}</h1>

            <div class="product-detail-stock">
                <span class="stock-status {{ $product->getStockStatusColor() }}"
                    style="
                        @if($product->stock > 10)
                            background-color: #dcfce7; color: #166534; border: 2px solid #16a34a;
                        @elseif($product->stock > 0)
                            background-color: #fef3c7; color: #92400e; border: 2px solid #ca8a04;
                        @else
                            background-color: #fee2e2; color: #991b1b; border: 2px solid #dc2626;
                        @endif
                        font-weight: 600; padding: 6px 14px; border-radius: 16px; font-size: 0.95em;">
                    {{ $product->getStockStatus() }}
                </span>
                @if($product->stock > 0)
                    <span class="stock-count">Stok: {{ $product->stock }}</span>
                @endif
            </div>

            <div class="product-detail-price">Rp {{ number_format($product->price, 0, '', '.') }}</div>

            <div class="product-detail-desc">
                <h3>Deskripsi</h3>
                <div class="desc-content">{!! nl2br(e($product->description)) !!}</div>
            </div>

            <div class="product-detail-actions">
                @auth
                    @if($product->isInStock())
                        <a href="{{ route('beli', ['id' => $product->id]) }}" class="btn btn-beli btn-detail-beli">Beli Sekarang</a>
                        <button type="button" class="btn btn-cart btn-detail-cart" onclick="addToCart({{ $product->id }}, {{ json_encode($product->name) }})" title="Tambah ke Keranjang">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                            </svg>
                            Tambah ke Keranjang
                        </button>
                    @else
                        <button type="button" class="btn btn-out-of-stock" disabled>Stok Habis</button>
                    @endif
                @else
                    @if($product->isInStock())
                        <a href="{{ route('login') }}" class="btn btn-beli btn-detail-beli">Login untuk Beli</a>
                        <button type="button" class="btn btn-login-required" onclick="window.location.href='{{ route('login') }}'">
                            Login untuk Tambah Keranjang
                        </button>
                    @else
                        <button type="button" class="btn btn-out-of-stock" disabled>Stok Habis</button>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <script>
        async function addToCart(productId, productName) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const response = await fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: 1 })
                });
                const result = await response.json();
                if (response.ok && result.success) {
                    updateCartCount();
                    showNotification(productName + ' berhasil ditambahkan ke keranjang!', 'success');
                } else {
                    showNotification(result.message || 'Gagal menambahkan ke keranjang', 'error');
                }
            } catch (error) {
                showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
            }
        }
        async function updateCartCount() {
            try {
                const response = await fetch('/cart/count');
                const result = await response.json();
                const el = document.getElementById('cartCount');
                if (el) {
                    el.textContent = result.count;
                    el.style.display = result.count > 0 ? 'block' : 'none';
                }
            } catch (e) {}
        }
        function showNotification(message, type) {
            const existing = document.querySelector('.cart-notification');
            if (existing) existing.remove();
            const n = document.createElement('div');
            n.className = 'cart-notification ' + type;
            n.textContent = message;
            Object.assign(n.style, {
                position: 'fixed', top: '20px', right: '20px', padding: '12px 20px', borderRadius: '8px',
                color: 'white', fontSize: '14px', fontWeight: '500', zIndex: '9999', boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                backgroundColor: type === 'success' ? '#2a9d8f' : type === 'error' ? '#dc2626' : '#6b7280'
            });
            document.body.appendChild(n);
            setTimeout(function() { n.remove(); }, 3000);
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userMenu = document.getElementById('userMenu');
            if (userMenuBtn && userMenu) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenu.classList.toggle('open');
                });
                document.addEventListener('click', function(e) {
                    if (!userMenu.contains(e.target) && e.target !== userMenuBtn) userMenu.classList.remove('open');
                });
            }
        });
    </script>
</body>

</html>
