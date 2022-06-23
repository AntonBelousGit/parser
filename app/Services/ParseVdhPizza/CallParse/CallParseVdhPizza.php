<?php

namespace App\Services\ParseVdhPizza\CallParse;

use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseVdhPizza\ProductService\Contracts\ProductServiceContract as ParseVdhPizzaServiceContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use Throwable;

class CallParseVdhPizza
{
    /**
     * CallParseVdhPizza constructor.
     * @param SizeServiceContract $sizeServiceContract
     * @param ToppingServiceContract $toppingServiceContract
     * @param VdhPizzaParseServiceContract $contract
     * @param VdhPizzaParseServiceAttributeContract $attributeContract
     * @param ParseVdhPizzaServiceContract $productServiceContract
     */
    public function __construct(
        public SizeServiceContract $sizeServiceContract,
        public ToppingServiceContract $toppingServiceContract,
        public VdhPizzaParseServiceContract $contract,
        public VdhPizzaParseServiceAttributeContract $attributeContract,
        public ParseVdhPizzaServiceContract $productServiceContract
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
