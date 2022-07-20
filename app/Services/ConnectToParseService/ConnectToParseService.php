<?php


namespace App\Services\ConnectToParseService;


use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ConnectToParseService implements ConnectToParseServiceContract
{
    public const CONNECTION_TYPES = [
        'DIDOM' => 'DiDom',
        'GUZZLE' => 'Guzzle',
    ];

    /**
     * @param string $type
     * @param string $url
     *
     * @return mixed
     */
    public function connect(string $type, string $url): mixed
    {
        $parsingMethod = 'callConnectToParse'.$type;

        return $this->$parsingMethod($url);
    }

    /**
     * Connect to parsed url use DiDom
     *
     * @param string $url
     *
     * @return Document
     */
    private function callConnectToParseDiDom(string $url): Document
    {
        return new Document($url, true);
    }

    /**
     * Connect to parsed url use GuzzleHttp
     *
     * @param string $url
     *
     * @return string
     *
     * @throws GuzzleException
     */
    private function callConnectToParseGuzzle(string $url): string
    {
        $client = new Client();
        $body = $client->get($url)->getBody();

        return (string)$body;
    }
}
