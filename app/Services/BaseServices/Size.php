<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Size
{
    public function __construct(
        public array $size = [],
    ) {
    }
}
