<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}?v={{ now() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
</head>

<body class="body-background-3d">
    {{-- Header dengan tombol kembali --}}
    <div class="header-bar">
        <a href="{{ url('/dashboard') }}" class="btn-kembali">
            &larr; Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <h1 style="margin-top: 80px;">Keranjang Belanja</h1>

    @if($carts->isEmpty())
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5l2.5-5m-2.5 5L9.5 18M17 13l-2.5 5M9.5 18l-2.5-2M9.5 18h6.5" />
                </svg>
            </div>
            <h3>Keranjang Anda Kosong</h3>
            <p>Yuk mulai belanja produk digital favorit Anda!</p>
            <a href="{{ url('/') }}" class="btn btn-beli">Mulai Belanja</a>
        </div>
    @else
        <div class="cart-container">
            <div class="cart-items">
                @foreach($carts as $cart)
                    <div class="cart-item" id="cart-item-{{ $cart->id }}">
                        <div class="cart-item-image">
                            <img src="{{ $cart->product->image }}" alt="{{ $cart->product->name }}" class="product-img">
                        </div>
                        <div class="cart-item-details">
                            <h4 class="cart-item-title">{{ $cart->product->name }}</h4>
                            <p class="cart-item-price">Rp {{ number_format($cart->price, 0, '', '.') }}</p>
                            <div class="cart-item-stock">
                                <span class="stock-status {{ $cart->product->getStockStatusColor() }}">
                                    Stok: {{ $cart->product->stock }}
                                </span>
                            </div>
                        </div>
                        <div class="cart-item-quantity">
                            <button type="button" id="decrease-qty-{{ $cart->id }}" class="quantity-btn" data-cart-id="{{ $cart->id }}" data-current-qty="{{ $cart->quantity }}" data-min-qty="1" data-max-qty="{{ $cart->product->stock }}" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>-</button>
                            <span id="quantity-value-{{ $cart->id }}" class="quantity-value">{{ $cart->quantity }}</span>
                            <button type="button" id="increase-qty-{{ $cart->id }}" class="quantity-btn" data-cart-id="{{ $cart->id }}" data-current-qty="{{ $cart->quantity }}" data-min-qty="1" data-max-qty="{{ $cart->product->stock }}" {{ $cart->quantity >= $cart->product->stock ? 'disabled' : '' }}>+</button>
                        </div>
                        <div class="cart-item-total">
                            <p class="cart-item-total-price">Rp {{ number_format($cart->total, 0, '', '.') }}</p>
                        </div>
                        <div class="cart-item-actions">
                            <button type="button" class="btn btn-remove" onclick="removeFromCart({{ $cart->id }}, '{{ $cart->product->name }}')" title="Hapus dari Keranjang">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="cart-summary-header">
                    <h3>Ringkasan Belanja</h3>
                </div>
                <div class="cart-summary-content">
                    <div class="summary-row">
                        <span>Total Item:</span>
                        <span>{{ $carts->sum('quantity') }} item</span>
                    </div>
                    <div class="summary-row total-row">
                        <span><strong>Total Harga:</strong></span>
                        <span><strong>Rp {{ number_format($total, 0, '', '.') }}</strong></span>
                    </div>
                </div>
                <div class="cart-summary-actions">
                    <button type="button" class="btn btn-clear" onclick="clearCart()">Kosongkan Keranjang</button>
                    <a href="{{ route('beli', ['id' => $carts->first()->product_id]) }}" class="btn btn-beli btn-checkout">Lanjutkan ke Pembayaran</a>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Fungsi untuk update quantity ke server
        async function updateQuantityServer(cartId, newQuantity) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/cart/update/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Update item total
                    const itemElement = document.getElementById(`cart-item-${cartId}`);
                    if (itemElement) {
                        const totalElement = itemElement.querySelector('.cart-item-total-price');
                        if (totalElement) {
                            totalElement.textContent = `Rp ${result.item_total.toLocaleString('id-ID')}`;
                        }
                    }

                    // Update cart total
                    updateCartTotal(result.cart_total);

                    showNotification('Quantity berhasil diupdate!', 'success');
                    return true;
                } else {
                    showNotification(result.message || 'Gagal mengupdate quantity', 'error');
                    return false;
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
                showNotification('Terjadi kesalahan saat mengupdate quantity', 'error');
                return false;
            }
        }

        // Fungsi untuk remove item dari cart
        async function removeFromCart(cartId, productName) {
            if (!confirm(`Apakah Anda yakin ingin menghapus "${productName}" dari keranjang?`)) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(`/cart/remove/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Remove item from DOM
                    const itemElement = document.getElementById(`cart-item-${cartId}`);
                    if (itemElement) {
                        itemElement.remove();
                    }

                    showNotification(`${productName} berhasil dihapus dari keranjang!`, 'success');

                    // Check if cart is empty
                    const cartItems = document.querySelectorAll('.cart-item');
                    if (cartItems.length === 1) { // Only 1 left (the one being removed)
                        setTimeout(() => {
                            location.reload(); // Reload to show empty cart message
                        }, 1000);
                    }
                } else {
                    showNotification(result.message || 'Gagal menghapus item', 'error');
                }
            } catch (error) {
                console.error('Error removing item:', error);
                showNotification('Terjadi kesalahan saat menghapus item', 'error');
            }
        }

        // Fungsi untuk clear cart
        async function clearCart() {
            if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    showNotification('Keranjang berhasil dikosongkan!', 'success');
                    setTimeout(() => {
                        location.reload(); // Reload to show empty cart
                    }, 1000);
                } else {
                    showNotification('Gagal mengosongkan keranjang', 'error');
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
                showNotification('Terjadi kesalahan saat mengosongkan keranjang', 'error');
            }
        }

        // Fungsi untuk update cart total
        function updateCartTotal(newTotal) {
            const totalElements = document.querySelectorAll('.cart-summary .total-row span:last-child');
            totalElements.forEach(element => {
                element.innerHTML = `<strong>Rp ${newTotal.toLocaleString('id-ID')}</strong>`;
            });
        }



        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            // Remove existing notification
            const existingNotification = document.querySelector('.cart-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create new notification
            const notification = document.createElement('div');
            notification.className = `cart-notification ${type}`;
            notification.textContent = message;

            // Style the notification
            Object.assign(notification.style, {
                position: 'fixed',
                top: '20px',
                right: '20px',
                padding: '12px 20px',
                borderRadius: '8px',
                color: 'white',
                fontSize: '14px',
                fontWeight: '500',
                zIndex: '9999',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                opacity: '0',
                transform: 'translateY(-10px)',
                transition: 'all 0.3s ease'
            });

            // Set background color based on type
            if (type === 'success') {
                notification.style.backgroundColor = '#2a9d8f';
            } else if (type === 'error') {
                notification.style.backgroundColor = '#dc2626';
            } else {
                notification.style.backgroundColor = '#6b7280';
            }

            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateY(0)';
            }, 10);

            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-10px)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Fungsi untuk inisialisasi quantity controls
        function initializeQuantityControls() {
            // Cari semua tombol decrease dan increase
            const decreaseButtons = document.querySelectorAll('[id^="decrease-qty-"]');
            const increaseButtons = document.querySelectorAll('[id^="increase-qty-"]');

            decreaseButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const cartId = this.getAttribute('data-cart-id');
                    const currentQty = parseInt(this.getAttribute('data-current-qty'));
                    const minQty = parseInt(this.getAttribute('data-min-qty'));

                    if (currentQty > minQty) {
                        const newQty = currentQty - 1;

                        // Update tampilan lokal terlebih dahulu
                        updateQuantityDisplay(cartId, newQty);

                        // Kirim ke server
                        const success = await updateQuantityServer(cartId, newQty);

                        if (!success) {
                            // Jika gagal, kembalikan ke nilai sebelumnya
                            updateQuantityDisplay(cartId, currentQty);
                        }
                    }
                });
            });

            increaseButtons.forEach(button => {
                button.addEventListener('click', async function() {
                    const cartId = this.getAttribute('data-cart-id');
                    const currentQty = parseInt(this.getAttribute('data-current-qty'));
                    const maxQty = parseInt(this.getAttribute('data-max-qty'));

                    if (currentQty < maxQty) {
                        const newQty = currentQty + 1;

                        // Update tampilan lokal terlebih dahulu
                        updateQuantityDisplay(cartId, newQty);

                        // Kirim ke server
                        const success = await updateQuantityServer(cartId, newQty);

                        if (!success) {
                            // Jika gagal, kembalikan ke nilai sebelumnya
                            updateQuantityDisplay(cartId, currentQty);
                        }
                    } else {
                        // Tampilkan pesan peringatan
                        showNotification(`Tidak bisa menambah quantity karena melebihi stok tersedia (${maxQty} item)`, 'error');
                    }
                });
            });
        }

        // Fungsi untuk update tampilan quantity secara lokal
        function updateQuantityDisplay(cartId, newQuantity) {
            const quantityValue = document.getElementById(`quantity-value-${cartId}`);
            const decreaseBtn = document.getElementById(`decrease-qty-${cartId}`);
            const increaseBtn = document.getElementById(`increase-qty-${cartId}`);

            if (quantityValue) {
                quantityValue.textContent = newQuantity;
            }

            // Update data attributes
            if (decreaseBtn) {
                decreaseBtn.setAttribute('data-current-qty', newQuantity);
                decreaseBtn.disabled = (newQuantity <= 1);
            }

            if (increaseBtn) {
                increaseBtn.setAttribute('data-current-qty', newQuantity);
                const maxQty = parseInt(increaseBtn.getAttribute('data-max-qty'));
                increaseBtn.disabled = (newQuantity >= maxQty);

                // Tambahkan styling untuk tombol yang disabled
                if (newQuantity >= maxQty) {
                    increaseBtn.style.opacity = '0.5';
                    increaseBtn.style.cursor = 'not-allowed';
                } else {
                    increaseBtn.style.opacity = '1';
                    increaseBtn.style.cursor = 'pointer';
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeQuantityControls();
        });
    </script>
</body>
</html>
