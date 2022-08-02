<?php


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
    $class =  app()->make(\App\Services\ParserManager\Drivers\BeerlinParseDriver::class);
    $products = $class->parseProduct('http://www.beerlin.od.ua/product-category/piczcza/');
});
