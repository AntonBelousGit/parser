<?php

declare(strict_types=1);

namespace App\Services\ConnectionService;

use App\Services\ConnectionService\Contracts\ConnectionServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ConnectionService implements ConnectionServiceContract
{
    /**
     * Connect to parsed url use Guzzle
     *
     * @param string $url
     *
     * @return string
     * @throws GuzzleException
     */
    public function getHtml(string $url): string
    {
        $client = new Client();
        $body = $client->get($url)->getBody();

        return (string)$body;
    }
}
