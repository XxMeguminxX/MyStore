<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Profil — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
</head>
<body>

<!-- CONFIRM MODAL -->
<div class="prf-confirm-overlay" id="confirmModal">
  <div class="prf-confirm-backdrop"></div>
  <div class="prf-confirm-card">
    <div class="prf-confirm-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
    </div>
    <div class="prf-confirm-body">
      <h3 class="prf-confirm-title">Yakin Mau Simpan?</h3>
      <p class="prf-confirm-desc">Pastiin data yang kamu input udah bener. Perubahan ini bakal langsung disimpan ke sistem.</p>
    </div>
    <div class="prf-confirm-actions">
      <button onclick="submitEditForm()" class="prf-confirm-btn-ok">Simpan Sekarang</button>
      <button onclick="closeConfirmModal()" class="prf-confirm-btn-cancel">Batal Dulu</button>
    </div>
  </div>
</div>

<!-- NAVBAR -->
<div class="navbar-wrap" id="navbarWrap">
  <nav class="navbar" id="navbar">
    <a href="{{ url('/') }}" class="nav-logo">
      <span class="nav-logo-name">E Store ID</span>
    </a>
    <div class="nav-links" id="navLinks">
      <span class="nav-pill" id="navPill"></span>
      <a href="{{ url('/') }}">Beranda</a>
      <a href="{{ url('/#produk') }}">Produk</a>
      <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
    </div>
    <div class="nav-actions">
      <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang">
        <i class="ph-bold ph-shopping-cart-simple"></i>
      </a>
      <div class="nav-user" id="navUser">
        <div class="nav-user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <i class="ph-bold ph-caret-down nav-user-caret"></i>
        <div class="nav-user-menu" id="navUserMenu">
          <div class="num-header">
            <div class="num-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="num-info">
              <h4 class="num-name">{{ Str::limit($user->name, 20) }}</h4>
              <p class="num-email">{{ Str::limit($user->email, 26) }}</p>
            </div>
          </div>
          <div class="num-links">
            <a href="{{ url('/profile') }}" class="num-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
              Profil Saya
            </a>
            <a href="{{ route('transaction.history') }}" class="num-link">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
              Pesanan Saya
            </a>
          </div>
          <div class="num-logout-wrap">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="num-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                Log Out
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
  </nav>
</div>
<div class="nav-mobile" id="navMobile">
  <a href="{{ url('/') }}">Beranda</a>
  <a href="{{ url('/#produk') }}">Produk</a>
  <div class="nav-mobile-btns">
    <a href="{{ route('cart.index') }}" class="m-login">Keranjang</a>
    <a href="{{ url('/') }}" class="m-register">Belanja</a>
  </div>
</div>

<!-- MAIN -->
<div class="prf-wrap">

  <!-- Page Header -->
  <div class="prf-page-header" id="pageHeader">
    <div class="prf-page-icon" id="pageIcon">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="pageIconSvg"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    </div>
    <div>
      <h2 class="prf-page-title" id="pageTitle">Profil Saya</h2>
      <p class="prf-page-subtitle" id="pageSubtitle">Manage Your Digital Identity</p>
    </div>
  </div>

  <!-- Layout -->
  <div class="prf-layout">

    <!-- Sidebar -->
    <aside class="prf-sidebar">
      <button class="prf-nav-btn active" id="navBtnProfile" onclick="switchTab('profile', this)">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Edit Profil
      </button>
      <button class="prf-nav-btn" id="navBtnTransactions" onclick="switchTab('transactions', this)">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
        Histori Transaksi
        @if($transactions->count() > 0)
          <span class="prf-nav-badge">{{ $transactions->count() }}</span>
        @endif
      </button>
    </aside>

    <!-- Content -->
    <div class="prf-content">

      <!-- ===== TAB: PROFIL ===== -->
      <div class="prf-tab active" id="tab-profile">

        @if(session('success'))
          <div class="prf-alert prf-alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
          </div>
        @endif
        @if(session('error'))
          <div class="prf-alert prf-alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ session('error') }}
          </div>
        @endif

        <div class="prf-card">
          <div class="prf-card-top">
            <h3 class="prf-card-heading">Data Pribadi</h3>
            <button type="button" class="prf-btn-edit" id="btnEditProfile" onclick="enableEditMode()">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Edit Profil
            </button>
          </div>

          <form method="POST" action="{{ route('profile.update') }}" id="editForm">
            @csrf

            <!-- Avatar Row -->
            <div class="prf-avatar-row">
              <div class="prf-avatar-wrap">
                <div class="prf-avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
              </div>
              <div class="prf-avatar-info">
                <p class="prf-avatar-label">Foto Profil</p>
                <p class="prf-avatar-hint">Pake foto yang paling kece biar makin slay!</p>
              </div>
            </div>

            <!-- Fields -->
            <div class="prf-fields">
              <div class="prf-field">
                <label class="prf-field-label">Nama Lengkap</label>
                <input type="text" name="name" id="inputName" class="prf-field-input prf-field-disabled" value="{{ $user->name }}" placeholder="Nama Lengkap Lo..." required disabled>
              </div>
              <div class="prf-field">
                <label class="prf-field-label">Username</label>
                <input type="text" class="prf-field-input prf-field-readonly" value="{{ $user->username }}" readonly>
              </div>
              <div class="prf-fields-row">
                <div class="prf-field">
                  <label class="prf-field-label">Email</label>
                  <input type="email" name="email" class="prf-field-input prf-field-readonly" value="{{ $user->email }}" readonly>
                </div>
                <div class="prf-field">
                  <label class="prf-field-label">Nomor Telepon</label>
                  <input type="tel" name="phone" id="inputPhone" class="prf-field-input prf-field-disabled" value="{{ $user->phone }}" placeholder="08xxxxxxxxxx" maxlength="13" disabled>
                </div>
              </div>
            </div>

          </form>
        </div>

        <!-- Actions (hanya muncul saat edit mode) -->
        <div class="prf-form-actions" id="formActions" style="display:none;">
          <button type="button" onclick="cancelEditMode()" class="prf-btn-cancel">Batal</button>
          <button type="button" onclick="openConfirmModal()" class="prf-btn-save">Simpan Data</button>
        </div>
      </div>

      <!-- ===== TAB: TRANSAKSI ===== -->
      <div class="prf-tab" id="tab-transactions">

        <!-- Filter Tabs -->
        <div class="prf-filter-tabs" id="filterTabs">
          <button class="prf-filter-btn active" onclick="filterByStatus('all', this)">Semua</button>
          <button class="prf-filter-btn" onclick="filterByStatus('PAID', this)">Selesai</button>
          <button class="prf-filter-btn" onclick="filterByStatus('UNPAID', this)">Proses</button>
          <button class="prf-filter-btn" onclick="filterByStatus('FAILED', this)">Gagal</button>
        </div>

        <!-- Transaction List -->
        <div class="prf-tx-list" id="txList">
          @if($transactions->count() > 0)
            @foreach($transactions as $tx)
            @php
              $statusMap = [
                'PAID'    => ['label' => 'Lunas',       'class' => 'tx-status-paid'],
                'UNPAID'  => ['label' => 'Menunggu',    'class' => 'tx-status-unpaid'],
                'FAILED'  => ['label' => 'Gagal',       'class' => 'tx-status-failed'],
                'EXPIRED' => ['label' => 'Kedaluwarsa', 'class' => 'tx-status-expired'],
                'REFUND'  => ['label' => 'Refund',      'class' => 'tx-status-refund'],
              ];
              $st = $statusMap[strtoupper($tx->status)] ?? ['label' => $tx->status, 'class' => 'tx-status-unpaid'];
            @endphp
            <div class="prf-tx-item"
              data-status="{{ strtoupper($tx->status) }}"
              onclick="window.location.href='/transaction/{{ $tx->merchant_ref }}'">
              <div class="prf-tx-left">
                <div class="prf-tx-img">
                  @if($tx instanceof \App\Models\PulsaTransaction)
                    <span class="prf-tx-img-icon">⚡</span>
                  @else
                    <span class="prf-tx-img-icon">📦</span>
                  @endif
                </div>
                <div class="prf-tx-info">
                  <p class="prf-tx-ref">#{{ $tx->merchant_ref }}</p>
                  <h4 class="prf-tx-name">
                    {{ $tx->getProductName() }}
                    @if($tx instanceof \App\Models\PulsaTransaction)
                      <span class="prf-tx-tag-pulsa">PULSA</span>
                    @endif
                  </h4>
                  <p class="prf-tx-date">{{ $tx->created_at->format('d M Y') }} · {{ $tx->created_at->format('H:i') }} WIB</p>
                </div>
              </div>
              <div class="prf-tx-right">
                <div class="prf-tx-amount-wrap">
                  <p class="prf-tx-amount">Rp {{ number_format($tx->amount, 0, ',', '.') }}</p>
                  <span class="prf-tx-status {{ $st['class'] }}">{{ $st['label'] }}</span>
                </div>
                <button class="prf-tx-arrow" onclick="event.stopPropagation(); window.location.href='/transaction/{{ $tx->merchant_ref }}'">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
              </div>
            </div>
            @endforeach
          @else
            <div class="prf-tx-empty">
              <div class="prf-tx-empty-icon">📋</div>
              <h4 class="prf-tx-empty-title">Belum Ada Transaksi</h4>
              <p class="prf-tx-empty-desc">Kamu belum pernah melakukan pembelian.</p>
            </div>
          @endif
        </div>

      </div>

    </div>
  </div>
</div>

<!-- WhatsApp -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<script>
// Navbar scroll
window.addEventListener('scroll', () => {
  document.getElementById('navbarWrap').classList.toggle('scrolled', window.scrollY > 20);
}, { passive: true });

// Nav pill
(function() {
  const navLinks = document.getElementById('navLinks');
  const pill = document.getElementById('navPill');
  if (!navLinks || !pill) return;
  function movePillTo(el) {
    const pR = navLinks.getBoundingClientRect(), eR = el.getBoundingClientRect();
    pill.style.left   = (eR.left - pR.left) + 'px';
    pill.style.top    = (eR.top  - pR.top)  + 'px';
    pill.style.width  = eR.width  + 'px';
    pill.style.height = eR.height + 'px';
  }
  const links = navLinks.querySelectorAll('a');
  const activeLink = navLinks.querySelector('a.active');
  if (activeLink) { pill.style.transition = 'none'; movePillTo(activeLink); requestAnimationFrame(() => { pill.style.transition = ''; }); }
  links.forEach(link => {
    link.addEventListener('mouseenter', () => movePillTo(link));
    link.addEventListener('mouseleave', () => { if (activeLink) movePillTo(activeLink); });
  });
})();

// Hamburger
const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('navMobile');
hamburger?.addEventListener('click', () => { hamburger.classList.toggle('open'); navMobile.classList.toggle('open'); });

// Nav user dropdown
const navUser = document.getElementById('navUser');
const navUserMenu = document.getElementById('navUserMenu');
if (navUser && navUserMenu) {
  navUser.addEventListener('click', e => { e.stopPropagation(); if (navUserMenu.contains(e.target)) return; navUserMenu.classList.toggle('open'); });
  document.addEventListener('click', e => { if (!navUser.contains(e.target)) navUserMenu.classList.remove('open'); });
}

// Tab config
const tabConfig = {
  profile: {
    title: 'Profil Saya',
    subtitle: 'Manage Your Digital Identity',
    icon: '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'
  },
  transactions: {
    title: 'Histori Transaksi',
    subtitle: 'Lacak Semua Pesanan Lo Di Sini',
    icon: '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>'
  }
};

function switchTab(tab, btn) {
  // Hide all tabs
  document.querySelectorAll('.prf-tab').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.prf-nav-btn').forEach(el => el.classList.remove('active'));
  // Show target
  document.getElementById('tab-' + tab).classList.add('active');
  btn.classList.add('active');
  // Update header
  const cfg = tabConfig[tab];
  if (cfg) {
    document.getElementById('pageTitle').textContent = cfg.title;
    document.getElementById('pageSubtitle').textContent = cfg.subtitle;
    document.getElementById('pageIconSvg').innerHTML = cfg.icon;
  }
  // Update URL
  const url = new URL(window.location);
  url.searchParams.set('tab', tab);
  window.history.replaceState({}, '', url);
}

// Auto-switch from URL
(function() {
  const tab = new URLSearchParams(window.location.search).get('tab');
  if (tab === 'transactions') {
    const btn = document.getElementById('navBtnTransactions');
    if (btn) switchTab('transactions', btn);
  }
})();

// Edit mode
const editableInputs = ['inputName', 'inputPhone'];
const originalValues = {};

function enableEditMode() {
  editableInputs.forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    originalValues[id] = el.value;
    el.disabled = false;
    el.classList.remove('prf-field-disabled');
  });
  document.getElementById('btnEditProfile').style.display = 'none';
  document.getElementById('formActions').style.display = 'flex';
  document.getElementById('inputName').focus();
}

function cancelEditMode() {
  editableInputs.forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.value = originalValues[id] ?? el.value;
    el.disabled = true;
    el.classList.add('prf-field-disabled');
  });
  document.getElementById('btnEditProfile').style.display = '';
  document.getElementById('formActions').style.display = 'none';
}

// Confirm modal
function openConfirmModal() {
  document.getElementById('confirmModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeConfirmModal() {
  document.getElementById('confirmModal').classList.remove('active');
  document.body.style.overflow = '';
}
function submitEditForm() {
  closeConfirmModal();
  document.getElementById('editForm').submit();
}
document.getElementById('confirmModal').addEventListener('click', function(e) {
  if (e.target === this || e.target.classList.contains('prf-confirm-backdrop')) closeConfirmModal();
});

// Filter transactions by status
function filterByStatus(status, btn) {
  document.querySelectorAll('.prf-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.prf-tx-item').forEach(item => {
    if (status === 'all' || item.dataset.status === status) {
      item.style.display = '';
    } else {
      item.style.display = 'none';
    }
  });
}
</script>

</body>
</html>
