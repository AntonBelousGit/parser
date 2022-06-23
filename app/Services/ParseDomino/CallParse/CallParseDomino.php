<?php


namespace App\Services\ParseDomino\CallParse;

use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
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
    public function __invoke(): void
    {
        try {
            $dataDomino = $this->contract->parseProduct();
            $attributeDomino = $this->attributeContract->parseAttribute($dataDomino);
            $this->sizeServiceContract->updateOrCreate($attributeDomino->size);
            $this->flavorServiceContract->updateOrCreate($attributeDomino->productRelation);
            $this->toppingServiceContract->updateOrCreate($attributeDomino->topping);
            $this->productServiceContract->updateOrCreate($dataDomino);
        } catch (Throwable $exception) {
            report('Error dominoParse' . $exception);
        }
    }
}
