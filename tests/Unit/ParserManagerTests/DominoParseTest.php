<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;

class DominoParseTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testParserDominoParse()
    {
        $config = config('parsers.dominoParse');
        $response = $this->parse($config, 'dominoParse', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->sizes[0]);
        $this->assertNotNull($response->attributes->toppings[0]);
        $this->assertNotNull($response->attributes->flavors[0]);
    }

    public function testParserDominoValidationProblemParse()
    {
        $config = config('parsers.dominoParse');
        try {
            $this->parse($config, 'corruptFile/dominoValidationParse', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }

    /**
     * Removed part of the code
     */
    public function testParserDominoCorruptedFileParse()
    {
        $config = config('parsers.dominoParse');
        try {
            $this->parse($config, 'corruptFile/dominoCorruptedParse', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Trying to access array offset on value of type null', $message);
        }
    }
}
