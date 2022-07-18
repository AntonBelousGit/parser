<?php

declare(strict_types=1);

namespace App\Services\ParseToFileService;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParseToFileService\Contracts\ParseToFileServiceContract;
use Config;
use File;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParseToFileService implements ParseToFileServiceContract
{
    public function __construct(
        protected ConnectToParseServiceContract $parseServiceContract,
        protected ConfigValidatorContract $configValidatorContract,
    ) {
    }

    /**
     * Parse site to file
     */
    public function parseToFile(): void
    {
        $config = config('parsers');
        foreach ($config as $key => $parser) {
            try {
                $method = $parser['method'];
                $html = $this->parseServiceContract->$method($parser['url']);
                $file = '/file/' . $key . '.html';

                if (File::exists(public_path($file))) {
                    $tempFile = '/file/temp/' . $key . '.html';
                    File::put(public_path() . $tempFile, $html);

                    if (filesize(public_path() . $tempFile) !== filesize(public_path() . $file)) {
                        File::move(public_path($tempFile), public_path($file));
                        //Not set in runtime
//                        Config::set('parsers.dominoParse.status', 'false');
//                        config(['parsers.dominoParse.status' => 'false']);
                        Log::info($key . ' maybe change structure!!!');
                    } else {
                        File::delete(public_path($tempFile));
                    }
                } else {
                    File::put(public_path() . '/file/' . $key . '.html', $html);
                }
            } catch (Throwable $exception) {
                Log::info('parseToFile - problem ' . $exception);
            }
        }
    }
}
