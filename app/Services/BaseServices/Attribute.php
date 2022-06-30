<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Attribute
{
    /**
     * Attribute constructor.
     * @param array $size
     * @param array $flavor
     * @param array $topping
     */
    public function __construct(
        public array $size = [],
        public array $flavor = [],
        public array $topping = []
    ) {
    }
}
