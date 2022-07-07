<?php


namespace App\Services\StoreManager\Drivers\ProductDriver;

use App\Services\BaseServices\DataBaseService\ProductDriver;
use App\Services\StoreManager\Contracts\ProductDriverContract;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ProductDriverContract::class, ProductDriver::class);
    }
}
