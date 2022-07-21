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
     * @param array $config
     * @return ParserProductDataDTO
     * @throws BindingResolutionException
     */
    public function callParse(array $config): ParserProductDataDTO
    {
        return $this->parser(app()->make($config['parser']), $config['url'], $config['connection']);
    }

    /**
     * Parser Pizza
     *
     * @param $app
     * @param string $url
     * @param string $method
     * @return ParserProductDataDTO
     */
    public function parser($app, string $url, string $method): ParserProductDataDTO
    {
        $data = $app->parseProduct($url, $method);
        $attribute = $app->parseAttribute($data);
        return new ParserProductDataDTO(
            products: $data,
            attributes: $attribute,
        );
    }
}
