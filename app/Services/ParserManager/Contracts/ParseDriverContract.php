<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

use App\Services\ParserManager\DTOs\ParserProductDataDTO;

interface ParseDriverContract
{
    /**
     * Return completed parsed file
     *
     * @param string $url
     * @return ParserProductDataDTO
     */
    public function parseProduct(string $url): ParserProductDataDTO;
}
