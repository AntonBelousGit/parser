<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\ParseToFileService\Contracts\ParseToFileServiceContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use Illuminate\Console\Command;
use Throwable;

class ParseDataToFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-to-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to start parsing product';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(
        ParseToFileServiceContract $parseToFileServiceContract,
    ) {
        try {
            $parseToFileServiceContract->parseToFile();
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
