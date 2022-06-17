<?php

declare(strict_types=1);

namespace Tests\Unit\BaseServices\SizeService;

use App\Services\BaseServices\SizeService\Contracts\SizeServiceContract;
use App\Services\BaseServices\SizeService\Contracts\SizeValidatorContract;
use App\Services\BaseServices\SizeService\SizeService;
use App\Services\BaseServices\SizeService\SizeValidator;
use Tests\Unit\BaseServiceProviderTest;

class SizeServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        SizeValidatorContract::class => SizeValidator::class,
        SizeServiceContract::class => SizeService::class
    ];
}
