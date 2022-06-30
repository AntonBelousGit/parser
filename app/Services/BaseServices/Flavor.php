<?php
declare(strict_types=1);

namespace App\Services\BaseServices;

class Flavor
{
    /**
     * Flavor constructor.
     * @param array $flavor
     */
    public function __construct(
        public array $flavor= [],
    ) {
    }
}
