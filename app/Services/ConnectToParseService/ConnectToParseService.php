<?php

declare(strict_types=1);

namespace App\Services\ConnectToParseService;

use App\Services\ConnectToParseService\Contracts\ConnectToParseServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ConnectToParseService implements ConnectToParseServiceContract
{
    /**
     * Connect to parsed url use Guzzle
     *
     * @param string $url
     *
     * @return string
     * @throws GuzzleException
     */
    public function connect(string $url): string
    {
        $client = new Client();
        $body = $client->get($url)->getBody();

        return (string)$body;
    }
}
