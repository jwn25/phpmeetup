<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'index'])->name('home');

Route::get('/price-logs', [PageController::class, 'getPriceLog'])->name('price-logs');

Route::get('/product-logs', [PageController::class, 'getProductLog'])->name('product-logs');

Route::resource('products', ProductController::class)->except('index', 'show');

