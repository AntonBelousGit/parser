<?php

declare(strict_types=1);

namespace App\Services\ParserService\Contracts;


use DiDom\Document;

interface ParseServiceContract
{
    /*
     *  config('services.parser.url') - connection url
     */
    public function callConnectToParse() : Document;

    public function parseProduct(): array;

}
