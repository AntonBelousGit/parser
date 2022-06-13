<?php

declare(strict_types=1);

namespace Tests\Unit\SizeService;

use App\Services\SizeService\Contracts\SizeServiceContract;
use App\Services\SizeService\Contracts\SizeValidatorContract;
use App\Services\SizeService\SizeService;
use App\Services\SizeService\SizeValidator;
use Tests\Unit\BaseServiceProviderTest;

class SizeServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        SizeValidatorContract::class => SizeValidator::class,
        SizeServiceContract::class => SizeService::class
    ];
}
