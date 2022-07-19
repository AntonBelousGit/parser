<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\ParseManager;
use DiDom\Document;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
    public function testParserDriver()
    {
        $file = public_path('file/dominoParse.xml');
        $mock = $this->app->make(ConnectToParseService::class);
        $finallyParsedFile = $mock->callConnectToParseDiDom($file);

        dd($finallyParsedFile);
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
     * @return ParseManager
     */
    protected function getParse(): ParseManager
    {
        return $this->app->make(ParseManager::class);
    }
}
