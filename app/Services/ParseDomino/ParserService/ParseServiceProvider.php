<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\ParserService;


use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
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
        $this->app->bind(DominoParseServiceContract::class, DominoParseService::class);
        $this->app->bind(DominoParseServiceAttributeContract::class, DominoParseService::class);
    }
}
