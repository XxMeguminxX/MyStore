# Cara Mengubah Kuantitas (Stock) Produk

Terdapat beberapa cara untuk mengubah stock produk di MyStore.

---

## 1. Melalui API Endpoint

### Set Stock (langsung ke nilai tertentu)

```http
POST /products/{id}/update-quantity
Content-Type: application/json

{
    "quantity": 50
}
```

### Tambah Stock

```http
POST /products/{id}/add-stock
Content-Type: application/json

{
    "amount": 10
}
```

### Kurangi Stock

```http
POST /products/{id}/reduce-stock
Content-Type: application/json

{
    "amount": 5
}
```

### Cek Info Stock

```http
GET /products/{id}/stock-info
```

**Contoh response:**
```json
{
    "product_id": 1,
    "product_name": "Akun Gmail Fresh 1 acc",
    "stock": 45,
    "is_in_stock": true,
    "stock_status": "In Stock"
}
```

> Ganti `{id}` dengan ID produk yang sesuai.

---

## 2. Melalui Database Langsung (Tinker)

Jalankan perintah berikut di terminal:

```bash
php artisan tinker
```

Lalu jalankan salah satu perintah berikut:

```php
// Set stock ke nilai tertentu
Product::find(1)->update(['stock' => 50]);

// Tambah stock
$p = Product::find(1);
$p->stock += 10;
$p->save();

// Kurangi stock
$p = Product::find(1);
$p->stock -= 5;
$p->save();

// Lihat stock semua produk
Product::all(['id', 'name', 'stock']);
```

---

## 3. Melalui Seeder

Buat atau gunakan seeder yang sudah ada untuk mengisi ulang stock:

```bash
php artisan db:seed --class=UpdateProductStockSeeder
```

Atau edit file `database/seeders/ProductSeeder.php` dan tambahkan field `stock` pada setiap produk:

```php
Product::create([
    'name'  => 'Nama Produk',
    'price' => 10000,
    'stock' => 25,    // <-- tambahkan ini
    // ...
]);
```

---

## Validasi Stock

| Field      | Aturan                        |
|------------|-------------------------------|
| `quantity` | integer, min: 0, max: 9999    |
| `amount`   | integer, min: 1, max: 1000    |

---

## Catatan

- Stock yang sudah **0** tidak bisa dikurangi lagi melalui endpoint `reduce-stock`.
- Setiap perubahan stock tercatat di log aplikasi (`storage/logs/laravel.log`).
- Saat transaksi berhasil dibuat, stock dikurangi secara otomatis oleh sistem.
