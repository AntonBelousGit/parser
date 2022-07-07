<?php


namespace App\Services\ParserManager\Drivers\ParseDomino;

use App\Services\ParserManager\Drivers\ParseDomino\Contracts\ProductValidatorContract;
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
