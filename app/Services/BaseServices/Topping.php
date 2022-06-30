<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Topping
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
