<?php

declare(strict_types=1);

namespace Tests\Unit\ParseDomino\ParserService;

use App\Services\ParseDomino\ParserService\DominoParseService;
use Tests\TestCase;

class ParseServiceTest extends TestCase
{
    public function testConnectionToParsedPage()
    {
        $this->getDominiParse()->callConnectToParse(config('parsers.dominoParse.config.address'));
        $this->assertTrue(true);
    }

    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseProduct(config('parsers.dominoParse.config.address'));
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]['id']);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseAttribute($this->getDominiParse()->parseProduct(config('parsers.dominoParse.config.address')), config('parsers.dominoParse.config.attribute'));
        if (count($response->size) > 0 && count($response->topping) > 0 && count($response->productRelation)) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }


    /**
     * @return DominoParseService
     */
    protected function getDominiParse(): DominoParseService
    {
        return $this->app->make(DominoParseService::class);
    }
}
