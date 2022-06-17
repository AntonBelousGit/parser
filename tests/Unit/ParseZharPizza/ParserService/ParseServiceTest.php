<?php

declare(strict_types=1);

namespace Tests\Unit\ParseZharPizza\ParserService;

use App\Services\ParseZharPizza\ParserService\ZharPizzaParseService;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

class ParseServiceTest extends TestCase
{

    /**
     * @throws GuzzleException
     */
    public function testConnectionToParsedPage()
    {
        $this->getDominiParse()->callConnectToParse();
        $this->assertTrue(true);
    }

    /**
     * @throws GuzzleException
     */
    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseProduct();
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]->id);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseAttribute($this->getDominiParse()->parseProduct());
        if (count($response->size) > 0 && count($response->topping) > 0) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    /**
     * @return ZharPizzaParseService
     */
    protected function getDominiParse(): ZharPizzaParseService
    {
        return $this->app->make(ZharPizzaParseService::class);
    }
}
