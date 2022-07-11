<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class FlavorDTO
{
    /**
     * Flavor constructor.
     *
     * @param array $flavor
     */
    public function __construct(
        public array $flavor= [],
    ) {
    }
}
