<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ProductService\Contracts\ProductServiceContract;
use App\Services\ParseDomino\SizeService\Contracts\SizeServiceContract;
use App\Services\ParseDomino\ToppingService\Contracts\ToppingServiceContract;
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
            $sizeServiceContract->update($attribute->size);
            $flavorServiceContract->update($attribute->productRelation);
            $toppingServiceContract->update($attribute->topping);
            $productServiceContract->update($data);
        } catch (Throwable) {
            report('Something went wrong! Check log file');
        }
    }
}
