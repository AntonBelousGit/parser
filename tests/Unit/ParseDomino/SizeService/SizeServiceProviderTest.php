<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\SizeService;

use App\Services\ParseDomino\SizeService\Contracts\SizeServiceContract;
use App\Services\ParseDomino\SizeService\Contracts\SizeValidatorContract;
use App\Services\ParseDomino\SizeService\SizeService;
use App\Services\ParseDomino\SizeService\SizeValidator;
use Tests\Unit\BaseServiceProviderTest;

class SizeServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        SizeValidatorContract::class => SizeValidator::class,
        SizeServiceContract::class => SizeService::class
    ];
}
