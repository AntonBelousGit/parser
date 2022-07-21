<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ParseAndStoreProductJob;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductParse extends Command
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
    public function handle(ConfigValidatorContract $configValidatorContract)
    {
        $configs = config('parsers');
        foreach ($configs as $key => $config) {
            try {
                $config = $configValidatorContract->validate($config);
                dispatch(new ParseAndStoreProductJob($config));
            } catch (Throwable) {
                Log::info('command "parse" - ProductParse - validate error ' . $key);
            }
        }
    }
}
