<?php

namespace App\Services\ParseZharPizza\CallParse;

use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;

use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use App\Services\ParseZharPizza\ProductService\Contracts\ProductServiceContract;
use Throwable;

class CallParseZharPizza
{
    /**
     * CallParseVdhPizza constructor.
     * @param SizeServiceContract $sizeServiceContract
     * @param ToppingServiceContract $toppingServiceContract
     * @param ZharPizzaParseServiceContract $contract
     * @param ZharPizzaParseServiceAttributeContract $attributeContract
     * @param ProductServiceContract $productServiceContract
     */
    public function __construct(
        public SizeServiceContract $sizeServiceContract,
        public ToppingServiceContract $toppingServiceContract,
        public ZharPizzaParseServiceContract $contract,
        public ZharPizzaParseServiceAttributeContract $attributeContract,
        public ProductServiceContract $productServiceContract,
    ) {
    }

    /**
     * Parser VdhPizza
     */
    public function __invoke(): void
    {
        try {
            $dataVdh = $this->contract->parseProduct();
            $attributeVdh = $this->attributeContract->parseAttribute($dataVdh);
            $this->sizeServiceContract->updateOrCreate($attributeVdh->size);
            $this->toppingServiceContract->updateOrCreate($attributeVdh->topping);
            $this->productServiceContract->updateOrCreate($dataVdh);
        } catch (Throwable $exception) {
            report('Error VdhPizza' . $exception);
        }
    }
}
