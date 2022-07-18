<?php

declare(strict_types=1);

namespace App\Services\ConnectToParseService\Contracts;

use DiDom\Document;

interface ConnectToParseServiceContract
{
    /**
     * Connect to parsed target - connection url use DiDom
     *
     * @param string $url
     * @return Document
     */
    public function callConnectToParseDiDom(string $url): Document;

    /**
     * Connect to parsed target - connection url use Guzzle
     *
     * @param string $url
     * @return mixed
     */
    public function callConnectToParseGuzzle(string $url): mixed;
}
