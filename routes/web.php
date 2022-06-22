<?php

use App\Models\Product;
use App\Services\ProductHistory\Contracts\ProductHistoryContract;
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

Route::get('/', function () {
    $product = Product::first();
    $product->update(['name'=> rand(0, 10000)]);
});
