<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParserService\Contracts\ParseServiceAttributeContract;
use App\Services\ParserService\Contracts\ParseServiceContract;
use App\Services\ProductService\Contracts\ProductServiceContract;
use App\Services\SizeService\Contracts\SizeServiceContract;
use App\Services\ToppingService\Contracts\ToppingServiceContract;
use Illuminate\Console\Command;
use Throwable;

class PizzaParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pizza:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to start parsing product';

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle(
        ParseServiceContract $contract,
        ParseServiceAttributeContract $attributeContract,
        SizeServiceContract $sizeServiceContract,
        FlavorServiceContract $flavorServiceContract,
        ToppingServiceContract $toppingServiceContract,
        ProductServiceContract $productServiceContract,
    )
    {
        try {
            $data = $contract->parseProduct();
            $attribute = $attributeContract->parseAttribute($data);
            $sizeServiceContract->store($attribute[config('services.parser.product_attribute')]);
            $flavorServiceContract->store($attribute[config('services.parser.product_relations_attribute')]);
            $toppingServiceContract->store($attribute[config('services.parser.product_topping')]);
            $productServiceContract->store($data);

            $this->info('The command was successful!');
        } catch (Throwable $e) {
            report($e);
            $this->error('Something went wrong! Check log file');
        }


    }
}
