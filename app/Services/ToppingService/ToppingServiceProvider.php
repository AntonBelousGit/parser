<?php


namespace App\Services\ToppingService;

use App\Services\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ToppingService\Contracts\ToppingValidatorContract;
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
