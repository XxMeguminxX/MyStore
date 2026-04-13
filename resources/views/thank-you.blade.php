<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Pembayaran — E Store ID</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }

    :root {
      --accent:        #6C63FF;
      --accent-light:  #EEF0FF;
      --accent-soft:   rgba(108,99,255,0.12);
      --border:        #EEEEEE;
      --bg-card:       #F7F7F7;
      --badge-bg:      #F0F0F0;
      --text:          #111111;
      --text-2:        #666666;
      --text-3:        #999999;
      --success-bg:    #ECFDF5;
      --success-border:#A7F3D0;
      --success-text:  #059669;
      --danger:        #EF4444;
      --r-card:        16px;
      --r-pill:        999px;
      --shadow-sm:     0 2px 12px rgba(0,0,0,0.06);
      --shadow-md:     0 4px 24px rgba(0,0,0,0.08);
      --t:             0.22s ease;
    }

    body {
      font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
      background: var(--bg-card);
      color: var(--text);
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }
    a { text-decoration: none; color: inherit; }
    button { font-family: inherit; }

    /* ── WRAPPER ── */
    .wrap {
      width: 100%;
      max-width: 440px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      animation: fadeUp 0.3s ease;
    }

    /* ── LOGO ── */
    .logo {
      display: flex; align-items: center; gap: 8px;
      padding: 0 4px;
    }
    .logo-icon {
      width: 32px; height: 32px;
      background: #111; border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      color: #fff; flex-shrink: 0;
    }
    .logo-name { font-size: 15px; font-weight: 700; letter-spacing: -0.3px; }

    /* ── HERO CARD ── */
    .hero-card {
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: var(--r-card);
      padding: 24px;
      box-shadow: var(--shadow-sm);
    }

    /* state modifier borders */
    .hero-card.pending { border-color: var(--border); }
    .hero-card.success { border-color: var(--success-border); background: var(--success-bg); }
    .hero-card.expired { border-color: #FECACA; background: #FEF2F2; }

    .hero-top {
      display: flex; align-items: flex-start; justify-content: space-between;
      margin-bottom: 16px;
    }
    .hero-label {
      font-size: 12px; font-weight: 600; color: var(--text-2);
      letter-spacing: 0.2px;
    }

    /* status pill */
    .status-pill {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 12px; border-radius: var(--r-pill);
      font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
    }
    .pill-pending { background: #FFF7ED; color: #EA580C; border: 1.5px solid #FED7AA; }
    .pill-success { background: var(--success-bg); color: var(--success-text); border: 1.5px solid var(--success-border); }
    .pill-expired { background: #FEF2F2; color: var(--danger); border: 1.5px solid #FECACA; }
    .pill-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
    .pill-pending .pill-dot { animation: dotPulse 1.4s ease-in-out infinite; }

    /* amount */
    .hero-amount {
      font-size: 40px; font-weight: 800;
      letter-spacing: -1.5px; line-height: 1;
      margin-bottom: 16px;
      color: var(--text);
    }
    .hero-card.success .hero-amount { color: var(--success-text); }
    .hero-card.expired .hero-amount { color: var(--danger); }

    /* timer */
    .timer-row {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 14px;
      background: var(--bg-card);
      border-radius: 10px;
      border: 1.5px solid var(--border);
    }
    .timer-label { font-size: 12px; font-weight: 600; color: var(--text-2); }
    .timer-value {
      font-size: 20px; font-weight: 800;
      letter-spacing: -0.5px;
      font-variant-numeric: tabular-nums;
      color: var(--success-text);
      transition: color var(--t);
      margin-left: auto;
    }
    .timer-value.orange { color: #EA580C; }
    .timer-value.red    { color: var(--danger); }

    /* redirect row */
    .redirect-row {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 14px;
      background: var(--success-bg);
      border-radius: 10px;
      border: 1.5px solid var(--success-border);
      font-size: 13px; font-weight: 600; color: var(--success-text);
    }
    .redirect-count {
      display: inline-flex; align-items: center; justify-content: center;
      width: 22px; height: 22px; border-radius: 50%;
      background: var(--success-text); color: #fff;
      font-size: 11px; font-weight: 700; flex-shrink: 0;
    }

    /* success / expired message */
    .hero-sub {
      margin-top: 12px;
      font-size: 13px; color: var(--text-2);
    }

    /* ── PAY SECTION ── */
    .pay-card {
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: var(--r-card);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    .pay-card-header {
      display: flex; align-items: center; gap: 8px;
      padding: 12px 20px;
      background: var(--bg-card);
      border-bottom: 1.5px solid var(--border);
      font-size: 12px; font-weight: 700;
      color: var(--text-2); letter-spacing: 0.3px;
      text-transform: uppercase;
    }
    .pay-card-body {
      padding: 20px;
      display: flex; flex-direction: column; align-items: center; gap: 12px;
    }

    /* QR frame */
    .qr-frame {
      width: 164px; height: 164px;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      background: #fff;
      display: flex; align-items: center; justify-content: center;
      overflow: hidden;
    }
    .qr-frame img { width: 100%; height: 100%; object-fit: contain; }

    /* pay code */
    .paycode {
      font-size: 24px; font-weight: 800;
      letter-spacing: 2px; color: var(--text);
      font-variant-numeric: tabular-nums;
      padding: 10px 20px;
      background: var(--bg-card);
      border-radius: 10px;
      border: 1.5px solid var(--border);
    }

    /* instruction */
    .pay-instruction {
      display: flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 600; color: var(--text-2);
      flex-wrap: wrap; justify-content: center;
    }
    .pay-instruction strong { color: var(--text); }
    .pay-arrow { color: var(--text-3); }

    /* ── BUTTONS ── */
    .btn-primary {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; padding: 14px 20px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 12px;
      font-family: inherit; font-size: 15px; font-weight: 700;
      cursor: pointer; transition: background var(--t), box-shadow var(--t), transform 0.15s;
      box-shadow: 0 4px 16px rgba(108,99,255,0.28);
      text-decoration: none;
    }
    .btn-primary:hover { background: #5b53ee; box-shadow: 0 6px 20px rgba(108,99,255,0.36); transform: translateY(-1px); }
    .btn-primary:active { transform: translateY(0); }
    .btn-primary.success-btn { background: var(--success-text); box-shadow: 0 4px 16px rgba(5,150,105,0.25); }
    .btn-primary.success-btn:hover { background: #047857; }
    .btn-primary.expired-btn { background: var(--text); box-shadow: none; }
    .btn-primary.expired-btn:hover { background: #333; }

    .btn-text {
      display: inline-flex; align-items: center; justify-content: center; gap: 5px;
      background: none; border: none; cursor: pointer;
      font-size: 13px; font-weight: 600; color: var(--accent);
      transition: opacity var(--t); padding: 4px 0;
      text-decoration: none;
    }
    .btn-text:hover { opacity: 0.75; }

    /* ── CTA BLOCK ── */
    .cta-block {
      display: flex; flex-direction: column; align-items: center; gap: 10px;
    }

    /* ── ACCORDION ── */
    .accordion {
      background: #fff;
      border: 1.5px solid var(--border);
      border-radius: var(--r-card);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
    }
    .accordion-trigger {
      width: 100%; background: none; border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 20px;
      font-size: 13px; font-weight: 700; color: var(--text);
      transition: background var(--t);
    }
    .accordion-trigger:hover { background: var(--bg-card); }
    .acc-chevron { color: var(--text-3); transition: transform 0.22s; flex-shrink: 0; }
    .accordion-trigger[aria-expanded="true"] .acc-chevron { transform: rotate(180deg); }

    .accordion-body { display: none; border-top: 1.5px solid var(--border); }
    .accordion-body.open { display: block; }

    .detail-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 10px 20px; font-size: 13px;
      border-bottom: 1px solid var(--border);
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: var(--text-2); font-weight: 500; }
    .detail-value { font-weight: 700; color: var(--text); text-align: right; max-width: 58%; word-break: break-all; }

    /* ── SUCCESS ICON ── */
    .success-icon {
      width: 64px; height: 64px; border-radius: 50%;
      background: var(--success-bg); color: var(--success-text);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 4px;
      animation: popIn 0.38s cubic-bezier(0.22,1,0.36,1), breathe 3.6s ease-in-out 0.6s infinite;
      position: relative;
    }
    .success-icon::after {
      content: ""; position: absolute; inset: 0; border-radius: 50%;
      animation: ring 2.2s ease-out 0.9s infinite;
    }
    .checkmark-path {
      stroke-dasharray: 100; stroke-dashoffset: 100;
      animation: drawLoop 2.8s ease-in-out 0.28s infinite;
    }

    /* ── FOOTER LINKS ── */
    .footer-links {
      display: flex; align-items: center; justify-content: center; gap: 12px;
      padding: 4px 0;
    }
    .footer-link {
      font-size: 12px; font-weight: 600; color: var(--text-3);
      transition: color var(--t);
      display: flex; align-items: center; gap: 4px;
    }
    .footer-link:hover { color: var(--text-2); }
    .footer-sep { color: var(--border); }

    /* ── ANIMATIONS ── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes dotPulse {
      0%, 100% { opacity: 1; } 50% { opacity: 0.3; }
    }
    @keyframes popIn {
      from { transform: scale(0.8); opacity: 0; }
      to   { transform: scale(1);   opacity: 1; }
    }
    @keyframes breathe {
      0%, 100% { transform: scale(1); } 50% { transform: scale(1.04); }
    }
    @keyframes ring {
      0%   { box-shadow: 0 0 0 0    rgba(5,150,105,0.3); opacity: 1; }
      70%  { box-shadow: 0 0 0 14px rgba(5,150,105,0);   opacity: 0.3; }
      100% { box-shadow: 0 0 0 14px rgba(5,150,105,0);   opacity: 0; }
    }
    @keyframes drawLoop {
      0%   { stroke-dashoffset: 100; } 35% { stroke-dashoffset: 0; }
      65%  { stroke-dashoffset: 0; }   100% { stroke-dashoffset: 100; }
    }

    @media (prefers-reduced-motion: reduce) {
      .success-icon, .success-icon::after, .checkmark-path, .pill-dot { animation: none !important; }
    }
    @media (max-width: 480px) {
      .hero-amount { font-size: 32px; }
      .timer-value { font-size: 17px; }
    }
  </style>
  <!-- Phosphor Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/bold/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/duotone/style.css">
</head>
<body>

@php
  $st          = isset($transaction) ? strtoupper($transaction->status) : '';
  $isSuccess   = in_array($st, ['PAID', 'SETTLED']);
  $isExpired   = $st === 'EXPIRED';
  $isPending   = !$isSuccess && !$isExpired;

  $payUrl      = isset($transaction) ? ($transaction->payment_url ?? null) : null;
  $data        = $transaction->response['data'] ?? [];
  $expiredTime = $data['expired_time'] ?? null;
  $qrUrl       = $data['qr_url']      ?? null;
  $payCode     = $data['pay_code']    ?? null;
  $payMethod   = isset($transaction) ? $transaction->payment_method : '';
  $amount      = isset($transaction) ? $transaction->amount : 0;
  $productName = isset($transaction) ? $transaction->getProductName() : null;
  $isQris      = str_contains(strtolower($payMethod), 'qris');
@endphp

<div class="wrap">

  <!-- Logo -->
  <div class="logo">
    <div class="logo-icon">
      <i class="ph-bold ph-lightning"></i>
    </div>
    <span class="logo-name">E Store ID</span>
  </div>

  <!-- ① Hero Card -->
  <div class="hero-card {{ $isSuccess ? 'success' : ($isExpired ? 'expired' : 'pending') }}">

    <div class="hero-top">
      <span class="hero-label">Total Pembayaran</span>
      <span class="status-pill {{ $isSuccess ? 'pill-success' : ($isExpired ? 'pill-expired' : 'pill-pending') }}" id="statusPill">
        <span class="pill-dot"></span>
        @if($isSuccess) Lunas
        @elseif($isExpired) Kedaluwarsa
        @else Menunggu pembayaran
        @endif
      </span>
    </div>

    @if($isSuccess)
      <div class="success-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path class="checkmark-path" d="M20 6L9 17l-5-5"/>
        </svg>
      </div>
    @endif

    <div class="hero-amount">Rp {{ number_format($amount, 0, ',', '.') }}</div>

    @if($isPending && $expiredTime)
      <div class="timer-row">
        <i class="ph-bold ph-clock"></i>
        <span class="timer-label">Bayar dalam</span>
        <span class="timer-value" id="timerValue">--:--:--</span>
      </div>
    @endif

    @if($isSuccess)
      <div class="redirect-row">
        <span class="redirect-count" id="redirectCount">5</span>
        Diarahkan ke riwayat transaksi...
      </div>
      <p class="hero-sub">Pembayaran diterima. Terima kasih!</p>
    @endif

    @if($isExpired)
      <p class="hero-sub" style="color:#EF4444;">Waktu pembayaran telah habis. Silakan buat transaksi baru.</p>
    @endif
  </div>

  <!-- ② QR / Pay Code -->
  @if($isPending)
    <div class="pay-card">
      <div class="pay-card-header">
        @if($isQris)
          <i class="ph-bold ph-qr-code"></i>
          Scan QR — {{ $payMethod }}
        @elseif($payCode)
          <i class="ph-bold ph-credit-card"></i>
          Kode Bayar — {{ $payMethod }}
        @else
          <i class="ph-bold ph-info"></i>
          {{ $payMethod }}
        @endif
      </div>
      <div class="pay-card-body">
        @if($isQris && $qrUrl)
          <div class="qr-frame">
            <img src="{{ $qrUrl }}" alt="QR Code Pembayaran">
          </div>
        @elseif($payCode)
          <div class="paycode">{{ $payCode }}</div>
        @endif

        <div class="pay-instruction">
          <strong>Buka e-wallet / Bank yang digunakan</strong>
          <span class="pay-arrow">→</span>
          <strong>{{ $isQris ? 'Scan QR' : 'Masukkan kode' }}</strong>
          <span class="pay-arrow">→</span>
          <strong>Konfirmasi</strong>
        </div>
      </div>
    </div>
  @endif

  <!-- ③ CTA -->
  <div class="cta-block">
    @if($isPending && $payUrl)
      <a href="{{ $payUrl }}" class="btn-primary" target="_blank" rel="noopener">
        <i class="ph-bold ph-credit-card"></i>
        Bayar Sekarang
      </a>
      @if($isQris && $qrUrl)
        <a href="{{ $qrUrl }}" class="btn-text" download>
          <i class="ph-bold ph-download-simple"></i>
          Unduh QR
        </a>
      @endif
    @elseif($isSuccess)
      <a href="{{ route('transaction.history') }}" class="btn-primary success-btn">
        <i class="ph-bold ph-clock-counter-clockwise"></i>
        Lihat Riwayat Transaksi
      </a>
    @elseif($isExpired)
      <a href="{{ url('/') }}" class="btn-primary expired-btn">
        <i class="ph-bold ph-house"></i>
        Kembali ke Beranda
      </a>
    @endif
  </div>

  <!-- ④ Accordion Detail -->
  @if(isset($transaction))
  <div class="accordion">
    <button class="accordion-trigger" id="accBtn" aria-expanded="false" onclick="toggleAcc()">
      <span>Detail Transaksi</span>
      <i class="ph-bold ph-caret-down acc-chevron"></i>
    </button>
    <div class="accordion-body" id="accBody">
      @if($productName)
        <div class="detail-row">
          <span class="detail-label">Produk</span>
          <span class="detail-value">{{ $productName }}</span>
        </div>
      @endif
      <div class="detail-row">
        <span class="detail-label">Referensi</span>
        <span class="detail-value">{{ $merchant_ref ?? '-' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Nama</span>
        <span class="detail-value">{{ $transaction->customer_name }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Email</span>
        <span class="detail-value">{{ $transaction->customer_email }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Metode</span>
        <span class="detail-value">{{ $transaction->payment_method }}</span>
      </div>
    </div>
  </div>
  @endif

  <!-- ⑤ Footer Links -->
  <div class="footer-links">
    <a href="{{ url('/') }}" class="footer-link">
      <i class="ph-bold ph-house"></i>
      Beranda
    </a>
    <span class="footer-sep">·</span>
    <a href="{{ route('transaction.history') }}" class="footer-link">
      <i class="ph-bold ph-clock-counter-clockwise"></i>
      Riwayat Transaksi
    </a>
  </div>

</div>

<script>
const MERCHANT_REF = @json($merchant_ref ?? null);
const IS_SUCCESS   = @json($isSuccess);
const IS_EXPIRED   = @json($isExpired);
const EXPIRED_TIME = @json($expiredTime);
const HISTORY_URL  = @json(route('transaction.history'));

/* ── ACCORDION ── */
function toggleAcc() {
  const btn  = document.getElementById('accBtn');
  const body = document.getElementById('accBody');
  const open = btn.getAttribute('aria-expanded') === 'true';
  btn.setAttribute('aria-expanded', !open);
  body.classList.toggle('open', !open);
}

/* ── AUTO-REDIRECT (success) ── */
if (IS_SUCCESS) {
  let s = 5;
  const el = document.getElementById('redirectCount');
  const t  = setInterval(() => {
    s--;
    if (el) el.textContent = s;
    if (s <= 0) { clearInterval(t); window.location.href = HISTORY_URL; }
  }, 1000);
}

/* ── COUNTDOWN + COLOR STATE ── */
if (!IS_SUCCESS && !IS_EXPIRED && EXPIRED_TIME) {
  const el = document.getElementById('timerValue');

  function tick() {
    const diff = EXPIRED_TIME - Math.floor(Date.now() / 1000);
    if (diff <= 0) {
      clearInterval(iv);
      if (el) { el.textContent = '00:00:00'; el.className = 'timer-value red'; }
      return;
    }
    const h = Math.floor(diff / 3600);
    const m = Math.floor((diff % 3600) / 60);
    const s = diff % 60;
    if (el) {
      el.textContent = [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
      el.className   = diff < 300 ? 'timer-value red' : diff < 600 ? 'timer-value orange' : 'timer-value';
    }
  }
  tick();
  const iv = setInterval(tick, 1000);
}

/* ── POLLING STATUS ── */
if (!IS_SUCCESS && !IS_EXPIRED && MERCHANT_REF) {
  async function poll() {
    try {
      const r = await fetch(`/payment/check-status?merchant_ref=${encodeURIComponent(MERCHANT_REF)}`);
      if (!r.ok) return;
      const d = await r.json();
      if (['PAID','SETTLED','EXPIRED'].includes((d.status||'').toUpperCase())) {
        clearInterval(pi);
        window.location.reload();
      }
    } catch (_) {}
  }
  const pi = setInterval(poll, 5000);
}
</script>

</body>
</html>
