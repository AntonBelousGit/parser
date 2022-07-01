<?php

declare(strict_types=1);

namespace Tests\Unit\BaseServices\DataBaseService;

use App\Services\BaseServices\DataBaseService\Contracts\DataBaseServiceContract;
use App\Services\BaseServices\DataBaseService\DataBaseService;
use Tests\Unit\BaseServiceProviderTest;

class DataServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        DataBaseServiceContract::class => DataBaseService::class,
    ];
}
