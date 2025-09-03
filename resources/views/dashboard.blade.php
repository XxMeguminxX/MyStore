<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>E Store ID</title>
    <title>E Store ID</title>
    <style>
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body class="body-background-3d">
    <div class="header-bar">
        {{-- Kolom Pencarian Baru, dipindahkan ke dalam header-bar --}}
        <div class="search-container">
            <input type="text" id="productSearch" onkeyup="filterProducts()" placeholder="Cari nama produk..."
                class="search-input">
        </div>

        <div class="header-icons">
            @auth
                {{-- User sudah login --}}
                <a href="{{ route('cart.index') }}" class="icon-btn cart-icon" title="Keranjang" id="cartBtn">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                    </svg>
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <a href="{{ route('transaction.history') }}" class="icon-btn" title="Histori Transaksi">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </a>
                <a href="{{ url('/profile') }}" class="icon-btn" title="Profil">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="8" r="4" stroke-width="2" />
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
                    </svg>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="icon-btn logout-btn" title="Logout">
                        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            @else
                {{-- User belum login --}}
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
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
  @endif

  {{-- Debug Info - Remove in production --}}
  @auth
    <div style="background: #e8f5e8; padding: 5px 10px; margin: 10px 0; border-radius: 4px; font-size: 12px; color: #2d5a2d;">
        ✅ Debug: Anda sudah login sebagai {{ Auth::user()->name ?? 'N/A' }} (ID: {{ Auth::id() }})
    </div>
  @else
    <div style="background: #ffe8e8; padding: 5px 10px; margin: 10px 0; border-radius: 4px; font-size: 12px; color: #8b2d2d;">
        ❌ Debug: Anda belum login
    </div>
  @endauth
  
  <h1>Produk Digital Saya</h1>
  <div id="noResults" class="no-results-message">
    Produk Tidak Ditemukan.
  </div>

  <!-- Filter Dropdown - Moved above product-section -->
  <div class="filter-container">
    <div class="filter-dropdown">
      <button type="button" class="filter-btn" id="filterBtn">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        <span class="filter-text" id="filterText">Urutkan</span>
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="filter-arrow">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="filter-options" id="filterOptions">
        <a href="{{ url()->current() }}?sort=newest" class="filter-option {{ $sortBy == 'newest' ? 'active' : '' }}" data-sort="newest">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Terbaru
        </a>
        <a href="{{ url()->current() }}?sort=price_high" class="filter-option {{ $sortBy == 'price_high' ? 'active' : '' }}" data-sort="price_high">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
          </svg>
          Harga Tertinggi
        </a>
        <a href="{{ url()->current() }}?sort=price_low" class="filter-option {{ $sortBy == 'price_low' ? 'active' : '' }}" data-sort="price_low">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
          </svg>
          Harga Terendah
        </a>
        <a href="{{ url()->current() }}?sort=stock_high" class="filter-option {{ $sortBy == 'stock_high' ? 'active' : '' }}" data-sort="stock_high">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          Stok Terbanyak
        </a>
        <a href="{{ url()->current() }}?sort=stock_low" class="filter-option {{ $sortBy == 'stock_low' ? 'active' : '' }}" data-sort="stock_low">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-5.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
          </svg>
          Stok Tersedikit
        </a>
        <a href="{{ url()->current() }}?sort=bestseller" class="filter-option {{ $sortBy == 'bestseller' ? 'active' : '' }}" data-sort="bestseller">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
          </svg>
          Terlaris
        </a>
      </div>
    </div>
  </div>

  <div class="product-section">
    <div class="background-3d"></div>

    <div class="product-grid">
        @foreach ($products as $data)
        <div class="product-card">
            <div class="product-id">ID: {{ $data->id }}</div>
            <img class="product-img" src="{{ $data->image }}" alt="Produk Digital">
            <div class="product-title">{{ $data->name }}</div>
            <div class="product-desc">
                <span class="desc-short">{{ substr($data->description,0,80) }}</span>
                <span class="desc-full" style="display:none;">{!! nl2br(e($data->description)) !!}</span>
                <button class="btn-desc-toggle" onclick="openDescModal(this)">Lihat Selengkapnya</button>
            </div>
            <div class="product-stock">
                <span class="stock-status {{ $data->getStockStatusColor() }}"
                      style="
                        @if($data->stock > 10)
                          background-color: #dcfce7; color: #166534; border: 2px solid #16a34a;
                        @elseif($data->stock > 0)
                          background-color: #fef3c7; color: #92400e; border: 2px solid #ca8a04;
                        @else
                          background-color: #fee2e2; color: #991b1b; border: 2px solid #dc2626;
                        @endif
                        font-weight: 600; padding: 4px 12px; border-radius: 16px; display: inline-block; font-size: 0.9em;">
                    {{ $data->getStockStatus() }}
                </span>
                @if($data->stock > 0)
                    <span class="stock-count" style="font-size: 0.85em; color: #666; font-weight: 500; margin-top: 4px; display: block;">
                        Stok: {{ $data->stock }}
                    </span>
                @endif
            </div>
            <div class="product-price">Rp {{ number_format($data->price,0,'','.') }}</div>
            <div class="product-actions">
                @auth
                    {{-- User sudah login --}}
                    @if($data->isInStock())
                        <a href="{{ route('beli', ['id' => $data->id]) }}" class="btn btn-beli" id="beli-produk-{{ $data->id }}">Beli</a>
                        <button type="button" class="btn btn-cart" onclick="addToCart({{ $data->id }}, '{{ $data->name }}')" id="cart-produk-{{ $data->id }}" title="Tambah ke Keranjang">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                            </svg>
                        </button>
                    @else
                        <button type="button" class="btn btn-out-of-stock" disabled id="beli-produk-{{ $data->id }}">
                            Stok Habis
                        </button>
                    @endif
                @else
                    {{-- User belum login --}}
                    @if($data->isInStock())
                        <button type="button" class="btn btn-login-required" onclick="showLoginRequiredModal()" id="beli-produk-{{ $data->id }}">
                            Login untuk Beli
                        </button>
                    @else
                        <button type="button" class="btn btn-out-of-stock" disabled id="beli-produk-{{ $data->id }}">
                            Stok Habis
                        </button>
                    @endif
                @endauth
            </div>
        </div>
        @endforeach
    </div>
  </div>

    <div id="desc-modal" class="desc-modal" style="display:none;">
        <div class="desc-modal-content">
            <span class="desc-modal-close" onclick="closeDescModal()">&times;</span>
            <div id="desc-modal-title"></div>
            <div id="desc-modal-body"></div>
        </div>
    </div>

    <!-- Modal Login Required -->
    <div id="loginRequiredModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Login Diperlukan</h3>
                <span class="modal-close" onclick="closeLoginRequiredModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="login-required-content">
                    <div class="login-icon">
                        <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h4>Anda harus login terlebih dahulu</h4>
                    <p>Untuk melakukan pembelian, Anda perlu memiliki akun. Silakan login atau daftar akun baru.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeLoginRequiredModal()">Nanti Saja</button>
                <a href="{{ route('login') }}" class="btn-confirm">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Daftar</a>
            </div>
        </div>
    </div>
    <script>
    function openDescModal(btn) {
        const card = btn.closest('.product-card');
        const title = card.querySelector('.product-title').textContent;
        const fullDesc = card.querySelector('.desc-full').innerHTML;

        document.getElementById('desc-modal-title').textContent = title;
        document.getElementById('desc-modal-body').innerHTML = fullDesc;
        document.getElementById('desc-modal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; /* Mencegah scroll saat modal terbuka */
    }

    function closeDescModal() {
        document.getElementById('desc-modal').style.display = 'none';
        document.body.style.overflow = ''; /* Mengembalikan scroll */
    }

    // Fungsi untuk menampilkan modal login required
    function showLoginRequiredModal() {
        document.getElementById('loginRequiredModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Fungsi untuk menutup modal login required
    function closeLoginRequiredModal() {
        document.getElementById('loginRequiredModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Menutup modal jika klik di luar konten modal
    window.onclick = function(event) {
        const descModal = document.getElementById('desc-modal');
        const loginRequiredModal = document.getElementById('loginRequiredModal');
        
        if (event.target == descModal) {
            descModal.style.display = "none";
            document.body.style.overflow = '';
        }
        
        if (event.target == loginRequiredModal) {
            closeLoginRequiredModal();
        }
    };

    // Fungsi Pencarian Produk
    function filterProducts() {
        // Dapatkan nilai input pencarian, ubah ke huruf kecil untuk pencarian case-insensitive
        const searchInput = document.getElementById('productSearch').value.toLowerCase();
        const productCards = document.querySelectorAll('.product-card');
        let visibleProductCount = 0; // Tambahkan penghitung produk yang terlihat
        
        productCards.forEach(card => {
          const productTitle = card.querySelector('.product-title').textContent.toLowerCase();

          if (productTitle.includes(searchInput)) {
            card.style.display = ''; // Tampilkan elemen
            visibleProductCount++; // Tambah hitungan jika produk terlihat
          } else {
            card.style.display = 'none'; // Sembunyikan elemen
          }
        });

        // Dapatkan elemen pesan "Tidak Ditemukan"
        const noResultsMessage = document.getElementById('noResults');

        // Tampilkan atau sembunyikan pesan berdasarkan jumlah produk yang terlihat
        if (visibleProductCount === 0) {
          noResultsMessage.style.display = 'block'; // Tampilkan pesan
        } else {
          noResultsMessage.style.display = 'none'; // Sembunyikan pesan
        }
    }
    
    window.onload = function() {
      filterProducts();
    };

    // Fungsi untuk menambahkan produk ke keranjang
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
                body: JSON.stringify({
                    quantity: 1
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Update cart count
                updateCartCount();

                // Show success message
                showNotification(`${productName} berhasil ditambahkan ke keranjang!`, 'success');

                // Add animation to cart button
                animateCartButton();
            } else {
                showNotification(result.message || 'Gagal menambahkan ke keranjang', 'error');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        }
    }

    // Fungsi untuk update cart count di header
    async function updateCartCount() {
        try {
            const response = await fetch('/cart/count');
            const result = await response.json();

            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                cartCountElement.textContent = result.count;
                cartCountElement.style.display = result.count > 0 ? 'block' : 'none';
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'info') {
        // Remove existing notification
        const existingNotification = document.querySelector('.cart-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `cart-notification ${type}`;
        notification.textContent = message;

        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 20px',
            borderRadius: '8px',
            color: 'white',
            fontSize: '14px',
            fontWeight: '500',
            zIndex: '9999',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            opacity: '0',
            transform: 'translateY(-10px)',
            transition: 'all 0.3s ease'
        });

        // Set background color based on type
        if (type === 'success') {
            notification.style.backgroundColor = '#2a9d8f';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#dc2626';
        } else {
            notification.style.backgroundColor = '#6b7280';
        }

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 10);

        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-10px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Fungsi untuk animasi cart button
    function animateCartButton() {
        const cartBtn = document.getElementById('cartBtn');
        if (cartBtn) {
            cartBtn.style.transform = 'scale(1.2)';
            setTimeout(() => {
                cartBtn.style.transform = 'scale(1)';
            }, 200);
        }
    }

    // Fungsi untuk memperbarui quantity produk
    async function updateProductQuantity(productId, newQuantity) {
        // Ambil CSRF token dari meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/products/${productId}/update-quantity`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // Mengirim CSRF token untuk keamanan
                },
                body: JSON.stringify({
                    quantity: newQuantity
                })
            });

            const result = await response.json();

            if (response.ok) {
                console.log(result.message); // Tampilkan pesan sukses
                // Kamu bisa menambahkan logika lain di sini,
                // misalnya memperbarui tampilan quantity di halaman
            } else {
                console.error('Gagal memperbarui quantity:', result.message);
            }

        } catch (error) {
            console.error('Terjadi kesalahan:', error);
        }
    }

    // Fungsi Filter Dropdown
    function initFilterDropdown() {
        const filterBtn = document.getElementById('filterBtn');
        const filterDropdown = document.querySelector('.filter-dropdown');
        const filterOptions = document.getElementById('filterOptions');
        const filterText = document.getElementById('filterText');

        if (!filterBtn || !filterDropdown) return;

        // Toggle dropdown
        filterBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            filterDropdown.classList.toggle('open');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!filterDropdown.contains(e.target)) {
                filterDropdown.classList.remove('open');
            }
        });

        // Update filter text based on active option
        const activeOption = filterOptions.querySelector('.filter-option.active');
        if (activeOption) {
            const sortType = activeOption.getAttribute('data-sort');
            updateFilterText(sortType);
        }
    }

    // Fungsi untuk mengupdate teks filter
    function updateFilterText(sortType) {
        const filterText = document.getElementById('filterText');
        const sortLabels = {
            'newest': 'Terbaru',
            'price_high': 'Harga Tertinggi',
            'price_low': 'Harga Terendah',
            'stock_high': 'Stok Terbanyak',
            'stock_low': 'Stok Tersedikit',
            'bestseller': 'Terlaris'
        };

        if (filterText && sortLabels[sortType]) {
            filterText.textContent = sortLabels[sortType];
        }
    }

    // Inisialisasi filter dropdown saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        initFilterDropdown();
        updateCartCount(); // Update cart count saat halaman dimuat
    });

    </script>
</body>

</html>
</html>