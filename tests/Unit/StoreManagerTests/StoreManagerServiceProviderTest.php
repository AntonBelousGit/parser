<?php

declare(strict_types=1);

namespace StoreManagerTests;

use App\Services\StoreManager\Contracts\AttributeDriverContract;
use App\Services\StoreManager\Contracts\AttributeValidatorContract;
use App\Services\StoreManager\Contracts\ProductDriverContract;
use App\Services\StoreManager\Contracts\ProductServiceContract;
use App\Services\StoreManager\Drivers\AttributeDriver\AttributeDriver;
use App\Services\StoreManager\Drivers\AttributeDriver\AttributeValidator;
use App\Services\StoreManager\Drivers\ProductDriver\ProductDriver;
use App\Services\StoreManager\StoreService;
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
