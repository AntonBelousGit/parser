<?php

declare(strict_types=1);

namespace App\Services\ParseVdhPizza\ParserService\Contracts;

interface VdhPizzaParseServiceContract
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
