<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\ProductQttController;


// Rute untuk Login dan Register (bisa diakses tanpa login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rute untuk Logout (hanya bisa diakses jika sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Tripay Callback (tidak memerlukan autentikasi)
Route::post('/tripay/callback', [TripayController::class, 'handleCallback']);

// Rute quantity produk (bisa diakses tanpa login)
Route::post('/products/{id}/update-quantity', [ProductQttController::class, 'updateQuantity'])->name('products.update_quantity');

// Rute Dashboard (bisa diakses tanpa login)
Route::get('/', [DonasiController::class, 'index'])->name('donasi.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Rute Donasi (bisa diakses tanpa login)
Route::get('/donasi', [DonasiController::class, 'index'])->name('donasi.index');

// --- Rute yang Membutuhkan Autentikasi (Hanya Bisa Diakses Setelah Login) ---
Route::middleware(['auth'])->group(function () {
    // Rute Beli Produk
Route::get('/beli/{id}', [App\Http\Controllers\CheckoutController::class, 'beli']);
    // Rute Beli Donasi (gunakan checkout yang sama)
    Route::get('/donasi/beli/{id}', [DonasiController::class, 'beli'])->name('donasi.beli');

    // Rute Histori Transaksi
Route::get('/transaction-history', [TransactionHistoryController::class, 'index'])->name('transaction.history');
Route::post('/transaction/update-status', [TransactionHistoryController::class, 'updateStatus'])->name('transaction.update-status');
Route::post('/transaction/manual-update-status', [TransactionHistoryController::class, 'manualUpdateStatus'])->name('transaction.manual-update-status');
Route::get('/callback-logs', [TransactionHistoryController::class, 'viewCallbackLogs'])->name('callback.logs');
Route::post('/test-callback/{transactionId}', [TransactionHistoryController::class, 'testCallback'])->name('test.callback');

    // Rute Tripay Transactions
Route::post('/tripay/transaction', [TripayController::class, 'createTransaction']);

    // Rute Profil
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

// (dihapus duplikat rute quantity produk)