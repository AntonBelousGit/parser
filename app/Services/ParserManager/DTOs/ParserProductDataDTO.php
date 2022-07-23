<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class ParserProductDataDTO
{
    /**
     * ParserProductData constructor.
     *
     * @param array $products
     * @param AttributeDTO $attributes
     */
    public function __construct(public array $products, public AttributeDTO $attributes)
    {
    }
}
