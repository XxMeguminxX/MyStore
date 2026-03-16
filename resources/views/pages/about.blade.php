@extends('layouts.static-page')

@section('title', $pageTitle)

@section('content')
<h1>Tentang Kami</h1>

<p>E Store ID adalah toko online yang fokus pada penjualan produk digital. Kami berkomitmen untuk memberikan pengalaman belanja yang aman, mudah, dan transparan bagi setiap pelanggan.</p>

<h2>Visi</h2>
<p>Menjadi platform terpercaya untuk pembelian produk digital dengan layanan berkualitas dan proses yang sederhana.</p>

<h2>Layanan Kami</h2>
<ul>
    <li>Beragam pilihan produk digital</li>
    <li>Pembayaran aman melalui channel yang terpercaya</li>
    <li>Pengiriman instan untuk produk digital</li>
    <li>Dukungan pelanggan yang responsif</li>
</ul>

<h2>Hubungi Kami</h2>
<p>Untuk pertanyaan, saran, atau kerja sama, silakan kunjungi halaman <a href="{{ route('static.page', 'kontak') }}">Kontak</a>.</p>
@endsection
