<?php

declare(strict_types=1);

namespace Tests\Unit\ToppingService;

use App\Services\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ToppingService\Contracts\ToppingValidatorContract;
use App\Services\ToppingService\ToppingService;
use App\Services\ToppingService\ToppingValidator;
use Tests\Unit\BaseServiceProviderTest;

class ToppingServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ToppingValidatorContract::class => ToppingValidator::class,
        ToppingServiceContract::class => ToppingService::class
    ];
}
