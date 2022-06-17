<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ProductService\Contracts\ProductServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
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
        ZharPizzaParseServiceContract $contractZhar,
        ZharPizzaParseServiceAttributeContract $attributeZharContract,
        \App\Services\ParseZharPizza\ProductService\Contracts\ProductServiceContract $productZharService

    ) {
        try {
            //Domino
            $data = $contract->parseProduct();
            $attribute = $attributeContract->parseAttribute($data);
            $sizeServiceContract->update($attribute->size);
            $flavorServiceContract->update($attribute->productRelation);
            $toppingServiceContract->update($attribute->topping);
            $productServiceContract->update($data);
            //ZharPizza
            $data = $contractZhar->parseProduct();
            $attribute = $attributeZharContract->parseAttribute($data);
            $sizeServiceContract->update($attribute->size);
            $toppingServiceContract->update($attribute->topping);
            $productZharService->update($data);

        } catch (Throwable) {
            report('Something went wrong! Check log file');
        }
    }
}
