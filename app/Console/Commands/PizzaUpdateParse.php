<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ParseConfig;
use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        ParseManagerContract $parseManagerContract,
    ) {
        try {
            $config = ParseConfig::where('enable', 1)->get(['id','enable','parser','connection','url']);
            $data = $parseManagerContract->callParse($config);
            $storeServiceContract->store($data);
        } catch (Throwable) {
            Log::info('command "parse" - PizzaUpdateParse.php - fatal error');
        }
    }
}
