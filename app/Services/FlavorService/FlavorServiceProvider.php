<?php


namespace App\Services\FlavorService;

use App\Services\FlavorService\Contracts\FlavorServiceContract;
use App\Services\FlavorService\Contracts\FlavorValidatorContract;
use Illuminate\Support\ServiceProvider;

class FlavorServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(FlavorValidatorContract::class, FlavorValidator::class);
        $this->app->bind(FlavorServiceContract::class, FlavorService::class);
    }
}
