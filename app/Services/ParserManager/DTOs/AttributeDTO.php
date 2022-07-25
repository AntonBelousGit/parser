<?php

declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

use Illuminate\Support\Collection;

class AttributeDTO
{
    /**
     * Attribute constructor.
     *
     * @param Collection $size
     * @param Collection $flavor
     * @param Collection $topping
     */
    public function __construct(
        public Collection $size,
        public Collection $flavor,
        public Collection $topping
    ) {
    }
}
