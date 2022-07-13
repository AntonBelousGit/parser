<?php

declare(strict_types=1);

namespace StoreManagerTests;

use App\Services\StoreService\Contracts\AttributeValidatorContract;
use App\Services\StoreService\Contracts\StoreServiceContract;
use App\Services\StoreService\StoreService;
use App\Services\StoreService\Validator\AttributeValidator;
use Tests\Unit\BaseServiceProviderTest;

class StoreManagerServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        StoreServiceContract::class => StoreService::class,
        AttributeValidatorContract::class =>  AttributeValidator::class,
    ];
}
