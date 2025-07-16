<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller
{
    // Removed beli method, now in CheckoutController
    public function index(Request $request)
    {
        $products = Product::get();
        return view('dashboard', compact('products'));
    }
}

