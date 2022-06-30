<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class ProductSize extends Attribute
{
    /**
     * ProductSize constructor.
     * @param array $attribute
     */
    public function __construct(
        public array $attribute = [],
    ) {
    }
}
