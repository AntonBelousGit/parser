<?php

declare(strict_types=1);

namespace App\Services\ProductHistory;

use App\Services\ProductHistory\Contracts\ProductHistoryContract;
use Illuminate\Support\ServiceProvider;

class ProductHistoryServiceProvider extends ServiceProvider
{
    /**
     * Register product history service.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ProductHistoryContract::class, ProductHistoryService::class);
    }
}
