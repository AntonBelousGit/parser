<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ParseStoreInFileJob;
use Illuminate\Console\Command;

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
    public function handle():void
    {
        $configs = config('parsers');
        foreach ($configs as $key => $config) {
            dispatch(new ParseStoreInFileJob($key, $config));
        }
    }
}
