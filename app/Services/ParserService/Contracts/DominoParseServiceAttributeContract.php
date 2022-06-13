<?php

declare(strict_types=1);

namespace App\Services\ParserService\Contracts;

use App\Services\ParserService\Attribute;

interface DominoParseServiceAttributeContract
{
    /**
     * Parse Product Attribute
     * @param array $array
     * @return Attribute
     */
    public function parseAttribute(array $array = []) : Attribute;
}
