<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardController extends Controller
{
    public function beli(Request $request,$id)
    {
        $product = Product::where('id', '=', $id)->first();
        if ($product==null){
            return redirect('/dashboard');
        }
        return view('checkout',compact('product'));
    }
    public function index(Request $request)
    {
        $product = Product::get();
        return view('dashboard', compact('product'));
    }
}

