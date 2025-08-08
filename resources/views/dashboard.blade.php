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
  
  <h1>Produk Digital Saya</h1>
  <div id="noResults" class="no-results-message">
    Produk Tidak Ditemukan.
  </div>
  <div class="product-section">
    <div class="background-3d"></div>
    <div class="product-grid">
        @foreach ($products as $data)
        <div class="product-card">
            <div class="product-id">ID: {{ $data->id }}</div>
            <img class="product-img" src="{{ $data->image }}" alt="Ebook Belajar Laravel">
            <div class="product-title">{{ $data->name }}</div>
            <div class="product-desc">
                <span class="desc-short">{{ substr($data->description,0,80) }}</span>
                <span class="desc-full" style="display:none;">{!! nl2br(e($data->description)) !!}</span>
                <button class="btn-desc-toggle" onclick="openDescModal(this)">Lihat Selengkapnya</button>
            </div>
            <div class="product-quantity">Tersisa: {{ $data->quantity }}</div>
            <div class="product-price">Rp {{ number_format($data->price,0,'','.') }}</div>
            <div class="product-actions">
                @auth
                    {{-- User sudah login --}}
                    <a href="{{url('/beli/'.$data->id) }}" class="btn btn-beli" id="beli-produk-{{ $data->id }}">Beli</a>
                @else
                    {{-- User belum login --}}
                    <button type="button" class="btn btn-login-required" onclick="showLoginRequiredModal()" id="beli-produk-{{ $data->id }}">
                        Login untuk Beli
                    </button>
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

    </script>
</body>

</html>
</html>