<?php


namespace App\Services\ParseVdhPizza\ProductService;

use App\Services\ParseVdhPizza\ProductService\Contracts\ProductValidatorContract;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ProductValidatorContract::class, ProductValidator::class);
    }
}
