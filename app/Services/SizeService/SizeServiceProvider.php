<?php


namespace App\Services\SizeService;

use App\Services\SizeService\Contracts\SizeServiceContract;
use App\Services\SizeService\Contracts\SizeValidatorContract;
use Illuminate\Support\ServiceProvider;

class SizeServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(SizeValidatorContract::class, SizeValidator::class);
        $this->app->bind(SizeServiceContract::class, SizeService::class);
    }
}
