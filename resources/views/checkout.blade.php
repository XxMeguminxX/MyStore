<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
</head>
<style>
</style>
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">

<body>
    <a href="javascript:history.back()" class="btn-back" title="Kembali">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6" />
        </svg>
    </a>
    <div class="checkout-container">
        <h2>Checkout</h2>
        @if(isset($error) && $error)
        <div class="alert alert-danger" style="color: red; margin-bottom: 1em;">{{ $error }}</div>
        @endif
        <div class="checkout-summary">
            <img class="checkout-img" src="{{ asset($product->image) }}" alt="Nama Produk">
            <div class="checkout-info">
                <div class="checkout-title">{{ $product->name }}</div>
                <div class="checkout-id">ID {{ $product->id }}</div>
                
                <div class="quantity-selector">
                    <button type="button" id="decrease-qty" class="quantity-btn">-</button>
                    <span id="quantity-value" class="quantity-value">1</span>
                    <button type="button" id="increase-qty" class="quantity-btn">+</button>
                </div>
                
                <div class="checkout-price" id="total-price">
                    RP {{ number_format($product->price,0,'','.') }}
                </div>
            </div>
        </div>
        
        <div class="info-box" style="background: #e8f5e8; border: 1px solid #2a9d8f; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center;">
            <p style="margin: 0; color: #2a9d8f; font-size: 0.9em;">
                <strong>ℹ️ Informasi:</strong> Data user diambil dari profile Anda dan tidak bisa diedit. 
                Jika ingin mengubah data, silakan update di halaman <a href="{{ route('profile') }}" style="color: #1a7a6f; text-decoration: underline;">Profile</a>.
            </p>
        </div>
        
        @if(!$user || empty($user->name) || empty($user->email) || empty($user->phone))
        <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            <strong>⚠️ Error:</strong> Data user tidak lengkap. Silakan lengkapi profile Anda terlebih dahulu.
            <br>
            <a href="{{ route('profile') }}" style="color: #721c24; text-decoration: underline;">Klik di sini untuk ke halaman Profile</a>
        </div>
        @else
        <form class="checkout-form" id="tripay-checkout-form">
            <label>Nama Lengkap</label>
            <input type="text" name="customer_name" placeholder="Nama Anda" value="{{ $user->name }}" readonly required>
            <label>Email</label>
            <input type="email" name="customer_email" placeholder="Email aktif" value="{{ $user->email }}" readonly required>
            <label>No HP</label>
            <input type="tel" name="customer_phone" placeholder="Nomor HP aktif" value="{{ $user->phone }}" readonly required>

            <input type="hidden" name="product_sku" value="{{ $product->id }}">
            <input type="hidden" name="product_name" value="{{ $product->name }}">
            <input type="hidden" name="amount" id="amount-input" value="{{ $product->price }}">

            <label>Metode Pembayaran</label>
            <div class="payment-methods">
                @foreach ($channels as $channel)
                @if (is_object($channel) && $channel->active)
                <div class="payment-method">
                    <input type="radio" id="{{ $channel->code }}" name="payment_method" value="{{ $channel->code }}"
                        required>
                    <label for="{{ $channel->code }}">
                        <img src="{{ $channel->icon_url ?? asset('assets/img/default.png') }}"
                            alt="{{ $channel->name }}" class="payment-icon">
                        <span class="payment-name">{{ $channel->name }}</span>
                    </label>
                </div>
                @endif
                @endforeach
            </div>

            <button type="submit" class="btn-checkout">Bayar Sekarang</button>
        </form>
        @endif
        <div id="tripay-message" style="display:none;margin-top:1em;"></div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bagian Logika Kuantitas
        const basePrice = {{ $product->price }};
        const decreaseBtn = document.getElementById('decrease-qty');
        const increaseBtn = document.getElementById('increase-qty');
        const quantityValue = document.getElementById('quantity-value');
        const totalPriceDisplay = document.getElementById('total-price');
        const amountInput = document.getElementById('amount-input');
        
        let quantity = 1;

        function updatePriceAndQuantity() {
            const total = basePrice * quantity;
            
            // Update tampilan kuantitas
            quantityValue.textContent = quantity;
            
            // Update tampilan harga (format Rupiah)
            totalPriceDisplay.textContent = 'RP ' + new Intl.NumberFormat('id-ID').format(total);
            
            // Update nilai pada form yang akan dikirim
            amountInput.value = total;
            
            // Nonaktifkan tombol kurang jika kuantitas = 1
            decreaseBtn.disabled = (quantity === 1);
        }
        
        increaseBtn.addEventListener('click', function() {
            quantity++;
            updatePriceAndQuantity();
        });
        
        decreaseBtn.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                updatePriceAndQuantity();
            }
        });

        // Panggil sekali saat halaman dimuat untuk inisialisasi
        updatePriceAndQuantity();

        // Bagian Logika Form Tripay
        const form = document.getElementById('tripay-checkout-form');
        const messageDiv = document.getElementById('tripay-message');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                messageDiv.style.display = 'none';
                messageDiv.textContent = '';
                messageDiv.className = '';
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                
                // Debug: log data yang akan dikirim
                console.log('Data yang akan dikirim ke Tripay:', data);
                
                // Validasi data sebelum dikirim
                const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'payment_method', 'amount', 'product_sku', 'product_name'];
                const missingFields = requiredFields.filter(field => !data[field]);
                
                if (missingFields.length > 0) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Data tidak lengkap: ' + missingFields.join(', ');
                    return;
                }
                
                try {
                    const response = await fetch('/tripay/transaction', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(data)
                    });
                    const result = await response.json();
                    if (response.ok && result.success && (result.payment_url || (result.data && result
                            .data.payment_url) || (result.data && result.data.checkout_url))) {
                        messageDiv.style.display = 'block';
                        messageDiv.className = 'alert alert-success';
                        messageDiv.innerHTML =
                            'Transaksi berhasil dibuat! Anda akan diarahkan ke halaman pembayaran...';
                        setTimeout(function() {
                            window.location.href = result.payment_url || (result.data && result
                                .data.payment_url) || (result.data && result.data
                                .checkout_url);
                        }, 1200);
                    } else {
                        messageDiv.style.display = 'block';
                        messageDiv.className = 'alert alert-danger';
                        messageDiv.innerHTML = result.error || (result.response && result.response
                            .message) || 'Gagal membuat transaksi.';
                    }
                } catch (err) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = 'Terjadi kesalahan koneksi.';
                }
            });
        }
    });
    </script>
</body>

</html>