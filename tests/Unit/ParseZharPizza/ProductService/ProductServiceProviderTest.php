<?php

declare(strict_types=1);

namespace Tests\Unit\ParseZharPizza\ProductService;

use App\Services\ParseZharPizza\ProductService\Contracts\ProductValidatorContract;
use App\Services\ParseZharPizza\ProductService\ProductValidator;
use Tests\Unit\BaseServiceProviderTest;

class ProductServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ProductValidatorContract::class => ProductValidator::class,
    ];
}
