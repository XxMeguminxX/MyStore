<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\AuthController; // Pastikan ini sudah ada atau tambahkan
use App\Http\Controllers\TransactionHistoryController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk Login (bisa diakses tanpa login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rute untuk Logout (hanya bisa diakses jika sudah login, tapi ini akan otomatis logout)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Tripay Callback (tidak memerlukan autentikasi)
Route::post('/tripay/callback', [TripayController::class, 'handleCallback']);

// --- Rute yang Membutuhkan Autentikasi (Hanya Bisa Diakses Setelah Login) ---
Route::middleware(['auth'])->group(function () {
    // Rute Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Rute Beli Produk
    Route::get('/beli/{id}', [App\Http\Controllers\CheckoutController::class, 'beli']);

    // Rute Histori Transaksi
    Route::get('/transaction-history', [TransactionHistoryController::class, 'index'])->name('transaction.history');
    Route::post('/transaction/update-status', [TransactionHistoryController::class, 'updateStatus'])->name('transaction.update-status');
    Route::post('/transaction/manual-update-status', [TransactionHistoryController::class, 'manualUpdateStatus'])->name('transaction.manual-update-status');
    Route::get('/callback-logs', [TransactionHistoryController::class, 'viewCallbackLogs'])->name('callback.logs');
    Route::post('/test-callback/{transactionId}', [TransactionHistoryController::class, 'testCallback'])->name('test.callback');

    // Rute Tripay Transactions
    Route::post('/tripay/transaction', [TripayController::class, 'createTransaction']);

    // Rute Profil
    Route::get('/profile', function () {
        return view('profile');
    });
});