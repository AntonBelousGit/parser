<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

use Illuminate\Support\Collection;

class AttributeDTO
{
    /**
     * Attribute constructor.
     *
     * @param Collection $sizes
     * @param Collection $flavors
     * @param Collection $toppings
     */
    public function __construct(
        public Collection $sizes,
        public Collection $flavors,
        public Collection $toppings
    ) {
    }
}
