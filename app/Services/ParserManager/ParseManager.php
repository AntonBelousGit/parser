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
        return $this->parser(app()->make($config['parser']), $config['url']);
    }

    /**
     * Parser Pizza
     *
     * @param $app
     * @param string $url
     * @return ParserProductDataDTO
     */
    public function parser($app, string $url): ParserProductDataDTO
    {
        $data = $app->parseProduct($url);
        $attribute = $app->parseAttribute($data);
        return new ParserProductDataDTO(
            products: $data,
            attributes: $attribute,
        );
    }
}
