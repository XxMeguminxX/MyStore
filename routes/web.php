<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripayController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\ProductQttController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;


// Rute untuk Login dan Register (bisa diakses tanpa login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rute untuk Logout (hanya bisa diakses jika sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Tripay Callback (tidak memerlukan autentikasi) - tanpa CSRF & tanpa grup web
Route::post('/tripay/callback', [TripayController::class, 'handleCallback'])
    ->withoutMiddleware(['web', VerifyCsrfToken::class, \App\Http\Middleware\VerifyCsrfToken::class]);

// Rute halaman terima kasih setelah kembali dari pembayaran
Route::get('/payment/thank-you', [TripayController::class, 'thankYou'])->name('payment.thank-you');

// Rute quantity/stock produk (bisa diakses tanpa login untuk get info, but admin access for updates)
Route::post('/products/{id}/update-quantity', [ProductQttController::class, 'updateQuantity'])->name('products.update_quantity');
Route::get('/products/{id}/stock-info', [ProductQttController::class, 'getStockInfo'])->name('products.stock_info');
Route::post('/products/{id}/add-stock', [ProductQttController::class, 'addStock'])->name('products.add_stock');
Route::post('/products/{id}/reduce-stock', [ProductQttController::class, 'reduceStock'])->name('products.reduce_stock');

// Rute Dashboard (bisa diakses tanpa login)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Rute Donasi (bisa diakses tanpa login)
Route::get('/donasi', [DonasiController::class, 'index'])->name('donasi.index');

// Test route for cart functionality
Route::get('/test-cart', function () {
    try {
        // Test 1: Check if we can query the carts table
        $cartCount = \App\Models\Cart::count();

        // Test 2: Check if we can create a cart item (if user is logged in)
        $testResult = 'No test performed';
        if (auth()->check()) {
            $testResult = 'User authenticated, cart ready to use';
        } else {
            $testResult = 'User not authenticated, please login first';
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart functionality test completed!',
            'data' => [
                'total_cart_items' => $cartCount,
                'authentication_status' => auth()->check() ? 'Logged in' : 'Not logged in',
                'test_result' => $testResult
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'file' => basename($e->getFile()),
            'line' => $e->getLine()
        ]);
    }
});

// --- Rute yang Membutuhkan Autentikasi (Hanya Bisa Diakses Setelah Login) ---
Route::middleware(['auth'])->group(function () {
    // Rute Beli Produk
Route::get('/beli/{id}', [CheckoutController::class, 'beli'])->name('beli');
    // Rute Beli Donasi (gunakan checkout yang sama)
    Route::get('/donasi/beli/{id}', [DonasiController::class, 'beli'])->name('donasi.beli');

    // Rute Keranjang
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

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