<x-mail::message>
# Konfirmasi Pembayaran Berhasil!

Halo {{ $transaction->customer_name ?? 'Pelanggan' }},

Terima kasih telah melakukan pembayaran di E Store ID. Pembayaran Anda untuk produk **{{ $product->name }}** telah berhasil kami terima!

**Detail Transaksi Anda:**

* **Nomor Referensi:** {{ $transaction->merchant_ref }}
* **Produk:** {{ $product->name }}
* **Jumlah Pembayaran:** Rp {{ number_format($transaction->amount, 0, ',', '.') }}
* **Metode Pembayaran:** {{ $transaction->payment_method }}
* **Status:** {{ $transaction->status }}
* **Tanggal Transaksi:** {{ $transaction->created_at->format('d M Y H:i:s') }}

Anda bisa melihat detail transaksi kapan saja di dashboard Anda.

<x-mail::button :url="url('/dashboard')">
Lihat Dashboard
</x-mail::button>

Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.

Hormat kami,
Tim E Store ID
</x-mail::message>