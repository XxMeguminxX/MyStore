<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>E Store ID — Produk Digital Terpercaya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v=1.0.0">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">

  <style>
  .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:16px; }
  .checkout-modal-box { background:#fff; border-radius:16px; width:100%; max-width:560px; max-height:90vh; overflow-y:auto; }
  .checkout-modal-header { display:flex; justify-content:space-between; align-items:center; padding:20px 24px 0; }
  .checkout-modal-header h3 { font-size:18px; font-weight:700; margin:0; }
  .checkout-modal-close { background:none; border:none; font-size:24px; cursor:pointer; color:#6b7280; }
  .checkout-modal-body { padding:20px 24px 24px; }
  .order-summary-box { display:flex; align-items:center; gap:12px; background:#f9fafb; border-radius:10px; padding:14px; margin-bottom:20px; }
  .order-summary-box img { width:48px; height:48px; object-fit:contain; }
  .order-summary-name { font-weight:600; font-size:15px; }
  .order-summary-price { color:#6b7280; font-size:13px; margin-top:2px; }
  .checkout-field { margin-bottom:14px; }
  .checkout-field label { display:block; font-size:13px; font-weight:600; margin-bottom:5px; color:#374151; }
  .checkout-field input, .checkout-field select { width:100%; padding:10px 12px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:14px; outline:none; box-sizing:border-box; }
  .checkout-field input:focus, .checkout-field select:focus { border-color:#6366f1; }
  .checkout-error { background:#fef2f2; color:#dc2626; border-radius:8px; padding:10px 14px; font-size:13px; margin-bottom:12px; display:none; }
  .checkout-submit-btn { width:100%; padding:13px; background:#6366f1; color:#fff; border:none; border-radius:10px; font-size:15px; font-weight:700; cursor:pointer; margin-top:4px; }
  .checkout-submit-btn:disabled { opacity:0.6; cursor:not-allowed; }
  .payment-info-box { text-align:center; padding:8px 0 16px; }
  .payment-info-product { font-size:15px; color:#374151; margin-bottom:4px; }
  .payment-info-amount { font-size:28px; font-weight:800; color:#111827; margin:8px 0 20px; }
  .payment-url-btn { display:inline-block; padding:13px 28px; background:#6366f1; color:#fff; border-radius:10px; font-weight:700; font-size:15px; text-decoration:none; }
  .payment-back-btn { background:none; border:none; color:#6b7280; font-size:13px; cursor:pointer; margin-top:12px; }
  </style>
</head>
<body>
@php $authUser = auth()->user(); @endphp

<!-- ============================================================
     NAVBAR
============================================================ -->
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
      <a href="{{ url('/') }}" class="active">Beranda</a>
      <a href="#produk">Produk</a>
      <a href="#pulsa">Beli Pulsa</a>
      <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
    </div>

    <div class="nav-actions">
      <button class="nav-icon-btn" onclick="focusHeroSearch()" title="Cari">
        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
        </svg>
      </button>

      @auth
        <a href="{{ route('cart.index') }}" class="nav-icon-btn" id="cartBtn" title="Keranjang">
          <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/>
          </svg>
          <span class="cart-badge" id="cartBadge" style="display:none;">0</span>
        </a>

        <div class="nav-user" id="navUser">
          <div class="nav-user-avatar">{{ strtoupper(substr($authUser->name, 0, 1)) }}</div>
          <span class="nav-user-name">{{ Str::limit($authUser->name, 12) }}</span>
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
      @else
        <a href="{{ route('login') }}" class="nav-btn-login">Masuk</a>
        <a href="{{ route('register') }}" class="nav-btn-register">Daftar Gratis</a>
      @endauth
    </div>

    <button class="nav-hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Mobile nav -->
<div class="nav-mobile" id="navMobile">
  <a href="{{ url('/') }}">Beranda</a>
  <a href="#produk" id="mobileNavProduk">Produk</a>
  <a href="#pulsa">Beli Pulsa</a>
  <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
  <div class="nav-mobile-btns">
    @auth
      <a href="{{ url('/profile') }}" class="m-login">Profil Saya</a>
      <a href="{{ route('cart.index') }}" class="m-register">Keranjang</a>
    @else
      <a href="{{ route('login') }}" class="m-login">Masuk</a>
      <a href="{{ route('register') }}" class="m-register">Daftar Gratis</a>
    @endauth
  </div>
</div>

<!-- Flash alerts -->
@if(session('success'))
  <div class="alert alert-success">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert alert-error">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
  </div>
@endif

<!-- ============================================================
     HERO
============================================================ -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <span class="hero-label">
      <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      Toko Digital Pilihan Indonesia
    </span>
    <h1 class="hero-title">Produk<br><em>Digital</em></h1>
    <div class="hero-search">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
      </svg>
      <input type="text" id="heroSearchInput" placeholder="Cari produk digital...">
      <button class="hero-search-btn" onclick="runSearch()">Cari</button>
    </div>
  </div>
</section>

<!-- ============================================================
     HERO CARDS
============================================================ -->
<div class="hero-cards">
  <!-- Left card -->
  <div class="hcard-left">
    <div class="hcard-badge">Toko Digital</div>
    <h2 class="hcard-headline">
      Produk Digital,<br><em>Aktivasi Instan</em><br>Harga Terbaik
    </h2>
    <p class="hcard-sub">
      Lebih hemat dari marketplace. Aktif dalam hitungan detik.
      Garansi <strong>100% uang kembali</strong> jika gagal.
    </p>
    <div class="hcard-stats">
      <div class="hcard-stat">4.9 <span>Rating</span></div>
      <div class="hcard-stat">1.2K+ <span>Pembeli</span></div>
      <div class="hcard-stat">100% <span>Aman</span></div>
    </div>
    <a href="#produk" class="hcard-cta">
      Lihat Produk Sekarang
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </a>
  </div>

  <!-- Right card -->
  <div class="hcard-right">
    <div class="hcard-right-header">
      <span class="hcard-right-title">Kenapa Pilih Kami?</span>
      <span class="hcard-trusted-badge">Terpercaya</span>
    </div>
    <div class="feature-grid">
      <div class="feature-item">
        <div class="feature-icon" style="background:#FFF7ED;"></div>
        <div class="feature-title">&lt; 1 Menit</div>
        <div class="feature-sub">Pengiriman instan otomatis</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon" style="background:#ECFDF5;"></div>
        <div class="feature-title">Garansi Penuh</div>
        <div class="feature-sub">Jaminan uang kembali</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon" style="background:#EFF6FF;"></div>
        <div class="feature-title">Harga Terbaik</div>
        <div class="feature-sub">Kompetitif & transparan</div>
      </div>
      <div class="feature-item">
        <div class="feature-icon" style="background:#F5F3FF;"></div>
        <div class="feature-title">Multi Pembayaran</div>
        <div class="feature-sub">VA, QRIS, e-wallet</div>
      </div>
    </div>
    <div class="hcard-checklist">
      <div class="hcard-check"><span class="check-icon"></span> Transaksi dienkripsi & 100% aman</div>
      <div class="hcard-check"><span class="check-icon"></span> Customer service aktif 7 hari seminggu</div>
      <div class="hcard-check"><span class="check-icon"></span> Sudah dipercaya 5.000+ pelanggan</div>
    </div>
  </div>
</div>

<!-- ============================================================
     PRODUCT SECTION
============================================================ -->
<section class="product-section" id="produk">

  <div class="section-header-row">
    <div></div>
    <div class="section-title-wrap">
      <h2 class="section-title">Produk Digital</h2>
      <div class="section-title-bar"></div>
    </div>
    <div>
      <select class="sort-select" onchange="window.location.href='/?sort='+this.value+'{{ $categoryId ? '&category='.$categoryId : '' }}'">
        <option value="newest"     {{ $sortBy == 'newest'     ? 'selected' : '' }}>Terbaru</option>
        <option value="price_low"  {{ $sortBy == 'price_low'  ? 'selected' : '' }}>Harga Terendah</option>
        <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
        <option value="bestseller" {{ $sortBy == 'bestseller' ? 'selected' : '' }}>Terlaris</option>
        <option value="stock_high" {{ $sortBy == 'stock_high' ? 'selected' : '' }}>Stok Terbanyak</option>
        <option value="stock_low"  {{ $sortBy == 'stock_low'  ? 'selected' : '' }}>Stok Tersedikit</option>
      </select>
    </div>
  </div>

  <!-- Filter tabs -->
  <div class="filter-tabs">
    <a href="{{ url('/') }}?sort={{ $sortBy }}" class="filter-tab {{ !$categoryId ? 'active' : '' }}">Semua</a>
    @foreach($categories as $cat)
      <a href="{{ url('/') }}?sort={{ $sortBy }}&category={{ $cat->id }}" class="filter-tab {{ $categoryId == $cat->id ? 'active' : '' }}">
        {{ $cat->name }}
      </a>
    @endforeach
  </div>

  <!-- Product grid -->
  <div class="product-grid" id="productGrid">
    @php
      $gradients = [
        'linear-gradient(135deg,#EA4335,#FBBC05)',
        'linear-gradient(135deg,#E50914,#B81D24)',
        'linear-gradient(135deg,#00A4EF,#0078D4)',
        'linear-gradient(135deg,#1DB954,#158a3e)',
        'linear-gradient(135deg,#7C3AED,#A78BFA)',
        'linear-gradient(135deg,#D83B01,#F05A28)',
        'linear-gradient(135deg,#F59E0B,#D97706)',
        'linear-gradient(135deg,#6366F1,#818CF8)',
      ];
    @endphp

    @forelse ($products as $data)
      @php
        $grad = $gradients[$loop->index % count($gradients)];

        // Promo badge
        $badgeClass = ''; $badgeText = '';
        if ($loop->index % 5 === 0) { $badgeClass = 'promo-terlaris'; $badgeText = 'Terlaris'; }
        elseif ($loop->index % 5 === 2) { $badgeClass = 'promo-hot'; $badgeText = 'Hot'; }
        elseif ($data->stock <= 5 && $data->stock > 0) { $badgeClass = 'promo-hot'; $badgeText = 'Hot'; }
      @endphp

      <a href="{{ route('product.show', $data->id) }}" class="product-card" data-category="{{ $data->category_id ?? '' }}" data-name="{{ strtolower($data->name) }}">
        <div class="product-img-wrap" style="background: {{ $grad }};">
          <span class="product-img-letter">{{ strtoupper(substr($data->name, 0, 1)) }}</span>
          <img src="{{ $data->image }}" alt="{{ $data->name }}" class="product-img-thumb"
               onerror="this.style.display='none'">
          <span class="product-badge-cat">Digital</span>
          @if($badgeText)
            <span class="product-badge-promo {{ $badgeClass }}">{{ $badgeText }}</span>
          @endif
        </div>

        <div class="product-body">
          <div class="product-name">{{ $data->name }}</div>


          <div class="product-stock">
            @if($data->stock > 10)
              <span class="stock-pill stock-ok">Tersedia</span>
              <span class="stock-count">{{ $data->stock }} unit</span>
            @elseif($data->stock > 0)
              <span class="stock-pill stock-low">Terbatas</span>
              <span class="stock-count">{{ $data->stock }} unit</span>
            @else
              <span class="stock-pill stock-out">Habis</span>
            @endif
          </div>

          <div class="product-footer-row">
            <span class="product-price">Rp {{ number_format($data->price, 0, ',', '.') }}</span>
          </div>
        </div>
      </a>
    @empty
      <div class="no-results" style="display:block;">
        <h3>Belum ada produk</h3>
        <p>Produk sedang dalam proses penambahan.</p>
      </div>
    @endforelse

    <!-- Client-side search no-results -->
    <div class="no-results" id="noResults">
      <h3>Produk tidak ditemukan</h3>
      <p>Coba kata kunci lain atau lihat semua produk.</p>
    </div>
  </div><!-- /product-grid -->

  {{ $products->links('vendor.pagination.custom') }}

</section>

<!-- ============================================================
     PULSA SECTION
============================================================ -->
<section class="product-section" id="pulsa">

  <div class="section-header-row">
    <div></div>
    <div class="section-title-wrap">
      <h2 class="section-title">Beli Pulsa</h2>
      <div class="section-title-bar"></div>
    </div>
    <div></div>
  </div>

  <!-- Filter operator tabs (diisi dinamis oleh JS) -->
  <div class="filter-tabs" id="pulsaOperatorTabs">
    <button class="filter-tab active" data-operator="all" onclick="filterPulsa('all', this)">Semua</button>
  </div>

  <!-- Product grid pulsa -->
  <div class="product-grid" id="pulsaGrid">
    <!-- Skeleton loader -->
    @for ($i = 0; $i < 8; $i++)
      <div class="product-card pulsa-skeleton">
        <div class="product-img-wrap" style="background: #e5e7eb;">
          <span class="product-img-letter" style="color:#d1d5db;"></span>
        </div>
        <div class="product-body">
          <div style="height:14px;background:#e5e7eb;border-radius:6px;margin-bottom:8px;"></div>
          <div style="height:12px;background:#f3f4f6;border-radius:6px;width:60%;margin-bottom:12px;"></div>
          <div style="height:20px;background:#e5e7eb;border-radius:6px;width:50%;"></div>
        </div>
      </div>
    @endfor
  </div>

  <div id="pulsaError" style="display:none;" class="no-results">
    <h3>Gagal memuat produk pulsa</h3>
    <p id="pulsaErrorMsg">Coba refresh halaman.</p>
  </div>

</section>

<script>
// ===== SHARED OPERATOR CONSTANTS (used by both pulsa grid & checkout modal) =====
const OPERATOR_IMAGES = {
  'S':   '{{ asset("assets/img/operators/telkomsel.png") }}',
  'X':   '{{ asset("assets/img/operators/xl.png") }}',
  'AX':  '{{ asset("assets/img/operators/axis.png") }}',
  'I':   '{{ asset("assets/img/operators/indosat.png") }}',
  'T':   '{{ asset("assets/img/operators/tri.png") }}',
  'SM':  '{{ asset("assets/img/operators/smartfren.png") }}',
  'BYU': '{{ asset("assets/img/operators/byu.png") }}',
};

const OPERATOR_COLORS = {
  'S':   '#FEE2E2',
  'X':   '#DBEAFE',
  'AX':  '#F3F4F6',
  'I':   '#FEF3C7',
  'T':   '#EDE9FE',
  'SM':  '#FFEDD5',
  'BYU': '#D1FAE5',
};

(function () {
  let allPulsaProducts = [];
  const pulsaProductMap = {};
  window._pulsaProductMap = pulsaProductMap;

  function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
  }

  function renderCards(products) {
    const grid = document.getElementById('pulsaGrid');
    grid.innerHTML = '';

    if (!products.length) {
      const msg = document.createElement('div');
      msg.className = 'no-results';
      msg.style.display = 'block';
      const h3 = document.createElement('h3');
      h3.textContent = 'Tidak ada produk';
      const p = document.createElement('p');
      p.textContent = 'Operator ini belum tersedia.';
      msg.append(h3, p);
      grid.appendChild(msg);
      return;
    }

    products.forEach(item => {
      const card = document.createElement('div');
      card.className = 'product-card';
      card.dataset.operator = item.operator_id;
      card.style.cursor = 'pointer';
      card.addEventListener('click', () => openPulsaCheckout(item.code));

      // Image wrap
      const imgWrap = document.createElement('div');
      imgWrap.className = 'product-img-wrap';
      imgWrap.style.background = OPERATOR_COLORS[item.operator_id] || '#F3F4F6';

      const imgSrc = OPERATOR_IMAGES[item.operator_id];
      if (imgSrc) {
        const img = document.createElement('img');
        img.src = imgSrc;
        img.alt = item.operator || '';
        img.className = 'product-img-thumb';
        img.style.cssText = 'object-fit:contain;padding:12px;';
        imgWrap.appendChild(img);
      } else {
        const letter = document.createElement('span');
        letter.className = 'product-img-letter';
        letter.textContent = (item.operator || item.name || 'P').charAt(0).toUpperCase();
        imgWrap.appendChild(letter);
      }

      const badgeCat = document.createElement('span');
      badgeCat.className = 'product-badge-cat';
      badgeCat.textContent = 'Pulsa';
      imgWrap.appendChild(badgeCat);

      // Body
      const body = document.createElement('div');
      body.className = 'product-body';

      const nameEl = document.createElement('div');
      nameEl.className = 'product-name';
      nameEl.textContent = item.name;

      const meta = document.createElement('div');
      meta.className = 'product-meta';
      const opSpan = document.createElement('span');
      opSpan.style.cssText = 'font-size:11px;color:#6b7280;';
      opSpan.textContent = item.operator || '';
      meta.appendChild(opSpan);

      const stockDiv = document.createElement('div');
      stockDiv.className = 'product-stock';
      const stockPill = document.createElement('span');
      stockPill.className = 'stock-pill stock-ok';
      stockPill.textContent = 'Tersedia';
      stockDiv.appendChild(stockPill);

      const footer = document.createElement('div');
      footer.className = 'product-footer-row';
      const priceEl = document.createElement('span');
      priceEl.className = 'product-price';
      priceEl.textContent = formatRupiah(item.price);
      footer.appendChild(priceEl);

      body.append(nameEl, meta, stockDiv, footer);
      card.append(imgWrap, body);
      grid.appendChild(card);
    });
  }

  function buildOperatorTabs(operators) {
    const container = document.getElementById('pulsaOperatorTabs');
    operators.forEach(op => {
      const btn = document.createElement('button');
      btn.className = 'filter-tab';
      btn.dataset.operator = op.id;
      btn.textContent = op.name;
      btn.onclick = function () { filterPulsa(op.id, this); };
      container.appendChild(btn);
    });
  }

  window.filterPulsa = function (operatorId, btn) {
    document.querySelectorAll('#pulsaOperatorTabs .filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const filtered = operatorId === 'all'
      ? allPulsaProducts
      : allPulsaProducts.filter(p => p.operator_id === operatorId);

    renderCards(filtered);
  };

  fetch('/api/kategori')
    .then(r => r.json())
    .then(res => {
      if (!res.success) throw new Error(res.message || 'Gagal memuat data');
      allPulsaProducts = res.products || [];
      allPulsaProducts.forEach(p => { pulsaProductMap[p.code] = p; });
      buildOperatorTabs(res.operators || []);
      renderCards(allPulsaProducts);
    })
    .catch(err => {
      document.getElementById('pulsaGrid').style.display = 'none';
      document.getElementById('pulsaError').style.display = 'block';
      document.getElementById('pulsaErrorMsg').textContent = err.message;
    });
})();
</script>

<!-- ============================================================
     RECOMMENDATIONS CAROUSEL
============================================================ -->
<section class="carousel-section">
  <div class="carousel-header">
    <h2 class="carousel-title">Produk Rekomendasi</h2>
    <div class="carousel-nav">
      <button class="carousel-btn" id="carouselPrev" aria-label="Previous">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <button class="carousel-btn" id="carouselNext" aria-label="Next">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </div>
  <div class="carousel-track-wrap">
    <div class="carousel-track" id="carouselTrack">
      @php $carouselGrads = ['linear-gradient(135deg,#F59E0B,#D97706)','linear-gradient(135deg,#0F4C81,#1a73e8)','linear-gradient(135deg,#00B4D8,#0077B6)','linear-gradient(135deg,#6366F1,#818CF8)','linear-gradient(135deg,#1DB954,#158a3e)','linear-gradient(135deg,#E50914,#B81D24)']; @endphp
      @foreach ($products->getCollection()->take(6) as $ci => $item)
        <a href="{{ route('product.show', $item->id) }}" class="product-card">
          <div class="product-img-wrap" style="background: {{ $carouselGrads[$ci % count($carouselGrads)] }};">
            <span class="product-img-letter">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
            <img src="{{ $item->image }}" alt="{{ $item->name }}" class="product-img-thumb" onerror="this.style.display='none'">
            <span class="product-badge-cat">Digital</span>
          </div>
          <div class="product-body">
            <div class="product-name">{{ $item->name }}</div>
            <div class="product-footer-row">
              <span class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>

<!-- ============================================================
     CTA / NEWSLETTER
============================================================ -->
<section class="cta-section">
  <div class="cta-inner">
    <div>
      <h2 class="cta-title">Dapatkan Notifikasi<br>Produk <em>Terbaru</em></h2>
      <div class="cta-input-row">
        <input type="email" class="cta-input" placeholder="Masukkan email kamu...">
        <button class="cta-send-btn">Kirim</button>
      </div>
    </div>
    <div>
      <div class="cta-desc-title">E Store ID untuk Produk Digital</div>
      <p class="cta-desc">
        Kami akan mengirimkan notifikasi produk terbaru, promo eksklusif,
        dan penawaran terbatas langsung ke inbox kamu. Tidak ada spam.
      </p>
      <div class="cta-perks">
        <div class="cta-perk"><span class="cta-perk-dot"></span>Update produk setiap minggu</div>
        <div class="cta-perk"><span class="cta-perk-dot"></span>Promo subscriber eksklusif</div>
        <div class="cta-perk"><span class="cta-perk-dot"></span>Bisa unsubscribe kapan saja</div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <div class="footer-brand-logo">
          <div class="footer-brand-icon">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
          </div>
          <span class="footer-brand-name">E Store ID</span>
        </div>
        <p class="footer-brand-desc">
          Marketplace produk digital terpercaya untuk kebutuhan software,
          akun premium, dan voucher di Indonesia.
        </p>
        <div class="footer-socials">
          <a href="#" class="social-btn" aria-label="X">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.629 5.905-5.629zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <a href="#" class="social-btn" aria-label="Facebook">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
          <a href="#" class="social-btn" aria-label="LinkedIn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a href="#" class="social-btn" aria-label="Instagram">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
          </a>
        </div>
      </div>

      <div class="footer-col">
        <div class="footer-col-title">Tentang</div>
        <ul>
          <li><a href="{{ route('static.page', 'tentang-kami') }}">Tentang Kami</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="{{ route('static.page', 'kontak') }}">Kontak</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <div class="footer-col-title">Support</div>
        <ul>
          <li><a href="{{ route('static.page', 'cara-beli') }}">Cara Beli</a></li>
          <li><a href="{{ route('static.page', 'kontak') }}">Hubungi Kami</a></li>
          <li><a href="#">FAQ</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <div class="footer-col-title">Legal</div>
        <ul>
          <li><a href="{{ route('static.page', 'kebijakan-privasi') }}">Kebijakan Privasi</a></li>
          <li><a href="{{ route('static.page', 'ketentuan-layanan') }}">Ketentuan Layanan</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span class="footer-copy">&copy; {{ date('Y') }} E Store ID. All Rights Reserved.</span>
      <div class="footer-legal">
        <a href="{{ route('static.page', 'ketentuan-layanan') }}">Terms of Service</a>
        <a href="{{ route('static.page', 'kebijakan-privasi') }}">Privacy Policy</a>
      </div>
    </div>
  </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<!-- Login modal -->
<div class="modal-backdrop" id="loginModal">
  <div class="modal-box">
    <button class="modal-close-btn" onclick="closeLoginModal()">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
    <div class="modal-title">Login Diperlukan</div>
    <p class="modal-desc">Buat akun gratis atau masuk untuk membeli produk digital dengan aman.</p>
    <div class="modal-actions">
      <a href="{{ route('login') }}" class="modal-btn-login">Masuk Sekarang</a>
      <a href="{{ route('register') }}" class="modal-btn-register">Daftar Gratis</a>
      <button class="modal-btn-cancel" onclick="closeLoginModal()">Nanti Saja</button>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="cart-toast" id="cartToast"></div>

<!-- ============================================================
     JAVASCRIPT
============================================================ -->
<script>
// ===== NAVBAR SCROLL =====
const navbar = document.getElementById('navbar');
window.addEventListener('scroll', () => {
  navbar.classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

// ===== HAMBURGER =====
const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('navMobile');
hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('open');
  navMobile.classList.toggle('open');
  document.body.style.overflow = navMobile.classList.contains('open') ? 'hidden' : '';
});
navMobile.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', () => {
    hamburger.classList.remove('open');
    navMobile.classList.remove('open');
    document.body.style.overflow = '';
  });
});

// ===== USER DROPDOWN =====
const navUser = document.getElementById('navUser');
const navUserMenu = document.getElementById('navUserMenu');
if (navUser && navUserMenu) {
  navUser.addEventListener('click', e => {
    e.stopPropagation();
    navUserMenu.classList.toggle('open');
  });
  document.addEventListener('click', e => {
    if (!navUser.contains(e.target)) navUserMenu.classList.remove('open');
  });
}

// ===== CART COUNT =====
async function updateCartCount() {
  try {
    const res = await fetch('/cart/count');
    const data = await res.json();
    const el = document.getElementById('cartBadge');
    if (el) {
      el.textContent = data.count > 9 ? '9+' : data.count;
      el.style.display = data.count > 0 ? 'flex' : 'none';
    }
  } catch(e) {}
}

// ===== CART ADD =====
async function addToCart(productId, btn) {
  btn.disabled = true;
  btn.textContent = '...';
  try {
    const res = await fetch(`/cart/add/${productId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ quantity: 1 })
    });
    const data = await res.json();
    if (data.success || res.ok) {
      showToast('Produk ditambahkan ke keranjang', 'success');
      updateCartCount();
      // Animate cart button
      const cartBtn = document.getElementById('cartBtn');
      if (cartBtn) {
        cartBtn.style.transform = 'scale(1.25)';
        setTimeout(() => cartBtn.style.transform = '', 220);
      }
    } else {
      showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
    }
  } catch(e) {
    showToast('Terjadi kesalahan, coba lagi', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Tambah Keranjang';
  }
}

// ===== TOAST =====
function showToast(msg, type = 'success') {
  const toast = document.getElementById('cartToast');
  toast.textContent = msg;
  toast.className = `cart-toast ${type} show`;
  setTimeout(() => toast.classList.remove('show'), 3000);
}

// ===== LOGIN MODAL =====
function showLoginModal() {
  document.getElementById('loginModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeLoginModal() {
  document.getElementById('loginModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('loginModal').addEventListener('click', function(e) {
  if (e.target === this) closeLoginModal();
});

// ===== HERO SEARCH =====
function focusHeroSearch() {
  document.getElementById('heroSearchInput').focus();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
function runSearch() {
  const q = document.getElementById('heroSearchInput').value.trim().toLowerCase();
  searchProducts(q);
  if (q) document.getElementById('produk').scrollIntoView({ behavior: 'smooth' });
}
document.getElementById('heroSearchInput').addEventListener('keydown', e => {
  if (e.key === 'Enter') runSearch();
});

// ===== SEARCH / FILTER =====
function searchProducts(q) {
  const cards = document.querySelectorAll('#productGrid .product-card');
  const activeTab = document.querySelector('.filter-tab.active')?.dataset.filter || 'semua';
  let visible = 0;
  cards.forEach(card => {
    if (card.id === 'noResults') return;
    const nameMatch = !q || card.dataset.name?.includes(q);
    const catMatch = activeTab === 'semua' || card.dataset.category === activeTab;
    const show = nameMatch && catMatch;
    card.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

// ===== FILTER TABS (scoped to product section only) =====
document.querySelectorAll('#produk .filter-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('#produk .filter-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const q = document.getElementById('heroSearchInput').value.trim().toLowerCase();
    searchProducts(q);
  });
});

// ===== CAROUSEL =====
const track = document.getElementById('carouselTrack');
let carouselIdx = 0;
function getCardW() {
  const c = track?.querySelector('.product-card');
  return c ? c.offsetWidth + 20 : 0;
}
function maxIdx() {
  const total = track?.querySelectorAll('.product-card').length || 0;
  return Math.max(0, total - 3);
}
document.getElementById('carouselNext')?.addEventListener('click', () => {
  carouselIdx = Math.min(carouselIdx + 1, maxIdx());
  track.style.transform = `translateX(-${carouselIdx * getCardW()}px)`;
});
document.getElementById('carouselPrev')?.addEventListener('click', () => {
  carouselIdx = Math.max(carouselIdx - 1, 0);
  track.style.transform = `translateX(-${carouselIdx * getCardW()}px)`;
});

// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
  searchProducts(''); // run initial filter
});
</script>

<!-- ============================================================
     PULSA CHECKOUT MODAL
============================================================ -->
<div id="pulsaCheckoutModal" class="modal-overlay" style="display:none;">
  <div class="checkout-modal-box">

    <!-- Step 1: Form -->
    <div id="checkoutStep1">
      <div class="checkout-modal-header">
        <h3>Beli Pulsa</h3>
        <button class="checkout-modal-close" onclick="closePulsaModal()" aria-label="Tutup">&times;</button>
      </div>
      <div class="checkout-modal-body">
        <!-- Order summary -->
        <div class="order-summary-box">
          <img id="modalOperatorImg" src="" alt="Operator" style="display:none;">
          <div>
            <div class="order-summary-name" id="modalProductName"></div>
            <div class="order-summary-price" id="modalProductPrice"></div>
          </div>
        </div>

        <!-- Form -->
        <form id="pulsaCheckoutForm" onsubmit="return false;">
          <div class="checkout-field">
            <label for="checkoutPhone">Nomor HP Tujuan</label>
            <input type="tel" id="checkoutPhone" placeholder="08xxx" autocomplete="tel">
          </div>
          <div class="checkout-field">
            <label for="checkoutName">Nama Pembeli</label>
            <input type="text" id="checkoutName" value="{{ $authUser ? $authUser->name : '' }}" readonly style="background:#f3f4f6;cursor:not-allowed;color:#6b7280;">
          </div>
          <div class="checkout-field">
            <label for="checkoutEmail">Email</label>
            <input type="email" id="checkoutEmail" value="{{ $authUser ? $authUser->email : '' }}" readonly style="background:#f3f4f6;cursor:not-allowed;color:#6b7280;">
          </div>
          <div class="checkout-field">
            <label for="checkoutPaymentMethod">Metode Pembayaran</label>
            <select id="checkoutPaymentMethod" disabled>
              <option value="">Memuat metode pembayaran...</option>
            </select>
          </div>

          <div id="checkoutError" class="checkout-error"></div>

          <button type="button" id="checkoutSubmitBtn" class="checkout-submit-btn" onclick="submitPulsaCheckout()">
            Bayar
          </button>
        </form>
      </div>
    </div>

    <!-- Step 2: Payment link -->
    <div id="checkoutStep2" style="display:none;">
      <div class="checkout-modal-header">
        <h3>Selesaikan Pembayaran</h3>
        <button class="checkout-modal-close" onclick="closePulsaModal()" aria-label="Tutup">&times;</button>
      </div>
      <div class="checkout-modal-body">
        <div class="payment-info-box">
          <div class="payment-info-product" id="paymentProductSummary"></div>
          <div class="payment-info-amount" id="paymentAmountDisplay"></div>

          <!-- Pay code (VA / kode bayar) -->
          <div id="payCodeBox" style="display:none;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:10px;padding:14px 18px;margin-bottom:18px;text-align:left;">
            <div style="font-size:12px;color:#6b7280;margin-bottom:4px;" id="payCodeLabel">Nomor Virtual Account</div>
            <div style="display:flex;align-items:center;gap:10px;">
              <span id="payCodeValue" style="font-size:22px;font-weight:700;letter-spacing:2px;color:#111827;"></span>
              <button onclick="copyPayCode(this)" style="padding:4px 10px;border:1.5px solid #6366f1;border-radius:6px;background:#fff;color:#6366f1;font-size:12px;font-weight:600;cursor:pointer;">Salin</button>
            </div>
          </div>

          <a id="paymentUrlBtn" href="#" target="_blank" rel="noopener" class="payment-url-btn">Lihat Instruksi Pembayaran &rarr;</a>
          <p style="font-size:12px;color:#9ca3af;margin-top:10px;">Link akan terbuka di tab baru (halaman pembayaran TriPay)</p>
        </div>
        <div style="text-align:center;">
          <button class="payment-back-btn" onclick="closePulsaModal(); location.reload();">Kembali ke Beranda</button>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
let currentPulsaItem = null;

function openPulsaCheckout(code) {
  const item = window._pulsaProductMap[code];
  if (!item) return;
  currentPulsaItem = item;

  // Reset ke step 1
  document.getElementById('checkoutStep1').style.display = 'block';
  document.getElementById('checkoutStep2').style.display = 'none';
  document.getElementById('checkoutError').style.display = 'none';
  document.getElementById('checkoutPhone').value = '';

  // Operator image (uses global OPERATOR_IMAGES)
  const imgSrc = OPERATOR_IMAGES[item.operator_id] || '';
  const imgEl  = document.getElementById('modalOperatorImg');
  if (imgSrc) { imgEl.src = imgSrc; imgEl.style.display = 'block'; }
  else { imgEl.style.display = 'none'; }

  document.getElementById('modalProductName').textContent  = item.name;
  document.getElementById('modalProductPrice').textContent = 'Rp ' + Number(item.price).toLocaleString('id-ID');
  document.getElementById('checkoutSubmitBtn').textContent = 'Bayar Rp ' + Number(item.price).toLocaleString('id-ID');

  loadPaymentChannels();

  document.getElementById('pulsaCheckoutModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closePulsaModal() {
  document.getElementById('pulsaCheckoutModal').style.display = 'none';
  document.body.style.overflow = '';
}

let _cachedChannels = null;

function populateChannelSelect(sel, channels) {
  sel.innerHTML = '<option value="">-- Pilih Metode Pembayaran --</option>';
  channels.forEach(ch => {
    const opt = document.createElement('option');
    opt.value = ch.code;
    opt.textContent = ch.name + (ch.fee_flat ? ' (+Rp ' + Number(ch.fee_flat).toLocaleString('id-ID') + ')' : '');
    sel.appendChild(opt);
  });
  sel.disabled = false;
}

function loadPaymentChannels() {
  const sel = document.getElementById('checkoutPaymentMethod');

  if (_cachedChannels) {
    populateChannelSelect(sel, _cachedChannels);
    return;
  }

  sel.innerHTML = '<option value="">Memuat metode pembayaran...</option>';
  sel.disabled = true;

  fetch('/api/payment-channels')
    .then(r => r.json())
    .then(channels => {
      _cachedChannels = Array.isArray(channels) ? channels : [];
      populateChannelSelect(sel, _cachedChannels);
    })
    .catch(() => {
      sel.innerHTML = '<option value="">Gagal memuat, coba lagi</option>';
      sel.disabled = false;
    });
}

function submitPulsaCheckout() {
  const phone   = document.getElementById('checkoutPhone').value.trim();
  const name    = document.getElementById('checkoutName').value.trim();
  const email   = document.getElementById('checkoutEmail').value.trim();
  const method  = document.getElementById('checkoutPaymentMethod').value;
  const errorEl = document.getElementById('checkoutError');

  errorEl.style.display = 'none';

  if (!phone || !/^08[0-9]{7,12}$/.test(phone)) { showCheckoutError('Nomor HP tujuan tidak valid. Gunakan format 08xx.'); return; }
  if (!name)                        { showCheckoutError('Nama pembeli wajib diisi.'); return; }
  if (!email)                       { showCheckoutError('Email wajib diisi.'); return; }
  if (!method)                      { showCheckoutError('Pilih metode pembayaran terlebih dahulu.'); return; }

  const btn = document.getElementById('checkoutSubmitBtn');
  btn.disabled    = true;
  btn.textContent = 'Memproses...';

  fetch('/api/transaksi', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({
      product_code:   currentPulsaItem.code,
      phone,
      payment_method: method,
      customer_name:  name,
      customer_email: email,
    }),
  })
  .then(r => r.json())
  .then(res => {
    if (!res.success) throw new Error(res.message || 'Gagal membuat transaksi');

    // Tampilkan step 2
    document.getElementById('checkoutStep1').style.display = 'none';
    document.getElementById('checkoutStep2').style.display = 'block';

    document.getElementById('paymentProductSummary').textContent = res.data.product_name + ' \u2192 ' + res.data.phone;
    document.getElementById('paymentAmountDisplay').textContent  = 'Rp ' + Number(res.data.amount).toLocaleString('id-ID');
    document.getElementById('paymentUrlBtn').href = res.data.payment_url || '#';

    // Tampilkan pay_code jika ada (VA / kode bayar)
    if (res.data.pay_code) {
      document.getElementById('payCodeLabel').textContent = res.data.payment_name || 'Kode Pembayaran';
      document.getElementById('payCodeValue').textContent = res.data.pay_code;
      document.getElementById('payCodeBox').style.display = 'block';
    }
  })
  .catch(err => {
    showCheckoutError(err.message);
    btn.disabled    = false;
    btn.textContent = 'Bayar Rp ' + Number(currentPulsaItem.price).toLocaleString('id-ID');
  });
}

function copyPayCode(btn) {
  const code = document.getElementById('payCodeValue').textContent;
  navigator.clipboard.writeText(code).then(() => {
    btn.textContent = 'Tersalin!';
    setTimeout(() => { btn.textContent = 'Salin'; }, 2000);
  });
}

function showCheckoutError(msg) {
  const el = document.getElementById('checkoutError');
  el.textContent     = msg;
  el.style.display   = 'block';
}

// Tutup modal ketika klik overlay
document.getElementById('pulsaCheckoutModal').addEventListener('click', function(e) {
  if (e.target === this) closePulsaModal();
});
</script>

</body>
</html>
