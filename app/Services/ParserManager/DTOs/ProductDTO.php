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
     * @param array $images
     * @param array $imagesMobile
     * @param Collection $toppings
     * @param Collection $sizes
     * @param Collection $flavors
     * @param AttributeDTO $attributes
     */
    public function __construct(
        public string $id,
        public string $name,
        public array $images,
        public array $imagesMobile,
        public Collection $toppings,
        public Collection $sizes,
        public Collection $flavors,
        public AttributeDTO $attributes
    ) {
    }
}
