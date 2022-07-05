<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class ToppingDTO
{
    /**
     * Topping constructor.
     * @param array $topping
     */
    public function __construct(
        public array $topping = []
    ) {
    }
}
