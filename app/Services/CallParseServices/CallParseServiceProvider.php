<?php


namespace App\Services\CallParseServices;

use App\Services\CallParseServices\Contracts\CallParseServiceContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class CallParseServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CallParseServiceContract::class, function (Application $app) {
            return $app->make(CallParseService::class, [
                'parsers' => $app->get('config')->get('parsers'),
            ]);
        });
    }
}
