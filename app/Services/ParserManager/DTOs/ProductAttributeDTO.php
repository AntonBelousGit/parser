<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

use Illuminate\Support\Collection;

class ProductAttributeDTO
{
    /**
     * ProductSize constructor.
     *
     * @param Collection $attributes
     */
    public function __construct(
        public Collection $attributes,
    ) {
    }
}
