<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ParseAndStoreProductJob;
use Illuminate\Console\Command;

class ProductParseAndStoreCommand extends Command
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
    public function handle()
    {
        $configs = config('parsers');
        foreach ($configs as $config) {
            dispatch(new ParseAndStoreProductJob($config));
        }
    }
}
