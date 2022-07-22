<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use Illuminate\Contracts\Container\BindingResolutionException;

class ParseManager implements ParseManagerContract
{
    /**
     * Call all method parse
     *
     * @param string $driverName
     * @param string $url
     * @return ParserProductDataDTO
     * @throws BindingResolutionException
     */
    public function callParse(string $driverName, string $url): ParserProductDataDTO
    {
        $driver = app()->make($driverName);
        return $driver->parseProduct($url);
    }
}
