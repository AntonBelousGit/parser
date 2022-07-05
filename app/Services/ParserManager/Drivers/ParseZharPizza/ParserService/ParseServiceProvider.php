<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers\ParseZharPizza\ParserService;

use App\Services\ParserManager\Drivers\ParseZharPizza\ParserService\Contracts\ZharPizzaProductValidatorContract;
use Illuminate\Support\ServiceProvider;

class ParseServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ZharPizzaProductValidatorContract::class, ProductValidator::class);
    }
}
