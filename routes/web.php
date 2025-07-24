<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripayController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[ DashboardController::class, 'index']);
Route::get('/beli/{id}', [App\Http\Controllers\CheckoutController::class, 'beli']);
// Route::get('/checkout/{id}', [App\Http\Controllers\CheckoutController::class, 'show']);
Route::post('/tripay/transaction', [TripayController::class, 'createTransaction']);
Route::post('/tripay/callback', [TripayController::class, 'handleCallback']);