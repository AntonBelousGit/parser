<?php

declare(strict_types=1);

namespace App\Services\CallParseServices;

use App\Services\CallParseServices\Contracts\CallParseServiceContract;

use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;

use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ProductService\Contracts\ProductServiceContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use App\Services\ParseZharPizza\ProductService\Contracts\ProductServiceContract as ParseZharPizzaServiceContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use App\Services\ParseVdhPizza\ProductService\Contracts\ProductServiceContract as ParseVdhPizzaServiceContract;
use Throwable;

class CallParseService implements CallParseServiceContract
{

    /**
     * CallParseService constructor.
     * @param SizeServiceContract $sizeServiceContract
     * @param FlavorServiceContract $flavorServiceContract
     * @param ToppingServiceContract $toppingServiceContract
     */
    public function __construct(
        public SizeServiceContract $sizeServiceContract,
        public FlavorServiceContract $flavorServiceContract,
        public ToppingServiceContract $toppingServiceContract,
    ) {
    }

    /**
     * Call all method parse
     */
    public function callParse(): void
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if ($method != '__construct' && $method != 'callParse') {
                $this->{$method}();
            }
        }
    }

    /**
     * @param DominoParseServiceContract $contract
     * @param DominoParseServiceAttributeContract $attributeContract
     * @param ProductServiceContract $productServiceContract
     */

    protected function dominoParse(
        DominoParseServiceContract $contract,
        DominoParseServiceAttributeContract $attributeContract,
        ProductServiceContract $productServiceContract
    ) {
        try {
            $dataDomino = $contract->parseProduct();
            $attributeDomino = $attributeContract->parseAttribute($dataDomino);
            $this->sizeServiceContract->updateOrCreate($attributeDomino->size);
            $this->flavorServiceContract->updateOrCreate($attributeDomino->productRelation);
            $this->toppingServiceContract->updateOrCreate($attributeDomino->topping);
            $productServiceContract->updateOrCreate($dataDomino);
        } catch (Throwable) {
            report('Error dominoParse');
        }
    }

    /**
     * @param ZharPizzaParseServiceContract $contractZhar
     * @param ZharPizzaParseServiceAttributeContract $attributeZharContract
     * @param ParseZharPizzaServiceContract $productZharService
     */

    protected function zharPizza(
        ZharPizzaParseServiceContract $contractZhar,
        ZharPizzaParseServiceAttributeContract $attributeZharContract,
        ParseZharPizzaServiceContract $productZharService,
    ) {
        try {
            $dataZhar = $contractZhar->parseProduct();
            $attributeZhar = $attributeZharContract->parseAttribute($dataZhar);
            $this->sizeServiceContract->updateOrCreate($attributeZhar->size);
            $this->toppingServiceContract->updateOrCreate($attributeZhar->topping);
            $productZharService->updateOrCreate($dataZhar);
        } catch (Throwable) {
            report('Error zharPizza');
        }
    }

    /**
     * @param VdhPizzaParseServiceContract $vdhPizzaParseServiceContract
     * @param VdhPizzaParseServiceAttributeContract $vdhPizzaParseServiceAttributeContract
     * @param ParseVdhPizzaServiceContract $productVdhServiceContract
     */

    protected function vdhBar(
        VdhPizzaParseServiceContract $vdhPizzaParseServiceContract,
        VdhPizzaParseServiceAttributeContract $vdhPizzaParseServiceAttributeContract,
        ParseVdhPizzaServiceContract $productVdhServiceContract
    ) {
        try {
            $dataVdh = $vdhPizzaParseServiceContract->parseProduct();
            $attributeVdh = $vdhPizzaParseServiceAttributeContract->parseAttribute($dataVdh);
            $this->sizeServiceContract->updateOrCreate($attributeVdh->size);
            $this->toppingServiceContract->updateOrCreate($attributeVdh->topping);
            $productVdhServiceContract->updateOrCreate($dataVdh);
        } catch (Throwable) {
            report('Error vdhBar');
        }
    }
}
