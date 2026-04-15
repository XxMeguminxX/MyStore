<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $product->name }} — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ filemtime(public_path('assets/css/dashboard.css')) }}">
  <link rel="stylesheet" href="{{ asset('assets/css/product-detail.css') }}?v={{ filemtime(public_path('assets/css/product-detail.css')) }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/duotone/style.css">
</head>
<body>

<!-- ============================================================
     NAVBAR
============================================================ -->
<div class="navbar-wrap" id="navbarWrap">
  <nav class="navbar" id="navbar">

    <a href="{{ url('/') }}" class="nav-logo">
      <span class="nav-logo-name">E Store ID</span>
    </a>

    <div class="nav-links" id="navLinks">
      <span class="nav-pill" id="navPill"></span>
      <a href="{{ url('/') }}">Beranda</a>
      <a href="{{ url('/') }}#produk" class="active">Produk</a>
      <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
    </div>

    <div class="nav-actions">
      @auth
        <a href="{{ route('cart.index') }}" class="nav-icon-btn" id="cartBtn" title="Keranjang">
          <i class="ph-bold ph-shopping-cart-simple"></i>
          <span class="cart-badge" id="cartBadge" style="display:none;">0</span>
        </a>

        <div class="nav-user" id="navUser">
          <div class="nav-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
          <i class="ph-bold ph-caret-down nav-user-caret"></i>

          <div class="nav-user-menu" id="navUserMenu">
            <div class="num-header">
              <div class="num-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
              <div class="num-info">
                <h4 class="num-name">{{ Str::limit(auth()->user()->name, 20) }}</h4>
                <p class="num-email">{{ Str::limit(auth()->user()->email, 26) }}</p>
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
      @else
        <a href="{{ route('login') }}" class="nav-btn-login">Masuk</a>
        <a href="{{ route('register') }}" class="nav-btn-register">Daftar Gratis</a>
      @endauth
    </div>

    <button class="nav-hamburger" id="hamburger" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>

  </nav>
</div>

<!-- Mobile nav -->
<div class="nav-mobile" id="navMobile">
  <a href="{{ url('/') }}">Beranda</a>
  <a href="{{ url('/') }}#produk">Produk</a>
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

<!-- Page top spacer (clears fixed navbar: 16px top + 72px height + 16px gap) -->
<div class="pd-nav-spacer"></div>

<!-- Flash alerts -->
@if(session('success'))
  <div class="flash-alert flash-success">
    <i class="ph-bold ph-check-circle"></i>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="flash-alert flash-error">
    <i class="ph-bold ph-warning-circle"></i>
    {{ session('error') }}
  </div>
@endif

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
  <a href="{{ route('dashboard') }}" class="breadcrumb-link">
    <i class="ph-bold ph-house"></i>
    Beranda
  </a>
  <i class="ph-bold ph-caret-right breadcrumb-sep"></i>
  <span class="breadcrumb-current">{{ $product->name }}</span>
</div>

<div class="pd-page">

  <!-- ========== HERO: TWO COLUMN ========== -->
  <div class="pd-hero">

    <!-- LEFT: Image -->
    <div class="pd-gallery">
      <div class="pd-img-wrap">
        <span class="pd-sku-badge">SKU #{{ $product->id }}</span>
        <img class="pd-main-img" id="pd-main-img"
             src="{{ asset($product->image) }}"
             alt="{{ $product->name }}"
             onerror="this.style.display='none'">
        <div class="pd-img-zoom-hint">
          <i class="ph-bold ph-magnifying-glass-plus"></i>
          Zoom
        </div>
      </div>
    </div>

    <!-- RIGHT: Info -->
    <div class="pd-info">
      <div class="pd-info-card">

        <!-- Title -->
        <h1 class="pd-title">{{ $product->name }}</h1>

        <!-- Price -->
        <div class="pd-price-wrap">
          <span class="pd-price">Rp {{ number_format($product->price, 0, '', '.') }}</span>
        </div>

        <!-- Stock -->
        <div class="pd-stock-wrap">
          @if($product->stock > 10)
            <div class="pd-stock pd-stock-ok">
              <span class="pd-stock-dot"></span>
              Ready Stock — {{ $product->stock }} unit tersedia
            </div>
          @elseif($product->stock > 0)
            <div class="pd-stock pd-stock-low">
              <i class="ph-bold ph-warning"></i>
              Tersisa <strong>{{ $product->stock }} unit</strong> — Segera habis!
            </div>
            <div class="pd-urgency-bar">
              <div class="pd-urgency-fill" style="width: {{ min(100, ($product->stock / 20) * 100) }}%"></div>
            </div>
          @else
            <div class="pd-stock pd-stock-out">
              <i class="ph-bold ph-x-circle"></i>
              Stok Habis
            </div>
          @endif
        </div>

        @if($product->isInStock())
        <!-- Quantity -->
        <div class="pd-qty-row" id="product-qty-container" data-max-qty="{{ min($product->stock, 100) }}">
          <span class="pd-qty-label">Jumlah:</span>
          <div class="pd-qty-selector">
            <button type="button" id="qty-decrease" class="pd-qty-btn" data-action="decrease" aria-label="Kurangi">−</button>
            <input type="number" id="product-detail-quantity" class="pd-qty-input" value="1" min="1" max="{{ min($product->stock, 100) }}" readonly>
            <button type="button" id="qty-increase" class="pd-qty-btn" data-action="increase" aria-label="Tambah">+</button>
          </div>
          <span class="pd-qty-hint">Maks. {{ min($product->stock, 100) }}/transaksi</span>
        </div>
        @endif

        <!-- CTA Buttons -->
        <div class="pd-actions">
          @auth
            @if($product->isInStock())
              <a href="{{ route('beli', ['id' => $product->id]) }}" id="btn-beli-sekarang" class="pd-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Beli Sekarang
              </a>
              <button type="button" class="pd-btn-secondary" id="btn-tambah-keranjang">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                Masukkan Keranjang
              </button>
            @else
              <button type="button" class="pd-btn-disabled" disabled>
                <i class="ph-bold ph-x-circle"></i>
                Stok Habis
              </button>
            @endif
          @else
            @if($product->isInStock())
              <a href="{{ route('login') }}" class="pd-btn-primary">
                <i class="ph-bold ph-sign-in"></i>
                Login untuk Membeli
              </a>
            @else
              <button type="button" class="pd-btn-disabled" disabled>Stok Habis</button>
            @endif
          @endauth
        </div>

        <!-- Trust Badges -->
        <div class="pd-trust-grid">
          <div class="pd-trust-item">
            <div class="pd-trust-icon">
              <i class="ph-bold ph-lock"></i>
            </div>
            <div>
              <p class="pd-trust-title">Pembayaran Aman</p>
              <p class="pd-trust-sub">SSL & enkripsi penuh</p>
            </div>
          </div>
          <div class="pd-trust-item">
            <div class="pd-trust-icon">
              <i class="ph-bold ph-shield-check"></i>
            </div>
            <div>
              <p class="pd-trust-title">Garansi 100%</p>
              <p class="pd-trust-sub">Refund jika gagal</p>
            </div>
          </div>
          <div class="pd-trust-item">
            <div class="pd-trust-icon">
              <i class="ph-bold ph-lightning"></i>
            </div>
            <div>
              <p class="pd-trust-title">Aktivasi Instan</p>
              <p class="pd-trust-sub">Otomatis setelah bayar</p>
            </div>
          </div>
        </div>

      </div><!-- /pd-info-card -->
    </div><!-- /pd-info -->
  </div><!-- /pd-hero -->

  <!-- ========== DESCRIPTION ========== -->
  <div class="pd-desc-section">
    <div class="pd-section-header">
      <div class="pd-section-bar"></div>
      <span class="pd-section-label">Deskripsi Produk</span>
    </div>
    <div class="pd-desc-content">{!! nl2br(e($product->description)) !!}</div>
  </div>

  <!-- ========== RELATED PRODUCTS ========== -->
  @if($relatedProducts->isNotEmpty())
  <div class="pd-related-section">
    <div class="pd-related-header">
      <div>
        <h2 class="pd-related-title">Cek Juga Yang Ini</h2>
        <p class="pd-related-subtitle">Lengkapi kebutuhan digitalmu dalam satu checkout.</p>
      </div>
      <a href="{{ route('dashboard') }}" class="pd-related-see-all">
        Katalog Utama
        <i class="ph-bold ph-caret-right"></i>
      </a>
    </div>
    <div class="pd-related-track">
      @foreach($relatedProducts as $related)
      <a href="{{ route('product.show', $related->id) }}" class="pd-related-card">
        <div class="pd-related-img-wrap">
          <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="pd-related-img">
          @if(!$related->isInStock())
          <div class="pd-related-soldout">Habis</div>
          @endif
        </div>
        <div class="pd-related-info">
          <p class="pd-related-name">{{ $related->name }}</p>
          <p class="pd-related-price">Rp {{ number_format($related->price, 0, '', '.') }}</p>
          <div class="pd-related-stock-row">
            @if($related->stock > 10)
              <span class="pd-related-stock-ok">Ready</span>
            @elseif($related->stock > 0)
              <span class="pd-related-stock-low">Sisa {{ $related->stock }}</span>
            @else
              <span class="pd-related-stock-out">Habis</span>
            @endif
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
  @endif

</div><!-- /pd-page -->

<!-- WhatsApp -->
<a href="https://wa.me/6285739188906" class="wa-float" target="_blank" rel="noopener" title="Hubungi via WhatsApp">
  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<!-- Image Zoom Overlay -->
<div class="pd-zoom-overlay" id="pdZoomOverlay">
  <button class="pd-zoom-close" id="pdZoomClose" aria-label="Tutup">
    <i class="ph-bold ph-x"></i>
  </button>
  <img class="pd-zoom-img" id="pdZoomImg" src="" alt="">
</div>

<script>
(function() {
  var productId   = {{ $product->id }};
  var productName = {!! json_encode($product->name) !!};
  var maxQty      = {{ $product->isInStock() ? min($product->stock, 100) : 1 }};

  /* ---- Quantity ---- */
  function getQtyInput() { return document.getElementById('product-detail-quantity'); }
  function getQty() {
    var el = getQtyInput();
    return el ? Math.min(maxQty, Math.max(1, parseInt(el.value, 10) || 1)) : 1;
  }
  function setQty(val) {
    var el = getQtyInput();
    if (!el) return;
    val = Math.min(maxQty, Math.max(1, val));
    el.value = val;
    var dec = document.getElementById('qty-decrease');
    var inc = document.getElementById('qty-increase');
    if (dec) dec.disabled = (val <= 1);
    if (inc) inc.disabled = (val >= maxQty);
  }

  document.addEventListener('click', function(ev) {
    var btn = ev.target && ev.target.closest && ev.target.closest('.pd-qty-row button.pd-qty-btn[data-action]');
    if (!btn) return;
    ev.preventDefault();
    var action = btn.getAttribute('data-action');
    var v = parseInt((getQtyInput() || {}).value, 10) || 1;
    setQty(action === 'increase' ? v + 1 : v - 1);
  }, true);

  /* ---- Beli Sekarang ---- */
  var btnBeli = document.getElementById('btn-beli-sekarang');
  if (btnBeli) {
    btnBeli.addEventListener('click', function(e) {
      e.preventDefault();
      var href = btnBeli.getAttribute('href') || '';
      window.location.href = href + (href.indexOf('?') >= 0 ? '&' : '?') + 'quantity=' + getQty();
    });
  }

  /* ---- Add to Cart ---- */
  async function addToCart() {
    var btn = document.getElementById('btn-tambah-keranjang');
    if (btn) { btn.disabled = true; btn.textContent = 'Menambahkan...'; }
    var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    try {
      var response = await fetch('/cart/add/' + productId, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ quantity: getQty() })
      });
      var result = await response.json();
      if (response.ok && result.success) {
        updateCartCount();
        showNotification(productName + ' berhasil ditambahkan ke keranjang!', 'success');
      } else {
        showNotification(result.message || 'Gagal menambahkan ke keranjang', 'error');
      }
    } catch (err) {
      showNotification('Terjadi kesalahan koneksi', 'error');
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<i class="ph-bold ph-shopping-cart"></i> Masukkan Keranjang';
      }
    }
  }

  var btnCart = document.getElementById('btn-tambah-keranjang');
  if (btnCart) btnCart.addEventListener('click', addToCart);

  /* ---- Cart Count ---- */
  async function updateCartCount() {
    try {
      var r   = await fetch('/cart/count');
      var res = await r.json();
      var el  = document.getElementById('cartBadge');
      if (el) {
        el.textContent   = res.count > 9 ? '9+' : res.count;
        el.style.display = res.count > 0 ? 'flex' : 'none';
      }
    } catch(e) {}
  }

  /* ---- Notification ---- */
  function showNotification(msg, type) {
    var old = document.querySelector('.pd-notification');
    if (old) old.remove();
    var n = document.createElement('div');
    n.className = 'pd-notification pd-notification-' + type;
    n.innerHTML = (type === 'success'
      ? '<i class="ph-bold ph-check-circle"></i>'
      : '<i class="ph-bold ph-warning-circle"></i>'
    ) + ' ' + msg;
    document.body.appendChild(n);
    setTimeout(function() { n.classList.add('show'); }, 10);
    setTimeout(function() { n.classList.remove('show'); setTimeout(function() { n.remove(); }, 300); }, 3500);
  }

  /* ---- Image Zoom ---- */
  var mainImg  = document.getElementById('pd-main-img');
  var overlay  = document.getElementById('pdZoomOverlay');
  var zoomImg  = document.getElementById('pdZoomImg');
  var closeBtn = document.getElementById('pdZoomClose');

  if (mainImg && overlay && zoomImg) {
    mainImg.addEventListener('click', function() {
      zoomImg.src = mainImg.src;
      overlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
    closeBtn && closeBtn.addEventListener('click', closeZoom);
    overlay.addEventListener('click', function(e) { if (e.target === overlay) closeZoom(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeZoom(); });
  }
  function closeZoom() {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  /* ---- Navbar scroll ---- */
  var navbarWrap = document.getElementById('navbarWrap');
  if (navbarWrap) {
    window.addEventListener('scroll', function() {
      navbarWrap.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
  }

  // NAV PILL
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
    if (activeLink) {
      pill.style.transition = 'none';
      movePillTo(activeLink);
      requestAnimationFrame(() => { pill.style.transition = ''; });
    }
    links.forEach(link => {
      link.addEventListener('mouseenter', () => movePillTo(link));
      link.addEventListener('mouseleave', () => { if (activeLink) movePillTo(activeLink); });
    });
  })();

  /* ---- Navbar interactions ---- */
  var navUser    = document.getElementById('navUser');
  var navMenu    = document.getElementById('navUserMenu');
  var hamburger  = document.getElementById('hamburger');
  var navMobile  = document.getElementById('navMobile');

  if (navUser && navMenu) {
    navUser.addEventListener('click', function(e) {
      e.stopPropagation();
      if (navMenu.contains(e.target)) return;
      navMenu.classList.toggle('open');
    });
    document.addEventListener('click', function(e) {
      if (!navUser.contains(e.target)) navMenu.classList.remove('open');
    });
  }
  if (hamburger && navMobile) {
    hamburger.addEventListener('click', function() {
      hamburger.classList.toggle('open');
      navMobile.classList.toggle('open');
      document.body.style.overflow = navMobile.classList.contains('open') ? 'hidden' : '';
    });
    navMobile.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        hamburger.classList.remove('open');
        navMobile.classList.remove('open');
        document.body.style.overflow = '';
      });
    });
  }

  /* ---- Init ---- */
  document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    setQty(1);
  });
  if (document.readyState !== 'loading') { updateCartCount(); setQty(1); }

})();
</script>

</body>
</html>
