<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Transaksi — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}?v={{ time() }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <style>
    /* ===== TRANSACTION DETAIL PAGE ===== */
    .tx-page-title { font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 20px; }

    /* Summary bar */
    .tx-summary {
      background: #fff; border: 1.5px solid var(--border);
      border-radius: var(--r-card); padding: 24px 28px;
      display: grid; grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 24px; margin-bottom: 20px; align-items: center;
    }
    .tx-summary-label {
      font-size: 10.5px; font-weight: 700; color: var(--text-3);
      text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 6px;
    }
    .tx-summary-ref { font-size: 15px; font-weight: 800; color: var(--text); font-family: monospace; }
    .tx-summary-value { font-size: 17px; font-weight: 800; color: var(--text); }
    .tx-summary-channel { font-size: 14px; font-weight: 700; color: var(--text); }

    /* Status badges */
    .tx-status-badge {
      display: inline-block; padding: 5px 14px; border-radius: 6px;
      font-size: 12px; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase;
    }
    .tx-status-paid, .tx-status-settled { background: #16a34a; color: #fff; }
    .tx-status-unpaid { background: #d97706; color: #fff; }
    .tx-status-failed, .tx-status-expired { background: #dc2626; color: #fff; }

    /* Detail sections */
    .detail-section {
      background: #fff; border: 1.5px solid var(--border);
      border-radius: var(--r-card); margin-bottom: 20px; overflow: hidden;
    }
    .detail-section-header { padding: 16px 24px; border-bottom: 1px solid var(--border); }
    .detail-section-title { font-size: 15px; font-weight: 700; color: var(--text); }
    .detail-section-body { padding: 0 24px; }

    /* Two-column grid */
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; }
    .detail-col:first-child { border-right: 1px solid var(--border); padding-right: 32px; }
    .detail-col:last-child { padding-left: 32px; }
    .detail-row {
      display: flex; align-items: baseline; gap: 0;
      padding: 14px 0; border-bottom: 1px solid var(--border);
    }
    .detail-col .detail-row:last-child { border-bottom: none; }
    .detail-label {
      font-size: 13.5px; font-weight: 600; color: var(--text);
      min-width: 180px; flex-shrink: 0;
    }
    .detail-sep { font-size: 13.5px; color: var(--text-2); margin-right: 8px; }
    .detail-value { font-size: 13.5px; color: var(--text-2); }
    .detail-value-red { color: #dc2626; font-weight: 600; }

    /* Order table */
    .order-table { width: 100%; border-collapse: collapse; }
    .order-table thead tr { border-bottom: 1.5px solid var(--border); }
    .order-table th {
      padding: 14px 24px; text-align: left;
      font-size: 12.5px; font-weight: 700; color: var(--text-2);
      text-transform: uppercase; letter-spacing: 0.3px;
    }
    .order-table th:nth-child(3),
    .order-table th:nth-child(4),
    .order-table th:nth-child(5),
    .order-table td:nth-child(3),
    .order-table td:nth-child(4),
    .order-table td:nth-child(5) { text-align: right; }
    .order-table td {
      padding: 18px 24px; font-size: 13.5px; color: var(--text);
      border-bottom: 1px solid var(--border);
    }
    .order-table tbody tr:last-child td { border-bottom: none; }
    .order-table tfoot td {
      padding: 16px 24px; font-size: 13.5px; font-weight: 700;
      border-top: 1.5px solid var(--border);
    }
    .order-table tfoot td:last-child { font-size: 15px; font-weight: 800; }

    /* Payment banner (UNPAID) */
    .pay-banner {
      display: flex; align-items: center; justify-content: space-between; gap: 20px;
      background: #fffbeb; border: 1.5px solid #fde68a;
      border-radius: var(--r-card); padding: 18px 24px; margin-bottom: 20px;
      flex-wrap: wrap;
    }
    .pay-banner-left { display: flex; align-items: center; gap: 14px; }
    .pay-banner-icon {
      width: 40px; height: 40px; border-radius: 50%; background: #fef3c7;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .pay-banner-title { font-size: 14px; font-weight: 700; color: #92400e; margin-bottom: 2px; }
    .pay-banner-sub { font-size: 12.5px; color: #b45309; }
    .btn-pay-banner {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 11px 24px; border-radius: var(--r-pill);
      background: #111; color: #fff;
      font-family: inherit; font-size: 14px; font-weight: 700;
      text-decoration: none; white-space: nowrap;
      transition: background var(--t);
    }
    .btn-pay-banner:hover { background: #333; color: #fff; }

    /* Actions */
    .tx-actions { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .btn-back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 600; color: var(--text-2); text-decoration: none; padding: 12px 0; transition: color var(--t); }
    .btn-back-link:hover { color: var(--text); }

    @media (max-width: 560px) {
      .pay-banner { flex-direction: column; align-items: flex-start; }
      .btn-pay-banner { width: 100%; justify-content: center; }
    }

    /* Responsive */
    @media (max-width: 900px) {
      .tx-summary { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
      .detail-grid { grid-template-columns: 1fr; }
      .detail-col:first-child { border-right: none; border-bottom: 1px solid var(--border); padding-right: 0; padding-bottom: 4px; }
      .detail-col:last-child { padding-left: 0; padding-top: 4px; }
      .detail-label { min-width: 140px; }
      .order-table th:nth-child(3),
      .order-table th:nth-child(4),
      .order-table td:nth-child(3),
      .order-table td:nth-child(4) { display: none; }
    }
    @media (max-width: 480px) {
      .tx-summary { grid-template-columns: 1fr; gap: 16px; }
      .tx-page-header { flex-direction: column; align-items: flex-start; }
    }
  </style>
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
      <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang">
        <i class="ph-bold ph-shopping-bag"></i>
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
  <div class="nav-mobile-btns">
    <a href="{{ route('cart.index') }}" class="m-login">Keranjang</a>
    <a href="{{ url('/') }}" class="m-register">Belanja</a>
  </div>
</div>

<!-- MAIN -->
<div class="page-wrap">

  <!-- Page header -->
  <div class="breadcrumb">
    <a href="{{ url('/') }}">Beranda</a>
    <span class="breadcrumb-sep">›</span>
    <a href="{{ route('profile') }}?tab=transactions">Histori Transaksi</a>
    <span class="breadcrumb-sep">›</span>
    <span class="breadcrumb-cur">Detail</span>
  </div>
  <h1 class="tx-page-title">Detail Transaksi</h1>

  <!-- Summary bar -->
  <div class="tx-summary">
    <div>
      <div class="tx-summary-label">No Referensi</div>
      <div class="tx-summary-ref">{{ $tripayRef }}</div>
    </div>
    <div>
      <div class="tx-summary-label">Jumlah Dibayar</div>
      <div class="tx-summary-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
    </div>
    <div>
      <div class="tx-summary-label">Channel</div>
      <div class="tx-summary-channel">{{ $paymentName }}</div>
    </div>
    <div>
      <div class="tx-summary-label">Status</div>
      <span class="tx-status-badge tx-status-{{ strtolower($transaction->status) }}">{{ $statusLabel }}</span>
    </div>
  </div>

  @if($transaction->status === 'UNPAID' && !empty($transaction->payment_url))
  <!-- Payment banner -->
  <div class="pay-banner">
    <div class="pay-banner-left">
      <div class="pay-banner-icon">
        <i class="ph-bold ph-clock" style="color:#d97706;font-size:20px;"></i>
      </div>
      <div>
        <div class="pay-banner-title">Menunggu Pembayaran</div>
        <div class="pay-banner-sub">Selesaikan pembayaran sebelum batas waktu habis{{ $expiredTime ? ' — ' . $expiredTime : '' }}</div>
      </div>
    </div>
    <a href="{{ $transaction->payment_url }}" target="_blank" rel="noopener noreferrer" class="btn-pay-banner">
      <i class="ph-bold ph-credit-card"></i>
      Bayar Sekarang
    </a>
  </div>
  @endif

  <!-- Detail Pembayaran -->
  <div class="detail-section">
    <div class="detail-section-header">
      <span class="detail-section-title">Detail Pembayaran</span>
    </div>
    <div class="detail-section-body">
      <div class="detail-grid">

        <!-- Kolom kiri -->
        <div class="detail-col">
          <div class="detail-row">
            <span class="detail-label">No. Invoice</span>
            <span class="detail-sep">:</span>
            <span class="detail-value">{{ $transaction->merchant_ref }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Tanggal Transaksi</span>
            <span class="detail-sep">:</span>
            <span class="detail-value">{{ $transaction->created_at->format('d-m-Y H:i:s') }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Total Pembayaran</span>
            <span class="detail-sep">:</span>
            <span class="detail-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
          </div>
        </div>

        <!-- Kolom kanan -->
        <div class="detail-col">
          <div class="detail-row">
            <span class="detail-label">Metode Pembayaran</span>
            <span class="detail-sep">:</span>
            <span class="detail-value">{{ $paymentName }}</span>
          </div>
          @if($expiredTime && $transaction->status === 'UNPAID')
          <div class="detail-row">
            <span class="detail-label">Batas Pembayaran</span>
            <span class="detail-sep">:</span>
            <span class="detail-value detail-value-red">{{ $expiredTime }}</span>
          </div>
          @endif
          @if($paidAt)
          <div class="detail-row">
            <span class="detail-label">Dibayar Pada</span>
            <span class="detail-sep">:</span>
            <span class="detail-value">{{ $paidAt }}</span>
          </div>
          @endif
        </div>

      </div>
    </div>
  </div>

  <!-- Detail Pesanan -->
  <div class="detail-section">
    <div class="detail-section-header">
      <span class="detail-section-title">Detail Pesanan</span>
    </div>
    <table class="order-table">
      <thead>
        <tr>
          <th>SKU</th>
          <th>Nama</th>
          <th>Harga Satuan</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @if(!empty($orderItems))
          @foreach($orderItems as $item)
          <tr>
            <td>{{ $item['sku'] ?? '-' }}</td>
            <td>{{ $item['name'] ?? '-' }}</td>
            <td>Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
            <td>{{ $item['quantity'] ?? 1 }}</td>
            <td>Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}</td>
          </tr>
          @endforeach
        @elseif($isPulsa)
          <tr>
            <td>{{ $transaction->product_code ?? '-' }}</td>
            <td>{{ $transaction->product_name }} → {{ $transaction->phone }}</td>
            <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
            <td>1</td>
            <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
          </tr>
        @else
          @php $qty = $transaction->quantity ?? 1; @endphp
          <tr>
            <td>{{ $transaction->product_id ?? '-' }}</td>
            <td>{{ $transaction->getProductName() }}</td>
            <td>Rp {{ number_format((int)($transaction->amount / $qty), 0, ',', '.') }}</td>
            <td>{{ $qty }}</td>
            <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
          </tr>
        @endif
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4" style="text-align:right;">Grand Total</td>
          <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
        </tr>
      </tfoot>
    </table>
  </div>

  <!-- Back -->
  <div class="tx-actions">
    <a href="{{ route('profile') }}?tab=transactions" class="btn-back-link">
      ← Kembali ke Histori Transaksi
    </a>
  </div>

</div>

<!-- WhatsApp -->
<a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat via WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
  </svg>
</a>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

const hamburger = document.getElementById('hamburger');
const navMobile = document.getElementById('navMobile');
hamburger?.addEventListener('click', () => {
  hamburger.classList.toggle('open');
  navMobile.classList.toggle('open');
});

const navUser = document.getElementById('navUser');
const navUserMenu = document.getElementById('navUserMenu');
if (navUser && navUserMenu) {
  navUser.addEventListener('click', e => { e.stopPropagation(); navUserMenu.classList.toggle('open'); });
  document.addEventListener('click', e => { if (!navUser.contains(e.target)) navUserMenu.classList.remove('open'); });
}
</script>

</body>
</html>
