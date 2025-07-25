<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\AuthController; // Pastikan ini sudah ada atau tambahkan


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Rute yang diubah: Mengarahkan rute dasar '/' ke halaman login ---
Route::get('/', function () {
    return redirect()->route('login'); // Ini akan mengarahkan ke rute yang bernama 'login'
});

// Rute untuk Login (bisa diakses tanpa login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rute untuk Logout (hanya bisa diakses jika sudah login, tapi ini akan otomatis logout)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- Rute yang Membutuhkan Autentikasi (Hanya Bisa Diakses Setelah Login) ---
Route::middleware(['auth'])->group(function () {
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Rute Beli Produk
    Route::get('/beli/{id}', [App\Http\Controllers\CheckoutController::class, 'beli']);

    // Rute Tripay Transactions (jika hanya untuk user terautentikasi)
    // Jika ini adalah endpoint callback dari Tripay, mungkin tidak perlu di-auth
    // Tinjau kembali apakah rute POST Tripay perlu diautentikasi atau tidak
    // Biasanya callback dari payment gateway tidak memerlukan autentikasi user
    Route::post('/tripay/transaction', [TripayController::class, 'createTransaction']);

    // Rute Profil
    Route::get('/profile', function () {
        return view('profile');
    });
});