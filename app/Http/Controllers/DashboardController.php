<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Removed beli method, now in CheckoutController
    public function index(Request $request)
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('dashboard', compact('products'));
    }
}

