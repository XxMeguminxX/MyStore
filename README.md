MyStore — E-Commerce & Donasi (Laravel)

Aplikasi web e-commerce dan donasi berbasis Laravel.
Sudah terintegrasi Tripay untuk pembayaran multi-channel dan dilengkapi sistem manajemen stok real-time.

Fitur Utama
User

Registrasi & login

Manajemen profil

Riwayat transaksi

Toko Online

Katalog produk

Pencarian produk

Keranjang belanja

Checkout dengan Tripay

Donasi

Daftar program donasi

Pembayaran via Tripay

Tracking status transaksi

Pembayaran

Integrasi Tripay (multi channel)

Callback otomatis

Validasi signature

Pengurangan stok otomatis saat pembayaran sukses

Stock Management

Update stok manual (admin/API)

Cegah checkout saat stok habis

Status stok: Tersedia / Terbatas / Habis

Endpoint API untuk kontrol stok

Admin

Dashboard produk, donasi, transaksi

Log callback

Testing callback

Teknologi

Backend

Laravel 12.x

PHP 8.2+

MySQL

Frontend

Blade

Tailwind CSS

Vite

JavaScript (ES6+)

Tools

Composer

Laravel Tinker

Laravel Pint

Instalasi
Persyaratan

PHP 8.2+

Composer

Node.js & npm

MySQL

Git

Setup
git clone <repository-url>
cd MyStore

composer install
npm install

cp .env.example .env
php artisan key:generate

# Atur database di .env
php artisan migrate --seed

# Build asset
npm run build
# atau
npm run dev

php artisan serve
Konfigurasi .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mystore_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

TRIPAY_API_KEY=your_api_key
TRIPAY_PRIVATE_KEY=your_private_key
TRIPAY_MERCHANT_CODE=your_merchant_code

APP_NAME=MyStore
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
Struktur Proyek (Ringkas)
app/
database/
public/
resources/
routes/
storage/
tests/
API Stock Management

GET
/products/{id}/stock-info

POST
/products/{id}/update-quantity
Body:

{ "quantity": 50 }

POST
/products/{id}/add-stock

{ "amount": 10 }

POST
/products/{id}/reduce-stock

{ "amount": 5 }

Contoh response:

{
  "id": 1,
  "name": "Nama Produk",
  "stock": 12,
  "status": "Tersedia"
}
Skema Database (Utama)
users

Data pengguna

products

Memiliki kolom stock

id BIGINT PRIMARY KEY
name VARCHAR
price INT
stock INT DEFAULT 10
description LONGTEXT
created_at TIMESTAMP
updated_at TIMESTAMP
donasis

Program donasi

transactions

Riwayat transaksi

Testing
php artisan test

Testing callback via Tinker:

app(\App\Http\Controllers\TransactionHistoryController::class)
    ->testCallback('transaction_id');
Deployment (Production)
composer install --no-dev --optimize-autoloader
npm install && npm run build
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

Pastikan:

Document root mengarah ke folder public/

Menggunakan HTTPS

File .env tidak di-commit

Keamanan

Simpan API key di .env

Validasi signature callback

Gunakan HTTPS di production

Batasi akses admin

Lisensi

MIT
