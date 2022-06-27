<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService\Contracts;

interface ZharPizzaParseServiceContract
{
    /**
     * @param string $address
     * @return mixed
     */

    public function callConnectToParse(string $address): mixed;

    /**
     * Return completed parsed file
     * @param string $address
     */

    public function parseProduct(string $address): array;
}
