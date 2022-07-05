<?php


namespace App\Services\ParserManager;

use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseServiceContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ParseServiceProvider extends ServiceProvider
{
    /**
     * Register port services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ConfigValidatorContract::class, ConfigValidator::class);
        $this->app->bind(ParseServiceContract::class, function (Application $app) {
            return $app->make(ParseService::class, [
                'parsers' => $app->get('config')->get('parsers'),
            ]);
        });
    }
}
