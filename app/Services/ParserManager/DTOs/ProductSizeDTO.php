<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

use Illuminate\Support\Collection;

class ProductSizeDTO extends AttributeDTO
{
    /**
     * ProductSize constructor.
     *
     * @param Collection $attribute
     */
    public function __construct(
        public Collection $attribute,
    ) {
    }
}
