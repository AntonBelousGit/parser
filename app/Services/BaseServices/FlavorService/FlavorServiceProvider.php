<?php


namespace App\Services\BaseServices\FlavorService;


use App\Services\BaseServices\FlavorService\Contracts\FlavorServiceContract;
use App\Services\BaseServices\FlavorService\Contracts\FlavorValidatorContract;
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
