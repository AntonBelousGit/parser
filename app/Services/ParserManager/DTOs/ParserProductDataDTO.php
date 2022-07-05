<?php


namespace App\Services\ParserManager\DTOs;


class ParserProductDataDTO
{
    /**
     * ParserProductData constructor.
     * @param array $products
     * @param AttributeDTO $attributes
     */
    public function __construct(public array $products, public AttributeDTO $attributes)
    {
    }
}
