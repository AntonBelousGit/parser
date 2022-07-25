<?php

declare(strict_types=1);

namespace App\Services\ConnectService;

use App\Services\ConnectService\Contracts\ConnectServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ConnectService implements ConnectServiceContract
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
