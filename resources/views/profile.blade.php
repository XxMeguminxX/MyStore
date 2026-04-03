<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun — E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-history.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <style>
        /* ===== Tab Content System ===== */
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* ===== Sidebar Tab Buttons ===== */
        .tab-btn {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 42px;
            padding: 9px 12px;
            border-radius: 10px;
            border: 1.5px solid transparent;
            background: transparent;
            color: var(--text-dark);
            font-size: 0.92rem;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s ease;
            font-family: inherit;
            box-sizing: border-box;
            gap: 8px;
        }

        .tab-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary-soft);
            color: var(--primary-color);
        }

        .tab-btn.active {
            background: var(--primary-light);
            border-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 700;
        }

        .tab-btn svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            stroke: currentColor;
        }

        /* ===== Search input in card header ===== */
        .card-search-input {
            padding: 9px 14px;
            border: 1.5px solid var(--border-light);
            border-radius: 10px;
            font-size: 0.88em;
            font-family: inherit;
            color: var(--text-dark);
            background: var(--bg-main);
            outline: none;
            transition: all 0.25s ease;
            max-width: 280px;
            width: 100%;
        }

        .card-search-input:focus {
            border-color: var(--primary-soft);
            background: var(--bg-card);
            box-shadow: 0 0 0 3px rgba(167, 199, 231, 0.3);
        }

        .card-search-input::placeholder { color: #adb5bd; }

        /* ===== Empty state ===== */
        .empty-state {
            text-align: center;
            padding: 56px 20px;
            color: var(--text-light);
        }

        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state-icon svg {
            width: 28px;
            height: 28px;
            stroke: var(--primary-color);
        }

        .empty-state h3 {
            font-size: 1.1em;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-size: 0.9em;
            line-height: 1.6;
        }

        /* ===== Transaction tab overrides for inline use ===== */
        .transaction-section {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .transaction-background { display: none; }
        .transaction-content { padding: 0; }

        .transaction-card {
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(68, 129, 174, 0.05);
            border: 1.5px solid var(--border-light);
            border-left: 4px solid var(--primary-soft);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
            background: var(--bg-card);
        }

        .transaction-card:hover {
            box-shadow: 0 5px 18px rgba(68, 129, 174, 0.1);
            transform: translateY(-2px);
        }

        .no-results-transactions {
            text-align: center;
            padding: 32px 20px;
            color: var(--text-light);
            display: none;
        }

        /* ===== Profile fields ===== */
        .profile-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        @media (max-width: 640px) {
            .profile-field-row { grid-template-columns: 1fr; }
            .card-search-input { max-width: 100%; }
        }
    </style>
</head>

<body class="page-account">

    {{-- Header --}}
    <div class="header-bar">
        <a href="{{ url('/dashboard') }}" class="btn-kembali" aria-label="Kembali ke Dashboard">
            ← Dashboard
        </a>
        <div class="header-title">Akun Saya</div>
        <div class="header-actions">
            <form method="POST" action="{{ route('logout') }}" class="header-logout">
                @csrf
                <button type="submit" class="header-link header-link-danger">Logout</button>
            </form>
        </div>
    </div>

    <div class="profile-container">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success" id="successAlert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="profile-layout">

            {{-- SIDEBAR (Left Column) --}}
            <aside class="profile-sidebar" aria-label="Menu Akun">
                <div class="sidebar-card">

                    {{-- User avatar / info singkat --}}
                    <div style="text-align:center; padding: 12px 0 18px; border-bottom: 1.5px solid var(--border-light); margin-bottom: 14px;">
                        <div style="width:56px; height:56px; border-radius:50%; background: linear-gradient(135deg,#A7C7E7,#C3B1E1); display:flex; align-items:center; justify-content:center; margin: 0 auto 10px; font-size:1.4em; font-weight:700; color:#fff;">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div style="font-weight:700; font-size:0.95em; color:var(--text-dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->name ?? 'Pengguna' }}</div>
                        <div style="font-size:0.78em; color:var(--text-light); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</div>
                    </div>

                    <div class="sidebar-title">Menu</div>
                    <nav class="sidebar-nav">
                        <div class="sidebar-group">
                            <div class="sidebar-group-title">Akun Saya</div>
                            <div class="sidebar-sublinks">
                                <button class="tab-btn active" data-tab="profile" onclick="switchTab('profile', this)">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <circle cx="12" cy="8" r="4" stroke-width="2"/>
                                        <path stroke-width="2" stroke-linecap="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/>
                                    </svg>
                                    Profil
                                </button>
                                <button class="tab-btn" data-tab="transactions" onclick="switchTab('transactions', this)">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Histori Transaksi
                                </button>
                            </div>
                        </div>

                        <a class="sidebar-link" href="{{ url('/dashboard') }}" style="margin-top:6px;">
                            ← Kembali ke Dashboard
                        </a>
                    </nav>

                    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
                        @csrf
                        <button type="submit" class="sidebar-link sidebar-link-danger">Logout</button>
                    </form>
                </div>
            </aside>

            {{-- MAIN CONTENT (Right Column) --}}
            <main class="profile-main">

                {{-- ==========================================
                     TAB 1: PROFIL
                     ========================================== --}}
                <div id="tab-profile" class="tab-content active">
                    <div class="profile-content">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Data Profil</div>
                                <div class="card-subtitle">Lengkapi data agar siap untuk checkout.</div>
                            </div>
                            <button type="button" class="btn-update" onclick="showEditProfileModal()">
                                Edit Profil
                            </button>
                        </div>

                        @if(empty($user->name) || empty($user->phone))
                            <div class="info-box" role="note" style="margin-bottom:16px;">
                                <p><strong>Perhatian:</strong> Lengkapi semua data profil agar dapat melakukan checkout.</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" class="profile-form" id="profileForm">
                            @csrf
                            <div class="profile-info">

                                <div class="profile-field-row">
                                    <div class="profile-item">
                                        <span class="profile-label">ID User</span>
                                        <div class="profile-value">{{ $user->id }}</div>
                                    </div>
                                    <div class="profile-item">
                                        <span class="profile-label">Tanggal Daftar</span>
                                        <div class="profile-value">{{ $user->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>

                                <div class="profile-item">
                                    <label for="name" class="profile-label">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" value="{{ $user->name }}"
                                           class="profile-input {{ empty($user->name) ? 'field-empty' : '' }}" readonly>
                                    @if(empty($user->name))
                                        <small class="field-warning">Nama harus diisi untuk checkout</small>
                                    @endif
                                </div>

                                <div class="profile-item">
                                    <label for="email" class="profile-label">Email</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                                           class="profile-input {{ empty($user->email) ? 'field-empty' : '' }}" readonly>
                                    @if(empty($user->email))
                                        <small class="field-warning">Email harus diisi untuk checkout</small>
                                    @endif
                                </div>

                                <div class="profile-item">
                                    <label for="phone" class="profile-label">Nomor HP</label>
                                    <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}"
                                           class="profile-input {{ empty($user->phone) ? 'field-empty' : '' }}" readonly>
                                    @if(empty($user->phone))
                                        <small class="field-warning">No HP harus diisi untuk checkout</small>
                                    @endif
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- ==========================================
                     TAB 2: HISTORI TRANSAKSI
                     ========================================== --}}
                <div id="tab-transactions" class="tab-content">
                    <div class="profile-content">
                        <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
                            <div>
                                <div class="card-title">Histori Transaksi</div>
                                <div class="card-subtitle">{{ $transactions->count() }} transaksi ditemukan.</div>
                            </div>
                            @if($transactions->count() > 0)
                                <input type="text" id="transactionSearch" onkeyup="filterTransactions()"
                                       placeholder="Cari transaksi..." class="card-search-input">
                            @endif
                        </div>

                        @if($transactions->count() > 0)
                            <div id="transactionsList">
                                @foreach($transactions as $transaction)
                                    <div class="transaction-card" data-transaction-id="{{ $transaction->id }}">
                                        <div class="transaction-header">
                                            <div class="transaction-id">
                                                #{{ $transaction->id }}
                                                @if($transaction->merchant_ref)
                                                    <span style="color:var(--text-light); font-weight:400; font-size:0.9em;">· {{ $transaction->merchant_ref }}</span>
                                                @endif
                                            </div>
                                            <div class="transaction-status status-{{ strtolower($transaction->status ?? 'unpaid') }}">
                                                {{ ucfirst(strtolower($transaction->status ?? 'Unpaid')) }}
                                            </div>
                                        </div>

                                        <div class="transaction-details">
                                            <div class="detail-item">
                                                <div class="detail-label">{{ $transaction->isDonation() ? 'Donasi' : 'Produk' }}</div>
                                                <div class="detail-value">{{ $transaction->getProductName() }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Jumlah</div>
                                                <div class="detail-value">{{ $transaction->quantity ?? 1 }} item</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Total Bayar</div>
                                                <div class="detail-value" style="color:var(--primary-color); font-weight:700;">
                                                    Rp {{ number_format($transaction->amount ?? 0, 0, '', '.') }}
                                                </div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Metode Pembayaran</div>
                                                <div class="detail-value">{{ $transaction->payment_method ?? 'N/A' }}</div>
                                            </div>
                                        </div>

                                        <div class="transaction-date">
                                            {{ $transaction->created_at ? $transaction->created_at->format('d M Y, H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="no-results-transactions" id="noTransactionResults">
                                <p>Tidak ada transaksi yang sesuai pencarian.</p>
                            </div>

                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h3>Belum ada transaksi</h3>
                                <p>Anda belum memiliki riwayat transaksi.<br>Mulai berbelanja sekarang!</p>
                            </div>
                        @endif
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{-- Modal Edit Profil --}}
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profil</h3>
                <button class="modal-close" onclick="closeEditProfileModal()" type="button">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" class="edit-profile-form">
                    <div class="form-group">
                        <label for="editName" class="form-label">Nama Lengkap</label>
                        <input type="text" id="editName" name="edit_name" value="{{ $user->name }}" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" id="editEmail" name="edit_email" value="{{ $user->email }}" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhone" class="form-label">Nomor HP</label>
                        <input type="tel" id="editPhone" name="edit_phone" value="{{ $user->phone ?? '' }}" class="form-input" placeholder="Contoh: 08123456789">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditProfileModal()">Batal</button>
                <button type="button" class="btn-confirm" onclick="saveProfileChanges()">Simpan</button>
            </div>
        </div>
    </div>

    <script>
        // ============================================================
        // TAB SWITCHING
        // ============================================================
        function switchTab(tabName, clickedBtn) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.remove('active');
            });
            // Deactivate all tab buttons
            document.querySelectorAll('.tab-btn').forEach(function(btn) {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');

            // Activate clicked button
            if (clickedBtn) clickedBtn.classList.add('active');

            // Update URL without page reload
            var newUrl = window.location.pathname + '?tab=' + tabName;
            history.pushState({ tab: tabName }, '', newUrl);
        }

        // Read URL param on load and activate the right tab
        document.addEventListener('DOMContentLoaded', function() {
            var params = new URLSearchParams(window.location.search);
            var tab = params.get('tab');
            if (tab && document.getElementById('tab-' + tab)) {
                var btn = document.querySelector('[data-tab="' + tab + '"]');
                switchTab(tab, btn);
            }

            // Auto-hide success alert
            var successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.classList.add('fade-out');
                    setTimeout(function() { successAlert.remove(); }, 400);
                }, 3000);
            }
        });

        // ============================================================
        // TRANSACTION SEARCH
        // ============================================================
        function filterTransactions() {
            var query = document.getElementById('transactionSearch').value.toLowerCase();
            var cards = document.querySelectorAll('#transactionsList .transaction-card');
            var visibleCount = 0;

            cards.forEach(function(card) {
                var match = card.textContent.toLowerCase().includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            var noResults = document.getElementById('noTransactionResults');
            if (noResults) {
                noResults.style.display = (visibleCount === 0 && query !== '') ? 'block' : 'none';
            }
        }

        // ============================================================
        // PROFILE MODAL
        // ============================================================
        function showEditProfileModal() {
            document.getElementById('editName').value = '{{ addslashes($user->name) }}';
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
            var name  = document.getElementById('editName').value.trim();
            var email = document.getElementById('editEmail').value.trim();
            var phone = document.getElementById('editPhone').value.trim();

            if (!name) {
                alert('Nama harus diisi.');
                document.getElementById('editName').focus();
                return;
            }
            if (!email) {
                alert('Email harus diisi.');
                document.getElementById('editEmail').focus();
                return;
            }
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Format email tidak valid.');
                document.getElementById('editEmail').focus();
                return;
            }

            // Sync ke field readonly
            document.getElementById('name').value  = name;
            document.getElementById('email').value = email;
            document.getElementById('phone').value = phone;

            // Submit form
            document.getElementById('profileForm').submit();
        }

        window.onclick = function(event) {
            var modal = document.getElementById('editProfileModal');
            if (event.target === modal) closeEditProfileModal();
        };
    </script>

</body>
</html>
