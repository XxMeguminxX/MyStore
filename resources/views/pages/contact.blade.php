@extends('layouts.static-page')

@section('title', $pageTitle)

@section('content')
<h1>Kontak</h1>

<p>Jika Anda memiliki pertanyaan, keluhan, atau saran terkait layanan E Store ID, silakan hubungi kami melalui salah satu cara berikut.</p>

<h2>Email</h2>
<p>Email: <strong>support@estoreid.com</strong> (ganti dengan email resmi Anda)</p>
<p>Waktu respons: Senin–Jumat, 09:00–17:00 WIB.</p>

<h2>Media Sosial</h2>
<p>Ikuti dan hubungi kami melalui akun resmi media sosial untuk informasi terbaru dan bantuan cepat.</p>

<h2>Pertanyaan Umum</h2>
<p>Untuk pertanyaan yang sering diajukan (cara belanja, pembayaran, pengiriman produk digital), kunjungi halaman <a href="{{ route('static.page', 'faq') }}">Pertanyaan Umum (FAQ)</a>.</p>
@endsection
