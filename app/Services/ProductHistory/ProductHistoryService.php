<?php

declare(strict_types=1);

namespace App\Services\ProductHistory;

use App\Http\Resources\Product\ProductHistoryResources;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\ProductRepositories;
use App\Services\ProductHistory\Contracts\ProductHistoryContract;
use Illuminate\Http\JsonResponse;

class ProductHistoryService implements ProductHistoryContract
{
    /**
     * ProductHistoryService constructor.
     *
     * @param ProductRepositories $productRepositories
     */
    public function __construct(
        protected ProductRepositories $productRepositories,
    ) {
    }

    /**
     * Get history of product
     *
     * @param string $id
     * @return ProductResource
     */
    public function getProductHistory(string $id): ProductResource
    {
        $product = $this->productRepositories->getProductHistory($id);

        return new ProductResource($product);
    }
}
