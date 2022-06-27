<?php

declare(strict_types=1);

namespace Tests\Unit\ParseVdhPizza\ParserService;

use App\Services\ParseVdhPizza\ParserService\VdhPizzaParseService;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

class ParseServiceTest extends TestCase
{

    /**
     * @throws GuzzleException
     */
    public function testConnectionToParsedPage()
    {
        $this->getDominiParse()->callConnectToParse(config('parsers.vdhBar.config.address'));
        $this->assertTrue(true);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseProduct(config('parsers.vdhBar.config.address'));
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]->id);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseAttribute($this->getDominiParse()->parseProduct(config('parsers.vdhBar.config.address')));
        if (count($response->size) > 0 && count($response->topping) > 0) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * @return VdhPizzaParseService
     */
    protected function getDominiParse(): VdhPizzaParseService
    {
        return $this->app->make(VdhPizzaParseService::class);
    }
}
