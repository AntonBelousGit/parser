<?php

declare(strict_types=1);

namespace App\Services\ProductHistory\Contracts;


use Illuminate\Http\JsonResponse;

interface ProductHistoryContract
{
    /**
     * Get product history price change.
     *
     * @param string $id
     */
    public function getProductHistory(string $id): JsonResponse;
}
