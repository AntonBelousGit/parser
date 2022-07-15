<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ParserManager\ParseManager;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
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
     * @return ParseManager
     */
    protected function getParse(): ParseManager
    {
        return $this->app->make(ParseManager::class);
    }
}
