<?php

declare(strict_types=1);

namespace App\Services\ParseDomino\ParserService\Contracts;


use DiDom\Document;

interface DominoParseServiceContract
{

    /**
     * Connect to parsed target - config('services.parser.url') - connection url
     * @param string $address
     * @return Document
     */

    public function callConnectToParse(string $address) : Document;

    /**
     * Return completed parsed file
     * @param string $address
     * @return array
     */
    public function parseProduct(string $address): array;

}
