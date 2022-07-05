<?php

declare(strict_types=1);

namespace Tests\Unit\ParseZharPizza\ParserService;

use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceAttributeContract;
use App\Services\ParseZharPizza\ParserService\Contracts\ZharPizzaParseServiceContract;
use App\Services\ParseZharPizza\ParserService\ZharPizzaParseDriver;
use Tests\Unit\BaseServiceProviderTest;

class ParseServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ZharPizzaParseServiceContract::class => ZharPizzaParseDriver::class,
        ZharPizzaParseServiceAttributeContract::class =>  ZharPizzaParseDriver::class
    ];
}
