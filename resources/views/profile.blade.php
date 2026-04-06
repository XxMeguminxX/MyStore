<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Profil — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <a href="{{ url('/') }}" class="nav-logo">
      <div class="nav-logo-icon">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
      <span class="nav-logo-name">E Store ID</span>
    </a>
    <div class="nav-links">
      <a href="{{ url('/') }}">Beranda</a>
      <a href="{{ url('/#produk') }}">Produk</a>
      <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
    </div>
    <div class="nav-actions">
      <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang">
        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/>
        </svg>
      </a>
      <div class="nav-user" id="navUser">
        <div class="nav-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <span class="nav-user-name">{{ Str::limit($user->name, 12) }}</span>
        <svg class="nav-user-caret" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
        <div class="nav-user-menu" id="navUserMenu">
          <a href="{{ url('/profile') }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/></svg>
            Profil
          </a>
          <a href="{{ route('transaction.history') }}">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Histori Transaksi
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>
    <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
  </div>
</nav>
<div class="nav-mobile" id="navMobile">
  <a href="{{ url('/') }}">Beranda</a>
  <a href="{{ url('/#produk') }}">Produk</a>
  <div class="nav-mobile-btns">
    <a href="{{ route('cart.index') }}" class="m-login">Keranjang</a>
    <a href="{{ url('/') }}" class="m-register">Belanja</a>
  </div>
</div>

<!-- MAIN -->
<div class="page-wrap">

  <div class="breadcrumb">
    <a href="{{ url('/') }}">Beranda</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-cur">Profil Saya</span>
  </div>

  <div class="profile-layout">

    <!-- SIDEBAR -->
    <aside class="profile-sidebar">
      <!-- User card -->
      <div class="sidebar-user-card">
        <div class="sidebar-user-hero"></div>
        <div class="sidebar-user-body">
          <div class="sidebar-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
          <div class="sidebar-name">{{ $user->name }}</div>
          <div class="sidebar-email">{{ $user->email }}</div>
          <div class="sidebar-joined">Bergabung sejak {{ $user->created_at->format('M Y') }}</div>
        </div>
      </div>

      <!-- Nav -->
      <div class="sidebar-nav-card">
        <button class="sidebar-nav-btn active" id="tabBtnProfile" onclick="switchTab('profile', this)">
          <svg class="sidebar-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/>
          </svg>
          Profil Saya
        </button>
        <button class="sidebar-nav-btn" id="tabBtnTransactions" onclick="switchTab('transactions', this)">
          <svg class="sidebar-nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          Histori Transaksi
          @if($transactions->count() > 0)
            <span style="margin-left:auto;background:#111;color:#fff;border-radius:999px;font-size:10px;font-weight:700;padding:1px 7px;">{{ $transactions->count() }}</span>
          @endif
        </button>
      </div>

      <!-- Logout -->
      <div class="sidebar-logout-card">
        <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">
          @csrf
          <button type="submit" class="sidebar-logout-btn">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
          </button>
        </form>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="profile-main">

      <!-- ===== TAB: PROFIL ===== -->
      <div class="tab-content active" id="tab-profile">
        <div class="profile-card">
          <div class="profile-card-header">
            <span class="profile-card-title">Informasi Akun</span>
            <button onclick="openEditModal()" style="font-size:13px;font-weight:600;color:#6C63FF;background:none;border:none;cursor:pointer;padding:6px 12px;border-radius:999px;border:1px solid #DDD6FE;transition:all 0.22s;"
              onmouseover="this.style.background='#F5F3FF'" onmouseout="this.style.background='none'">
              Edit Profil
            </button>
          </div>
          <div class="profile-card-body">
            @if(session('success'))
              <div class="profile-alert profile-alert-success">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
              </div>
            @endif
            @if(session('error'))
              <div class="profile-alert profile-alert-error">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
              </div>
            @endif

            <div class="profile-form">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Nama Lengkap</label>
                  <div class="form-input" style="background:var(--bg-card);color:var(--text-2);">{{ $user->name }}</div>
                </div>
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <div class="form-input" style="background:var(--bg-card);color:var(--text-2);">{{ $user->email }}</div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <div class="form-input" style="background:var(--bg-card);color:var(--text-2);">
                  {{ $user->phone ?? 'Belum diisi' }}
                </div>
                @if(!$user->phone)
                  <span class="form-input-hint">Tambahkan nomor telepon untuk mempermudah transaksi.</span>
                @endif
              </div>
              <div class="form-group">
                <label class="form-label">Bergabung Sejak</label>
                <div class="form-input" style="background:var(--bg-card);color:var(--text-2);">
                  {{ $user->created_at->format('d F Y') }}
                </div>
              </div>
              <div>
                <button onclick="openEditModal()" class="btn-save-profile">Edit Profil</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ===== TAB: TRANSAKSI ===== -->
      <div class="tab-content" id="tab-transactions">
        <div class="profile-card">
          <div class="profile-card-header">
            <span class="profile-card-title">Histori Transaksi</span>
            <span style="font-size:12.5px;color:var(--text-3);">{{ $transactions->count() }} transaksi</span>
          </div>
          <div class="profile-card-body">
            @if($transactions->count() > 0)
              <div class="transactions-toolbar">
                <input type="text" class="transactions-search" id="txSearch" placeholder="Cari transaksi..." oninput="filterTx()">
              </div>
              <div id="txList">
                @foreach($transactions as $tx)
                @php
                  $statusMap = [
                    'PAID'    => ['label' => 'Lunas',    'class' => 'status-paid'],
                    'UNPAID'  => ['label' => 'Menunggu', 'class' => 'status-unpaid'],
                    'FAILED'  => ['label' => 'Gagal',    'class' => 'status-failed'],
                    'EXPIRED' => ['label' => 'Kedaluwarsa','class'=> 'status-expired'],
                    'REFUND'  => ['label' => 'Refund',   'class' => 'status-refund'],
                  ];
                  $st = $statusMap[strtoupper($tx->status)] ?? ['label' => $tx->status, 'class' => 'status-unpaid'];
                @endphp
                <div class="transaction-item" data-search="{{ strtolower($tx->merchant_ref . ' ' . $tx->getProductName()) }}">
                  <div class="transaction-item-header">
                    <span class="transaction-ref">#{{ $tx->merchant_ref }}</span>
                    <span class="transaction-date">{{ $tx->created_at->format('d M Y, H:i') }}</span>
                  </div>
                  <div class="transaction-item-body">
                    <div>
                      <div class="transaction-product">{{ $tx->getProductName() }}</div>
                      <div class="transaction-meta">
                        {{ $tx->quantity ?? 1 }} item · {{ $tx->payment_method ?? '-' }}
                      </div>
                    </div>
                    <div class="transaction-amount">Rp {{ number_format($tx->amount, 0, ',', '.') }}</div>
                    <span class="transaction-status {{ $st['class'] }}">{{ $st['label'] }}</span>
                  </div>
                </div>
                @endforeach
              </div>
            @else
              <div class="empty-transactions">
                <div class="empty-transactions-icon">📋</div>
                <h4>Belum Ada Transaksi</h4>
                <p>Kamu belum pernah melakukan pembelian.</p>
              </div>
            @endif
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-backdrop" id="editModal">
  <div class="modal-box">
    <div class="modal-box-header">
      <span class="modal-box-title">Edit Profil</span>
      <button class="modal-close-btn" onclick="closeEditModal()">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <form method="POST" action="{{ route('profile.update') }}" class="modal-form" id="editForm">
      @csrf
      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-input" value="{{ $user->email }}" readonly style="background:var(--bg-card);color:var(--text-2);">
        <span class="form-input-hint">Email tidak dapat diubah.</span>
      </div>
      <div class="form-group">
        <label class="form-label">Nomor Telepon</label>
        <input type="tel" name="phone" class="form-input" value="{{ $user->phone }}" placeholder="08xxxxxxxxxx" maxlength="13">
      </div>
      <div class="modal-actions">
        <button type="submit" class="btn-modal-save">Simpan Perubahan</button>
        <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- WhatsApp -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<script>
// Navbar
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('navMobile');
hamburger?.addEventListener('click', () => {
  hamburger.classList.toggle('open');
  navMobile.classList.toggle('open');
});

const navUser = document.getElementById('navUser');
const navUserMenu = document.getElementById('navUserMenu');
if (navUser && navUserMenu) {
  navUser.addEventListener('click', e => { e.stopPropagation(); navUserMenu.classList.toggle('open'); });
  document.addEventListener('click', e => { if (!navUser.contains(e.target)) navUserMenu.classList.remove('open'); });
}

// Tab switching
function switchTab(tab, btn) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.sidebar-nav-btn').forEach(el => el.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  btn.classList.add('active');
  // Update URL
  const url = new URL(window.location);
  url.searchParams.set('tab', tab);
  window.history.replaceState({}, '', url);
}

// Auto-switch tab from URL
(function() {
  const tab = new URLSearchParams(window.location.search).get('tab');
  if (tab === 'transactions') {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.sidebar-nav-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-transactions')?.classList.add('active');
    document.getElementById('tabBtnTransactions')?.classList.add('active');
  }
})();

// Transaction search
function filterTx() {
  const q = document.getElementById('txSearch').value.toLowerCase();
  document.querySelectorAll('#txList .transaction-item').forEach(el => {
    el.style.display = el.dataset.search.includes(q) ? '' : 'none';
  });
}

// Edit modal
function openEditModal() {
  document.getElementById('editModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeEditModal() {
  document.getElementById('editModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) closeEditModal();
});
</script>

</body>
</html>
