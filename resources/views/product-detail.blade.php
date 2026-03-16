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
            <div class="product-detail-top">
                <div class="product-detail-gallery">
                    <div class="product-detail-id">ID: {{ $product->id }}</div>
                    <img class="product-detail-img" src="{{ $product->image }}" alt="{{ $product->name }}">
                </div>
                <div class="product-detail-info">
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

                    @if($product->isInStock())
                    <div class="product-detail-qty" id="product-qty-container" data-max-qty="{{ min($product->stock, 100) }}">
                        <label class="qty-label">Kuantitas</label>
                        <div class="quantity-selector">
                            <button type="button" id="qty-decrease" class="qty-btn" aria-label="Kurangi" data-action="decrease">−</button>
                            <input type="number" id="product-detail-quantity" class="qty-input" value="1" min="1" max="{{ min($product->stock, 100) }}" readonly aria-label="Kuantitas">
                            <button type="button" id="qty-increase" class="qty-btn" aria-label="Tambah" data-action="increase">+</button>
                        </div>
                        <span class="qty-hint">Maks. {{ min($product->stock, 100) }} per transaksi</span>
                    </div>
                    @endif

                    <div class="product-detail-actions">
                        @auth
                            @if($product->isInStock())
                                <a href="{{ route('beli', ['id' => $product->id]) }}" id="btn-beli-sekarang" class="btn btn-beli btn-detail-beli">Beli Sekarang</a>
                                <button type="button" class="btn btn-cart btn-detail-cart" id="btn-tambah-keranjang" title="Tambah ke Keranjang">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                                    </svg>
                                    Masukkan Keranjang
                                </button>
                            @else
                                <button type="button" class="btn btn-out-of-stock" disabled>Stok Habis</button>
                            @endif
                        @else
                            @if($product->isInStock())
                                <a href="{{ route('login') }}" class="btn btn-beli btn-detail-beli">Login untuk Beli</a>
                                <button type="button" class="btn btn-login-required" onclick="window.location.href='{{ route('login') }}'">
                                    Login untuk Masukkan Keranjang
                                </button>
                            @else
                                <button type="button" class="btn btn-out-of-stock" disabled>Stok Habis</button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>

            <div class="product-detail-desc">
                <h3>Deskripsi</h3>
                <div class="desc-content">{!! nl2br(e($product->description)) !!}</div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var productId = {{ $product->id }};
            var productName = {!! json_encode($product->name) !!};
            var maxQty = {{ $product->isInStock() ? min($product->stock, 100) : 1 }};

            function getQtyInput() {
                return document.getElementById('product-detail-quantity');
            }

            function getQty() {
                var el = getQtyInput();
                return el ? Math.min(maxQty, Math.max(1, parseInt(el.value, 10) || 1)) : 1;
            }

            function setQty(val, container) {
                var el = getQtyInput();
                if (!el) return;
                var box = container || el.closest('.product-detail-qty');
                var max = maxQty;
                if (box && box.dataset.maxQty) max = parseInt(box.dataset.maxQty, 10) || maxQty;
                val = Math.min(max, Math.max(1, val));
                el.value = val;
                var dec = document.getElementById('qty-decrease');
                var inc = document.getElementById('qty-increase');
                if (dec) dec.disabled = (val <= 1);
                if (inc) inc.disabled = (val >= max);
            }

            /** Event delegation: tangani klik tombol +/- dari mana saja */
            document.addEventListener('click', function(ev) {
                var btn = ev.target && ev.target.closest && ev.target.closest('.product-detail-qty button.qty-btn[data-action]');
                if (!btn) return;
                ev.preventDefault();
                ev.stopPropagation();
                var action = btn.getAttribute('data-action');
                var input = document.getElementById('product-detail-quantity');
                if (!input) return;
                var container = btn.closest('.product-detail-qty');
                var max = maxQty;
                if (container && container.dataset.maxQty) max = parseInt(container.dataset.maxQty, 10) || maxQty;
                var v = parseInt(input.value, 10) || 1;
                if (action === 'increase') v = Math.min(max, v + 1);
                else if (action === 'decrease') v = Math.max(1, v - 1);
                setQty(v, container);
            }, true);

            async function addToCart() {
                var qty = getQty();
                var meta = document.querySelector('meta[name="csrf-token"]');
                var csrfToken = meta ? meta.getAttribute('content') : '';
                try {
                    var response = await fetch('/cart/add/' + productId, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ quantity: qty })
                    });
                    var result = await response.json();
                    if (response.ok && result.success) {
                        updateCartCount();
                        showNotification(productName + ' (' + qty + ' item) berhasil ditambahkan ke keranjang!', 'success');
                    } else {
                        showNotification(result.message || 'Gagal menambahkan ke keranjang', 'error');
                    }
                } catch (error) {
                    showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
                }
            }

            async function updateCartCount() {
                try {
                    var response = await fetch('/cart/count');
                    var result = await response.json();
                    var el = document.getElementById('cartCount');
                    if (el) {
                        el.textContent = result.count;
                        el.style.display = result.count > 0 ? 'block' : 'none';
                    }
                } catch (e) {}
            }

            function showNotification(message, type) {
                var existing = document.querySelector('.cart-notification');
                if (existing) existing.remove();
                var n = document.createElement('div');
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

            function initProductDetail() {
                updateCartCount();
                var qtyInput = getQtyInput();
                if (qtyInput) setQty(parseInt(qtyInput.value, 10) || 1);

                var btnBeli = document.getElementById('btn-beli-sekarang');
                if (btnBeli) {
                    btnBeli.addEventListener('click', function(e) {
                        e.preventDefault();
                        var q = getQty();
                        var href = btnBeli.getAttribute('href') || '';
                        window.location.href = href + (href.indexOf('?') >= 0 ? '&' : '?') + 'quantity=' + q;
                    });
                }

                var btnCart = document.getElementById('btn-tambah-keranjang');
                if (btnCart) {
                    btnCart.addEventListener('click', addToCart);
                }

                var userMenuBtn = document.getElementById('userMenuBtn');
                var userMenu = document.getElementById('userMenu');
                if (userMenuBtn && userMenu) {
                    userMenuBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        userMenu.classList.toggle('open');
                    });
                    document.addEventListener('click', function(e) {
                        if (!userMenu.contains(e.target) && e.target !== userMenuBtn) userMenu.classList.remove('open');
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initProductDetail);
            } else {
                initProductDetail();
            }
        })();
    </script>
</body>

</html>
