<?php


namespace App\Services\ConnectToParseService;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ConnectToParseService implements ConnectToParseServiceContract
{
    /**
     * Connect to parsed url use DiDom
     *
     * @param string $url
     *
     * @return Document
     */
    public function connect(string $url): Document
    {
        return new Document($url, true);
    }
}
