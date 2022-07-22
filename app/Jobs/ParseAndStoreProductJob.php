<?php

namespace App\Jobs;

use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParseAndStoreProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $config;

    /**
     * Create a new job instance.
     *
     * @param array $config
     * @return void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        StoreServiceContract $storeServiceContract,
        ParseManagerContract $parseManagerContract,
        ConfigValidatorContract $configValidatorContract
    ) {
        $configValid = $configValidatorContract->validate($this->config);
        $data = $parseManagerContract->callParse($configValid['parser'], $configValid['url']);
        $storeServiceContract->store($data);
    }
}
