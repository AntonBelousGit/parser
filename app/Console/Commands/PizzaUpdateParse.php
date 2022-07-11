<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParserManager\Contracts\ParseServiceContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use Illuminate\Console\Command;
use Throwable;

class PizzaUpdateParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse';

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
        StoreServiceContract $storeServiceContract,
        ParseServiceContract $parseServiceContract,
    ) {
        try {
            $config = config('parsers');
            $data = $parseServiceContract->callParse($config);
            $storeServiceContract->store($data);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
