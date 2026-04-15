<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>E Store ID — Produk Digital Terpercaya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ filemtime(public_path('assets/css/dashboard.css')) }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
  <style>
  /* ---- PULSA CHECKOUT MODAL ---- */
  .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:1000;display:flex;align-items:center;justify-content:center;padding:16px;}
  .checkout-modal-box{background:#fff;border-radius:24px;width:100%;max-width:560px;max-height:90vh;overflow-y:auto;}
  .checkout-modal-header{display:flex;justify-content:space-between;align-items:center;padding:20px 24px 0;}
  .checkout-modal-header h3{font-size:18px;font-weight:700;margin:0;}
  .checkout-modal-close{background:none;border:none;font-size:24px;cursor:pointer;color:#6b7280;}
  .checkout-modal-body{padding:20px 24px 24px;}
  .order-summary-box{display:flex;align-items:center;gap:12px;background:#f9fafb;border-radius:12px;padding:14px;margin-bottom:20px;}
  .order-summary-box img{width:48px;height:48px;object-fit:contain;}
  .order-summary-name{font-weight:600;font-size:15px;}
  .order-summary-price{color:#6b7280;font-size:13px;margin-top:2px;}
  .checkout-field{margin-bottom:14px;}
  .checkout-field label{display:block;font-size:13px;font-weight:600;margin-bottom:5px;color:#374151;}
  .checkout-field input,.checkout-field select{width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:14px;outline:none;box-sizing:border-box;font-family:inherit;}
  .checkout-field input:focus,.checkout-field select:focus{border-color:#111;}
  .checkout-error{background:#fef2f2;color:#dc2626;border-radius:10px;padding:10px 14px;font-size:13px;margin-bottom:12px;display:none;}
  .checkout-submit-btn{width:100%;padding:14px;background:#111;color:#fff;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;margin-top:4px;font-family:inherit;}
  .checkout-submit-btn:disabled{opacity:0.6;cursor:not-allowed;}
  .payment-info-box{text-align:center;padding:8px 0 16px;}
  .payment-info-product{font-size:15px;color:#374151;margin-bottom:4px;}
  .payment-info-amount{font-size:28px;font-weight:800;color:#111827;margin:8px 0 20px;}
  .payment-url-btn{display:inline-block;padding:13px 28px;background:#111;color:#fff;border-radius:12px;font-weight:700;font-size:15px;text-decoration:none;}
  .payment-back-btn{background:none;border:none;color:#6b7280;font-size:13px;cursor:pointer;margin-top:12px;font-family:inherit;}
  </style>
</head>
<body>
@php $authUser = auth()->user(); @endphp

<!-- =============================================
     FLOATING NAVBAR
============================================= -->
<div class="navbar-wrap" id="navbarWrap">
  <nav class="navbar" id="navbar">

    <a href="{{ url('/') }}" class="nav-logo">
      <span class="nav-logo-name">E Store ID</span>
    </a>

    <div class="nav-links" id="navLinks">
      <span class="nav-pill" id="navPill"></span>
      <a href="#beranda" class="active">Beranda</a>
      <a href="#produk">Produk</a>
      <a href="{{ route('pulsa.page') }}">Beli Pulsa</a>
      <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
    </div>

    <div class="nav-actions">
@auth
        <a href="{{ route('cart.index') }}" class="nav-icon-btn" id="cartBtn" title="Keranjang">
          <i class="ph-bold ph-shopping-cart-simple"></i>
          <span class="cart-badge" id="cartBadge" style="display:none;">0</span>
        </a>

        <div class="nav-user" id="navUser">
          <div class="nav-user-avatar">{{ strtoupper(substr($authUser->name, 0, 1)) }}</div>
          <i class="ph-bold ph-caret-down nav-user-caret"></i>

          <div class="nav-user-menu" id="navUserMenu">
            {{-- Header: avatar + name + email --}}
            <div class="num-header">
              <div class="num-avatar">{{ strtoupper(substr($authUser->name, 0, 1)) }}</div>
              <div class="num-info">
                <h4 class="num-name">{{ Str::limit($authUser->name, 20) }}</h4>
                <p class="num-email">{{ Str::limit($authUser->email, 26) }}</p>
              </div>
            </div>

            {{-- Menu links --}}
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

            {{-- Logout --}}
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

<!-- Mobile Nav -->
<div class="nav-mobile" id="navMobile">
  <a href="#beranda">Beranda</a>
  <a href="#produk">Produk</a>
  <a href="{{ route('pulsa.page') }}">Beli Pulsa</a>
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

<!-- Flash Alerts -->
@if(session('success'))
  <div class="alert alert-success">
    <i class="ph-bold ph-check-circle"></i>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert alert-error">
    <i class="ph-bold ph-warning-circle"></i>
    {{ session('error') }}
  </div>
@endif

<!-- =============================================
     HERO + SEARCH
============================================= -->
<div id="beranda" class="hero-section">
  <div class="page-wrap">

    <!-- Hero Banner -->
    <div class="hero-banner">
      <div class="hero-bottom">
        <p class="hero-eyebrow">Spesialis Produk Digital</p>
        <h3 class="hero-headline">Temukan Produk Digital Terpercaya &amp; Bergaransi Hanya Di Sini.</h3>
      </div>
    </div>

    <!-- Search Row -->
    <div class="search-row">
      <h3 class="search-heading">Give All You Need</h3>
      <div class="search-box">
        <i class="ph-bold ph-magnifying-glass"></i>
        <input type="text" id="heroSearchInput" placeholder="Search on E Store ID...">
        <button class="search-btn" onclick="searchProducts(document.getElementById('heroSearchInput').value.toLowerCase().trim())">Search</button>
      </div>
    </div>

  </div>
</div>

<!-- =============================================
     PRODUCT SECTION
============================================= -->
<section class="product-section" id="produk">
  <div class="page-wrap">
    <div class="product-layout">

      <!-- Category Sidebar -->
      <aside class="category-sidebar">
        <h4 class="sb-label">Categories</h4>
        <nav class="sb-list">

          <a href="{{ url('/') }}?sort={{ $sortBy }}" class="sb-item {{ !$categoryId ? 'active' : '' }}">
            <div class="sb-item-left">
              <i class="ph-bold ph-squares-four sb-icon"></i>
              <span>Semua Produk</span>
            </div>
            @if(!$categoryId)
              <span class="sb-badge">{{ $products->total() }}</span>
            @endif
          </a>

          @foreach($categories as $cat)
            <a href="{{ url('/') }}?sort={{ $sortBy }}&category={{ $cat->id }}" class="sb-item {{ $categoryId == $cat->id ? 'active' : '' }}">
              <div class="sb-item-left">
                <i class="ph-bold ph-tag sb-icon"></i>
                <span>{{ $cat->name }}</span>
              </div>
              @if($categoryId == $cat->id)
                <span class="sb-badge">{{ $products->total() }}</span>
              @endif
            </a>
          @endforeach

        </nav>
      </aside>

      <!-- Product Content -->
      <div class="product-content">

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
            @endphp

            <a href="{{ route('product.show', $data->id) }}" class="product-card"
               data-category="{{ $data->category_id ?? '' }}"
               data-name="{{ strtolower($data->name) }}">
              <div class="product-img-wrap" style="background: {{ $grad }};">
                <span class="product-img-letter">{{ strtoupper(substr($data->name, 0, 1)) }}</span>
                <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" class="product-img-thumb"
                     onerror="this.style.display='none'">
                <span class="product-badge-cat">{{ $data->category->name ?? 'Digital' }}</span>
              </div>
              <div class="product-info">
                <h5 class="product-name">{{ $data->name }}</h5>
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
                <p class="product-price">Rp {{ number_format($data->price, 0, ',', '.') }}</p>
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
        </div>

        {{ $products->links('vendor.pagination.custom') }}

      </div><!-- /product-content -->

    </div><!-- /product-layout -->
  </div>
</section>

<!-- =============================================
     CAROUSEL — REKOMENDASI
============================================= -->
<section class="carousel-section">
  <div class="page-wrap">
    <div class="carousel-header">
      <h2 class="carousel-title">Produk Rekomendasi</h2>
      <div class="carousel-nav">
        <button class="carousel-btn" id="carouselPrev" aria-label="Previous">
          <i class="ph-bold ph-caret-left"></i>
        </button>
        <button class="carousel-btn" id="carouselNext" aria-label="Next">
          <i class="ph-bold ph-caret-right"></i>
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
              <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="product-img-thumb" onerror="this.style.display='none'">
              <span class="product-badge-cat">Digital</span>
            </div>
            <div class="product-info">
              <div class="product-name">{{ $item->name }}</div>
              <p class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     CTA / NEWSLETTER
============================================= -->
<section class="cta-section">
  <div class="page-wrap">
    <div class="cta-inner">
      <div class="cta-glow"></div>
      <div class="cta-left">
        <h2 class="cta-title">Mau Dapet Update Promo &amp; Info <em>Terbaru</em> Dari Kita?</h2>
        <div class="cta-input-row">
          <input type="email" class="cta-input" placeholder="Email kamu...">
          <button class="cta-send-btn" disabled>Kirim</button>
        </div>
      </div>
      <div class="cta-right">
        <div class="cta-desc-title">E Store ID Official</div>
        <p class="cta-desc">
          Gak mau ketinggalan penawaran menarik? Masukin email lo sekarang dan dapatkan notifikasi produk terbaru &amp; promo eksklusif langsung ke inbox kamu.
        </p>
        <div class="cta-perks">
          <div class="cta-perk"><span class="cta-perk-dot"></span>Update produk setiap minggu</div>
          <div class="cta-perk"><span class="cta-perk-dot"></span>Promo subscriber eksklusif</div>
          <div class="cta-perk"><span class="cta-perk-dot"></span>Bisa unsubscribe kapan saja</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =============================================
     FOOTER
============================================= -->
<footer class="site-footer">
  <div class="page-wrap">
    <div class="footer-top">

      <div class="footer-brand">
        <div class="footer-brand-logo">
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
    </div>
  </div>
</footer>

<!-- WhatsApp Float -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<!-- Login Modal -->
<div class="modal-backdrop" id="loginModal">
  <div class="modal-box">
    <button class="modal-close-btn" onclick="closeLoginModal()">
      <i class="ph-bold ph-x"></i>
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

<!-- =============================================
     JAVASCRIPT
============================================= -->
<script>
// ===== NAVBAR SCROLL =====
const navbarWrap = document.getElementById('navbarWrap');
window.addEventListener('scroll', () => {
  navbarWrap.classList.toggle('scrolled', window.scrollY > 20);
}, { passive: true });

// ===== ANIMATED NAV PILL =====
(function() {
  const navLinks = document.getElementById('navLinks');
  const pill = document.getElementById('navPill');
  if (!navLinks || !pill) return;

  function movePillTo(el) {
    const parentRect = navLinks.getBoundingClientRect();
    const elRect = el.getBoundingClientRect();
    pill.style.left   = (elRect.left - parentRect.left) + 'px';
    pill.style.top    = (elRect.top  - parentRect.top)  + 'px';
    pill.style.width  = elRect.width  + 'px';
    pill.style.height = elRect.height + 'px';
  }

  const links = navLinks.querySelectorAll('a');
  const activeLink = navLinks.querySelector('a.active');
  if (activeLink) {
    pill.style.transition = 'none';
    movePillTo(activeLink);
    requestAnimationFrame(() => { pill.style.transition = ''; });
  }

  links.forEach(link => {
    link.addEventListener('click', function() {
      links.forEach(l => l.classList.remove('active'));
      this.classList.add('active');
      movePillTo(this);
    });
    link.addEventListener('mouseenter', function() { movePillTo(this); });
    link.addEventListener('mouseleave', function() {
      const current = navLinks.querySelector('a.active');
      if (current) movePillTo(current);
    });
  });
})();

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
    if (navUserMenu.contains(e.target)) return;
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
  btn.disabled = true; btn.textContent = '...';
  try {
    const res = await fetch(`/cart/add/${productId}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ quantity: 1 })
    });
    const data = await res.json();
    if (data.success || res.ok) {
      showToast('Produk ditambahkan ke keranjang', 'success');
      updateCartCount();
      const cartBtn = document.getElementById('cartBtn');
      if (cartBtn) { cartBtn.style.transform = 'scale(1.25)'; setTimeout(() => cartBtn.style.transform = '', 220); }
    } else { showToast(data.message || 'Gagal menambahkan ke keranjang', 'error'); }
  } catch(e) { showToast('Terjadi kesalahan, coba lagi', 'error'); }
  finally { btn.disabled = false; btn.textContent = 'Tambah Keranjang'; }
}

// ===== TOAST =====
function showToast(msg, type = 'success') {
  const toast = document.getElementById('cartToast');
  toast.textContent = msg;
  toast.className = `cart-toast ${type} show`;
  setTimeout(() => toast.classList.remove('show'), 3000);
}

// ===== LOGIN MODAL =====
function showLoginModal() { document.getElementById('loginModal').classList.add('open'); document.body.style.overflow = 'hidden'; }
function closeLoginModal() { document.getElementById('loginModal').classList.remove('open'); document.body.style.overflow = ''; }
document.getElementById('loginModal').addEventListener('click', function(e) { if (e.target === this) closeLoginModal(); });

// ===== SEARCH / FILTER =====
function searchProducts(q) {
  const cards = document.querySelectorAll('#productGrid .product-card');
  let visible = 0;
  cards.forEach(card => {
    if (card.id === 'noResults') return;
    const nameMatch = !q || card.dataset.name?.includes(q);
    card.style.display = nameMatch ? '' : 'none';
    if (nameMatch) visible++;
  });
  document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

// ===== CAROUSEL =====
const track = document.getElementById('carouselTrack');
let carouselIdx = 0;
function getCardW() { const c = track?.querySelector('.product-card'); return c ? c.offsetWidth + 20 : 0; }
function maxIdx()   { return Math.max(0, (track?.querySelectorAll('.product-card').length || 0) - 3); }
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
  searchProducts('');

  const heroInput = document.getElementById('heroSearchInput');
  if (heroInput) {
    heroInput.addEventListener('keydown', e => {
      if (e.key === 'Enter') searchProducts(heroInput.value.toLowerCase().trim());
    });
  }
});
</script>


</body>
</html>
