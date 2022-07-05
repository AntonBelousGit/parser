<?php

declare(strict_types=1);

namespace Tests\Unit\ParseVdhPizza\ParserService;

use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceAttributeContract;
use App\Services\ParseVdhPizza\ParserService\Contracts\VdhPizzaParseServiceContract;
use App\Services\ParseVdhPizza\ParserService\VdhPizzaParseDriver;
use Tests\Unit\BaseServiceProviderTest;

class ParseServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        VdhPizzaParseServiceContract::class => VdhPizzaParseDriver::class,
        VdhPizzaParseServiceAttributeContract::class =>  VdhPizzaParseDriver::class
    ];
}
