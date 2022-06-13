<?php

declare(strict_types=1);

namespace Tests\Unit\ProductService;

use App\Services\ProductService\Contracts\ProductServiceContract;
use App\Services\ProductService\Contracts\ProductValidatorContract;
use App\Services\ProductService\ProductService;
use App\Services\ProductService\ProductValidator;
use Tests\Unit\BaseServiceProviderTest;

class ProductServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ProductValidatorContract::class => ProductValidator::class,
        ProductServiceContract::class => ProductService::class
    ];
}
