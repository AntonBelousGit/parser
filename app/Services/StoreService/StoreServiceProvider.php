<?php
declare(strict_types=1);

namespace App\Services\StoreService;

use App\Services\StoreService\Contracts\AttributeValidatorContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use App\Services\StoreService\Validator\AttributeValidator;
use Illuminate\Support\ServiceProvider;

class StoreServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(StoreServiceContract::class, StoreService::class);
        $this->app->bind(AttributeValidatorContract::class, AttributeValidator::class);
    }
}
