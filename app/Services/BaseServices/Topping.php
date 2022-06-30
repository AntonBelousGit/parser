<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Topping
{
    public function __construct(
        public array $topping = []
    ) {
    }
}
