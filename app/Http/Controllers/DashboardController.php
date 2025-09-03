<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Removed beli method, now in CheckoutController
    public function index(Request $request)
    {
        $sortBy = $request->get('sort', 'newest');

        $query = Product::query();

        switch ($sortBy) {
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'stock_high':
                $query->orderBy('stock', 'desc');
                break;
            case 'stock_low':
                $query->orderBy('stock', 'asc');
                break;
            case 'bestseller':
                $query->leftJoin('transactions', 'product.id', '=', 'transactions.product_id')
                      ->select('product.*', DB::raw('COUNT(transactions.id) as sales_count'))
                      ->groupBy('product.id')
                      ->orderBy('sales_count', 'desc')
                      ->orderBy('product.created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->get();

        return view('dashboard', compact('products', 'sortBy'));
    }
}

