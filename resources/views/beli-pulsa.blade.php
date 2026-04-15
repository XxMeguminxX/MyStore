<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Beli Pulsa — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ filemtime(public_path('assets/css/dashboard.css')) }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">

  <style>
  /* ============================================================
     PULSA PAGE STYLES
  ============================================================ */
  ::-webkit-scrollbar { width: 5px; }
  ::-webkit-scrollbar-track { background: #f1f1f1; }
  ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
  ::-webkit-scrollbar-thumb:hover { background: #aaa; }

  /* Page layout */
  .pulsa-page {
    padding-top: 112px;
    padding-bottom: 80px;
    min-height: 100vh;
  }
  .pulsa-header {
    text-align: center;
    margin-bottom: 48px;
    animation: fadeUp 0.4s ease-out;
  }
  .pulsa-header .eyebrow {
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.5em;
    color: #9CA3AF; margin-bottom: 14px;
    font-style: italic; display: block;
  }
  .pulsa-header h1 {
    font-size: clamp(32px, 6vw, 56px);
    font-weight: 800; letter-spacing: -0.05em;
    color: #111; line-height: 1;
    margin-bottom: 16px;
  }
  .pulsa-header p {
    font-size: 14px; font-weight: 500;
    color: #9CA3AF; max-width: 420px; margin: 0 auto;
  }

  /* Main card */
  .pulsa-card {
    background: #fff;
    border-radius: 48px;
    border: 1.5px solid #F0F0F0;
    box-shadow: 0 24px 64px rgba(0,0,0,0.06);
    padding: clamp(28px, 5vw, 48px);
    max-width: 800px;
    margin: 0 auto;
    animation: fadeUp 0.4s ease-out 0.1s both;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Field wrapper */
  .pulsa-field { margin-bottom: 36px; }
  .pulsa-field:last-child { margin-bottom: 0; }
  .pulsa-label {
    display: block;
    font-size: 9px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.3em;
    color: #9CA3AF; margin-bottom: 16px; padding-left: 4px;
  }

  /* Phone input */
  .phone-wrap {
    position: relative;
    display: flex;
    align-items: center;
  }
  #phoneInput {
    width: 100%;
    background: #F9FAFB;
    border: none;
    border-radius: 28px;
    padding: 24px 200px 24px 32px;
    font-size: clamp(18px, 3vw, 26px);
    font-family: inherit;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #111;
    outline: none;
    transition: box-shadow 0.2s;
  }
  #phoneInput:focus {
    box-shadow: 0 0 0 2.5px #111;
  }
  #phoneInput::placeholder { color: #D1D5DB; letter-spacing: 0; }
  .provider-badge {
    position: absolute; right: 16px;
    background: #fff;
    border: 1.5px solid #E5E7EB;
    border-radius: 16px;
    padding: 8px 16px;
    display: flex; align-items: center; gap: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    pointer-events: none;
  }
  .badge-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: #E5E7EB;
    transition: background 0.2s;
    flex-shrink: 0;
  }
  .badge-dot.active { background: #111; }
  .badge-text {
    font-size: 10px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.15em;
    color: #9CA3AF;
    transition: color 0.2s;
  }
  .badge-text.active { color: #111; }

  /* Operator grid */
  .operator-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 10px;
  }
  .operator-card {
    cursor: pointer;
    padding: 16px 12px;
    border-radius: 24px;
    border: 2px solid transparent;
    background: #F9FAFB;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 10px;
    transition: all 0.2s;
  }
  .operator-card:hover {
    background: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }
  .operator-card.selected {
    border-color: #111;
    background: #fff;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
  }
  .operator-logo {
    width: 44px; height: 44px; border-radius: 50%;
    background: #F3F4F6;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex-shrink: 0;
  }
  .operator-logo img {
    width: 100%; height: 100%; object-fit: contain; padding: 6px;
  }
  .operator-logo-letter {
    font-size: 14px; font-weight: 800; color: #6B7280;
  }
  .operator-name {
    font-size: 10px; font-weight: 700;
    color: #9CA3AF; text-align: center;
    line-height: 1.3; transition: color 0.2s;
  }
  .operator-card:hover .operator-name,
  .operator-card.selected .operator-name { color: #111; }

  /* Skeleton loader for operators */
  .op-skeleton {
    height: 86px; border-radius: 24px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
  }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

  /* Nominal grid */
  .nominal-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
  .nominal-card {
    cursor: pointer;
    background: #F9FAFB;
    border: 2px solid transparent;
    border-radius: 32px;
    padding: 24px 16px;
    text-align: center;
    transition: all 0.22s;
  }
  .nominal-card:hover { background: #F3F4F6; }
  .nominal-card.selected {
    background: #111;
    border-color: #111;
    color: #fff;
    transform: scale(1.04);
    box-shadow: 0 12px 32px rgba(0,0,0,0.2);
  }
  .nominal-type {
    font-size: 9px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.2em;
    color: #9CA3AF; margin-bottom: 6px;
    transition: color 0.2s;
  }
  .nominal-card.selected .nominal-type { color: rgba(255,255,255,0.5); }
  .nominal-amount {
    font-size: clamp(17px, 2.5vw, 22px);
    font-weight: 800; color: #111;
    margin-bottom: 10px;
    transition: color 0.2s;
  }
  .nominal-card.selected .nominal-amount { color: #fff; }
  .nominal-price {
    font-size: 11px; font-weight: 700;
    color: #D1D5DB; font-style: italic;
    transition: color 0.2s;
  }
  .nominal-card.selected .nominal-price { color: rgba(255,255,255,0.4); }

  /* Nominal skeleton */
  .nom-skeleton {
    height: 100px; border-radius: 32px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e8e8e8 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
  }

  /* Nominals empty state */
  .nominal-empty {
    grid-column: 1/-1; text-align: center;
    padding: 40px 0; color: #9CA3AF;
    font-size: 14px; font-weight: 500;
  }

  /* Confirm button */
  .confirm-btn {
    width: 100%;
    background: #111;
    color: #fff;
    border: none;
    border-radius: 24px;
    padding: 22px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.3em;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    margin-top: 8px;
  }
  .confirm-btn:hover  { background: #222; transform: translateY(-1px); box-shadow: 0 12px 40px rgba(0,0,0,0.22); }
  .confirm-btn:active { transform: scale(0.99); }
  .confirm-btn:disabled {
    opacity: 0.35; cursor: not-allowed;
    transform: none; box-shadow: none;
  }

  /* Section divider */
  .pulsa-divider {
    height: 1px; background: #F3F4F6;
    margin: 32px 0;
  }

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

  @media (max-width: 600px) {
    .nominal-grid { grid-template-columns: repeat(2, 1fr); }
    .operator-grid { grid-template-columns: repeat(3, 1fr); }
    #phoneInput { padding: 20px 160px 20px 24px; font-size: 18px; }
    .pulsa-card { border-radius: 36px; }
    .pulsa-header h1 { font-size: 36px; }
  }
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
      <a href="{{ url('/') }}">Beranda</a>
      <a href="{{ url('/#produk') }}">Produk</a>
      <a href="{{ route('pulsa.page') }}" class="active">Beli Pulsa</a>
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
            <div class="num-header">
              <div class="num-avatar">{{ strtoupper(substr($authUser->name, 0, 1)) }}</div>
              <div class="num-info">
                <h4 class="num-name">{{ Str::limit($authUser->name, 20) }}</h4>
                <p class="num-email">{{ Str::limit($authUser->email, 26) }}</p>
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

<!-- Mobile Nav -->
<div class="nav-mobile" id="navMobile">
  <a href="{{ url('/') }}">Beranda</a>
  <a href="{{ url('/#produk') }}">Produk</a>
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

<!-- =============================================
     MAIN CONTENT
============================================= -->
<main class="pulsa-page">
  <div class="page-wrap">

    <!-- Header -->
    <div class="pulsa-header">
      <span class="eyebrow">Service Recharge</span>
      <h1>Isi Pulsa Kilat</h1>
      <p>Beli pulsa tanpa ribet, proses instan. Semua operator tersedia.</p>
    </div>

    <!-- Main Card -->
    <div class="pulsa-card">

      <!-- 1. Phone Number -->
      <div class="pulsa-field">
        <label class="pulsa-label">Nomor Handphone</label>
        <div class="phone-wrap">
          <input id="phoneInput" type="tel" inputmode="numeric"
                 placeholder="Contoh: 08123456789"
                 oninput="onPhoneInput(this.value)"
                 maxlength="16">
          <div class="provider-badge" id="providerBadge">
            <span class="badge-dot" id="badgeDot"></span>
            <span class="badge-text" id="badgeText">Detecting</span>
          </div>
        </div>
      </div>

      <div class="pulsa-divider"></div>

      <!-- 2. Operator Selection -->
      <div class="pulsa-field">
        <label class="pulsa-label">Pilih Operator</label>
        <div class="operator-grid" id="operatorGrid">
          <!-- Skeleton -->
          <div class="op-skeleton"></div>
          <div class="op-skeleton" style="animation-delay:.1s"></div>
          <div class="op-skeleton" style="animation-delay:.2s"></div>
          <div class="op-skeleton" style="animation-delay:.3s"></div>
          <div class="op-skeleton" style="animation-delay:.4s"></div>
        </div>
      </div>

      <div class="pulsa-divider"></div>

      <!-- 3. Nominal Selection -->
      <div class="pulsa-field" id="nominalSection">
        <label class="pulsa-label">Pilih Nominal</label>
        <div class="nominal-grid" id="nominalGrid">
          <div class="nominal-empty">Pilih operator terlebih dahulu.</div>
        </div>
      </div>

      <div class="pulsa-divider"></div>

      <!-- 4. Confirm Button -->
      <button class="confirm-btn" id="confirmBtn" disabled onclick="openCheckoutFromPage()">
        Konfirmasi Pembayaran
      </button>

    </div><!-- /pulsa-card -->
  </div>
</main>

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
    <p class="modal-desc">Masuk untuk melanjutkan pembelian pulsa.</p>
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
     PULSA CHECKOUT MODAL
============================================= -->
<div id="pulsaCheckoutModal" class="modal-overlay" style="display:none;">
  <div class="checkout-modal-box">

    <div id="checkoutStep1">
      <div class="checkout-modal-header">
        <h3>Konfirmasi Pembelian</h3>
        <button class="checkout-modal-close" onclick="closeModal()" aria-label="Tutup">&times;</button>
      </div>
      <div class="checkout-modal-body">
        <div class="order-summary-box">
          <img id="modalOperatorImg" src="" alt="Operator" style="display:none;">
          <div>
            <div class="order-summary-name" id="modalProductName"></div>
            <div class="order-summary-price" id="modalProductPrice"></div>
          </div>
        </div>
        <form id="pulsaCheckoutForm" onsubmit="return false;">
          <div class="checkout-field">
            <label>Nomor HP Tujuan</label>
            <input type="tel" id="checkoutPhone" placeholder="08xxx" autocomplete="tel">
          </div>
          <div class="checkout-field">
            <label>Nama Pembeli</label>
            <input type="text" id="checkoutName" value="{{ $authUser ? $authUser->name : '' }}" readonly style="background:#f3f4f6;cursor:not-allowed;color:#6b7280;">
          </div>
          <div class="checkout-field">
            <label>Email</label>
            <input type="email" id="checkoutEmail" value="{{ $authUser ? $authUser->email : '' }}" readonly style="background:#f3f4f6;cursor:not-allowed;color:#6b7280;">
          </div>
          <div class="checkout-field">
            <label>Metode Pembayaran</label>
            <select id="checkoutPaymentMethod" disabled>
              <option value="">Memuat metode pembayaran...</option>
            </select>
          </div>
          <div id="checkoutError" class="checkout-error"></div>
          <button type="button" id="checkoutSubmitBtn" class="checkout-submit-btn" onclick="submitCheckout()">Bayar</button>
        </form>
      </div>
    </div>

    <div id="checkoutStep2" style="display:none;">
      <div class="checkout-modal-header">
        <h3>Selesaikan Pembayaran</h3>
        <button class="checkout-modal-close" onclick="closeModal()" aria-label="Tutup">&times;</button>
      </div>
      <div class="checkout-modal-body">
        <div class="payment-info-box">
          <div class="payment-info-product" id="paymentProductSummary"></div>
          <div class="payment-info-amount" id="paymentAmountDisplay"></div>
          <div id="payCodeBox" style="display:none;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:10px;padding:14px 18px;margin-bottom:18px;text-align:left;">
            <div style="font-size:12px;color:#6b7280;margin-bottom:4px;" id="payCodeLabel">Kode Pembayaran</div>
            <div style="display:flex;align-items:center;gap:10px;">
              <span id="payCodeValue" style="font-size:22px;font-weight:700;letter-spacing:2px;color:#111;"></span>
              <button onclick="copyPayCode(this)" style="padding:4px 10px;border:1.5px solid #111;border-radius:6px;background:#fff;color:#111;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Salin</button>
            </div>
          </div>
          <a id="paymentUrlBtn" href="#" target="_blank" rel="noopener" class="payment-url-btn">Lihat Instruksi Pembayaran &rarr;</a>
          <p style="font-size:12px;color:#9ca3af;margin-top:10px;">Link akan terbuka di tab baru (halaman pembayaran TriPay)</p>
        </div>
        <div style="text-align:center;">
          <button class="payment-back-btn" onclick="closeModal(); location.reload();">Kembali</button>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- =============================================
     JAVASCRIPT
============================================= -->
<script>
// =============================================
// OPERATOR IMAGES & COLORS (same as dashboard)
// =============================================
const OPERATOR_IMAGES = {
  'S':   '{{ asset("assets/img/operators/telkomsel.png") }}',
  'X':   '{{ asset("assets/img/operators/xl.png") }}',
  'AX':  '{{ asset("assets/img/operators/axis.png") }}',
  'I':   '{{ asset("assets/img/operators/indosat.png") }}',
  'T':   '{{ asset("assets/img/operators/tri.png") }}',
  'SM':  '{{ asset("assets/img/operators/smartfren.png") }}',
  'BYU': '{{ asset("assets/img/operators/byu.png") }}',
};
const OPERATOR_BG = {
  'S': '#FEE2E2', 'X': '#DBEAFE', 'AX': '#F3F4F6',
  'I': '#FEF3C7', 'T': '#EDE9FE', 'SM': '#FFEDD5', 'BYU': '#D1FAE5',
};

// Phone prefix → operator_id mapping
const PREFIX_TO_OP = {
  '0811':'S','0812':'S','0813':'S','0821':'S','0822':'S','0852':'S','0853':'S','0823':'S','0851':'S',
  '0814':'I','0815':'I','0816':'I','0855':'I','0856':'I','0857':'I','0858':'I',
  '0817':'X','0818':'X','0819':'X','0859':'X','0877':'X','0878':'X',
  '0831':'AX','0832':'AX','0833':'AX','0838':'AX',
  '0895':'T','0896':'T','0897':'T','0898':'T','0899':'T',
  '0881':'SM','0882':'SM','0883':'SM','0884':'SM','0885':'SM',
  '0886':'SM','0887':'SM','0888':'SM','0889':'SM',
  '0895':'BYU',
};

// =============================================
// STATE
// =============================================
let allProducts     = [];
let allOperators    = [];
let selectedOpId    = null;
let selectedProduct = null;
let productMap      = {};

// =============================================
// NAVBAR SCROLL
// =============================================
const navbarWrap = document.getElementById('navbarWrap');
window.addEventListener('scroll', () => {
  navbarWrap.classList.toggle('scrolled', window.scrollY > 20);
}, { passive: true });

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

// HAMBURGER
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

// USER DROPDOWN
const navUser = document.getElementById('navUser');
const navUserMenu = document.getElementById('navUserMenu');
if (navUser && navUserMenu) {
  navUser.addEventListener('click', e => {
    e.stopPropagation();
    if (navUserMenu.contains(e.target)) return;
    navUserMenu.classList.toggle('open');
  });
  document.addEventListener('click', e => { if (!navUser.contains(e.target)) navUserMenu.classList.remove('open'); });
}

// CART COUNT
async function updateCartCount() {
  try {
    const res = await fetch('/cart/count');
    const data = await res.json();
    const el = document.getElementById('cartBadge');
    if (el) { el.textContent = data.count > 9 ? '9+' : data.count; el.style.display = data.count > 0 ? 'flex' : 'none'; }
  } catch(e) {}
}

// LOGIN MODAL
function closeLoginModal() { document.getElementById('loginModal').classList.remove('open'); document.body.style.overflow = ''; }
document.getElementById('loginModal').addEventListener('click', function(e) { if (e.target === this) closeLoginModal(); });

// TOAST
function showToast(msg, type = 'success') {
  const t = document.getElementById('cartToast');
  t.textContent = msg; t.className = `cart-toast ${type} show`;
  setTimeout(() => t.classList.remove('show'), 3000);
}

// =============================================
// PULSA DATA LOADING
// =============================================
function formatRupiah(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

function renderOperators(operators) {
  const grid = document.getElementById('operatorGrid');
  grid.innerHTML = '';
  operators.forEach(op => {
    const card = document.createElement('div');
    card.className = 'operator-card';
    card.dataset.opId = op.id;
    card.onclick = () => selectOperator(op.id);

    const logoWrap = document.createElement('div');
    logoWrap.className = 'operator-logo';
    logoWrap.style.background = OPERATOR_BG[op.id] || '#F3F4F6';

    const imgSrc = OPERATOR_IMAGES[op.id];
    if (imgSrc) {
      const img = document.createElement('img');
      img.src = imgSrc; img.alt = op.name;
      logoWrap.appendChild(img);
    } else {
      const letter = document.createElement('span');
      letter.className = 'operator-logo-letter';
      letter.textContent = op.name.charAt(0).toUpperCase();
      logoWrap.appendChild(letter);
    }

    const name = document.createElement('span');
    name.className = 'operator-name';
    name.textContent = op.name;

    card.append(logoWrap, name);
    grid.appendChild(card);
  });
}

function renderNominals(operatorId) {
  const grid = document.getElementById('nominalGrid');
  grid.innerHTML = '';
  selectedProduct = null;
  updateConfirmBtn();

  const products = allProducts.filter(p => p.operator_id === operatorId);
  if (!products.length) {
    grid.innerHTML = '<div class="nominal-empty">Belum ada produk untuk operator ini.</div>';
    return;
  }

  // Sort by price ascending
  products.sort((a,b) => a.price - b.price);

  products.forEach((p, idx) => {
    productMap[p.code] = p;

    const card = document.createElement('div');
    card.className = 'nominal-card' + (idx === 0 ? ' selected' : '');
    card.dataset.code = p.code;
    card.onclick = () => selectNominal(card, p);

    const type = document.createElement('div');
    type.className = 'nominal-type'; type.textContent = 'Pulsa';

    const amount = document.createElement('div');
    amount.className = 'nominal-amount';
    amount.textContent = Number(p.name.replace(/[^0-9]/g, '') || p.price).toLocaleString('id-ID');

    const price = document.createElement('div');
    price.className = 'nominal-price';
    price.textContent = 'Harga: ' + formatRupiah(p.price);

    card.append(type, amount, price);
    grid.appendChild(card);

    // Auto-select first product
    if (idx === 0) {
      selectedProduct = p;
      updateConfirmBtn();
    }
  });
}

function selectOperator(opId) {
  selectedOpId = opId;
  // Update operator card styles
  document.querySelectorAll('.operator-card').forEach(c => {
    c.classList.toggle('selected', c.dataset.opId === opId);
  });
  // Update badge
  const op = allOperators.find(o => o.id === opId);
  if (op) setBadge(op.name, true);
  // Render nominals
  renderNominals(opId);
}

function selectNominal(card, product) {
  document.querySelectorAll('.nominal-card').forEach(c => c.classList.remove('selected'));
  card.classList.add('selected');
  selectedProduct = product;
  updateConfirmBtn();
}

function updateConfirmBtn() {
  const btn = document.getElementById('confirmBtn');
  const ready = !!selectedProduct;
  btn.disabled = !ready;
  btn.textContent = ready
    ? `Beli ${selectedProduct.name} — ${formatRupiah(selectedProduct.price)}`
    : 'Konfirmasi Pembayaran';
}

function setBadge(text, active) {
  const dot  = document.getElementById('badgeDot');
  const txt  = document.getElementById('badgeText');
  txt.textContent = text;
  if (active) { dot.classList.add('active'); txt.classList.add('active'); }
  else        { dot.classList.remove('active'); txt.classList.remove('active'); }
}

// Phone input handler
function onPhoneInput(val) {
  const clean = val.replace(/\D/g, '');
  if (clean.length >= 4) {
    const prefix = '0' + clean.substring(1, 4);
    // Try 4-digit prefix match (0XXX)
    const fullPrefix = clean.substring(0, 4);
    const opId = PREFIX_TO_OP[fullPrefix] || PREFIX_TO_OP[prefix];
    if (opId && allOperators.find(o => o.id === opId)) {
      selectOperator(opId);
    } else {
      setBadge('Unknown', false);
    }
  } else {
    setBadge('Detecting', false);
  }
}

// =============================================
// CHECKOUT
// =============================================
function openCheckoutFromPage() {
  @if(!auth()->check())
    document.getElementById('loginModal').classList.add('open');
    document.body.style.overflow = 'hidden';
    return;
  @endif

  if (!selectedProduct) return;

  const phone = document.getElementById('phoneInput').value.trim();

  // Reset modal
  document.getElementById('checkoutStep1').style.display = 'block';
  document.getElementById('checkoutStep2').style.display = 'none';
  document.getElementById('checkoutError').style.display = 'none';
  document.getElementById('checkoutPhone').value = phone;

  // Operator image
  const imgSrc = OPERATOR_IMAGES[selectedProduct.operator_id] || '';
  const imgEl  = document.getElementById('modalOperatorImg');
  if (imgSrc) { imgEl.src = imgSrc; imgEl.style.display = 'block'; } else { imgEl.style.display = 'none'; }

  document.getElementById('modalProductName').textContent  = selectedProduct.name;
  document.getElementById('modalProductPrice').textContent = formatRupiah(selectedProduct.price);
  document.getElementById('checkoutSubmitBtn').textContent = 'Bayar ' + formatRupiah(selectedProduct.price);

  loadPaymentChannels();
  document.getElementById('pulsaCheckoutModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeModal() {
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
  if (_cachedChannels) { populateChannelSelect(sel, _cachedChannels); return; }
  sel.innerHTML = '<option value="">Memuat metode pembayaran...</option>'; sel.disabled = true;
  fetch('/api/payment-channels').then(r => r.json())
    .then(channels => { _cachedChannels = Array.isArray(channels) ? channels : []; populateChannelSelect(sel, _cachedChannels); })
    .catch(() => { sel.innerHTML = '<option value="">Gagal memuat, coba lagi</option>'; sel.disabled = false; });
}

function submitCheckout() {
  const phone   = document.getElementById('checkoutPhone').value.trim();
  const name    = document.getElementById('checkoutName').value.trim();
  const email   = document.getElementById('checkoutEmail').value.trim();
  const method  = document.getElementById('checkoutPaymentMethod').value;
  const errorEl = document.getElementById('checkoutError');
  errorEl.style.display = 'none';

  if (!phone || !/^08[0-9]{7,12}$/.test(phone)) { showCheckoutError('Nomor HP tidak valid. Gunakan format 08xx.'); return; }
  if (!name)   { showCheckoutError('Nama pembeli wajib diisi.'); return; }
  if (!email)  { showCheckoutError('Email wajib diisi.'); return; }
  if (!method) { showCheckoutError('Pilih metode pembayaran.'); return; }

  const btn = document.getElementById('checkoutSubmitBtn');
  btn.disabled = true; btn.textContent = 'Memproses...';

  fetch('/api/transaksi', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    body: JSON.stringify({ product_code: selectedProduct.code, phone, payment_method: method, customer_name: name, customer_email: email }),
  })
  .then(r => r.json())
  .then(res => {
    if (!res.success) throw new Error(res.message || 'Gagal membuat transaksi');
    document.getElementById('checkoutStep1').style.display = 'none';
    document.getElementById('checkoutStep2').style.display = 'block';
    document.getElementById('paymentProductSummary').textContent = res.data.product_name + ' → ' + res.data.phone;
    document.getElementById('paymentAmountDisplay').textContent  = formatRupiah(res.data.amount);
    document.getElementById('paymentUrlBtn').href = res.data.payment_url || '#';
    if (res.data.pay_code) {
      document.getElementById('payCodeLabel').textContent = res.data.payment_name || 'Kode Pembayaran';
      document.getElementById('payCodeValue').textContent = res.data.pay_code;
      document.getElementById('payCodeBox').style.display = 'block';
    }
  })
  .catch(err => {
    showCheckoutError(err.message);
    btn.disabled = false;
    btn.textContent = 'Bayar ' + formatRupiah(selectedProduct.price);
  });
}

function copyPayCode(btn) {
  navigator.clipboard.writeText(document.getElementById('payCodeValue').textContent).then(() => {
    btn.textContent = 'Tersalin!';
    setTimeout(() => { btn.textContent = 'Salin'; }, 2000);
  });
}

function showCheckoutError(msg) {
  const el = document.getElementById('checkoutError');
  el.textContent = msg; el.style.display = 'block';
}

// Close modal on backdrop click
document.getElementById('pulsaCheckoutModal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// =============================================
// INIT — Load pulsa data from API
// =============================================
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();

  fetch('/api/kategori')
    .then(r => r.json())
    .then(res => {
      if (!res.success) throw new Error(res.message || 'Gagal memuat data');
      allProducts  = res.products  || [];
      allOperators = res.operators || [];
      renderOperators(allOperators);
    })
    .catch(err => {
      document.getElementById('operatorGrid').innerHTML =
        `<p style="grid-column:1/-1;color:#EF4444;font-size:13px;font-weight:600;padding:16px 0;">
          Gagal memuat operator: ${err.message}
        </p>`;
    });
});
</script>

</body>
</html>
