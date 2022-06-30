<?php


namespace App\Services\ParseDomino\CallParse;

use App\Services\BaseServices\FlavorService\Contracts\FlavorServiceContract;
use App\Services\BaseServices\ParserProductData;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ProductService\Contracts\ProductServiceContract;
use Throwable;

class CallParseDomino
{
    /**
     * CallParseDomino constructor.
     * @param SizeServiceContract $sizeServiceContract
     * @param FlavorServiceContract $flavorServiceContract
     * @param ToppingServiceContract $toppingServiceContract
     * @param DominoParseServiceContract $contract
     * @param DominoParseServiceAttributeContract $attributeContract
     * @param ProductServiceContract $productServiceContract
     */
    public function __construct(
        public SizeServiceContract $sizeServiceContract,
        public FlavorServiceContract $flavorServiceContract,
        public ToppingServiceContract $toppingServiceContract,
        public DominoParseServiceContract $contract,
        public DominoParseServiceAttributeContract $attributeContract,
        public ProductServiceContract $productServiceContract
    ) {
    }

    /**
     * Parser DominoPizza
     */
    public function parser(array $config): ParserProductData
    {
        $address = $config['address'] ?? '';
        try {
            $dataDomino = $this->contract->parseProduct($address);
            $attributeDomino = $this->attributeContract->parseAttribute($dataDomino);
//            $this->sizeServiceContract->updateOrCreate($attributeDomino->size);
//            $this->flavorServiceContract->updateOrCreate($attributeDomino->productRelation);
//            $this->toppingServiceContract->updateOrCreate($attributeDomino->topping);
//            $this->productServiceContract->updateOrCreate($dataDomino);
        } catch (Throwable) {
            report('Error dominoParse');
        }

        return new ParserProductData(
            products: $dataDomino,
            attributes: $attributeDomino
        );
    }
}
