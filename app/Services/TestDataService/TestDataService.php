<?php

declare(strict_types=1);

namespace App\Services\TestDataService;

use App\Models\ParseConfig;
use App\Services\ParserManager\ParseManager;
use App\Services\TestDataService\Contracts\TestDataServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestDataService implements TestDataServiceContract
{
    /**
     * Test valid config and parsed data
     */
    public function test(): void
    {
        try {
            $configOld = ParseConfig::where('enable', 1)->get();
            $parsingManager = app(ParseManager::class);
            $parsingManager->callParse(Collection::make($configOld));
        } catch (Throwable $exception) {
            Log::info('parseToFile - problem ' . $exception);
        }
    }
}
