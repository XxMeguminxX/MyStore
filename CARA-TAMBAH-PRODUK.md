# Panduan Menambahkan Produk — MyStore

Karena MyStore belum memiliki halaman admin panel, produk dikelola langsung melalui **database** menggunakan perintah `php artisan tinker` atau tool database (TablePlus, phpMyAdmin, dll).

---

## Struktur Data Produk

| Kolom         | Tipe            | Keterangan                                      |
|---------------|-----------------|-------------------------------------------------|
| `id`          | bigint (auto)   | ID unik, diisi otomatis                         |
| `name`        | string          | Nama produk                                     |
| `price`       | integer         | Harga dalam **Rupiah** (tanpa titik/koma)       |
| `description` | longText        | Deskripsi lengkap produk                        |
| `image`       | string          | Path gambar atau URL gambar eksternal           |
| `stock`       | integer         | Jumlah stok tersedia (0 = habis)                |
| `created_at`  | timestamp       | Diisi otomatis                                  |
| `updated_at`  | timestamp       | Diisi otomatis                                  |

---

## Cara 1 — Menggunakan `php artisan tinker` (Direkomendasikan)

Buka terminal di root project, lalu jalankan:

```bash
php artisan tinker
```

### Menambahkan produk baru

```php
App\Models\Product::create([
    'name'        => 'Nama Produk',
    'price'       => 25000,
    'description' => 'Deskripsi produk di sini.',
    'image'       => 'assets/img/nama-gambar.jpg',
    'stock'       => 100,
]);
```

**Contoh nyata:**

```php
App\Models\Product::create([
    'name'        => 'Akun Gmail Fresh 1acc',
    'price'       => 15000,
    'description' => 'Akun Gmail baru siap pakai, full akses Google Drive, Docs, dan layanan Google lainnya.',
    'image'       => 'assets/img/gmail.jpg',
    'stock'       => 50,
]);
```

### Melihat semua produk

```php
App\Models\Product::all(['id', 'name', 'price', 'stock']);
```

### Mengupdate produk yang sudah ada

```php
// Ganti 1 dengan ID produk yang ingin diubah
App\Models\Product::where('id', 1)->update([
    'name'  => 'Nama Baru',
    'price' => 20000,
    'stock' => 75,
]);
```

### Mengupdate stok saja

```php
App\Models\Product::where('id', 1)->update(['stock' => 99]);
```

### Menghapus produk

```php
App\Models\Product::where('id', 1)->delete();
```

Ketik `exit` untuk keluar dari tinker.

---

## Cara 2 — Menggunakan Seeder (Untuk banyak produk sekaligus)

Buat file seeder baru:

```bash
php artisan make:seeder ProductSeeder
```

Edit file `database/seeders/ProductSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'        => 'Akun Gmail Fresh 1acc',
                'price'       => 15000,
                'description' => 'Akun Gmail baru siap pakai.',
                'image'       => 'assets/img/gmail.jpg',
                'stock'       => 50,
            ],
            [
                'name'        => 'Akun Netflix Premium 1 Bulan',
                'price'       => 45000,
                'description' => 'Akses Netflix Premium selama 30 hari.',
                'image'       => 'assets/img/netflix.jpg',
                'stock'       => 30,
            ],
            // Tambahkan produk lain di sini...
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
```

Jalankan seeder:

```bash
php artisan db:seed --class=ProductSeeder
```

---

## Cara Menambahkan Gambar Produk

### Opsi A — Gambar lokal (upload ke server)

1. Letakkan file gambar di folder: `public/assets/img/`
2. Format yang didukung: `.jpg`, `.jpeg`, `.png`, `.webp`
3. Isi kolom `image` dengan path relatif:

```php
'image' => 'assets/img/nama-file.jpg'
```

### Opsi B — Gambar dari URL eksternal

Isi kolom `image` langsung dengan URL lengkap:

```php
'image' => 'https://contoh.com/gambar-produk.png'
```

> **Tips:** Gunakan gambar berukuran **800×800 px** agar tampil optimal di halaman produk.

---

## Status Stok Otomatis

Sistem akan menampilkan status stok secara otomatis berdasarkan nilai kolom `stock`:

| Nilai `stock` | Status yang Ditampilkan              |
|---------------|--------------------------------------|
| > 10          | 🟢 Ready Stock                       |
| 1 – 10        | ⚠️ Tersisa N unit — Segera habis!    |
| 0             | 🔴 Stok Habis (tombol beli nonaktif) |

---

## Contoh Perintah Cepat (Copy-Paste)

```bash
# Buka tinker
php artisan tinker

# Tambah produk
App\Models\Product::create(['name'=>'Produk Baru','price'=>10000,'description'=>'Deskripsi singkat.','image'=>'assets/img/gambar.jpg','stock'=>100]);

# Lihat semua produk
App\Models\Product::all(['id','name','price','stock']);

# Update nama dan harga produk ID 2
App\Models\Product::where('id',2)->update(['name'=>'Nama Baru','price'=>20000]);

# Hapus produk ID 3
App\Models\Product::where('id',3)->delete();

# Keluar
exit
```
