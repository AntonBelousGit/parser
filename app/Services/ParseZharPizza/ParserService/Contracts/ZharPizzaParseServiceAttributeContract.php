<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService\Contracts;

use App\Services\ParseZharPizza\ParserService\Attribute;

interface ZharPizzaParseServiceAttributeContract
{
    /**
     * Parse Product Attribute
     * @param array $array
     */
    public function parseAttribute(array $array = []) : Attribute;
}
