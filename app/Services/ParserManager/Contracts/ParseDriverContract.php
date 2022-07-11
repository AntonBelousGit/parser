<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

interface ParseDriverContract
{
    /**
     * Connect to parsed target - connection url
     *
     * @param string $url
     * @return mixed
     */
    public function callConnectToParse(string $url): mixed;

    /**
     * Return completed parsed file
     *
     * @param string $url
     * @return array
     */
    public function parseProduct(string $url): array;
}
