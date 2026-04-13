<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Keranjang — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}?v={{ time() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/duotone/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <a href="{{ url('/') }}" class="nav-logo">
      <div class="nav-logo-icon">
        <i class="ph-bold ph-lightning"></i>
      </div>
      <span class="nav-logo-name">E Store ID</span>
    </a>
    <div class="nav-links">
      <a href="{{ url('/') }}">Beranda</a>
      <a href="{{ url('/#produk') }}">Produk</a>
      <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
    </div>
    <div class="nav-actions">
      <a href="{{ route('cart.index') }}" class="nav-icon-btn active" title="Keranjang">
        <i class="ph-bold ph-shopping-bag"></i>
        @if($carts->count() > 0)
          <span class="cart-badge">{{ $carts->count() }}</span>
        @endif
      </a>
      <div class="nav-user" id="navUser">
        <div class="nav-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <span class="nav-user-name">{{ Str::limit(auth()->user()->name, 12) }}</span>
        <i class="ph-bold ph-caret-down nav-user-caret"></i>
        <div class="nav-user-menu" id="navUserMenu">
          <a href="{{ url('/profile') }}">
            <i class="ph-bold ph-user"></i>
            Profil
          </a>
          <a href="{{ route('transaction.history') }}">
            <i class="ph-bold ph-clock-counter-clockwise"></i>
            Histori Transaksi
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
              <i class="ph-bold ph-sign-out"></i>
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
  <a href="{{ url('/halaman/cara-beli') }}">Cara Beli</a>
  <div class="nav-mobile-btns">
    <a href="{{ url('/profile') }}" class="m-login">Profil Saya</a>
    <a href="{{ url('/') }}" class="m-register">Lanjut Belanja</a>
  </div>
</div>

<!-- MAIN -->
<div class="page-wrap">

  <div class="breadcrumb">
    <a href="{{ url('/') }}">Beranda</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-cur">Keranjang</span>
  </div>

  <div class="page-title">
    Keranjang Belanja
    @if($carts->count() > 0)
      <span>({{ $carts->count() }} item)</span>
    @endif
  </div>

  @if($carts->count() > 0)
  <div class="cart-layout">

    <!-- Items -->
    <div>
      <div class="cart-items-card">
        <div class="cart-items-header">
          <span class="cart-items-title">Item Dipilih</span>
          <button class="btn-clear-cart" onclick="clearCart()">Hapus Semua</button>
        </div>

        @foreach($carts as $cart)
        @php
          $grads = ['linear-gradient(135deg,#EA4335,#FBBC05)','linear-gradient(135deg,#E50914,#B81D24)','linear-gradient(135deg,#00A4EF,#0078D4)','linear-gradient(135deg,#1DB954,#158a3e)','linear-gradient(135deg,#7C3AED,#A78BFA)','linear-gradient(135deg,#D83B01,#F05A28)'];
          $grad = $grads[$loop->index % count($grads)];
        @endphp
        <div class="cart-item" id="cart-item-{{ $cart->id }}">
          <!-- Image -->
          <div class="cart-item-img" style="background: {{ $grad }};">
            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" onerror="this.style.display='none'">
          </div>

          <!-- Info -->
          <div>
            <div class="cart-item-name">{{ $cart->product->name }}</div>
            <div class="cart-item-price-unit">Rp {{ number_format($cart->price, 0, ',', '.') }} / item</div>
          </div>

          <!-- Qty -->
          <div class="qty-ctrl">
            <button class="qty-btn"
              id="decrease-qty-{{ $cart->id }}"
              data-cart-id="{{ $cart->id }}"
              data-current-qty="{{ $cart->quantity }}"
              data-min-qty="1"
              data-max-qty="{{ $cart->product->stock }}"
              onclick="changeQty({{ $cart->id }}, -1)">−</button>
            <div class="qty-val" id="qty-val-{{ $cart->id }}">{{ $cart->quantity }}</div>
            <button class="qty-btn"
              id="increase-qty-{{ $cart->id }}"
              data-cart-id="{{ $cart->id }}"
              data-current-qty="{{ $cart->quantity }}"
              data-min-qty="1"
              data-max-qty="{{ $cart->product->stock }}"
              onclick="changeQty({{ $cart->id }}, 1)">+</button>
          </div>

          <!-- Subtotal -->
          <div class="cart-item-subtotal" id="subtotal-{{ $cart->id }}">
            Rp {{ number_format($cart->price * $cart->quantity, 0, ',', '.') }}
          </div>

          <!-- Remove -->
          <button class="btn-remove-item" onclick="removeItem({{ $cart->id }}, '{{ addslashes($cart->product->name) }}')" title="Hapus">
            <i class="ph-bold ph-x"></i>
          </button>
        </div>
        @endforeach
      </div>

      <!-- Continue shopping -->
      <div style="margin-top: 12px;">
        <a href="{{ url('/') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--text-2);transition:color 0.22s;">
          <i class="ph-bold ph-caret-left"></i>
          Lanjut Belanja
        </a>
      </div>
    </div>

    <!-- Summary -->
    <div class="cart-summary-card">
      <div class="cart-summary-header">
        <div class="cart-summary-title">Ringkasan Pesanan</div>
      </div>
      <div class="cart-summary-body">
        <div class="summary-row">
          <span class="summary-row-label">Total Item</span>
          <span class="summary-row-val">{{ $carts->count() }} item</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row summary-total">
          <span class="summary-row-label">Total Harga</span>
          <span class="summary-row-val" id="cartTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
      </div>
      <div class="cart-summary-footer">
        @if($carts->count() > 0)
          <a href="{{ route('beli', ['id' => $carts->first()->product_id]) }}" class="btn-checkout">
            Lanjut ke Pembayaran
            <i class="ph-bold ph-arrow-right"></i>
          </a>
        @else
          <button class="btn-checkout" disabled>Keranjang Kosong</button>
        @endif
        <a href="{{ url('/') }}" class="btn-shop-more">Lanjut Belanja</a>
      </div>
      <div class="trust-mini">
        <div class="trust-mini-item"><span class="trust-mini-dot"></span>Pembayaran 100% aman & terenkripsi</div>
        <div class="trust-mini-item"><span class="trust-mini-dot"></span>Aktivasi produk instan otomatis</div>
        <div class="trust-mini-item"><span class="trust-mini-dot"></span>Garansi uang kembali jika gagal</div>
      </div>
    </div>

  </div>
  @else
  <!-- Empty state -->
  <div class="cart-items-card">
    <div class="empty-cart">
      <div class="empty-cart-icon">🛒</div>
      <h3>Keranjang Kosong</h3>
      <p>Belum ada produk yang ditambahkan ke keranjang.</p>
      <a href="{{ url('/') }}" class="btn-shop">
        <i class="ph-bold ph-caret-left"></i>
        Mulai Belanja
      </a>
    </div>
  </div>
  @endif

</div><!-- /page-wrap -->

<!-- WhatsApp -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<!-- Toast -->
<div class="cart-toast" id="cartToast"></div>

<script>
// Navbar scroll
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

// Hamburger
const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('navMobile');
hamburger?.addEventListener('click', () => {
  hamburger.classList.toggle('open');
  navMobile.classList.toggle('open');
});

// User dropdown
const navUser = document.getElementById('navUser');
const navUserMenu = document.getElementById('navUserMenu');
if (navUser && navUserMenu) {
  navUser.addEventListener('click', e => { e.stopPropagation(); navUserMenu.classList.toggle('open'); });
  document.addEventListener('click', e => { if (!navUser.contains(e.target)) navUserMenu.classList.remove('open'); });
}

// Toast
function showToast(msg, type = 'success') {
  const t = document.getElementById('cartToast');
  t.textContent = msg;
  t.className = `cart-toast ${type} show`;
  setTimeout(() => t.classList.remove('show'), 3000);
}

// Get current qty for a cart item
function getCurrentQty(cartId) {
  return parseInt(document.getElementById('qty-val-' + cartId).textContent);
}

// Format price IDR
function fmtRp(n) {
  return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Change quantity
async function changeQty(cartId, delta) {
  const valEl = document.getElementById('qty-val-' + cartId);
  const decBtn = document.getElementById('decrease-qty-' + cartId);
  const incBtn = document.getElementById('increase-qty-' + cartId);
  const maxQty = parseInt(incBtn.dataset.maxQty);
  const current = parseInt(valEl.textContent);
  const newQty = current + delta;

  if (newQty < 1 || newQty > maxQty) return;

  decBtn.disabled = true; incBtn.disabled = true;
  try {
    const res = await fetch(`/cart/update/${cartId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ quantity: newQty })
    });
    const data = await res.json();
    if (data.success) {
      valEl.textContent = newQty;
      // Update subtotal
      const subtotalEl = document.getElementById('subtotal-' + cartId);
      if (subtotalEl && data.item_total) {
        subtotalEl.textContent = fmtRp(data.item_total);
      }
      // Update cart total
      if (data.cart_total) {
        document.getElementById('cartTotal').textContent = fmtRp(data.cart_total);
      }
      // Disable dec btn at min
      if (newQty <= 1) decBtn.disabled = true;
      if (newQty >= maxQty) incBtn.disabled = true;
    }
  } catch(e) {
    showToast('Gagal memperbarui kuantitas', 'error');
  } finally {
    decBtn.disabled = newQty <= 1;
    incBtn.disabled = newQty >= maxQty;
  }
}

// Remove item
async function removeItem(cartId, name) {
  if (!confirm(`Hapus "${name}" dari keranjang?`)) return;
  try {
    const res = await fetch(`/cart/remove/${cartId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      }
    });
    const data = await res.json();
    if (data.success) {
      document.getElementById('cart-item-' + cartId)?.remove();
      showToast('Item dihapus dari keranjang');
      setTimeout(() => location.reload(), 800);
    }
  } catch(e) {
    showToast('Gagal menghapus item', 'error');
  }
}

// Clear cart
async function clearCart() {
  if (!confirm('Hapus semua item dari keranjang?')) return;
  try {
    const res = await fetch('/cart/clear', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      }
    });
    const data = await res.json();
    if (data.success) {
      showToast('Keranjang dikosongkan');
      setTimeout(() => location.reload(), 800);
    }
  } catch(e) {
    showToast('Gagal mengosongkan keranjang', 'error');
  }
}

// Init disabled states for qty buttons
document.querySelectorAll('.qty-btn').forEach(btn => {
  const cartId = btn.dataset.cartId;
  const current = parseInt(document.getElementById('qty-val-' + cartId)?.textContent || 1);
  const max = parseInt(btn.dataset.maxQty);
  if (btn.id.startsWith('decrease')) btn.disabled = current <= 1;
  if (btn.id.startsWith('increase')) btn.disabled = current >= max;
});
</script>

</body>
</html>
