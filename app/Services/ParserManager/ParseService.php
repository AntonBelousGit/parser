<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseServiceContract;
use App\Services\ParserManager\Drivers\BaseParserServiceDriver;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParseService extends BaseParserServiceDriver implements ParseServiceContract
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
                Log::info('ParseService - validate problem'. $exception);
            }
        }
        return $parsedData;
    }
}
