<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class ProductSizeDTO extends AttributeDTO
{
    /**
     * ProductSize constructor.
     *
     * @param array $attribute
     */
    public function __construct(
        public array $attribute = [],
    ) {
    }
}
