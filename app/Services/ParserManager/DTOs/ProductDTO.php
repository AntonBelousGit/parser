<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class ProductDTO
{
    /**
     * Product constructor.
     *
     * @param string $id
     * @param string $name
     * @param array $image
     * @param array $imageMobile
     * @param ToppingDTO $topping
     * @param SizeDTO $sizes
     * @param FlavorDTO $flavors
     * @param AttributeDTO $attribute
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $image,
        public array $imageMobile,
        public ToppingDTO $topping,
        public SizeDTO $sizes,
        public FlavorDTO $flavors,
        public AttributeDTO $attribute
    ) {
    }
}
