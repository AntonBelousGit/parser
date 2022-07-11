<?php
declare(strict_types=1);

namespace App\Services\StoreManager;

use App\Services\StoreManager\Contracts\StoreServiceContract;
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
    }
}
