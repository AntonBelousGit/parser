<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\ParserService;

use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParseDomino\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParseDomino\ParserService\DominoParseDriver;
use Tests\Unit\BaseServiceProviderTest;

class ParseServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        DominoParseServiceContract::class => DominoParseDriver::class,
        DominoParseServiceAttributeContract::class =>  DominoParseDriver::class
    ];
}
