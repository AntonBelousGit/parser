<?php


namespace App\Services\ParseDomino\FlavorService;

use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParseDomino\FlavorService\Contracts\FlavorValidatorContract;
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
