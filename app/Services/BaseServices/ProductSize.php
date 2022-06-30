<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class ProductSize extends Attribute
{
    public function __construct(
        public array $attribute = [],
    ) {
    }
}
