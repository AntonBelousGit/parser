<?php

declare(strict_types=1);

namespace App\Services\ParserService\Contracts;


use DiDom\Document;

interface DominoParseServiceContract
{

    /**
     * Connect to parsed target - config('services.parser.url') - connection url
     * @return Document
     */

    public function callConnectToParse() : Document;

    /**
     * Return completed parsed file
     * @return array
     */
    public function parseProduct(): array;

}
