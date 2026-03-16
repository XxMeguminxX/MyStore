@extends('layouts.static-page')

@section('title', $pageTitle)

@section('content')
<h1>Pertanyaan Umum (FAQ)</h1>

<h2>Bagaimana cara membeli produk?</h2>
<p>Daftar atau login ke akun Anda, pilih produk yang diinginkan, lalu lanjutkan ke checkout. Pilih metode pembayaran dan selesaikan pembayaran. Produk digital akan dikirim/diaktifkan setelah pembayaran terverifikasi.</p>

<h2>Metode pembayaran apa saja yang diterima?</h2>
<p>Kami menerima pembayaran melalui channel yang terintegrasi (misalnya transfer bank, e-wallet, QRIS) sesuai yang ditampilkan di halaman checkout.</p>

<h2>Kapan produk digital saya dikirim?</h2>
<p>Untuk produk digital, informasi atau kode/akses umumnya dikirim setelah pembayaran dikonfirmasi oleh sistem, atau dapat dilihat di halaman Histori Transaksi dan email konfirmasi.</p>

<h2>Bagaimana jika pembayaran saya belum terkonfirmasi?</h2>
<p>Pastikan Anda telah menyelesaikan pembayaran sesuai nominal dan tenggat waktu. Jika sudah bayar tetapi status belum berubah, hubungi kami via halaman <a href="{{ route('static.page', 'kontak') }}">Kontak</a> dengan bukti pembayaran.</p>

<h2>Apakah data saya aman?</h2>
<p>Kami memproses data sesuai <a href="{{ route('static.page', 'kebijakan-privasi') }}">Kebijakan Privasi</a>. Data pembayaran ditangani oleh penyedia pembayaran yang memenuhi standar keamanan.</p>
@endsection
