<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Donate me</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body class="body-background-3d">
    <div class="header-bar">
        <div class="search-container">
            <input type="text" id="donasiSearch" onkeyup="filterDonasi()" placeholder="Donate me..."
                class="search-input">
        </div>

        <div class="header-icons">
            @auth
                <div class="user-menu-dropdown">
                    <button type="button" class="icon-btn user-menu-btn" id="userMenuBtnDonasi" title="Menu Akun">
                        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="8" r="4" stroke-width="2" />
                            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
                        </svg>
                    </button>
                    <div class="user-menu" id="userMenuDonasi">
                        <a href="{{ url('/profile') }}" class="user-menu-item">
                            Profil
                        </a>
                        <a href="{{ route('transaction.history') }}" class="user-menu-item">
                            Histori Transaksi
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-menu-item user-menu-logout">
                                Logout
                            </button>
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

    // User menu dropdown (donasi)
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuBtnDonasi = document.getElementById('userMenuBtnDonasi');
        const userMenuDonasi = document.getElementById('userMenuDonasi');

        if (userMenuBtnDonasi && userMenuDonasi) {
            userMenuBtnDonasi.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenuDonasi.classList.toggle('open');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuDonasi.contains(e.target) && e.target !== userMenuBtnDonasi) {
                    userMenuDonasi.classList.remove('open');
                }
            });
        }
    });
    </script>
</body>

</html>


