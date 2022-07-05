<?php

declare(strict_types=1);

namespace Tests\Unit\ParseZharPizza\ParserService;

use App\Services\ParseZharPizza\ParserService\ZharPizzaParseDriver;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

class ParseServiceTest extends TestCase
{

    /**
     * @throws GuzzleException
     */
    public function testConnectionToParsedPage()
    {
        $this->getDominiParse()->callConnectToParse(config('parsers.zharPizza.config.address'));
        $this->assertTrue(true);
    }

    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseProduct(config('parsers.zharPizza.config.address'));
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]->id);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseAttribute($this->getDominiParse()->parseProduct(config('parsers.zharPizza.config.address')));
        if (count($response->size) > 0 && count($response->topping) > 0) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * @return ZharPizzaParseDriver
     */
    protected function getDominiParse(): ZharPizzaParseDriver
    {
        return $this->app->make(ZharPizzaParseDriver::class);
    }
}
