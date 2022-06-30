<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\ParserService\Contracts;

use App\Services\BaseServices\Attribute;

interface DominoParseServiceAttributeContract
{
    /**
     * Parse Product Attribute
     * @param array $array
     * @return Attribute
     */
    public function parseAttribute(array $array = []) : Attribute;
}
