<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Events\DisableCorruptedParserEvent;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParseManager implements ParseManagerContract
{
    /**
     * ParseService constructor.
     *
     * @param ConfigValidatorContract $configValidatorContract
     */
    public function __construct(
        private ConfigValidatorContract $configValidatorContract,
    ) {
    }

    /**
     * Call all method parse
     *
     * @param Collection $config
     * @return array
     */
    public function callParse(Collection $config): array
    {
        $parsedData = [];
        foreach ($config as $parser) {
            try {
                $parser = $this->configValidatorContract->validate($parser->toArray());
                $parsedData[] = $this->parser(app()->make($parser['parser']), $parser['url'], $parser['connection']);
            } catch (Throwable) {
                Log::info('ParseManager - validate problem '. $parser['name']);
                event(new DisableCorruptedParserEvent($parser));
            }
        }
        return $parsedData;
    }

    /**
     * Parser Pizza
     *
     * @param $app
     * @param string $url
     * @param string $method
     * @return ParserProductDataDTO|null
     */
    public function parser($app, string $url, string $method): ParserProductDataDTO|null
    {
        try {
            $data = $app->parseProduct($url, $method);
            $attribute = $app->parseAttribute($data);
        } catch (Throwable) {
            Log::info('Error Parse');
            return null;
        }
        return new ParserProductDataDTO(
            products: $data,
            attributes: $attribute,
        );
    }
}
