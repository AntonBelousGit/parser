<?php


namespace App\Services\ProductService;

use App\Services\ProductService\Contracts\ProductServiceContract;
use App\Services\ProductService\Contracts\ProductValidatorContract;
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
        $this->app->bind(ProductServiceContract::class, ProductService::class);
    }
}
