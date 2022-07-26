<?php

declare(strict_types=1);

namespace App\Services\ConnectionService;

use App\Services\ConnectionService\Contracts\ConnectionServiceContract;
use Illuminate\Support\ServiceProvider;

class ConnectionServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ConnectionServiceContract::class, ConnectionService::class);
    }
}
