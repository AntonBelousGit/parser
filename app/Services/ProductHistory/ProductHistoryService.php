<?php

declare(strict_types=1);

namespace App\Services\ProductHistory;

use App\Http\Resources\ProductHistory\ProductHistoryResources;
use App\Repositories\ProductRepositories;
use App\Services\ProductHistory\Contracts\ProductHistoryContract;
use Illuminate\Http\JsonResponse;

class ProductHistoryService implements ProductHistoryContract
{
    /**
     * ProductHistoryService constructor.
     * @param ProductRepositories $productRepositories
     */
    public function __construct(
        protected ProductRepositories $productRepositories,
    ) {
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getProductHistory(string $id): JsonResponse
    {
        $product = $this->productRepositories->getProductHistory($id);
        return response()->json([
            'id' => $product->id ?? '',
            'name' => $product->name ?? '',
            'image' => $product->image ?? '',
            'image_mobile' => $product->image_mobile ?? '',
            'variants' => ProductHistoryResources::collection($product->attributeProduct ?? ''),
        ]);
    }
}
