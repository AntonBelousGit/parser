<?php
declare(strict_types=1);

namespace App\Services\ConnectToParseService;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use Illuminate\Support\ServiceProvider;

class ConnectToParseServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ConnectToParseServiceContract::class, ConnectToParseService::class);
    }
}
