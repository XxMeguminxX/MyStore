<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Checkout — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}?v={{ time() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>
<body>

<!-- MINIMAL HEADER -->
<header class="checkout-header">
  <div class="checkout-header-inner">
    <a href="{{ url('/') }}" class="checkout-logo">
      <div class="checkout-logo-icon">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
      </div>
      <span class="checkout-logo-name">E Store ID</span>
    </a>
    <a href="{{ url('/') }}" class="checkout-back">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Kembali
    </a>
  </div>
</header>

<!-- STEPS -->
<div class="checkout-steps">
  <div class="step active">
    <div class="step-num">1</div>
    <span>Pesanan</span>
  </div>
  <div class="step-line"></div>
  <div class="step active">
    <div class="step-num">2</div>
    <span>Pembayaran</span>
  </div>
  <div class="step-line"></div>
  <div class="step">
    <div class="step-num">3</div>
    <span>Selesai</span>
  </div>
</div>

<!-- ERROR ALERT -->
@if(isset($error))
<div style="max-width:900px;margin:0 auto;padding:0 24px;">
  <div class="co-alert co-alert-error">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ $error }}
  </div>
</div>
@endif

<!-- MAIN LAYOUT -->
<div class="checkout-wrap">

  <!-- LEFT COLUMN -->
  <div>

    <!-- 1. Product -->
    <div class="co-card">
      <div class="co-card-header">
        <div class="co-card-step-num">1</div>
        <span class="co-card-title">Detail Produk</span>
      </div>
      <div class="co-card-body">
        <div class="co-product">
          <div class="co-product-img" style="background:linear-gradient(135deg,#6C63FF,#a78bfa);">
            <img src="{{ $product->image }}" alt="{{ $product->name }}" onerror="this.style.display='none'">
          </div>
          <div style="flex:1;">
            <div class="co-product-name">{{ $product->name }}</div>
            <div class="co-product-meta">
              @if($product->stock > 0)
                <span style="color:#059669;font-weight:600;">✓ Tersedia ({{ $product->stock }} unit)</span>
              @else
                <span style="color:#B91C1C;font-weight:600;">✕ Stok Habis</span>
              @endif
            </div>
            <div class="co-product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
          </div>
        </div>

        <div class="co-qty-row">
          <span class="co-qty-label">Kuantitas:</span>
          <div class="co-qty-ctrl">
            <button class="co-qty-btn" id="btnDecQty" onclick="changeQty(-1)">−</button>
            <div class="co-qty-val" id="qtyDisplay">{{ $initialQuantity ?? 1 }}</div>
            <button class="co-qty-btn" id="btnIncQty" onclick="changeQty(1)">+</button>
          </div>
          <span style="font-size:13px;color:var(--text-2);">Maks. {{ $product->stock }}</span>
        </div>
      </div>
    </div>

    <!-- 2. Customer info -->
    <div class="co-card">
      <div class="co-card-header">
        <div class="co-card-step-num">2</div>
        <span class="co-card-title">Informasi Pemesan</span>
      </div>
      <div class="co-card-body">
        <div class="co-info-grid">
          <div class="co-info-item">
            <div class="co-info-label">Nama</div>
            <div class="co-info-val">{{ $user->name }}</div>
          </div>
          <div class="co-info-item">
            <div class="co-info-label">Email</div>
            <div class="co-info-val">{{ $user->email }}</div>
            <div class="co-info-badge">✓ Terverifikasi</div>
          </div>
          <div class="co-info-item" style="grid-column: 1/-1;">
            <div class="co-info-label">Nomor Telepon</div>
            <div class="co-info-val">
              {{ $user->phone ?? '—' }}
              @if(!$user->phone)
                <a href="{{ route('profile') }}" style="font-size:12px;font-weight:600;color:#6C63FF;margin-left:8px;">Tambahkan →</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Payment methods -->
    <div class="co-card">
      <div class="co-card-header">
        <div class="co-card-step-num">3</div>
        <span class="co-card-title">Metode Pembayaran</span>
      </div>
      <div class="co-card-body">
        @if(isset($channels) && count($channels) > 0)
          <div class="payment-grid">
            @foreach($channels as $i => $ch)
              @if($ch->active)
              <div class="payment-option">
                <input type="radio" name="payment_method" id="pm_{{ $ch->code }}"
                  value="{{ $ch->code }}" {{ $i === 0 ? 'checked' : '' }}>
                <label class="payment-option-label" for="pm_{{ $ch->code }}">
                  <div class="payment-option-radio"></div>
                  @if($ch->icon_url)
                    <img src="{{ $ch->icon_url }}" class="payment-icon" alt="{{ $ch->name }}" onerror="this.style.display='none'">
                  @else
                    <div class="payment-icon-placeholder">{{ strtoupper(substr($ch->name, 0, 3)) }}</div>
                  @endif
                  <span class="payment-name">{{ $ch->name }}</span>
                </label>
              </div>
              @endif
            @endforeach
          </div>
        @else
          <div class="co-alert co-alert-warning">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Metode pembayaran tidak tersedia saat ini. Coba lagi nanti.
          </div>
        @endif
      </div>
    </div>

  </div><!-- /left -->

  <!-- RIGHT COLUMN: Order Summary -->
  <div class="order-summary-card">
    <div class="co-card">
      <div class="co-card-header">
        <div class="co-card-step-num" style="background:#059669;">✓</div>
        <span class="co-card-title">Ringkasan Pesanan</span>
      </div>
      <div class="co-card-body">
        <div class="order-row">
          <span class="order-row-label">{{ $product->name }}</span>
          <span class="order-row-val" id="summaryProductPrice">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
        </div>
        <div class="order-row">
          <span class="order-row-label">Kuantitas</span>
          <span class="order-row-val" id="summaryQty">{{ $initialQuantity ?? 1 }}x</span>
        </div>
        <div class="order-divider"></div>
        <div class="order-row">
          <span class="order-total-label">Total</span>
          <span class="order-total-val" id="summaryTotal">Rp {{ number_format($product->price * ($initialQuantity ?? 1), 0, ',', '.') }}</span>
        </div>
      </div>

      <!-- Submit -->
      <div class="co-submit-area">
        <button class="btn-pay" id="btnPay" onclick="submitPayment()" {{ $product->stock == 0 ? 'disabled' : '' }}>
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          Bayar Sekarang
        </button>

        <div class="trust-row-co">
          <div class="trust-co-item">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Pembayaran dienkripsi SSL
          </div>
          <div class="trust-co-item">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Aktivasi produk instan
          </div>
          <div class="trust-co-item">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Garansi uang kembali
          </div>
        </div>
      </div>
    </div>
  </div><!-- /right -->

</div><!-- /checkout-wrap -->

<!-- Hidden form fields (submitted via JS) -->
<form id="paymentForm" style="display:none;">
  <input type="hidden" name="product_sku"       id="fProductSku"    value="{{ $product->id }}">
  <input type="hidden" name="product_name"      id="fProductName"   value="{{ $product->name }}">
  <input type="hidden" name="amount"            id="fAmount"        value="{{ $product->price }}">
  <input type="hidden" name="quantity"          id="fQuantity"      value="{{ $initialQuantity ?? 1 }}">
  <input type="hidden" name="transaction_type"  id="fTxType"        value="{{ $product->transaction_type ?? 'digital' }}">
  <input type="hidden" name="customer_name"     value="{{ $user->name }}">
  <input type="hidden" name="customer_email"    value="{{ $user->email }}">
  <input type="hidden" name="customer_phone"    value="{{ $user->phone ?? '' }}">
  <input type="hidden" name="payment_method"    id="fPaymentMethod" value="">
</form>

<!-- WhatsApp -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<script>
const UNIT_PRICE = {{ $product->price }};
const MAX_STOCK  = {{ $product->stock }};
let qty = {{ $initialQuantity ?? 1 }};

function fmtRp(n) {
  return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function updateSummary() {
  document.getElementById('qtyDisplay').textContent = qty;
  document.getElementById('summaryQty').textContent = qty + 'x';
  document.getElementById('summaryTotal').textContent = fmtRp(UNIT_PRICE * qty);
  document.getElementById('fAmount').value = UNIT_PRICE * qty;
  document.getElementById('fQuantity').value = qty;
  document.getElementById('btnDecQty').disabled = qty <= 1;
  document.getElementById('btnIncQty').disabled = qty >= MAX_STOCK;
}

function changeQty(delta) {
  const next = qty + delta;
  if (next < 1 || next > MAX_STOCK) return;
  qty = next;
  updateSummary();
}

async function submitPayment() {
  const selectedPm = document.querySelector('input[name="payment_method"]:checked');
  if (!selectedPm) {
    alert('Pilih metode pembayaran terlebih dahulu.');
    return;
  }

  const btn = document.getElementById('btnPay');
  btn.disabled = true;
  btn.classList.add('loading');
  btn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Memproses...`;

  try {
    const formData = new FormData();
    formData.append('product_sku',      document.getElementById('fProductSku').value);
    formData.append('product_name',     document.getElementById('fProductName').value);
    formData.append('amount',           document.getElementById('fAmount').value);
    formData.append('quantity',         document.getElementById('fQuantity').value);
    formData.append('transaction_type', document.getElementById('fTxType').value);
    formData.append('customer_name',    '{{ $user->name }}');
    formData.append('customer_email',   '{{ $user->email }}');
    formData.append('customer_phone',   '{{ $user->phone ?? "" }}');
    formData.append('payment_method',   selectedPm.value);
    formData.append('_token',           document.querySelector('meta[name="csrf-token"]').content);

    const res = await fetch('/tripay/transaction', {
      method: 'POST',
      body: formData
    });
    const result = await res.json();

    if (result.success) {
      btn.innerHTML = '✓ Mengarahkan ke pembayaran...';
      const url = result.payment_url || result.checkout_url;
      setTimeout(() => { if (url) window.location.href = url; }, 1200);
    } else {
      alert(result.message || 'Terjadi kesalahan. Coba lagi.');
      resetBtn();
    }
  } catch(e) {
    alert('Koneksi bermasalah. Coba lagi.');
    resetBtn();
  }
}

function resetBtn() {
  const btn = document.getElementById('btnPay');
  btn.disabled = false;
  btn.classList.remove('loading');
  btn.innerHTML = `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg> Bayar Sekarang`;
}

// Spinner animation
const style = document.createElement('style');
style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
document.head.appendChild(style);

// Init
updateSummary();
</script>

</body>
</html>
