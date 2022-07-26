<?php

declare(strict_types=1);

namespace App\Services\ConnectionService\Contracts;

interface ConnectionServiceContract
{
    /**
     * Connect to parsed target - connection url use Guzzle
     *
     * @param string $url
     * @return mixed
     */
    public function getHtml(string $url): mixed;
}
