# 🛍️ MyStore - Website E-Commerce & Donasi

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

## 📋 Deskripsi Proyek

**MyStore** adalah platform web e-commerce modern yang dibangun dengan Laravel Framework. Website ini menyediakan dua layanan utama:

- 🛒 **Toko Online**: Penjualan produk dengan sistem keranjang belanja
- 💝 **Platform Donasi**: Sistem donasi untuk berbagai program sosial

Website ini terintegrasi dengan sistem pembayaran **Tripay** untuk memproses transaksi yang aman dan terpercaya.

## ✨ Fitur Utama

### 👤 Sistem Pengguna
- ✅ Registrasi dan Login pengguna
- 👤 Manajemen profil pengguna
- 🔒 Autentikasi yang amancd
- 📱 Responsive design untuk semua perangkat

### 🛒 Toko Online
- 📦 Katalog produk lengkap
- 🛒 Sistem keranjang belanja
- 💳 Integrasi pembayaran Tripay
- 📊 Riwayat transaksi
- 🔍 Pencarian produk

### 💝 Sistem Donasi
- 📋 Daftar program donasi
- 💰 Pembayaran donasi yang mudah
- 📈 Tracking donasi
- 🎯 Target donasi yang jelas

### 💳 Sistem Pembayaran
- 🔗 Integrasi Tripay Payment Gateway
- 💳 Berbagai metode pembayaran (Bank Transfer, E-Wallet, dll)
- 📱 Callback otomatis
- ✅ Verifikasi pembayaran real-time
- 📦 **Stock Management**: Otomatis mengurangi stock produk setelah pembayaran berhasil

### 📊 Manajemen Stock
- 📈 Sistem stock produk real-time
- 🚫 Pencegahan pembelian produk stock habis
- 🎨 Indikator status stock (Tersedia/Stok Terbatas/Stok Habis)
- 🔄 Auto-reduce stock saat pembayaran sukses
- 📊 API endpoints untuk manajemen stock

## 🛠️ Teknologi yang Digunakan

### Backend
- **Laravel 12.x** - Framework PHP utama
- **PHP 8.2+** - Bahasa pemrograman server
- **MySQL** - Database utama
- **Composer** - Dependency management

### Frontend
- **Tailwind CSS 4.0** - Framework CSS utility-first
- **Vite** - Build tool dan development server
- **Blade Templates** - Template engine Laravel
- **JavaScript ES6+** - Interaktivitas frontend

### Tools & Libraries
- **Laravel Tinker** - REPL untuk development
- **Laravel Pint** - Code formatter
- **Axios** - HTTP client untuk AJAX
- **Concurrently** - Menjalankan multiple commands

## 🚀 Instalasi dan Setup

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & npm
- MySQL Database
- Git

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd MyStore
   ```

2. **Install Dependencies PHP**
   ```bash
   composer install
   ```

3. **Install Dependencies Node.js**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi Database**
   - Buat database MySQL baru
   - Update file `.env` dengan kredensial database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mystore_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Jalankan Migration dan Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Konfigurasi Tripay Payment**
   - Daftar akun di [Tripay](https://tripay.co.id)
   - Update konfigurasi di `.env`:
   ```env
   TRIPAY_API_KEY=your_api_key
   TRIPAY_PRIVATE_KEY=your_private_key
   TRIPAY_MERCHANT_CODE=your_merchant_code
   ```

8. **Build Assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

9. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

   Atau gunakan script development yang tersedia:
   ```bash
   composer run dev
   ```

## 📁 Struktur Proyek

```
MyStore/
├── app/                    # Aplikasi Laravel
│   ├── Http/Controllers/   # Controllers
│   ├── Models/            # Eloquent Models
│   ├── Mail/              # Email templates
│   └── Providers/         # Service Providers
├── database/              # Database migrations & seeders
│   ├── migrations/        # Schema migrations
│   └── seeders/          # Database seeders
├── public/                # Public assets
│   ├── assets/           # CSS, JS, Images
│   └── index.php         # Entry point
├── resources/            # Views & raw assets
│   ├── views/            # Blade templates
│   └── css/              # Raw CSS files
├── routes/               # Route definitions
│   └── web.php           # Web routes
├── storage/              # File storage
├── tests/                # Unit & Feature tests
├── vendor/               # Composer dependencies
├── composer.json         # Composer configuration
├── package.json          # Node.js dependencies
├── vite.config.js        # Vite configuration
└── README.md            # Documentation
```

## 🎯 Cara Penggunaan

### Untuk Pembeli/Donatur
1. **Registrasi Akun**: Daftar akun baru atau login jika sudah punya
2. **Lengkapi Profil**: Isi data lengkap (nama, email, nomor HP)
3. **Pilih Produk/Donasi**: Browse katalog produk atau program donasi
4. **Pembayaran**: Pilih metode pembayaran yang tersedia
5. **Konfirmasi**: Lakukan pembayaran sesuai instruksi
6. **Tracking**: Pantau status transaksi di halaman histori

### Untuk Admin/Developer
- **Dashboard Admin**: Mengelola produk dan donasi
- **Transaction Management**: Monitoring semua transaksi
- **Stock Management**: Mengelola stock produk via API
- **Callback Testing**: Testing integrasi pembayaran
- **Log Monitoring**: Melihat log callback dan error

### 📊 API Stock Management

```bash
# Mendapatkan informasi stock produk
GET /products/{id}/stock-info

# Memperbarui stock produk
POST /products/{id}/update-quantity
Body: { "quantity": 50 }

# Menambah stock produk
POST /products/{id}/add-stock
Body: { "amount": 10 }

# Mengurangi stock produk
POST /products/{id}/reduce-stock
Body: { "amount": 5 }
```

## 🔧 Konfigurasi

### Environment Variables
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

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password

# App Configuration
APP_NAME=MyStore
APP_ENV=local
APP_KEY=base64_generated_key
APP_DEBUG=true
APP_URL=http://localhost
```

## 📊 Database Schema

### Tabel Utama
- `users` - Data pengguna
- `products` - Katalog produk (dengan field stock)
- `donasis` - Program donasi
- `transactions` - Riwayat transaksi

### Struktur Tabel Products
```sql
products:
- id (bigint, primary key)
- image (string)
- name (string)
- price (integer)
- description (longText)
- stock (integer, default: 10)  -- BARU: Field stock produk
- created_at (timestamp)
- updated_at (timestamp)
```

### Relasi
- User → Transactions (One to Many)
- Product → Transactions (One to Many)
- Donasi → Transactions (One to Many)

### Status Stock
- **Tersedia**: stock > 10
- **Stok Terbatas**: stock 1-10
- **Stok Habis**: stock = 0

## 🧪 Testing

### Menjalankan Tests
```bash
# Jalankan semua tests
php artisan test

# Jalankan dengan coverage
php artisan test --coverage
```

### Testing Payment Callback
```bash
# Test callback untuk transaksi tertentu
php artisan tinker
# Kemudian jalankan:
app(\App\Http\Controllers\TransactionHistoryController::class)->testCallback('transaction_id');
```

### Testing Stock Management
```bash
# Test stock methods via tinker
php artisan tinker

# Cek stock produk
$product = App\Models\Product::find(1);
echo $product->stock;
echo $product->isInStock();
echo $product->getStockStatus();

# Test pengurangan stock
$product->reduceStock(1);
echo $product->stock;
```

## 🚀 Deployment

### Production Setup
1. **Server Requirements**
   - PHP 8.2+ dengan ekstensi yang diperlukan
   - MySQL 5.7+ atau MariaDB 10.0+
   - Web server (Apache/Nginx)
   - SSL Certificate

2. **Deployment Steps**
   ```bash
   # Clone dan install
   git clone <repo> production
   cd production
   composer install --no-dev --optimize-autoloader
   npm install && npm run build

   # Konfigurasi environment
   cp .env.example .env
   # Edit .env untuk production

   # Setup database
   php artisan migrate --seed
   php artisan key:generate
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Web Server Configuration**
   - Pastikan `public/` directory sebagai document root
   - Setup rewrite rules untuk Laravel
   - Konfigurasi SSL

## 🤝 Contributing

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📝 License

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk informasi lebih detail.

## 📞 Support

Jika Anda mengalami masalah atau memiliki pertanyaan:

- 📧 **Email**: [your-email@example.com]
- 💬 **Issues**: [GitHub Issues](https://github.com/your-repo/issues)
- 📖 **Documentation**: Lihat folder `docs/` untuk dokumentasi lengkap

## 🙏 Acknowledgments

- **Laravel Framework** - Framework PHP yang powerful
- **Tripay** - Payment gateway terpercaya
- **Tailwind CSS** - Framework CSS modern
- **Open Source Community** - Untuk semua kontribusi

## 📋 Changelog - Stock Management

### v2.0.0 - Stock Management System
**Tanggal**: 28 Agustus 2025

#### ✨ Fitur Baru
- 🆕 **Sistem Stock Management**: Menambahkan field stock pada tabel products
- 🚫 **Pencegahan Pembelian**: Pengunjung tidak bisa checkout jika stock habis
- 🎨 **UI Stock Indicator**: Menampilkan status stock (Tersedia/Terbatas/Habis)
- 🔄 **Auto Stock Reduction**: Stock otomatis berkurang setelah pembayaran berhasil
- 📊 **API Stock Management**: Endpoint API untuk mengelola stock produk

#### 🔧 Perubahan Teknis
- **Migration**: `rename_quantity_to_stock_in_products_table.php`
- **Migration**: `update_stock_default_value_in_products_table.php`
- **Model**: Update `Product.php` dengan method stock management
- **Controller**: Update `CheckoutController.php` untuk validasi stock
- **Controller**: Update `TripayController.php` untuk auto-reduce stock
- **Controller**: Update `ProductQttController.php` dengan method stock management
- **View**: Update `dashboard.blade.php` untuk menampilkan status stock
- **CSS**: Update `dashboard.css` dengan styling stock status
- **Routes**: Menambahkan routes untuk stock management API

#### 🗄️ Database Changes
- Rename kolom `quantity` → `stock` di tabel `products`
- Set default value stock = 10
- Update semua produk existing dengan stock = 10

#### 📊 Status Stock
- **🟢 Tersedia**: stock > 10
- **🟡 Stok Terbatas**: stock 1-10
- **🔴 Stok Habis**: stock = 0

---

<p align="center">Dibuat dengan ❤️ menggunakan Laravel Framework</p>
