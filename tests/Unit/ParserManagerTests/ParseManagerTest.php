<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ParserManager\ParseService;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
    public function testConnectionToParsedPage()
    {
        $this->getParse();
        $this->assertTrue(true);
    }

    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getParse()->callParse(config('parsers'));
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]->products);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getParse()->callParse(config('parsers'));
        if (count($response[0]->attributes->size) > 0 && count($response[0]->attributes->topping) > 0 && count($response[0]->attributes->flavor)) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * @return ParseService
     */
    protected function getParse(): ParseService
    {
        return $this->app->make(ParseService::class);
    }
}
