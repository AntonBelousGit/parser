<?php

declare(strict_types=1);

namespace App\Services\ParserManager;

use App\Services\BaseServices\DataBaseService\Contracts\DataBaseServiceContract;
use App\Services\BaseServices\FlavorService\Contracts\FlavorServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseServiceContract;
use App\Services\ParserManager\Drivers\BaseParserServiceDriver;
use Illuminate\Support\Facades\Log;
use Throwable;

class ParseService extends BaseParserServiceDriver implements ParseServiceContract
{
    /**
     * ParseService constructor.
     * @param array $parsers
     * @param ConfigValidatorContract $configValidatorContract
     * @param SizeServiceContract $sizeServiceContract
     * @param FlavorServiceContract $flavorServiceContract
     * @param ToppingServiceContract $toppingServiceContract
     * @param DataBaseServiceContract $contract
     */
    public function __construct(
        protected array $parsers,
        private ConfigValidatorContract $configValidatorContract,
        private SizeServiceContract $sizeServiceContract,
        private FlavorServiceContract $flavorServiceContract,
        private ToppingServiceContract $toppingServiceContract,
        private DataBaseServiceContract $contract
    ) {
    }

    /**
     * Call all method parse
     * @return array
     */
    public function callParse(): array
    {
        $parsedData = [];
        foreach ($this->parsers as $parser) {
            try {
                $parser = $this->configValidatorContract->validate($parser);
                $parsedData[] = $this->parser(app()->make($parser['parser']), $parser['config']);
            } catch (Throwable) {
                Log::info('ParseService - validate problem');
            }
        }
        return $parsedData;
    }

    /**
     * Store or update parsed data
     * @return void
     */
    public function parse(): void
    {
        $data = $this->callParse();
        try {
            foreach ($data as $item) {
                $this->sizeServiceContract->updateOrCreate($item->attributes->size);
                $this->flavorServiceContract->updateOrCreate($item->attributes->flavor);
                $this->toppingServiceContract->updateOrCreate($item->attributes->topping);
                $this->contract->updateOrCreate($item->products);
            }
        } catch (Throwable) {
            Log::info('Store or update parsed data - problem');
        }
    }
}
