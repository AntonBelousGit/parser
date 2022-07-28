<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class ToppingDTO
{
    /**
     * Topping constructor.
     *
     * @param string $id
     * @param string $name
     * @param float $price
     */
    public function __construct(
        public string $id,
        public string $name,
        public float $price = 0
    ) {
    }
}
