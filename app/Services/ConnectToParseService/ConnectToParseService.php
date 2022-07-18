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
     * @return Document
     */
    public function callConnectToParseDiDom(string $url): Document
    {
        return new Document($url, true);
    }

    /**
     * Connect to parsed url use GuzzleHttp
     *
     * @param string $url
     * @return mixed
     * @throws GuzzleException
     */
    public function callConnectToParseGuzzle(string $url): mixed
    {
        $client = new Client();
        $body = $client->get($url)->getBody();
        return (string)$body;
    }
}
