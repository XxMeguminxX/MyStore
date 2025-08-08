<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Program Donasi</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body class="body-background-3d">
    <div class="header-bar">
        <div class="search-container">
            <input type="text" id="donasiSearch" onkeyup="filterDonasi()" placeholder="Cari program donasi..."
                class="search-input">
        </div>

        <div class="header-icons">
            <a href="{{ route('dashboard') }}" class="icon-btn" title="Dashboard">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>
            @auth
                <a href="{{ route('transaction.history') }}" class="icon-btn" title="Histori Transaksi">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </a>
                <a href="{{ url('/profile') }}" class="icon-btn" title="Profil">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="8" r="4" stroke-width="2" />
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
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

    <h1>Donate me</h1>

    <div id="noDonasiResults" class="no-results-message">Program Donasi Tidak Ditemukan.</div>

    <div class="product-section">
        <div class="background-3d"></div>
        <div class="product-grid">
            @foreach ($donasis as $item)
                <div class="product-card">
                    <div class="product-id">ID: {{ $item->id }}</div>
                    @if($item->image)
                        <img class="product-img" src="{{ asset($item->image) }}" alt="{{ $item->title }}">
                    @endif
                    <div class="product-title">{{ $item->title }}</div>
                    <div class="product-desc">
                        <span class="desc-short">{{ \Illuminate\Support\Str::limit($item->description, 80) }}</span>
                        <span class="desc-full" style="display:none;">{!! nl2br(e($item->description)) !!}</span>
                        <!-- <button class="btn-desc-toggle" onclick="openDescModal(this)">Lihat Selengkapnya</button> -->
                    </div>
                    <div class="product-price">Harga: Rp {{ number_format($item->amount,0,'','.') }}</div>
                    <div class="product-actions">
                        @auth
                        <a href="{{ route('donasi.beli', $item->id) }}" class="btn btn-beli">Bayar</a>
                        @else
                        <button type="button" class="btn btn-login-required" onclick="showLoginRequiredModal()">Login untuk Bayar</button>
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
                    <p>Untuk melakukan pembayaran donasi, silakan login atau daftar akun baru. Atau lanjut lihat-lihat website dulu.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeLoginRequiredModal()">Lihat-lihat dulu</button>
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
        document.body.style.overflow = 'hidden';
    }
    function closeDescModal() {
        document.getElementById('desc-modal').style.display = 'none';
        document.body.style.overflow = '';
    }
    function filterDonasi() {
        const searchInput = document.getElementById('donasiSearch').value.toLowerCase();
        const cards = document.querySelectorAll('.product-card');
        let visible = 0;
        cards.forEach(card => {
            const title = card.querySelector('.product-title').textContent.toLowerCase();
            if (title.includes(searchInput)) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });
        const noResults = document.getElementById('noDonasiResults');
        noResults.style.display = visible === 0 ? 'block' : 'none';
    }
    window.onload = filterDonasi;

    // Modal Login Required
    function showLoginRequiredModal() {
        document.getElementById('loginRequiredModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    function closeLoginRequiredModal() {
        document.getElementById('loginRequiredModal').style.display = 'none';
        document.body.style.overflow = '';
    }
    // Tutup modal saat klik di luar konten
    window.addEventListener('click', function(event) {
        const loginRequiredModal = document.getElementById('loginRequiredModal');
        if (event.target === loginRequiredModal) {
            closeLoginRequiredModal();
        }
    });
    </script>
</body>

</html>


