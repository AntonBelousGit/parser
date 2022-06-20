<?php
declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService;

class Attribute
{
    public function __construct(
        public array $size = [],
        public array $topping = []
    ) {
    }
}
