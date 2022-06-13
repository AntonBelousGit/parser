<?php

declare(strict_types=1);

namespace App\Services\ParserService\Contracts;

interface DominoParseServiceAttributeContract
{
    /**
     * Parse Product Attribute
     * @param array $array
     * @return array
     */
    public function parseAttribute(array $array = []) : array;
}
