<?php


namespace App\Services\ParseDomino\ToppingService;

use App\Services\ParseDomino\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseDomino\ToppingService\Contracts\ToppingValidatorContract;
use Illuminate\Support\ServiceProvider;

class ToppingServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ToppingValidatorContract::class, ToppingValidator::class);
        $this->app->bind(ToppingServiceContract::class, ToppingService::class);
    }
}
