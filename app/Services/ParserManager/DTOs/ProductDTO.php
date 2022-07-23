<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

use Illuminate\Support\Collection;

class ProductDTO
{
    /**
     * Product constructor.
     *
     * @param string $id
     * @param string $name
     * @param array $image
     * @param array $imageMobile
     * @param Collection $topping
     * @param Collection $sizes
     * @param Collection $flavors
     * @param AttributeDTO $attribute
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $image,
        public array $imageMobile,
        public Collection $topping,
        public Collection $sizes,
        public Collection $flavors,
        public AttributeDTO $attribute
    ) {
    }
}
