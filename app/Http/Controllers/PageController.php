<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLog;
use App\Models\ProductPriceLog;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $products = Product::latest()->paginate(12);
        return view('home', compact('products'));
    }

    public function getPriceLog() {
        $price_logs = ProductPriceLog::with(['user', 'product'])->latest()->get();
        return view('price_logs', compact('price_logs'));
    }

    public function getProductLog() {
        $product_logs = ProductLog::with(['user', 'product'])->latest()->get();
        return view('product_logs', compact('product_logs'));
    }
}
