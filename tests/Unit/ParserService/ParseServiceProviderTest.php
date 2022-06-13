<?php

declare(strict_types=1);

namespace Tests\Unit\ParserService;

use App\Services\ParserService\Contracts\DominoParseServiceAttributeContract;
use App\Services\ParserService\Contracts\DominoParseServiceContract;
use App\Services\ParserService\DominoParseService;
use Tests\Unit\BaseServiceProviderTest;

class ParseServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        DominoParseServiceContract::class => DominoParseService::class,
        DominoParseServiceAttributeContract::class =>  DominoParseService::class
    ];
}
