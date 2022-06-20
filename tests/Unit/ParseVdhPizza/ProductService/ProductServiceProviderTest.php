<?php

declare(strict_types=1);

namespace Tests\Unit\ParseVdhPizza\ProductService;

use App\Services\ParseVdhPizza\ProductService\Contracts\ProductServiceContract;
use App\Services\ParseVdhPizza\ProductService\Contracts\ProductValidatorContract;
use App\Services\ParseVdhPizza\ProductService\ProductService;
use App\Services\ParseVdhPizza\ProductService\ProductValidator;
use Tests\Unit\BaseServiceProviderTest;

class ProductServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ProductValidatorContract::class => ProductValidator::class,
        ProductServiceContract::class => ProductService::class
    ];
}
