<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun — E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-history.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body class="page-account">

    <!-- ===== HEADER ===== -->
    <header class="header-bar">
        <a href="{{ url('/') }}" class="header-brand">
            <div class="brand-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span>E Store ID</span>
        </a>

        <div class="header-center">
            <a href="{{ url('/dashboard') }}" class="btn-back">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Toko
            </a>
            <span class="header-page-title">Akun Saya</span>
        </div>

        <div class="header-actions">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout-header">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <div class="profile-container">

        @if(session('success'))
            <div class="alert alert-success" id="successAlert">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="profile-layout">

            <!-- ===== SIDEBAR ===== -->
            <aside class="profile-sidebar">
                <div class="sidebar-card">

                    <!-- Avatar & user info -->
                    <div class="sidebar-user">
                        <div class="sidebar-avatar">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="sidebar-user-info">
                            <div class="sidebar-user-name">{{ $user->name ?? 'Pengguna' }}</div>
                            <div class="sidebar-user-email">{{ $user->email }}</div>
                            <span class="sidebar-user-badge">
                                <span class="badge-dot"></span>
                                Member Aktif
                            </span>
                        </div>
                    </div>

                    <!-- Nav -->
                    <div class="sidebar-nav-label">Menu Akun</div>
                    <nav class="sidebar-nav">
                        <button class="tab-btn active" data-tab="profile" onclick="switchTab('profile', this)">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="12" cy="8" r="4" stroke-width="2"/>
                                <path stroke-width="2" stroke-linecap="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/>
                            </svg>
                            Data Profil
                        </button>
                        <button class="tab-btn" data-tab="transactions" onclick="switchTab('transactions', this)">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Histori Transaksi
                            @if($transactions->count() > 0)
                                <span class="tab-count">{{ $transactions->count() }}</span>
                            @endif
                        </button>

                        <div class="sidebar-divider"></div>

                        <a class="sidebar-link-item" href="{{ url('/dashboard') }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Kembali ke Toko
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link-item sidebar-logout-btn">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <!-- ===== MAIN CONTENT ===== -->
            <main class="profile-main">

                <!-- ===== TAB: PROFIL ===== -->
                <div id="tab-profile" class="tab-content active">

                    <!-- Profile hero banner -->
                    <div class="profile-hero-card">
                        <div class="profile-hero-orb"></div>
                        <div class="profile-hero-left">
                            <div class="profile-hero-avatar">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="profile-hero-name">{{ $user->name ?? 'Pengguna' }}</div>
                                <div class="profile-hero-email">{{ $user->email }}</div>
                                <div class="profile-hero-since">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Member sejak {{ $user->created_at->format('M Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="profile-hero-stats">
                            <div class="profile-stat">
                                <span class="profile-stat-number">{{ $transactions->count() }}</span>
                                <span class="profile-stat-label">Transaksi</span>
                            </div>
                            <div class="profile-stat-divider"></div>
                            <div class="profile-stat">
                                <span class="profile-stat-number">
                                    {{ $transactions->where('status', 'PAID')->count() }}
                                </span>
                                <span class="profile-stat-label">Berhasil</span>
                            </div>
                        </div>
                    </div>

                    <!-- Profile form card -->
                    <div class="profile-content">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Data Profil</div>
                                <div class="card-subtitle">Lengkapi data untuk mempercepat proses checkout.</div>
                            </div>
                            <button type="button" class="btn-update" onclick="showEditProfileModal()">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profil
                            </button>
                        </div>

                        @if(empty($user->name) || empty($user->phone))
                            <div class="info-box">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p><strong>Profil belum lengkap.</strong> Lengkapi nama & nomor HP untuk dapat melakukan checkout.</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" class="profile-form" id="profileForm">
                            @csrf
                            <div class="profile-fields">

                                <div class="profile-field-row">
                                    <div class="profile-item">
                                        <span class="profile-label">ID Pengguna</span>
                                        <div class="profile-value">
                                            <span class="value-badge">#{{ $user->id }}</span>
                                        </div>
                                    </div>
                                    <div class="profile-item">
                                        <span class="profile-label">Tanggal Daftar</span>
                                        <div class="profile-value">{{ $user->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>

                                <div class="profile-item">
                                    <label for="name" class="profile-label">
                                        Nama Lengkap
                                        @if(empty($user->name))
                                            <span class="label-required">Wajib diisi</span>
                                        @endif
                                    </label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <circle cx="12" cy="8" r="4" stroke-width="2"/><path stroke-width="2" stroke-linecap="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/>
                                        </svg>
                                        <input type="text" id="name" name="name" value="{{ $user->name }}"
                                               class="profile-input {{ empty($user->name) ? 'field-empty' : '' }}" readonly>
                                    </div>
                                    @if(empty($user->name))
                                        <small class="field-warning">Diperlukan untuk checkout</small>
                                    @endif
                                </div>

                                <div class="profile-item">
                                    <label for="email" class="profile-label">Alamat Email</label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <input type="email" id="email" name="email" value="{{ $user->email }}"
                                               class="profile-input" readonly>
                                    </div>
                                </div>

                                <div class="profile-item">
                                    <label for="phone" class="profile-label">
                                        Nomor HP
                                        @if(empty($user->phone))
                                            <span class="label-required">Wajib diisi</span>
                                        @endif
                                    </label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}"
                                               class="profile-input {{ empty($user->phone) ? 'field-empty' : '' }}" readonly
                                               placeholder="Belum diisi">
                                    </div>
                                    @if(empty($user->phone))
                                        <small class="field-warning">Diperlukan untuk checkout</small>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <!-- ===== TAB: HISTORI TRANSAKSI ===== -->
                <div id="tab-transactions" class="tab-content">
                    <div class="profile-content">
                        <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
                            <div>
                                <div class="card-title">Histori Transaksi</div>
                                <div class="card-subtitle">
                                    {{ $transactions->count() }} transaksi tercatat
                                    @if($transactions->where('status', 'PAID')->count() > 0)
                                        · <span style="color:var(--success);">{{ $transactions->where('status', 'PAID')->count() }} berhasil</span>
                                    @endif
                                </div>
                            </div>
                            @if($transactions->count() > 0)
                                <div class="search-field-wrapper">
                                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="search-field-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input type="text" id="transactionSearch" onkeyup="filterTransactions()"
                                           placeholder="Cari transaksi..." class="card-search-input">
                                </div>
                            @endif
                        </div>

                        @if($transactions->count() > 0)
                            <div id="transactionsList">
                                @foreach($transactions as $transaction)
                                    <div class="transaction-card status-border-{{ strtolower($transaction->status ?? 'unpaid') }}" data-transaction-id="{{ $transaction->id }}">
                                        <div class="transaction-top">
                                            <div class="transaction-id-group">
                                                <span class="transaction-id">#{{ $transaction->id }}</span>
                                                @if($transaction->merchant_ref)
                                                    <span class="transaction-ref">{{ $transaction->merchant_ref }}</span>
                                                @endif
                                            </div>
                                            <span class="transaction-status-badge status-{{ strtolower($transaction->status ?? 'unpaid') }}">
                                                @if(strtolower($transaction->status ?? 'unpaid') === 'paid')
                                                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                @elseif(strtolower($transaction->status ?? 'unpaid') === 'expired')
                                                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                                @else
                                                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                                @endif
                                                {{ ucfirst(strtolower($transaction->status ?? 'Unpaid')) }}
                                            </span>
                                        </div>

                                        <div class="transaction-details">
                                            <div class="detail-col">
                                                <div class="detail-label">{{ $transaction->isDonation() ? 'Donasi' : 'Produk' }}</div>
                                                <div class="detail-value font-semibold">{{ $transaction->getProductName() }}</div>
                                            </div>
                                            <div class="detail-col">
                                                <div class="detail-label">Jumlah</div>
                                                <div class="detail-value">{{ $transaction->quantity ?? 1 }} item</div>
                                            </div>
                                            <div class="detail-col">
                                                <div class="detail-label">Total Bayar</div>
                                                <div class="detail-value detail-price">Rp {{ number_format($transaction->amount ?? 0, 0, '', '.') }}</div>
                                            </div>
                                            <div class="detail-col">
                                                <div class="detail-label">Metode</div>
                                                <div class="detail-value">{{ $transaction->payment_method ?? 'N/A' }}</div>
                                            </div>
                                        </div>

                                        <div class="transaction-footer">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $transaction->created_at ? $transaction->created_at->format('d M Y, H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="no-results-transactions" id="noTransactionResults">
                                Tidak ada transaksi yang cocok.
                            </div>

                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3>Belum ada transaksi</h3>
                                <p>Riwayat pembelian kamu akan muncul di sini.</p>
                                <a href="{{ url('/dashboard') }}" class="empty-state-cta">Mulai Belanja</a>
                            </div>
                        @endif
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- ===== MODAL EDIT PROFIL ===== -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h3>Edit Profil</h3>
                    <p class="modal-header-sub">Perubahan akan langsung tersimpan.</p>
                </div>
                <button class="modal-close" onclick="closeEditProfileModal()" type="button">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" class="edit-profile-form">
                    <div class="form-group">
                        <label for="editName" class="form-label">Nama Lengkap <span class="form-required">*</span></label>
                        <input type="text" id="editName" name="edit_name" value="{{ $user->name }}" class="form-input" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="form-group">
                        <label for="editEmail" class="form-label">Alamat Email <span class="form-required">*</span></label>
                        <input type="email" id="editEmail" name="edit_email" value="{{ $user->email }}" class="form-input" required placeholder="email@contoh.com">
                    </div>
                    <div class="form-group">
                        <label for="editPhone" class="form-label">Nomor HP</label>
                        <input type="tel" id="editPhone" name="edit_phone" value="{{ $user->phone ?? '' }}" class="form-input" placeholder="Contoh: 08123456789">
                        <small class="form-hint">Diperlukan untuk proses checkout & notifikasi</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditProfileModal()">Batal</button>
                <button type="button" class="btn-confirm" onclick="saveProfileChanges()">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <script>
        // ===== TAB SWITCHING =====
        function switchTab(tabName, clickedBtn) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('tab-' + tabName).classList.add('active');
            if (clickedBtn) clickedBtn.classList.add('active');
            history.pushState({ tab: tabName }, '', window.location.pathname + '?tab=' + tabName);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tab = new URLSearchParams(window.location.search).get('tab');
            if (tab && document.getElementById('tab-' + tab)) {
                switchTab(tab, document.querySelector('[data-tab="' + tab + '"]'));
            }
            const alert = document.getElementById('successAlert');
            if (alert) {
                setTimeout(() => { alert.style.opacity = '0'; setTimeout(() => alert.remove(), 400); }, 3000);
            }
        });

        // ===== TRANSACTION SEARCH =====
        function filterTransactions() {
            const q = document.getElementById('transactionSearch').value.toLowerCase();
            const cards = document.querySelectorAll('#transactionsList .transaction-card');
            let count = 0;
            cards.forEach(card => {
                const match = card.textContent.toLowerCase().includes(q);
                card.style.display = match ? '' : 'none';
                if (match) count++;
            });
            const noRes = document.getElementById('noTransactionResults');
            if (noRes) noRes.style.display = (count === 0 && q) ? 'block' : 'none';
        }

        // ===== PROFILE MODAL =====
        function showEditProfileModal() {
            document.getElementById('editName').value  = '{{ addslashes($user->name) }}';
            document.getElementById('editEmail').value = '{{ addslashes($user->email) }}';
            document.getElementById('editPhone').value = '{{ addslashes($user->phone ?? '') }}';
            document.getElementById('editProfileModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeEditProfileModal() {
            document.getElementById('editProfileModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function saveProfileChanges() {
            const name  = document.getElementById('editName').value.trim();
            const email = document.getElementById('editEmail').value.trim();
            const phone = document.getElementById('editPhone').value.trim();

            if (!name)  { alert('Nama harus diisi.'); document.getElementById('editName').focus(); return; }
            if (!email) { alert('Email harus diisi.'); document.getElementById('editEmail').focus(); return; }
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { alert('Format email tidak valid.'); document.getElementById('editEmail').focus(); return; }

            document.getElementById('name').value  = name;
            document.getElementById('email').value = email;
            document.getElementById('phone').value = phone;
            document.getElementById('profileForm').submit();
        }

        window.onclick = e => {
            if (e.target === document.getElementById('editProfileModal')) closeEditProfileModal();
        };
    </script>

</body>
</html>
