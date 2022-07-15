<?php
declare(strict_types=1);

namespace App\Services\ParserManager\DTOs;

class SizeDTO
{
    /**
     * Size constructor.
     *
     * @param string $id
     * @param string $name
     */
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
