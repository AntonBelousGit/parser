<?php

declare(strict_types=1);

namespace App\Services\ParseServices;

use App\Services\BaseServices\DataBaseService\Contracts\DataBaseServiceContract;
use App\Services\BaseServices\FlavorService\Contracts\FlavorServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseServices\Contracts\ParseServiceContract;
use Throwable;

class ParseService implements ParseServiceContract
{
    /**
     * ParseService constructor.
     * @param array $parsers
     * @param SizeServiceContract $sizeServiceContract
     * @param FlavorServiceContract $flavorServiceContract
     * @param ToppingServiceContract $toppingServiceContract
     * @param DataBaseServiceContract $contract
     */
    public function __construct(
        protected array $parsers,
        public SizeServiceContract $sizeServiceContract,
        public FlavorServiceContract $flavorServiceContract,
        public ToppingServiceContract $toppingServiceContract,
        public DataBaseServiceContract $contract
    ) {
    }

    /**
     * Call all method parse
     */
    public function callParse(): array
    {
        $parsedData = [];
        foreach ($this->parsers as $parser) {
            $parsedData[] = app()->call($parser['parser']. '@parser', ['config' => $parser['config']]);
        }
        return $parsedData;
    }

    /**
     * Store or update parsed data
     */
    public function storeOrUpdateParse(): void
    {
        $data = $this->callParse();
        try {
            foreach ($data as $item) {
                $this->sizeServiceContract->updateOrCreate($item->attributes->size);
                $this->flavorServiceContract->updateOrCreate($item->attributes->flavor);
                $this->toppingServiceContract->updateOrCreate($item->attributes->topping);
                $this->contract->updateOrCreate($item->products);
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
