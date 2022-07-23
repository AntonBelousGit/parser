<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\ParserManager\Contracts\ParseValidatorContract;
use App\Services\ParserManager\Validator\ConfigValidator;
use App\Services\ParserManager\Validator\ParseValidator;
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
        $this->app->bind(ConfigValidatorContract::class, ConfigValidator::class);
        $this->app->bind(ParseValidatorContract::class, ParseValidator::class);
        $this->app->bind(ParseManagerContract::class, ParseManager::class);
    }
}
