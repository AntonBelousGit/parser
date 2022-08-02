<?php

declare(strict_types=1);

namespace ParserManagerTests;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ParseTestCase;
use Throwable;

class BeerlinParseTest extends ParseTestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testBeerlinParse()
    {
        $config = config('parsers.beerlin');
        $response = $this->parseTwo($config, 'beerlin', 'beerlinSingle', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->sizes[0]);
        $this->assertNotNull($response->attributes->toppings[0]);
    }

    public function testBeerlinParseValidationProblemParse()
    {
        $config = config('parsers.beerlin');
        try {
            $this->parseTwo($config, 'beerlin', 'corruptFile/beerlinSingleValidation', 'DiDom');
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Parse data is invalid', $message);
        }
    }

    public function testBeerlinParseCorrupted()
    {
        $config = config('parsers.beerlin');
        try {
            $this->parseTwo($config, 'beerlin', 'corruptFile/beerlinSingleCorruptedParse', 'DiDom');
        } catch (Throwable $exception) {
            $message = $exception->getMessage();
            $this->assertStringContainsString('Call to a member function text() on null', $message);
        }
    }
}
