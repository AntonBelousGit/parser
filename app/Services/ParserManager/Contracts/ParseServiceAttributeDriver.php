<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

use App\Services\ParserManager\DTOs\AttributeDTO;

interface ParseServiceAttributeDriver
{
    /**
     * Parse Product Attribute
     *
     * @param array $array
     * @return AttributeDTO
     */
    public function parseAttribute(array $array = []) : AttributeDTO;
}
