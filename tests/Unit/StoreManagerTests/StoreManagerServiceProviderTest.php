<?php

declare(strict_types=1);

namespace StoreManagerTests;

use App\Services\StoreService\Contracts\AttributeDriverContract;
use App\Services\StoreService\Contracts\AttributeValidatorContract;
use App\Services\StoreService\Contracts\ProductDriverContract;
use App\Services\StoreService\Contracts\ProductServiceContract;
use App\Services\StoreService\Drivers\AttributeDriver\AttributeDriver;
use App\Services\StoreService\Drivers\AttributeDriver\AttributeValidator;
use App\Services\StoreService\Drivers\ProductDriver\ProductDriver;
use App\Services\StoreService\StoreService;
use Tests\Unit\BaseServiceProviderTest;

class StoreManagerServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        AttributeDriverContract::class => AttributeDriver::class,
        AttributeValidatorContract::class =>  AttributeValidator::class,
        ProductDriverContract::class =>  ProductDriver::class,
        ProductServiceContract::class =>  StoreService::class,
    ];
}
