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

    <!-- ===== HEADER ===== -->
    <header class="header-bar">
        <a href="{{ url('/') }}" class="header-brand">
            <div class="brand-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span>E Store ID</span>
        </a>

        <div class="header-center">
            <a href="javascript:history.back()" class="btn-back-header">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <span class="header-page-title">Checkout</span>
        </div>

        <div class="header-secure">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span>Aman &amp; Terenkripsi</span>
        </div>
    </header>

    <!-- ===== STEP INDICATOR ===== -->
    <div class="steps-bar">
        <div class="step done">
            <div class="step-dot">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span>Keranjang</span>
        </div>
        <div class="step-line done"></div>
        <div class="step active">
            <div class="step-dot"><span>2</span></div>
            <span>Checkout</span>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-dot"><span>3</span></div>
            <span>Selesai</span>
        </div>
    </div>

    <div class="checkout-wrapper">

        @if(isset($error) && $error)
        <div class="alert alert-danger">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $error }}
        </div>
        @endif

        @if(!$user || empty($user->name) || empty($user->email) || empty($user->phone))
        <div class="alert alert-warning">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <div>
                <strong>Data profil tidak lengkap.</strong><br>
                Lengkapi nama, email, dan nomor HP di <a href="{{ route('profile') }}">halaman Profil</a> sebelum checkout.
            </div>
        </div>
        @else

        <form class="checkout-form" id="tripay-checkout-form">

            <!-- ===== PRODUCT SUMMARY ===== -->
            <section class="card product-card">
                <div class="card-label">Pesanan Anda</div>
                <div class="product-preview">
                    <div class="product-img-wrap">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                    </div>
                    <div class="product-meta">
                        <p class="product-id-badge">SKU #{{ $product->id }}</p>
                        <h2 class="product-name">{{ $product->name }}</h2>
                        <div class="product-qty-row">
                            <span class="qty-badge">× {{ $initialQuantity ?? 1 }}</span>
                            <span class="stock-info">Stok: {{ $product->stock }}</span>
                        </div>
                    </div>
                </div>
                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Harga satuan</span>
                        <span>Rp {{ number_format($product->price, 0, '', '.') }}</span>
                    </div>
                    <div class="price-row">
                        <span>Jumlah</span>
                        <span>× {{ $initialQuantity ?? 1 }}</span>
                    </div>
                    <div class="price-divider"></div>
                    <div class="price-row total-row">
                        <span>Total Pembayaran</span>
                        <span class="total-amount" id="total-price">Rp {{ number_format($product->price * ($initialQuantity ?? 1), 0, '', '.') }}</span>
                    </div>
                </div>
            </section>

            <!-- ===== CUSTOMER INFO ===== -->
            <section class="card form-card">
                <div class="card-label">Data Pemesan</div>
                <div class="info-notice-inline">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Data diambil dari profil Anda. <a href="{{ route('profile') }}">Ubah di sini</a>
                </div>
                <div class="customer-fields">
                    <div class="form-group">
                        <label>
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Nama Lengkap
                        </label>
                        <div class="readonly-field">{{ $user->name }}</div>
                        <input type="hidden" name="customer_name" value="{{ $user->name }}">
                    </div>
                    <div class="form-group">
                        <label>
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Email
                        </label>
                        <div class="readonly-field">{{ $user->email }}</div>
                        <input type="hidden" name="customer_email" value="{{ $user->email }}">
                    </div>
                    <div class="form-group">
                        <label>
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            No. HP
                        </label>
                        <div class="readonly-field">{{ $user->phone }}</div>
                        <input type="hidden" name="customer_phone" value="{{ $user->phone }}">
                    </div>
                </div>
            </section>

            <!-- ===== PAYMENT METHOD ===== -->
            <section class="card form-card">
                <div class="card-label">Metode Pembayaran</div>
                <p class="payment-hint">Pilih salah satu metode pembayaran yang tersedia</p>
                <div class="payment-grid">
                    @foreach ($channels as $channel)
                    @if (is_object($channel) && $channel->active)
                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="{{ $channel->code }}" required>
                        <span class="payment-option-inner">
                            <span class="payment-check">
                                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <img src="{{ $channel->icon_url ?? asset('assets/img/default.png') }}" alt="{{ $channel->name }}" class="payment-icon">
                            <span class="payment-name">{{ $channel->name }}</span>
                        </span>
                    </label>
                    @endif
                    @endforeach
                </div>
            </section>

            <!-- Hidden fields -->
            <input type="hidden" name="product_sku" value="{{ $product->id }}">
            <input type="hidden" name="product_name" value="{{ $product->name }}">
            <input type="hidden" name="amount" id="amount-input" value="{{ $product->price * ($initialQuantity ?? 1) }}">
            <input type="hidden" name="quantity" id="quantity-input" value="{{ $initialQuantity ?? 1 }}">
            <input type="hidden" name="transaction_type" value="{{ $product->transaction_type ?? 'product' }}">

            <!-- ===== TRUST BADGES ===== -->
            <div class="trust-row">
                <div class="trust-item">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Transaksi Aman</span>
                </div>
                <div class="trust-item">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Proses Instan</span>
                </div>
                <div class="trust-item">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Dukungan 24/7</span>
                </div>
            </div>

            <!-- ===== CTA ===== -->
            <div class="cta-block">
                <div class="cta-total">
                    <span class="cta-total-label">Total</span>
                    <span class="cta-total-value" id="cta-total-price">Rp {{ number_format($product->price * ($initialQuantity ?? 1), 0, '', '.') }}</span>
                </div>
                <button type="submit" class="btn-submit" id="btn-submit">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Bayar Sekarang
                </button>
            </div>

        </form>
        @endif

        <div id="tripay-message" class="tripay-message" style="display:none;"></div>

    </div>

    <!-- ===== WHATSAPP FLOAT ===== -->
    <a href="https://wa.me/6281234567890" class="wa-float" target="_blank" rel="noopener" title="Hubungi via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tripay-checkout-form');
        const messageDiv = document.getElementById('tripay-message');
        const btnSubmit = document.getElementById('btn-submit');
        if (!form) return;

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Button loading state
            btnSubmit.disabled = true;
            btnSubmit.classList.add('loading');
            btnSubmit.innerHTML = '<span class="spinner"></span> Memproses...';

            messageDiv.style.display = 'none';
            messageDiv.textContent = '';
            messageDiv.className = 'tripay-message';

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            const required = ['customer_name', 'customer_email', 'customer_phone', 'payment_method', 'amount', 'product_sku', 'product_name'];
            const missing = required.filter(function(f) { return !data[f]; });

            if (missing.length > 0) {
                messageDiv.style.display = 'flex';
                messageDiv.className = 'tripay-message alert-danger';
                messageDiv.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Harap pilih metode pembayaran terlebih dahulu.';
                resetButton();
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
                    messageDiv.style.display = 'flex';
                    messageDiv.className = 'tripay-message alert-success';
                    messageDiv.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Transaksi berhasil! Mengalihkan ke halaman pembayaran...';
                    setTimeout(function() { window.location.href = url; }, 1200);
                } else {
                    messageDiv.style.display = 'flex';
                    messageDiv.className = 'tripay-message alert-danger';
                    messageDiv.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ' + (result.error || (result.response && result.response.message) || 'Gagal membuat transaksi.');
                    resetButton();
                }
            } catch (err) {
                messageDiv.style.display = 'flex';
                messageDiv.className = 'tripay-message alert-danger';
                messageDiv.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Terjadi kesalahan koneksi. Silakan coba lagi.';
                resetButton();
            }
        });

        function resetButton() {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('loading');
            btnSubmit.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Bayar Sekarang';
        }
    });
    </script>

</body>
</html>
