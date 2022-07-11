<?php

declare(strict_types=1);

namespace App\Services\ParserManager\Drivers;

use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class BaseParserServiceDriver
{
    /**
     * Parser Pizza
     * @param $app
     * @param string $url
     * @return ParserProductDataDTO
     */
    public function parser($app, string $url): ParserProductDataDTO
    {
        try {
            $data = $app->parseProduct($url);
            $attribute = $app->parseAttribute($data);
        } catch (Throwable) {
            Log::info('Error Parse');
        }
        return new ParserProductDataDTO(
            products: $data,
            attributes: $attribute,
//            config: $config
        );
    }
}
