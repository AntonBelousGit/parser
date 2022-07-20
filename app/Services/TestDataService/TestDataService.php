<?php

declare(strict_types=1);

namespace App\Services\TestDataService;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\TestDataService\Contracts\TestDataServiceContract;
use File;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestDataService implements TestDataServiceContract
{
    public function __construct(private ConnectToParseServiceContract $parseServiceContract)
    {
    }

    public function test(): void
    {
        $config = config('parsers');
        foreach ($config as $key => $parser) {
            try {
                $html = $this->parseServiceContract->connect($parser['connection'], $parser['url']);
                $file = "/app/public/file/{$key}.xml";
                if (File::exists(storage_path($file))) {
                    $tempFile = "/app/public/file/temp/{$key}.xml";
                    File::put(storage_path($tempFile), $html);
                    if (filesize(storage_path($tempFile)) !== filesize(storage_path($file))) {
                        File::move(storage_path($tempFile), storage_path($file));
                        $config[$key]['status'] = false;
                        Log::info($key . ' change structure!!!');
                    } else {
                        File::delete(storage_path($tempFile));
                    }
                } else {
                    File::put(storage_path("/app/public/file/{$key}.xml"), $html);
                }
            } catch (Throwable $exception) {
                Log::info('parseToFile - problem ' . $exception);
            }
        }
        $config = var_export($config, true);
        File::put(config_path('/parsers.php'), "<?php\n declare(strict_types=1);\n return $config ;");
    }
}
