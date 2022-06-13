<?php

declare(strict_types=1);

namespace Tests\Unit\FlavorService;

use App\Services\FlavorService\Contracts\FlavorServiceContract;
use App\Services\FlavorService\Contracts\FlavorValidatorContract;
use App\Services\FlavorService\FlavorService;
use App\Services\FlavorService\FlavorValidator;
use Tests\Unit\BaseServiceProviderTest;

class FlavorServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        FlavorServiceContract::class => FlavorService::class,
        FlavorValidatorContract::class => FlavorValidator::class
    ];
}
