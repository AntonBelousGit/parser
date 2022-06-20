<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ProductService\Contracts\ProductServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
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
        \App\Services\ParseZharPizza\ProductService\Contracts\ProductServiceContract $productZharService,
        VdhPizzaParseServiceContract $vdhPizzaParseServiceContract,
        VdhPizzaParseServiceAttributeContract $vdhPizzaParseServiceAttributeContract,
        \App\Services\ParseVdhPizza\ProductService\Contracts\ProductServiceContract $productVdhServiceContract
    ) {
        try {
//          Domino
            $dataDomino = $contract->parseProduct();
            $attributeDomino = $attributeContract->parseAttribute($dataDomino);
            $sizeServiceContract->update($attributeDomino->size);
            $flavorServiceContract->update($attributeDomino->productRelation);
            $toppingServiceContract->update($attributeDomino->topping);
            $productServiceContract->update($dataDomino);
//          ZharPizza
            $dataZhar = $contractZhar->parseProduct();
            $attributeZhar = $attributeZharContract->parseAttribute($dataZhar);
            $sizeServiceContract->update($attributeZhar->size);
            $toppingServiceContract->update($attributeZhar->topping);
            $productZharService->update($dataZhar);
//          VdhBar
            $dataVdh = $vdhPizzaParseServiceContract->parseProduct();
            $attributeVdh = $vdhPizzaParseServiceAttributeContract->parseAttribute($dataVdh);
            $sizeServiceContract->update($attributeVdh->size);
            $toppingServiceContract->update($attributeVdh->topping);
            $productVdhServiceContract->update($dataVdh);
        } catch (Throwable) {
            report('Something went wrong! Check log file');
        }
    }
}
