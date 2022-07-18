<?php
declare(strict_types=1);

namespace App\Services\ParseToFileService;

use App\Services\ParseToFileService\Contracts\ParseToFileServiceContract;
use Illuminate\Support\ServiceProvider;

class ParseToFileServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ParseToFileServiceContract::class, ParseToFileService::class);
    }
}
