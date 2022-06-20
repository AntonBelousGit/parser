<?php
declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService;

class ProductSize extends Attribute
{
    public function __construct(
        public array $attribute = [],
        public float $price = 0
    ) {
    }
}