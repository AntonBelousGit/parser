<?php

declare(strict_types=1);

namespace Tests\Unit\BaseServices\ToppingService;

use App\Services\BaseServices\ToppingService\Contracts\ToppingServiceContract;
use App\Services\BaseServices\ToppingService\Contracts\ToppingValidatorContract;
use App\Services\BaseServices\ToppingService\ToppingService;
use App\Services\BaseServices\ToppingService\ToppingValidator;
use Tests\Unit\BaseServiceProviderTest;

class ToppingServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ToppingValidatorContract::class => ToppingValidator::class,
        ToppingServiceContract::class => ToppingService::class
    ];
}
