<?php

declare(strict_types=1);

namespace App\Services\ConnectService;

use App\Services\ConnectService\Contracts\ConnectServiceContract;
use Illuminate\Support\ServiceProvider;

class ConnectServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ConnectServiceContract::class, ConnectService::class);
    }
}
