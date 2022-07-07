<?php

namespace App\Services\StoreManager\Drivers\AttributeDriver;

use App\Services\StoreManager\Contracts\AttributeServiceContract;
use App\Services\StoreManager\Contracts\AttributeValidatorContract;
use Illuminate\Support\ServiceProvider;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(AttributeServiceContract::class, AttributeDriver::class);
        $this->app->bind(AttributeValidatorContract::class, AttributeValidator::class);
    }
}
