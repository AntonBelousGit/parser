<?php


namespace App\Services\ParseDomino\ProductService;

use App\Services\ParseDomino\ProductService\Contracts\ProductValidatorContract;
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
