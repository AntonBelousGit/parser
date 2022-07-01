<?php

declare(strict_types=1);

namespace Tests\Unit\BaseServices\FlavorService;

use App\Services\BaseServices\FlavorService\Contracts\FlavorServiceContract;
use App\Services\BaseServices\FlavorService\Contracts\FlavorValidatorContract;
use App\Services\BaseServices\FlavorService\FlavorService;
use App\Services\BaseServices\FlavorService\FlavorValidator;
use Tests\Unit\BaseServiceProviderTest;

class FlavorServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        FlavorServiceContract::class => FlavorService::class,
        FlavorValidatorContract::class => FlavorValidator::class
    ];
}
