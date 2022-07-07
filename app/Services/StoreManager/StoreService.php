<?php


namespace App\Services\StoreManager;


use App\Services\ParserManager\Contracts\ParseServiceContract;
use App\Services\StoreManager\Contracts\AttributeServiceContract;
use App\Services\StoreManager\Contracts\ConfigValidatorContract;
use App\Services\StoreManager\Contracts\ProductServiceContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreService implements ProductServiceContract
{
    /**
     * StoreService constructor.
     * @param array $config
     */
    public function __construct(
        protected array $config,
        private ParseServiceContract $parseServiceContract,
        private AttributeServiceContract $attributeServiceContract,
        private ProductServiceContract $productServiceContract,
    ) {
    }

    /**
     * Store or update parsed data
     * @return void
     */
    public function parse(): void
    {
        $data = $this->parseServiceContract->callParse($this->config);
        try {
            foreach ($data as $item) {
                $this->attributeServiceContract->updateOrCreate($item->attributes, $item->config['attribute']);
                $this->productServiceContract->updateOrCreate($item->products);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update parsed data - problem', ['data'=> $exception]);
        }
    }
}
