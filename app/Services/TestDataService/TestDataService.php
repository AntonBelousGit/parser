<?php

declare(strict_types=1);

namespace App\Services\TestDataService;

use App\Models\ParseConfig;
use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\ParseManager;
use App\Services\TestDataService\Contracts\TestDataServiceContract;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestDataService implements TestDataServiceContract
{
    /**
     * TestDataService constructor.
     * @param ConnectToParseServiceContract $parseServiceContract
     */
    public function __construct(private ConnectToParseServiceContract $parseServiceContract)
    {
    }

    /**
     * Test valid config and parsed data
     */
    public function test(): void
    {
        try {
            $configOld = ParseConfig::where('enable', 1)->get();
            $parsingManager = app(ParseManager::class);
            $parsingManager->callParse(Collection::make($configOld));

            $configNew = ParseConfig::where('enable', 1)->get();
            foreach ($configNew as $parser) {
                $html = $this->parseServiceContract->connect($parser['connection'], $parser['url']);
                File::put(storage_path("/app/public/file/{$parser['name']}.xml"), $html);
            }
        } catch (Throwable $exception) {
            Log::info('parseToFile - problem ' . $exception);
        }
    }
}
