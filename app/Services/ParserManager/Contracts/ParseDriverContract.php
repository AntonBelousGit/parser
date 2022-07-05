<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Contracts;

interface ParseDriverContract
{
    /**
     * Connect to parsed target - connection url
     * @param string $address
     * @return mixed
     */
    public function callConnectToParse(string $address): mixed;

    /**
     * Return completed parsed file
     * @param string $address
     * @return array
     */
    public function parseProduct(string $address): array;
}
