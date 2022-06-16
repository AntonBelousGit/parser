<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use Illuminate\Console\Command;
use Throwable;

class ZharPizzaUpdateParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhar:update';

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
        ZharPizzaParseServiceContract $contract,
        ZharPizzaParseServiceAttributeContract $attributeContract,
    ) {
        try {
            $data = $contract->parseProduct();
            $attribute = $attributeContract->parseAttribute($data);

            dd($attribute);
        } catch (Throwable) {
            report('Something went wrong! Check log file');
        }
    }
}
