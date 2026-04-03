<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
    <style>
        .thankyou-container { max-width: 520px; margin: 56px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 32px 28px; text-align: center; }
        .thankyou-icon { position: relative; width: 86px; height: 86px; margin: 8px auto 12px auto; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 14px rgba(0,0,0,0.15); animation: popIn 380ms cubic-bezier(0.22,1,0.36,1), breathe 3.6s ease-in-out 600ms infinite; background: var(--bg, #e6f9f1); color: var(--accent, #2a9d8f); }
        .thankyou-icon svg { color: currentColor; filter: drop-shadow(0 2px 8px rgba(var(--accent-rgb, 42,157,143), 0.28)); }
        .thankyou-icon::after { content: ""; position: absolute; inset: 0; border-radius: 50%; box-shadow: 0 0 0 0 rgba(var(--accent-rgb, 42,157,143), 0.35); animation: ring 2.2s ease-out 900ms infinite; }
        .thankyou-icon.is-success { --accent: #2a9d8f; --accent-rgb: 42,157,143; --bg: #e6f9f1; }
        .thankyou-icon.is-pending { --accent: #f4a261; --accent-rgb: 244,162,97; --bg: #fff5e9; }
        .thankyou-title { color: #2a9d8f; font-size: 1.6em; margin: 10px 0 6px 0; letter-spacing: 0.5px; }
        .thankyou-subtitle { color: #555; margin-bottom: 18px; }
        .thankyou-details { background: #f7f8fa; border: 1px solid #e0e0e0; border-radius: 10px; padding: 14px; text-align: left; margin: 18px 0; }
        .thankyou-details .row { display: flex; justify-content: space-between; margin: 6px 0; color: #333; }
        .thankyou-actions { display: flex; gap: 12px; justify-content: center; margin-top: 16px; flex-wrap: wrap; }
        .btn-primary { background: #2a9d8f; color: #fff; border: none; border-radius: 8px; padding: 12px 16px; font-size: 1em; font-weight: 600; cursor: pointer; text-decoration: none; box-shadow: 0 2px 8px rgba(42,157,143,0.18); }
        .btn-primary:hover { background: #21867a; }
        .btn-secondary { background: #fff; color: #2a9d8f; border: 1.5px solid #2a9d8f; border-radius: 8px; padding: 12px 16px; font-size: 1em; font-weight: 600; cursor: pointer; text-decoration: none; }
        .btn-pay { background: #f4a261; color: #fff; border: none; border-radius: 8px; padding: 12px 16px; font-size: 1em; font-weight: 600; cursor: pointer; text-decoration: none; box-shadow: 0 2px 8px rgba(244,162,97,0.25); }
        .btn-pay:hover { background: #e48f45; }
        .status-pill { display: inline-block; padding: 6px 10px; border-radius: 999px; font-weight: 600; font-size: 0.92em; }
        .status-paid { background: #e6f9f1; color: #21867a; border: 1.5px solid #2a9d8f; }
        .status-unpaid { background: #fff0f0; color: #d90429; border: 1.5px solid #d90429; }

        /* Checkmark animation (looping) */
        .checkmark-path {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: drawLoop 2.8s ease-in-out 280ms infinite;
        }
        /* Spinner (pending) */
        .spinner { position: relative; width: 34px; height: 34px; border-radius: 50%;
            border: 3px solid rgba(var(--accent-rgb, 42,157,143), .22);
            border-top-color: currentColor; border-right-color: currentColor;
            animation: spin 1s linear infinite; }
        .spinner::after { content: ""; position: absolute; inset: 3px; border-radius: 50%;
            border: 3px solid transparent; border-left-color: currentColor; opacity: .6;
            animation: spin 1.6s linear reverse infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes popIn {
            from { transform: scale(0.85); opacity: 0; }
            to   { transform: scale(1);    opacity: 1; }
        }
        @keyframes drawLoop {
            0%   { stroke-dashoffset: 100; }
            35%  { stroke-dashoffset: 0; }
            55%  { stroke-dashoffset: 0; }
            100% { stroke-dashoffset: 100; }
        }
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50%      { transform: scale(1.035); }
        }
        @keyframes ring {
            0%   { transform: scale(1);   box-shadow: 0 0 0 0 rgba(42,157,143,0.35); opacity: 1; }
            70%  { transform: scale(1.25); box-shadow: 0 0 0 12px rgba(42,157,143,0); opacity: 0.25; }
            100% { transform: scale(1.25); box-shadow: 0 0 0 12px rgba(42,157,143,0); opacity: 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            .thankyou-icon, .thankyou-icon::after, .checkmark-path, .spinner, .spinner::after { animation: none !important; }
        }
    </style>
    
</head>

<body>
    <a href="{{ url('/') }}" class="btn-back" title="Kembali ke Beranda" style="position: fixed; top: 24px; left: 24px;">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2a9d8f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 18l-6-6 6-6" />
        </svg>
    </a>
    <div class="thankyou-container">
        @php $st = isset($transaction) ? strtoupper($transaction->status) : ''; $isSuccess = in_array($st, ['PAID','SETTLED']); @endphp
        <div class="thankyou-icon {{ $isSuccess ? 'is-success' : 'is-pending' }}">
            @if($isSuccess)
                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path class="checkmark-path" d="M20 6L9 17l-5-5"/></svg>
            @else
                <div class="spinner"></div>
            @endif
        </div>
        @if($isSuccess)
            <div class="thankyou-title">Terima Kasih!</div>
            <div class="thankyou-subtitle">Pembayaran Anda telah kami terima.</div>
        @else
            <div class="thankyou-title">Silahkan selesaikan pesanan anda</div>
            <div class="thankyou-subtitle">Selesaikan pembayaran untuk memproses pesanan Anda.</div>
        @endif

        <div class="thankyou-details">
            <div class="row"><span>Nomor Referensi</span><strong>{{ $merchant_ref ?? '-' }}</strong></div>
            @if($transaction)
            <div class="row"><span>Nama</span><strong>{{ $transaction->customer_name }}</strong></div>
            <div class="row"><span>Email</span><strong>{{ $transaction->customer_email }}</strong></div>
            <div class="row"><span>Metode</span><strong>{{ $transaction->payment_method }}</strong></div>
            <div class="row"><span>Jumlah</span><strong>RP {{ number_format($transaction->amount,0,'','.') }}</strong></div>
            <div class="row"><span>Status</span>
                @php $st = strtoupper($transaction->status); @endphp
                <span id="payment-status" data-status="{{ $st }}" class="status-pill {{ in_array($st,['PAID','SETTLED']) ? 'status-paid' : 'status-unpaid' }}">{{ $st }}</span>
            </div>
            @else
            <div class="row"><span>Status</span><span id="payment-status" data-status="" class="status-pill status-unpaid">-</span></div>
            @endif
        </div>

        @php $payUrl = isset($transaction) ? ($transaction->payment_url ?? null) : null; @endphp
        <div class="thankyou-actions">
            @if(!$isSuccess && $payUrl)
                <a href="{{ $payUrl }}" class="btn-pay">Lanjutkan Pembayaran</a>
            @endif
            <a href="{{ url('/dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
            <a href="{{ url('/transaction-history') }}" class="btn-secondary">Lihat Riwayat</a>
        </div>
    </div>


    <!-- WhatsApp CS Floating Button -->
    <a href="https://wa.me/6285739188906" target="_blank" rel="noopener noreferrer" class="wa-float" title="Chat Customer Service via WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
</body>

</html>


