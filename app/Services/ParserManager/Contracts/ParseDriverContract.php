<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

interface ParseDriverContract
{
    /**
     * Return completed parsed file
     *
     * @param string $url
     * @return array
     */
    public function parseProduct(string $url): array;
}
