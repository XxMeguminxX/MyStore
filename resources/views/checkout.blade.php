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
  <div class="checkout-summary">
    <img class="checkout-img" src="{{ $product->image }}" alt="Nama Produk">
    <div class="checkout-info">
      <div class="checkout-title">{{ $product->name }}</div>
      <div class="checkout-id">ID {{ $product->id }}</div>
      <div class="checkout-price">RP {{ number_format($product->price,0,'','.') }}</div>
    </div>
  </div>
  <form class="checkout-form">
    <label>Nama Lengkap</label>
    <input type="text" placeholder="Nama Anda" required>
    <label>Email</label>
    <input type="email" placeholder="Email aktif" required>
    <label>No HP</label>
    <input type="tel" placeholder="Nomor HP aktif" required pattern="[0-9]+" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
    <label>Metode Pembayaran</label>
    <select>
      <option>Transfer Bank</option>
      <option>QRIS</option>
      <option>E-Wallet</option>
    </select>
    <button type="submit" class="btn-checkout">Bayar Sekarang</button>
  </form>
</div>
</body>
</html>