# 🛍️ MyStore — E‑Commerce & Donasi (Laravel)

> Platform web **e‑commerce + donasi** yang simple, modern, dan siap produksi. Sudah **terintegrasi Tripay** buat pembayaran multi‑channel, plus **Stock Management** real‑time biar nggak ada drama double order. 🚀

---

## 🔗 Daftar Isi

* [Kenapa MyStore?](#-kenapa-mystore)
* [Fitur](#-fitur)
* [Teknologi](#-teknologi)
* [Instalasi Cepat](#-instalasi-cepat)
* [Konfigurasi `.env`](#-konfigurasi-env)
* [Struktur Proyek](#-struktur-proyek)
* [Cara Pakai](#-cara-pakai)
* [API Stock Management](#-api-stock-management)
* [Skema Database](#-skema-database)
* [Testing](#-testing)
* [Deployment](#-deployment)
* [Keamanan](#-keamanan)
* [Contributing](#-contributing)
* [Lisensi](#-lisensi)
* [Support](#-support)
* [Changelog](#-changelog)

---

## 💡 Kenapa MyStore?

* **All‑in‑one**: jualan produk dan terima **donasi** dalam satu aplikasi.
* **Pembayaran gampang**: Tripay gateway (bank transfer, e‑wallet, dll) + callback otomatis.
* **Aman & rapi**: autentikasi solid, validasi transaksi, dan log callback.
* **Stok anti ribet**: stok otomatis berkurang saat pembayaran sukses.
* **UI responsif**: nyaman dipakai di HP sampai desktop.

---

## ✨ Fitur

### 👤 Pengguna

* Registrasi & login
* Manajemen profil
* Autentikasi aman
* Desain responsif

### 🛒 Toko Online

* Katalog produk + pencarian
* Keranjang belanja
* Riwayat transaksi

### 🎁 Modul Donasi

* Daftar program donasi
* Donasi cepat via Tripay
* Tracking status donasi

### 💳 Pembayaran (Tripay)

* Integrasi gateway Tripay
* Berbagai metode bayar
* Callback otomatis
* Verifikasi real‑time
* **Auto reduce stock** saat pembayaran sukses

### 📦 Stock Management

* Stok real‑time
* Cegah checkout saat stok 0
* Indikator status: Tersedia / Terbatas / Habis
* Endpoint API untuk kelola stok

### 🛠️ Admin

* Dashboard admin (produk, donasi, transaksi)
* Log callback & error
* Tools testing callback

---

## 🧰 Teknologi

**Backend**: Laravel 12.x, PHP 8.2+, MySQL, Composer
**Frontend**: Tailwind CSS 4.0, Vite, Blade, JS ES6+
**Tools**: Laravel Tinker, Laravel Pint, Axios, Concurrently

---

## ⚡ Instalasi Cepat

### Persyaratan

* PHP 8.2+
* Composer
* Node.js + npm
* MySQL
* Git

### Langkah

```bash
# 1) Clone repo
git clone <repository-url>
cd MyStore

# 2) Install dependencies PHP
composer install

# 3) Install dependencies frontend
npm install

# 4) Setup environment
cp .env.example .env
php artisan key:generate

# 5) Konfigurasi database (edit .env sesuai kredensial)

# 6) Migrasi & seed
php artisan migrate
php artisan db:seed

# 7) Konfigurasi Tripay (isi TRIPAY_* di .env)

# 8) Build assets
npm run build
# atau dev server
npm run dev

# 9) Jalanin app
php artisan serve
# atau script dev
composer run dev
```

---

## 🔧 Konfigurasi `.env`

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mystore_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Tripay Payment Gateway
TRIPAY_API_KEY=your_api_key
TRIPAY_PRIVATE_KEY=your_private_key
TRIPAY_MERCHANT_CODE=your_merchant_code

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password

# App
APP_NAME=MyStore
APP_ENV=local
APP_KEY=base64_generated_key
APP_DEBUG=true
APP_URL=http://localhost
```

---

## 📁 Struktur Proyek

```
MyStore/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Mail/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── assets/
│   └── index.php
├── resources/
│   ├── views/
│   └── css/
├── routes/
│   └── web.php
├── storage/
├── tests/
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## 🎯 Cara Pakai

### Untuk Pembeli / Donatur

1. Daftar akun / login
2. Lengkapi profil (nama, email, HP)
3. Pilih produk atau program donasi
4. Checkout, pilih metode bayar (Tripay)
5. Ikuti instruksi pembayaran
6. Lacak status di halaman histori

### Untuk Admin / Dev

* Kelola produk & program donasi via dashboard
* Pantau seluruh transaksi
* Kelola stok (UI atau API)
* Uji callback pembayaran
* Cek log callback & error

---

## 📊 API Stock Management

> Base path default mengikuti konfigurasi route API Anda.

**GET** `/products/{id}/stock-info`
Balik info stok terkini produk.

**POST** `/products/{id}/update-quantity`
Body: `{ "quantity": 50 }` — set jumlah stok langsung.

**POST** `/products/{id}/add-stock`
Body: `{ "amount": 10 }` — tambah stok sekian.

**POST** `/products/{id}/reduce-stock`
Body: `{ "amount": 5 }` — kurangi stok sekian.

*Response contoh*

```json
{
  "id": 1,
  "name": "Nama Produk",
  "stock": 12,
  "status": "Tersedia"
}
```

---

## 🗄️ Skema Database

**Tabel utama**

* `users` — data pengguna
* `products` — katalog produk (punya kolom `stock`)
* `donasis` — program donasi
* `transactions` — riwayat transaksi

**`products`**

```sql
id BIGINT PK,
image VARCHAR,
name VARCHAR,
price INT,
description LONGTEXT,
stock INT DEFAULT 10,
created_at TIMESTAMP,
updated_at TIMESTAMP
```

**Relasi**

* User → Transactions (1:M)
* Product → Transactions (1:M)
* Donasi → Transactions (1:M)

**Status Stok**

* 🟢 **Tersedia**: `stock > 10`
* 🟡 **Stok Terbatas**: `1–10`
* 🔴 **Stok Habis**: `0`

---

## 🧪 Testing

```bash
# Semua test
yarn test # jika ada
php artisan test

# Dengan coverage (jika diset)
php artisan test --coverage
```

**Testing Payment Callback**

```bash
php artisan tinker
app(\App\Http\Controllers\TransactionHistoryController::class)
  ->testCallback('transaction_id');
```

**Testing Stock via Tinker**

```php
$product = App\Models\Product::find(1);
$product->stock;            // cek stok
$product->isInStock();      // boolean
$product->getStockStatus(); // label status

$product->reduceStock(1);   // kurangi stok
$product->stock;            // pastikan berubah
```

---

## 🚀 Deployment (Production)

1. **Server**: PHP 8.2+, MySQL/MariaDB, Apache/Nginx, SSL
2. **Build & Optimasi**

```bash
git clone <repo> production
cd production
composer install --no-dev --optimize-autoloader
npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Web Server**: arahkan **document root** ke folder `public/` + aktifkan rewrite/SSL

---

## 🔐 Keamanan

* Simpan **API key** & **private key** di `.env` (jangan commit).
* Gunakan HTTPS di production.
* Validasi signature Tripay pada callback.
* Rotasi key & batasi akses panel admin.

---

## 🤝 Contributing

1. Fork repo
2. Buat branch fitur: `git checkout -b feature/AmazingFeature`
3. Commit: `git commit -m "feat: add AmazingFeature"`
4. Push: `git push origin feature/AmazingFeature`
5. Buka Pull Request

---

## 📝 Lisensi

MIT — lihat file `LICENSE`.

---

## 📞 Support

* Email: **[saputraerik042@gmail.com](mailto:saputraerik042@gmail.com)**
* Issues: [GitHub Issues](https://github.com/your-repo/issues)
* Docs: folder `docs/`

---

## 📋 Changelog

### v2.0.0 — Stock Management System (28 Agustus 2025)

**Baru**

* 🆕 Kolom `stock` pada `products`
* 🚫 Cegah checkout saat stok habis
* 🎨 Indikator status stok (Tersedia/Terbatas/Habis)
* 🔄 Auto reduce stock setelah pembayaran sukses
* 📊 Endpoint API stok

**Perubahan Teknis**

* Migration: `rename_quantity_to_stock_in_products_table.php`
* Migration: `update_stock_default_value_in_products_table.php`
* Model: update `Product.php` (helper stok)
* Controller: `CheckoutController.php` (validasi stok)
* Controller: `TripayController.php` (auto reduce stok)
* Controller: `ProductQttController.php` (CRUD stok)
* View: `dashboard.blade.php` (status stok)
* CSS: `dashboard.css` (badge status)
* Routes: tambah endpoint manajemen stok

**Status Stok**

* 🟢 `> 10`
* 🟡 `1–10`
* 🔴 `0`

---

<p align="center">Dibuat dengan ❤️ pakai Laravel</p>
