<?php
declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService;

class Product
{
    public function __construct(
        public string $id,
        public string $name,
        public string $image,
        public Topping $topping,
        public Attribute $attribute
    ) {
    }
}
