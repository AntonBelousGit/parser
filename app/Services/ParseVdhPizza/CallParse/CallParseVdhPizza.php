<?php

namespace App\Services\ParseVdhPizza\CallParse;

use App\Services\BaseServices\ParserProductData;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use Throwable;

class CallParseVdhPizza
{
    /**
     * CallParseVdhPizza constructor.
     * @param VdhPizzaParseServiceContract $contract
     * @param VdhPizzaParseServiceAttributeContract $attributeContract
     */
    public function __construct(
        public VdhPizzaParseServiceContract $contract,
        public VdhPizzaParseServiceAttributeContract $attributeContract,
    ) {
    }

    /**
     * Parser VdhPizza
     */
    public function parser(array $config): ParserProductData
    {
        $address = $config['address'] ?? '';
        try {
            $data = $this->contract->parseProduct($address);
            $attribute= $this->attributeContract->parseAttribute($data);
        } catch (Throwable) {
            report('Error VdhPizza');
        }

        return new ParserProductData(
            products: $data,
            attributes: $attribute
        );
    }
}
