<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

use Illuminate\Support\Collection;

class ParserProductDataDTO
{
    /**
     * ParserProductData constructor.
     *
     * @param Collection $products
     * @param AttributeDTO $attributes
     */
    public function __construct(public Collection $products, public AttributeDTO $attributes)
    {
    }
}
