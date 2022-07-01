<?php


namespace App\Services\ParseDomino\CallParse;

use App\Services\BaseServices\ParserProductData;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use Throwable;

class CallParseDomino
{
    /**
     * CallParseDomino constructor.
     * @param DominoParseServiceContract $contract
     * @param DominoParseServiceAttributeContract $attributeContract
     */
    public function __construct(
        public DominoParseServiceContract $contract,
        public DominoParseServiceAttributeContract $attributeContract,
    ) {
    }

    /**
     * Parser DominoPizza
     * @param array $config
     * @return ParserProductData
     */
    public function parser(array $config): ParserProductData
    {
        $address = $config['address'] ?? '';
        try {
            $data = $this->contract->parseProduct($address);
            $attribute = $this->attributeContract->parseAttribute($data);
        } catch (Throwable) {
            report('Error dominoParse');
        }
        return new ParserProductData(
            products: $data,
            attributes: $attribute
        );
    }
}
