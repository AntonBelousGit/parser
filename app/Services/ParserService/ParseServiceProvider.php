<?php

declare(strict_types=1);

namespace App\Services\ParserService;


use App\Services\ParserService\Contracts\ParseServiceAttributeContract;
use App\Services\ParserService\Contracts\ParseServiceContract;
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
        $this->app->bind(ParseServiceContract::class, ParseService::class);
        $this->app->bind(ParseServiceAttributeContract::class, ParseService::class);
    }
}
