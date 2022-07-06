<?php

declare(strict_types=1);

namespace App\Services\ProductHistory\Contracts;

use App\Http\Resources\Product\ProductResource;

interface ProductHistoryContract
{
    /**
     * Get product history price change.
     *
     * @param string $id
     * @return ProductResource
     */
    public function getProductHistory(string $id): ProductResource;
}
