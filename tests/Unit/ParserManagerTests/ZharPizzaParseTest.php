<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;

class ZharPizzaParseTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testZharPizzaParse()
    {
        $config = config('parsers.zharPizza');
        $response = $this->parse($config, 'zharPizza');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->sizes[0]);
        $this->assertNotNull($response->attributes->toppings[0]);
    }

    public function testZharPizzaWrongTypeParse()
    {
        $config = config('parsers.zharPizza');
        try {
            $this->parse($config, 'zharPizza', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Attempt to read property "products" on null', $message);
        }
    }

    public function testZharPizzaValidationProblemParse()
    {
        $config = config('parsers.zharPizza');
        try {
            $this->parse($config, 'corruptFile/zharPizzaValidation');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }

    /**
     * Removed part of the code
     */
    public function testParserZharPizzaCorruptedFileParse()
    {
        $config = config('parsers.zharPizza');
        try {
            $this->parse($config, 'corruptFile/zharPizzaCorrupted');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Attempt to read property "products" on null', $message);
        }
    }
}
