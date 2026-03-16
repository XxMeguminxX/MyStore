@extends('layouts.static-page')

@section('title', $pageTitle)

@section('content')
<h1>Ketentuan Layanan</h1>
<p class="updated">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

<p>Dengan mengakses dan menggunakan E Store ID, Anda setuju untuk terikat oleh ketentuan layanan berikut. Harap baca dengan saksama.</p>

<h2>1. Layanan Kami</h2>
<p>E Store ID menyediakan platform penjualan produk digital. Anda dapat membeli produk digital melalui situs kami setelah mendaftar dan mematuhi ketentuan ini.</p>

<h2>2. Akun Pengguna</h2>
<p>Anda bertanggung jawab untuk menjaga kerahasiaan akun dan kata sandi. Semua aktivitas yang terjadi di bawah akun Anda menjadi tanggung jawab Anda. Beri tahu kami segera jika ada penggunaan yang tidak sah.</p>

<h2>3. Pembelian dan Pembayaran</h2>
<p>Dengan menyelesaikan pembelian, Anda setuju untuk membayar biaya yang tercantum. Produk digital umumnya dikirim atau diaktifkan setelah pembayaran dikonfirmasi. Kebijakan pengembalian mengikuti ketentuan yang berlaku untuk setiap produk.</p>

<h2>4. Penggunaan yang Dilarang</h2>
<p>Anda tidak boleh:</p>
<ul>
    <li>Menggunakan layanan untuk tujuan ilegal atau melanggar hak pihak ketiga</li>
    <li>Menyalahgunakan sistem, melakukan penipuan, atau manipulasi transaksi</li>
    <li>Mendistribusikan ulang atau menjual kembali produk digital tanpa izin</li>
</ul>

<h2>5. Batasan Tanggung Jawab</h2>
<p>Layanan kami disediakan "sebagaimana adanya". Kami tidak menjamin bahwa layanan akan bebas gangguan atau bebas kesalahan. Tanggung jawab kami dibatasi sesuai peraturan yang berlaku.</p>

<h2>6. Perubahan Ketentuan</h2>
<p>Kami berhak mengubah ketentuan layanan kapan saja. Perubahan akan berlaku setelah dipublikasikan di situs. Penggunaan berkelanjutan setelah perubahan dianggap sebagai penerimaan Anda.</p>
@endsection
