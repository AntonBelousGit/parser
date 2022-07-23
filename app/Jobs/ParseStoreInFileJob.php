<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseStoreInFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private string $name, private array $config)
    {
    }

    /**
     * Parser site and store in file
     *
     * @param ConfigValidatorContract $configValidatorContract
     * @param ConnectToParseServiceContract $parseServiceContract
     */
    public function handle(
        ConfigValidatorContract $configValidatorContract,
        ConnectToParseServiceContract $parseServiceContract
    ) {
        $configValid = $configValidatorContract->validate($this->config);
        $html = $parseServiceContract->connect($configValid['url']);
        File::put(storage_path("/app/public/file/{$this->name}.xml"), $html);
    }
}
