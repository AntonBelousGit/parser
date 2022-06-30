<?php

namespace App\Services\ParseZharPizza\CallParse;

use App\Services\BaseServices\ParserProductData;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use Throwable;

class CallParseZharPizza
{
    /**
     * CallParseVdhPizza constructor.
     * @param ZharPizzaParseServiceContract $contract
     * @param ZharPizzaParseServiceAttributeContract $attributeContract
     */
    public function __construct(
        public ZharPizzaParseServiceContract $contract,
        public ZharPizzaParseServiceAttributeContract $attributeContract,
    ) {
    }

    /**
     * Parser ZharPizza
     * @param $config
     * @return ParserProductData
     */
    public function parser($config): ParserProductData
    {
        $address = $config['address'] ?? '';
        try {
            $dataZhar = $this->contract->parseProduct($address);
            $attributeZhar = $this->attributeContract->parseAttribute($dataZhar);
        } catch (Throwable) {
            report('Error VdhPizza');
        }
        return new ParserProductData(
            products: $dataZhar,
            attributes: $attributeZhar
        );
    }
}
