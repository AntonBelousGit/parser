<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use App\Services\TestDataService\Contracts\TestDataServiceContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class TestDataParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to start testing parsing data';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(
        TestDataServiceContract $testDataServiceContract
    ) {
        try {
            $testDataServiceContract->test();
        } catch (Throwable) {
            Log::info('TestDataParse error');
        }
    }
}
