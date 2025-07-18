<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
</head>
<body>
<a href="javascript:history.back()" class="btn-back" title="Kembali">
  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
</a>
<div class="checkout-container">
  <h2>Checkout</h2>
  @if(isset($error) && $error)
    <div class="alert alert-danger" style="color: red; margin-bottom: 1em;">{{ $error }}</div>
  @endif
  <div class="checkout-summary">
    <img class="checkout-img" src="{{ $product->image }}" alt="Nama Produk">
    <div class="checkout-info">
      <div class="checkout-title">{{ $product->name }}</div>
      <div class="checkout-id">ID {{ $product->id }}</div>
      <div class="checkout-price">RP {{ number_format($product->price,0,'','.') }}</div>
    </div>
  </div>
  <form class="checkout-form" id="tripay-checkout-form">
    <label>Nama Lengkap</label>
    <input type="text" name="customer_name" placeholder="Nama Anda" required>
    <label>Email</label>
    <input type="email" name="customer_email" placeholder="Email aktif" required>
    <label>No HP</label>
    <input type="tel" name="customer_phone" placeholder="Nomor HP aktif" required pattern="[0-9]+" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')">

    <input type="hidden" name="product_sku" value="{{ $product->id }}">
    <input type="hidden" name="product_name" value="{{ $product->name }}">
    <input type="hidden" name="amount" value="{{ $product->price }}">

    <label>Metode Pembayaran</label>
    <div class="payment-methods">
      @foreach ($channels as $channel)
        @if (is_object($channel) && $channel->active)
          <div class="payment-method">
            <input type="radio" id="{{ $channel->code }}" name="payment_method" value="{{ $channel->code }}" required>
            <label for="{{ $channel->code }}">
              <img src="{{ $channel->icon_url ?? asset('assets/img/default.png') }}" alt="{{ $channel->name }}" class="payment-icon">
              <span class="payment-name">{{ $channel->name }}</span>
            </label>
          </div>
        @endif
      @endforeach
    </div>

    <button type="submit" class="btn-checkout">Bayar Sekarang</button>
  </form>
  <div id="tripay-error" style="color:red;margin-top:1em;"></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('tripay-checkout-form');
  const errorDiv = document.getElementById('tripay-error');
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    errorDiv.textContent = '';
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    try {
      const response = await fetch('/tripay/transaction', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      const result = await response.json();
      if (response.ok && result.success && result.data && result.data.payment_url) {
        window.location.href = result.data.payment_url;
      } else {
        errorDiv.textContent = result.error || (result.response && result.response.message) || 'Gagal membuat transaksi.';
      }
    } catch (err) {
      errorDiv.textContent = 'Terjadi kesalahan koneksi.';
    }
  });
});
</script>
</body>
</html>