<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Histori Transaksi - E Store ID</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-history.css') }}">
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
                                    <div class="detail-label">Nama {{ $transaction->isDonation() ? 'Donasi' : 'Produk' }}</div>
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

    <!-- WhatsApp CS Floating Button -->
    <a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat Customer Service via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>

</html> 