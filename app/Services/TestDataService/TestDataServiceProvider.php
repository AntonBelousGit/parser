<?php
declare(strict_types=1);

namespace App\Services\TestDataService;

use App\Services\TestDataService\Contracts\TestDataServiceContract;
use Illuminate\Support\ServiceProvider;

class TestDataServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(TestDataServiceContract::class, TestDataService::class);
    }
}
