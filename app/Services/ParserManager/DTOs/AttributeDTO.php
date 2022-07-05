<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class AttributeDTO
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
