<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Attribute
{
    public function __construct(
        public array $size = [],
        public array $flavor = [],
        public array $topping = []
    ) {
    }
}
