<?php
declare(strict_types=1);

namespace App\Services\StoreManager;

use App\Services\ParserManager\Contracts\ParseServiceContract;
use App\Services\StoreManager\Contracts\AttributeDriverContract;
use App\Services\StoreManager\Contracts\ProductDriverContract;
use App\Services\StoreManager\Contracts\ProductServiceContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreService implements ProductServiceContract
{
    /**
     * StoreService constructor.
     * @param array $config
     * @param ParseServiceContract $parseServiceContract
     * @param AttributeDriverContract $attributeDriverContract
     * @param ProductDriverContract $productDriverContract
     */
    public function __construct(
        protected array $config,
        private ParseServiceContract $parseServiceContract,
        private AttributeDriverContract $attributeDriverContract,
        private ProductDriverContract $productDriverContract,
    ) {
    }

    /**
     * Store or update parsed data
     * @return void
     */
    public function parse(): void
    {
        try {
            $data = $this->parseServiceContract->callParse($this->config);
            foreach ($data as $item) {
                $this->attributeDriverContract->updateOrCreate($item->attributes);
                $this->productDriverContract->updateOrCreate($item->products);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update parsed data - problem', ['data'=> $exception]);
        }
    }
}
