@extends('layouts.static-page')

@section('title', $pageTitle)

@section('content')
<h1>Kebijakan Privasi</h1>
<p class="updated">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

<p>E Store ID menghormati privasi Anda. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi data pribadi Anda ketika Anda menggunakan layanan kami.</p>

<h2>1. Data yang Kami Kumpulkan</h2>
<p>Kami dapat mengumpulkan informasi berikut ketika Anda menggunakan situs kami:</p>
<ul>
    <li>Data yang Anda berikan saat mendaftar (nama, email, nomor telepon, alamat)</li>
    <li>Riwayat transaksi dan pembelian</li>
    <li>Data teknis seperti alamat IP dan jenis perangkat</li>
    <li>Cookie dan data penggunaan untuk meningkatkan layanan</li>
</ul>

<h2>2. Penggunaan Data</h2>
<p>Data yang dikumpulkan digunakan untuk:</p>
<ul>
    <li>Memproses pesanan dan mengirim produk digital</li>
    <li>Berkomunikasi mengenai pesanan dan layanan</li>
    <li>Meningkatkan pengalaman pengguna dan layanan kami</li>
    <li>Kepatuhan hukum dan perlindungan hak kami</li>
</ul>

<h2>3. Perlindungan Data</h2>
<p>Kami menerapkan langkah-langkah keamanan yang wajar untuk melindungi data pribadi Anda dari akses, perubahan, atau pengungkapan yang tidak sah.</p>

<h2>4. Hak Anda</h2>
<p>Anda berhak meminta akses, koreksi, atau penghapusan data pribadi Anda. Hubungi kami melalui halaman <a href="{{ route('static.page', 'kontak') }}">Kontak</a> untuk permintaan tersebut.</p>

<h2>5. Perubahan</h2>
<p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Perubahan akan dipublikasikan di halaman ini dengan tanggal pembaruan.</p>
@endsection
