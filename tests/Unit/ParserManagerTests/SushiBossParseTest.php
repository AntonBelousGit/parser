<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;

class SushiBossParseTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testParserDominoParse()
    {
        $config = config('parsers.sushiboss');
        $response = $this->parse($config, 'sushiboss', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->sizes[0]);
        $this->assertNotNull($response->attributes->toppings[0]);
        $this->assertNotNull($response->attributes->flavors[0]);
    }

    public function testParserDominoValidationProblemParse()
    {
        $config = config('parsers.sushiboss');
        try {
            $this->parse($config, 'corruptFile/sushibossValidation', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }
}
