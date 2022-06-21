<?php

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

Route::get('/', function (ProductHistoryContract $historyContract) {
    return $historyContract->getProductHistory('53aa39d4-500e-4b41-9d42-9e901707335f');

});
