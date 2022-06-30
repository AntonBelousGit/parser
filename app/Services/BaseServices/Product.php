<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Product
{
    public function __construct(
        public string $id,
        public string $name,
        public array $image,
        public array $imageMobile,
        public Topping $topping,
        public Size $sizes,
        public Flavor $flavors,
        public Attribute $attribute
    ) {
    }
}
