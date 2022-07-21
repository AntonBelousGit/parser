<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Models\ParseConfig;
use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Drivers\DominoParseDriver;
use App\Services\ParserManager\ParseManager;
use DiDom\Document;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
    use RefreshDatabase;

    protected $config;

    public function setUp(): void
    {
        parent::setUp();
        $this->config =  ParseConfig::factory()->create();
    }

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
        return $parsingManager->callParse(Collection::make([$this->config]));
    }
}
