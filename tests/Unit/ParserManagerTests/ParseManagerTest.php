<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Drivers\DominoParseDriver;
use App\Services\ParserManager\ParseManager;
use DiDom\Document;
use Mockery;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
    public function testGetProductDataFromParsedPage()
    {
        $response = $this->parse();
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response[0]->products);
    }

    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->parse();
        $this->assertNotNull($response[0]->attributes->size);
        $this->assertNotNull($response[0]->attributes->topping);
        $this->assertNotNull($response[0]->attributes->flavor);
    }

    /**
     * @return array
     */
    protected function parse(): array
    {
        $document = new Document(storage_path('app/public/file/dominoParse.xml'), true);
        $mock = Mockery::mock(ConnectToParseService::class)->makePartial();
        $mock->shouldReceive('connect')->andReturns($document);
        app()->instance(ConnectToParseService::class, $mock);

        $parsingManager = app(ParseManager::class);
        return $parsingManager->callParse(
            [
                'dominoParse' => [
                    'enable' => true,
                    'parser' => DominoParseDriver::class,
                    'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
                    'url' => 'https://dominos.ua/uk/chornomorsk/',
                ],
            ]
        );
    }
}
