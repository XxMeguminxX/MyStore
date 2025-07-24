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

// Rute untuk halaman awal yang bisa diakses tanpa login
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[ DashboardController::class, 'index']);
Route::get('/beli/{id}', [App\Http\Controllers\CheckoutController::class, 'beli']);
// Route::get('/checkout/{id}', [App\Http\Controllers\CheckoutController::class, 'show']);
// Route::post('/tripay/transaction', [TripayController::class, 'createTransaction']);

Route::get('/webtest', function() {
    return 'web ok';
});