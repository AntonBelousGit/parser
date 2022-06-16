<?php

declare(strict_types=1);

namespace App\Services\ParseZharPizza\ParserService\Contracts;

interface ZharPizzaParseServiceContract
{
    /**
     * @return mixed
     */

    public function callConnectToParse(): mixed;

    /**
     * Return completed parsed file
     */
    public function parseProduct(): array;
}
