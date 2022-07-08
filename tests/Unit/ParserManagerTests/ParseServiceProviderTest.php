<?php

declare(strict_types=1);

namespace Tests\Unit\ParserManagerTests;

use App\Services\ParserManager\ConfigValidator;
use App\Services\ParserManager\Contracts\ConfigValidatorContract;
use App\Services\ParserManager\Contracts\ParseServiceContract;
use App\Services\ParserManager\ParseService;
use Tests\Unit\BaseServiceProviderTest;

class ParseServiceProviderTest extends BaseServiceProviderTest
{
    protected array $services = [
        ConfigValidatorContract::class =>  ConfigValidator::class,
        ParseServiceContract::class =>  ParseService::class,
    ];
}
