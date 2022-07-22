<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;

class ParseOrigamiTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testOrigamiParse()
    {
        $config = config('parsers.origami');
        $response = $this->parse($config, 'origami', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
    }

    public function testOrigamiValidationProblemParse()
    {
        $config = config('parsers.origami');
        try {
            $this->parse($config, 'corruptFile/origamiValidation', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }

    /**
     * Change tag h3 to h2
     */
    public function testOrigamiCorruptedFileParse()
    {
        $config = config('parsers.origami');
        try {
            $this->parse($config, 'corruptFile/origamiCorrupted', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Undefined array key 0', $message);
        }
    }
}
