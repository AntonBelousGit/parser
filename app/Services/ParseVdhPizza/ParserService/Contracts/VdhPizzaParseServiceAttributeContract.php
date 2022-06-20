<?php

declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService\Contracts;

use App\Services\ParseVdhPizza\ParserService\Attribute;

interface VdhPizzaParseServiceAttributeContract
{
    /**
     * Parse Product Attribute
     * @param array $array
     */
    public function parseAttribute(array $array = []) : Attribute;
}
