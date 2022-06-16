<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\ToppingService;

use App\Services\ParseDomino\ToppingService\Contracts\ToppingServiceContract;
use App\Services\ParseDomino\ToppingService\Contracts\ToppingValidatorContract;
use App\Services\ParseDomino\ToppingService\ToppingService;
use App\Services\ParseDomino\ToppingService\ToppingValidator;
use Tests\Unit\BaseServiceProviderTest;

class ToppingServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ToppingValidatorContract::class => ToppingValidator::class,
        ToppingServiceContract::class => ToppingService::class
    ];
}
