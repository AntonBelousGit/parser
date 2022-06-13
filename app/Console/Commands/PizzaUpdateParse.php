<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ProductService\Contracts\ProductServiceContract;
use App\Services\SizeService\Contracts\SizeServiceContract;
use App\Services\ToppingService\Contracts\ToppingServiceContract;
use Illuminate\Console\Command;
use Throwable;

class PizzaUpdateParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pizza:update';

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
        DominoParseServiceContract $contract,
        DominoParseServiceAttributeContract $attributeContract,
        SizeServiceContract $sizeServiceContract,
        FlavorServiceContract $flavorServiceContract,
        ToppingServiceContract $toppingServiceContract,
        ProductServiceContract $productServiceContract,
    ) {
        try {
            $data = $contract->parseProduct();
            $attribute = $attributeContract->parseAttribute($data);
            $sizeServiceContract->update($attribute[config('services.parser.product_attribute')]);
            $flavorServiceContract->update($attribute[config('services.parser.product_relations_attribute')]);
            $toppingServiceContract->update($attribute[config('services.parser.product_topping')]);
            $productServiceContract->update($data);
        } catch (Throwable) {
            report('Something went wrong! Check log file');
        }
    }
}
