<?php

declare(strict_types=1);

namespace Tests\Unit\ParserService;

use App\Services\ParserService\DominoParseService;
use PHPUnit\Framework\TestCase;

class ParseServiceTest extends TestCase
{
    public function testConnectionToParsedPage()
    {
        $this->getDominiParse()->callConnectToParse();
        $this->assertTrue(true);
    }

    public function testGetProductDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseProduct();
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]['id']);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->getDominiParse()->parseAttribute($this->getDominiParse()->parseProduct());
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
        return new DominoParseService();
    }
}
