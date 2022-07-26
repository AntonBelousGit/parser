<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ConnectionService\Contracts\ConnectionServiceContract;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreParsedDataInFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private string $name, private array $config)
    {
    }

    /**
     * Parser site and store in file
     *
     * @param ConfigValidatorContract $configValidatorContract
     * @param ConnectionServiceContract $parseServiceContract
     */
    public function handle(
        ConfigValidatorContract $configValidatorContract,
        ConnectionServiceContract $parseServiceContract
    ) {
        $configValid = $configValidatorContract->validate($this->config);
        $html = $parseServiceContract->getHtml($configValid['url']);
        File::put(storage_path("/app/public/file/{$this->name}.xml"), $html);
    }
}
