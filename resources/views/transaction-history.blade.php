<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Histori Transaksi - E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-history.css') }}">
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/duotone/style.css">
</head>

<body class="body-background-3d page-account">
    <div class="header-bar">
        <a href="{{ url('/dashboard') }}" class="btn-kembali" aria-label="Kembali ke Dashboard">
            &larr; Dashboard
        </a>
        <div class="header-title">Histori Transaksi</div>
    </div>

    <div class="profile-container">
        <div class="page-heading">
            <h1>Histori Transaksi</h1>
            <div class="page-subtitle">Lihat status dan detail transaksi Anda.</div>
        </div>

        <div class="profile-layout">
            <aside class="profile-sidebar" aria-label="Menu Akun">
                <div class="sidebar-card">
                    <div class="sidebar-title">Menu</div>
                    <nav class="sidebar-nav">
                        <div class="sidebar-group">
                            <div class="sidebar-group-title">Profil & Transaksi</div>
                            <div class="sidebar-sublinks">
                                <a class="sidebar-link" href="{{ url('/profile') }}">Profil</a>
                                <a class="sidebar-link active" href="{{ route('transaction.history') }}">Histori Transaksi</a>
                            </div>
                        </div>
                        <a class="sidebar-link" href="{{ url('/dashboard') }}">Kembali ke Dashboard</a>
                    </nav>
                    <form method="POST" action="{{ route('logout') }}" class="sidebar-logout">
                        @csrf
                        <button type="submit" class="sidebar-link sidebar-link-danger">Logout</button>
                    </form>
                </div>
            </aside>

            <main class="profile-main">
                <div class="profile-content">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Daftar Transaksi</div>
                            <div class="card-subtitle">Gunakan pencarian untuk menemukan transaksi dengan cepat.</div>
                        </div>
                        <input type="text" id="transactionSearch" onkeyup="filterTransactions()" placeholder="Cari transaksi..." class="search-input" style="max-width: 320px;">
                    </div>

                    @if($transactions->count() > 0)
                {{-- <!-- Debug: Tampilkan status mentah -->
                <div class="debug-info">
                    <strong>Debug Info:</strong><br>
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
                    @endforeach
                </div> --}}
                
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
                            <div style="margin-bottom: 10px; text-align: right;">
                                {{-- <button onclick="updateTransactionStatus({{ $transaction->id }}, 'PAID')" 
                                        style="background: #28a745; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 0.8em; cursor: pointer; margin-right: 5px;">
                                    Set PAID
                                </button>
                                <button onclick="updateTransactionStatus({{ $transaction->id }}, 'EXPIRED')" 
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
                                </button> --}}
                            </div>
                            
                            <div class="transaction-details">
                                <div class="detail-item">
                                    <div class="detail-label">Nama Produk</div>
                                    <div class="detail-value">{{ $transaction->getProductName() }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Merchant Ref</div>
                                    <div class="detail-value">{{ $transaction->merchant_ref ?? 'N/A' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">Jumlah</div>
                                    <div class="detail-value">{{ $transaction->quantity ?? 1 }} item</div>
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
            </main>
        </div>
    </div>

    <script>
        function filterTransactions() {
            const searchEl = document.getElementById('transactionSearch');
            if (!searchEl) return;

            const query = searchEl.value.toLowerCase();
            const cards = document.querySelectorAll('#transactionsList .transaction-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const match = card.textContent.toLowerCase().includes(query);
                card.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            const container = document.querySelector('.profile-content');
            if (!container) return;

            const existingNoResults = container.querySelector('.no-transactions.no-results');
            if (visibleCount === 0 && query !== '') {
                if (!existingNoResults) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-transactions no-results';
                    noResults.innerHTML = '<h3>Tidak ada hasil</h3><p>Tidak ada transaksi yang sesuai dengan pencarian Anda.</p>';
                    container.appendChild(noResults);
                }
            } else if (existingNoResults) {
                existingNoResults.remove();
            }
        }

        // function updateTransactionStatus(transactionId, newStatus) {
        //     if (confirm(`Apakah Anda yakin ingin mengubah status transaksi ID ${transactionId} menjadi ${newStatus}?`)) {
        //         fetch('/transaction/manual-update-status', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //             },
        //             body: JSON.stringify({
        //                 transaction_id: transactionId,
        //                 status: newStatus
        //             })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 alert(data.message);
        //                 location.reload(); // Refresh halaman untuk melihat perubahan
        //             } else {
        //                 alert('Gagal mengupdate status: ' + (data.message || 'Unknown error'));
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //             alert('Terjadi kesalahan saat mengupdate status');
        //         });
        //     }
        // }



        
        // function testCallback(transactionId) {
        //     if (confirm(`Apakah Anda yakin ingin test callback untuk transaksi ID ${transactionId}?`)) {
        //         fetch(`/test-callback/${transactionId}`, {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //             }
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 alert('Callback test berhasil! Status transaksi akan diupdate menjadi PAID.');
        //                 location.reload(); // Refresh halaman untuk melihat perubahan
        //             } else {
        //                 alert('Callback test gagal: ' + (data.error || data.response || 'Unknown error'));
        //                 console.log('Callback test details:', data);
        //             }
        //         })
        //         .catch(error => {
        //             console.error('Error:', error);
        //             alert('Terjadi kesalahan saat test callback');
        //         });
        //     }
        // }
    </script>

</body>

</html> 