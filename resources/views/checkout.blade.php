<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — MyStore</title>
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body>
    <a href="javascript:history.back()" class="btn-back" title="Kembali" aria-label="Kembali">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
    </a>

    <div class="checkout-wrapper">
        <header class="checkout-header">
            <h1>Checkout</h1>
            <p class="checkout-subtitle">Lengkapi pembayaran Anda</p>
        </header>

        @if(isset($error) && $error)
        <div class="alert alert-danger">{{ $error }}</div>
        @endif

        <section class="card product-card">
            <div class="product-preview">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                <div class="product-meta">
                    <h2 class="product-name">{{ $product->name }}</h2>
                    <span class="product-id">ID {{ $product->id }}</span>
                    <div class="product-detail-row">
                        <span class="product-qty">Jumlah: {{ $initialQuantity ?? 1 }}</span>
                        <span class="product-stock">Stok: {{ $product->stock }}</span>
                    </div>
                    <p class="product-total" id="total-price">
                        Rp {{ number_format($product->price * ($initialQuantity ?? 1), 0, '', '.') }}
                    </p>
                </div>
            </div>
        </section>

        <div class="info-notice">
            <p>Data pemesan diambil dari profil Anda. Ubah di halaman <a href="{{ route('profile') }}">Profil</a> jika perlu.</p>
        </div>

        @if(!$user || empty($user->name) || empty($user->email) || empty($user->phone))
        <div class="alert alert-warning">
            <strong>Data tidak lengkap.</strong> Lengkapi profil Anda terlebih dahulu.
            <a href="{{ route('profile') }}">Ke halaman Profil</a>
        </div>
        @else
        <form class="checkout-form" id="tripay-checkout-form">
            <section class="card form-card">
                <h3 class="form-section-title">Data Pemesan</h3>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="customer_name" value="{{ $user->name }}" readonly required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="customer_email" value="{{ $user->email }}" readonly required>
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="tel" name="customer_phone" value="{{ $user->phone }}" readonly required>
                </div>
            </section>

            <input type="hidden" name="product_sku" value="{{ $product->id }}">
            <input type="hidden" name="product_name" value="{{ $product->name }}">
            <input type="hidden" name="amount" id="amount-input" value="{{ $product->price * ($initialQuantity ?? 1) }}">
            <input type="hidden" name="quantity" id="quantity-input" value="{{ $initialQuantity ?? 1 }}">
            <input type="hidden" name="transaction_type" value="{{ $product->transaction_type ?? 'product' }}">

            <section class="card form-card">
                <h3 class="form-section-title">Metode Pembayaran</h3>
                <div class="payment-grid">
                    @foreach ($channels as $channel)
                    @if (is_object($channel) && $channel->active)
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="{{ $channel->code }}" required>
                        <span class="payment-option-inner">
                            <img src="{{ $channel->icon_url ?? asset('assets/img/default.png') }}" alt="{{ $channel->name }}" class="payment-icon">
                            <span class="payment-name">{{ $channel->name }}</span>
                        </span>
                    </label>
                    @endif
                    @endforeach
                </div>
            </section>

            <button type="submit" class="btn-submit">Bayar Sekarang</button>
        </form>
        @endif

        <div id="tripay-message" class="tripay-message" style="display:none;"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tripay-checkout-form');
        const messageDiv = document.getElementById('tripay-message');
        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = 'tripay-message';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const required = ['customer_name', 'customer_email', 'customer_phone', 'payment_method', 'amount', 'product_sku', 'product_name'];
            const missing = required.filter(function(f) { return !data[f]; });

            if (missing.length > 0) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'tripay-message alert-danger';
                messageDiv.textContent = 'Data tidak lengkap: ' + missing.join(', ');
                return;
            }

            try {
                const response = await fetch('/tripay/transaction', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                const url = result.payment_url || (result.data && (result.data.payment_url || result.data.checkout_url));

                if (response.ok && result.success && url) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'tripay-message alert-success';
                    messageDiv.textContent = 'Transaksi berhasil. Mengalihkan ke halaman pembayaran...';
                    setTimeout(function() { window.location.href = url; }, 1200);
                } else {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'tripay-message alert-danger';
                    messageDiv.textContent = result.error || (result.response && result.response.message) || 'Gagal membuat transaksi.';
                }
            } catch (err) {
                messageDiv.style.display = 'block';
                messageDiv.className = 'tripay-message alert-danger';
                messageDiv.textContent = 'Terjadi kesalahan koneksi.';
            }
        });
    });
    </script>
</body>

</html>
