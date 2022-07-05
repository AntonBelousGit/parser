<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers\ParseVdhPizza\ParserService;



use App\Services\ParserManager\Drivers\ParseVdhPizza\ParserService\Contracts\VdhPizzaProductValidatorContract;
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
        $this->app->bind(VdhPizzaProductValidatorContract::class, ProductValidator::class);
    }
}
