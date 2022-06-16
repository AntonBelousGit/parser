<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\FlavorService;

use App\Services\ParseDomino\FlavorService\Contracts\FlavorServiceContract;
use App\Services\ParseDomino\FlavorService\Contracts\FlavorValidatorContract;
use App\Services\ParseDomino\FlavorService\FlavorService;
use App\Services\ParseDomino\FlavorService\FlavorValidator;
use Tests\Unit\BaseServiceProviderTest;

class FlavorServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        FlavorServiceContract::class => FlavorService::class,
        FlavorValidatorContract::class => FlavorValidator::class
    ];
}
