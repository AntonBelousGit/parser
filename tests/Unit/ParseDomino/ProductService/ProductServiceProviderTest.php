<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\ProductService;

use App\Services\ParseDomino\ProductService\Contracts\ProductValidatorContract;
use App\Services\ParseDomino\ProductService\ProductValidator;
use Tests\Unit\BaseServiceProviderTest;

class ProductServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ProductValidatorContract::class => ProductValidator::class,
    ];
}
