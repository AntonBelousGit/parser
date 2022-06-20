<?php

declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService;


use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaProductValidatorContract;
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
        $this->app->bind(VdhPizzaParseServiceContract::class, VdhPizzaParseService::class);
        $this->app->bind(VdhPizzaParseServiceAttributeContract::class, VdhPizzaParseService::class);
        $this->app->bind(VdhPizzaProductValidatorContract::class, ProductValidator::class);

    }
}
