<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseManagerContract;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
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
     * @param array $config
     * @return array
     */
    public function callParse(array $config): array
    {
        $parsedData = [];
        foreach ($config as $parser) {
            try {
                $parser = $this->configValidatorContract->validate($parser);
                $parsedData[] = $this->parser(app()->make($parser['parser']), $parser['url']);
            } catch (Throwable $exception) {
                Log::info('ParseManager - validate problem'. $exception);
            }
        }
        return $parsedData;
    }

    /**
     * Parser Pizza
     *
     * @param $app
     * @param string $url
     * @return ParserProductDataDTO|null
     */
    public function parser($app, string $url): ParserProductDataDTO|null
    {
        try {
            $data = $app->parseProduct($url);
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
