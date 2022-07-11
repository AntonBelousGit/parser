<?php
declare(strict_types=1);

namespace App\Services\StoreManager;

use App\Services\StoreManager\Contracts\AttributeDriverContract;
use App\Services\StoreManager\Contracts\ProductDriverContract;
use App\Services\StoreManager\Contracts\StoreServiceContract;
use Illuminate\Support\Facades\Log;
use Throwable;

class StoreService implements StoreServiceContract
{
    /**
     * StoreService constructor.
     * @param AttributeDriverContract $attributeDriverContract
     * @param ProductDriverContract $productDriverContract
     */
    public function __construct(
        private AttributeDriverContract $attributeDriverContract,
        private ProductDriverContract $productDriverContract,
    ) {
    }

    /**
     * Store or update parsed data
     * @param $data
     * @return void
     */
    public function store($data): void
    {
        try {
            foreach ($data as $item) {
                $this->attributeDriverContract->updateOrCreate($item->attributes);
                $this->productDriverContract->updateOrCreate($item->products);
            }
        } catch (Throwable $exception) {
            Log::info('Store or update parsed data - problem', ['data'=> $exception]);
        }
    }
}
