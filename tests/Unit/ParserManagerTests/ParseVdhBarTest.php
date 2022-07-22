<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;

class ParseVdhBarTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testVdhBarParse()
    {
        $config = config('parsers.vdhBar');
        $response = $this->parse($config, 'vdhBar');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
    }

    public function testVdhBarWrongTypeParse()
    {
        $config = config('parsers.vdhBar');
        try {
            $this->parse($config, 'vdhBar', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Attempt to read property "products" on null', $message);
        }
    }

    public function testVdhBarValidationProblemParse()
    {
        $config = config('parsers.vdhBar');
        try {
            $this->parse($config, 'corruptFile/vdhBarValidation');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }

    /**
     * Removed part of the code
     */
    public function testVdhBarCorruptedFileParse()
    {
        $config = config('parsers.vdhBar');
        try {
            $this->parse($config, 'corruptFile/vdhBarCorrupted');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Attempt to read property "products" on null', $message);
        }
    }
}
