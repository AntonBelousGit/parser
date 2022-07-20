<?php
declare(strict_types=1);

namespace App\Services\ConnectToParseService\Contracts;

interface ConnectToParseServiceContract
{
    /**
     * Connect to parsed target - connection url use (DiDom,Guzzle)
     *
     * @param string $type
     * @param string $url
     * @return mixed
     */
    public function connect(string $type, string $url): mixed;

}
