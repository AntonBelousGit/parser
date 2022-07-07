<?php


namespace App\Services\StoreManager;

use App\Services\StoreManager\Contracts\ConfigValidatorContract;
use App\Services\StoreManager\Contracts\ProductServiceContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ProductDBServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ConfigValidatorContract::class, ConfigValidator::class);
        $this->app->bind(ProductServiceContract::class, function (Application $app) {
            return $app->make(StoreService::class, [
                'config' => $app->get('config')->get('parsers'),
            ]);
        });
    }
}
