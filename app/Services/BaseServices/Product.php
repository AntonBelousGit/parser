<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Product
{
    /**
     * Product constructor.
     * @param string $id
     * @param string $name
     * @param array $image
     * @param array $imageMobile
     * @param Topping $topping
     * @param Size $sizes
     * @param Flavor $flavors
     * @param Attribute $attribute
     */
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
