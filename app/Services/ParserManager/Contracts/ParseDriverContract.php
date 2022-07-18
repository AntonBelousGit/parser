<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

interface ParseDriverContract
{
    /**
     * Return completed parsed file
     *
     * @param string $url
     * @param string $method
     * @return array
     */
    public function parseProduct(string $url, string $method): array;
}
