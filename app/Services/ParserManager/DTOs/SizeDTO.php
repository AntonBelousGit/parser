<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class SizeDTO
{
    /**
     * Size constructor.
     *
     * @param array $size
     */
    public function __construct(
        public array $size = [],
    ) {
    }
}
