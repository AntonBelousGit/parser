<?php


namespace App\Services\BaseServices;


class ParserProductData
{
    /**
     * ParserProductData constructor.
     * @param array $products
     * @param Attribute $attributes
     */
    public function __construct(public array $products, public Attribute $attributes)
    {
    }
}
