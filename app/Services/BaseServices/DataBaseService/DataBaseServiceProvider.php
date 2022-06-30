<?php


namespace App\Services\BaseServices\DataBaseService;

use App\Services\BaseServices\DataBaseService\Contracts\DataBaseServiceContract;
use Illuminate\Support\ServiceProvider;

class DataBaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(DataBaseServiceContract::class, DataBaseService::class);
    }
}
