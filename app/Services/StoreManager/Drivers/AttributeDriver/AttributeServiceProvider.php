<?php
declare(strict_types=1);

namespace App\Services\StoreManager\Drivers\AttributeDriver;

use App\Services\StoreManager\Contracts\AttributeDriverContract;
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
        $this->app->bind(AttributeDriverContract::class, AttributeDriver::class);
        $this->app->bind(AttributeValidatorContract::class, AttributeValidator::class);
    }
}
