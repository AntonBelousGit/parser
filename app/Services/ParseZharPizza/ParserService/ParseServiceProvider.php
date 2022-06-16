<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService;


use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaProductValidatorContract;
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
        $this->app->bind(ZharPizzaParseServiceContract::class, ZharPizzaParseService::class);
        $this->app->bind(ZharPizzaParseServiceAttributeContract::class, ZharPizzaParseService::class);
        $this->app->bind(ZharPizzaProductValidatorContract::class, ProductValidator::class);

    }
}
