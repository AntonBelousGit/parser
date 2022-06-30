<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Size
{
    /**
     * Size constructor.
     * @param array $size
     */
    public function __construct(
        public array $size = [],
    ) {
    }
}
