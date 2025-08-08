<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Histori Transaksi - E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-history.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <style>
       
    </style>
</head>

<body class="body-background-3d">
    <div class="header-bar">
        <div class="search-container">
            <input type="text" id="transactionSearch" onkeyup="filterTransactions()" placeholder="Cari transaksi..."
                class="search-input">
        </div>

        <div class="header-icons">
            <a href="{{ route('transaction.history') }}" class="icon-btn" title="Histori Transaksi">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </a>
            <a href="{{ url('/profile') }}" class="icon-btn" title="Profil">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="8" r="4" stroke-width="2" />
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" />
                </svg>
            </a>
        </div>
    </div>

    <h1>Histori Transaksi</h1>
    
    <div class="transaction-section">
        <div class="transaction-background"></div>
        <div class="transaction-content">
            <div style="margin-bottom: 20px;">
                <a href="{{ url('/dashboard') }}" class="back-btn">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            @if($transactions->count() > 0)
                <!-- Debug: Tampilkan status mentah -->
                <div class="debug-info">
                    <!-- <strong>Debug Info:</strong><br>
                    @foreach($transactions as $transaction)
                        @php
                            $rawStatus = $transaction->status;
                            $lowercaseStatus = strtolower($transaction->status ?? 'unpaid');
                            $statusClass = 'status-' . $lowercaseStatus;
                            $displayText = ucfirst($lowercaseStatus);
                        @endphp
                        Transaction ID: {{ $transaction->id }} | 
                        Raw Status: "{{ $rawStatus }}" | 
                        Lowercase: "{{ $lowercaseStatus }}" | 
                        CSS Class: "{{ $statusClass }}" | 
                        Display Text: "{{ $displayText }}"<br>
                    @endforeach -->
                </div>
                
                <div id="transactionsList">
                    @foreach($transactions as $transaction)
                        <div class="transaction-card" data-transaction-id="{{ $transaction->id }}">
                            <div class="transaction-header">
                                <div class="transaction-id">ID: {{ $transaction->id }}</div>
                                <div class="transaction-status status-{{ strtolower($transaction->status ?? 'unpaid') }}">
                                    {{ ucfirst(strtolower($transaction->status ?? 'Unpaid')) }}
                                </div>
                            </div>
                            
                            <!-- Tombol untuk update status manual (untuk testing) -->
                            <!-- <div style="margin-bottom: 10px; text-align: right;">
                                <button onclick="updateTransactionStatus({{ $transaction->id }}, 'PAID')" 
                                        style="background: #28a745; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; cursor: pointer; margin-right: 5px;">
                                    Set PAID
                                </button> -->
                                <!-- <button onclick="updateTransactionStatus({{ $transaction->id }}, 'EXPIRED')" 
                                        style="background: #dc3545; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; cursor: pointer; margin-right: 5px;">
                                    Set EXPIRED
                                </button>
                                <button onclick="updateTransactionStatus({{ $transaction->id }}, 'CANCELLED')" 
                                        style="background: #6c757d; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; cursor: pointer; margin-right: 5px;">
                                    Set CANCELLED
                                </button>
                                <button onclick="testCallback({{ $transaction->id }})" 
                                        style="background: #007bff; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; cursor: pointer;">
                                    Test Callback
                                </button>
                            </div> -->
                            
                            <div class="transaction-details">
                                <div class="detail-item">
                                    <div class="detail-label">Merchant Ref</div>
                                    <div class="detail-value">{{ $transaction->merchant_ref ?? 'N/A' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Total Bayar</div>
                                    <div class="detail-value">Rp {{ number_format($transaction->amount ?? 0, 0, '', '.') }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Metode Pembayaran</div>
                                    <div class="detail-value">{{ $transaction->payment_method ?? 'N/A' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Customer</div>
                                    <div class="detail-value">{{ $transaction->customer_name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            
                            <div class="transaction-date">
                                {{ $transaction->created_at ? $transaction->created_at->format('d M Y H:i') : 'N/A' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-transactions">
                    <h3>Belum ada transaksi</h3>
                    <p>Anda belum memiliki riwayat transaksi.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterTransactions() {
            const searchInput = document.getElementById('transactionSearch').value.toLowerCase();
            const transactionCards = document.querySelectorAll('.transaction-card');
            let visibleCount = 0;

            transactionCards.forEach(card => {
                const transactionId = card.querySelector('.transaction-id').textContent.toLowerCase();
                const merchantRef = card.querySelector('.detail-item:nth-child(1) .detail-value').textContent.toLowerCase();
                const totalAmount = card.querySelector('.detail-item:nth-child(2) .detail-value').textContent.toLowerCase();
                const paymentMethod = card.querySelector('.detail-item:nth-child(3) .detail-value').textContent.toLowerCase();
                const customerName = card.querySelector('.detail-item:nth-child(4) .detail-value').textContent.toLowerCase();
                const status = card.querySelector('.transaction-status').textContent.toLowerCase();

                if (transactionId.includes(searchInput) || 
                    merchantRef.includes(searchInput) || 
                    totalAmount.includes(searchInput) || 
                    paymentMethod.includes(searchInput) || 
                    customerName.includes(searchInput) || 
                    status.includes(searchInput)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada hasil
            const noTransactions = document.querySelector('.no-transactions');
            if (visibleCount === 0 && searchInput !== '') {
                if (!noTransactions) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-transactions';
                    noResults.innerHTML = '<h3>Tidak ada hasil</h3><p>Tidak ada transaksi yang sesuai dengan pencarian Anda.</p>';
                    document.querySelector('.transaction-content').appendChild(noResults);
                }
            } else if (noTransactions) {
                noTransactions.remove();
            }
        }

        function updateTransactionStatus(transactionId, newStatus) {
            if (confirm(`Apakah Anda yakin ingin mengubah status transaksi ID ${transactionId} menjadi ${newStatus}?`)) {
                fetch('/transaction/manual-update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        transaction_id: transactionId,
                        status: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Refresh halaman untuk melihat perubahan
                    } else {
                        alert('Gagal mengupdate status: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate status');
                });
            }
        }



        
        function testCallback(transactionId) {
            if (confirm(`Apakah Anda yakin ingin test callback untuk transaksi ID ${transactionId}?`)) {
                fetch(`/test-callback/${transactionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Callback test berhasil! Status transaksi akan diupdate menjadi PAID.');
                        location.reload(); // Refresh halaman untuk melihat perubahan
                    } else {
                        alert('Callback test gagal: ' + (data.error || data.response || 'Unknown error'));
                        console.log('Callback test details:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat test callback');
                });
            }
        }
    </script>
</body>

</html> 