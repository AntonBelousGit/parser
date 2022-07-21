<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\ParseManager;
use DiDom\Document;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ParseManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws BindingResolutionException
     */
    public function testGetProductDataFromParsedPage()
    {
        $response = $this->parse();
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testGetAttributeDataFromParsedPage()
    {
        $response = $this->parse();
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
        $this->assertNotNull($response->attributes->flavor[0]);
    }

    /**
     * @return ParserProductDataDTO
     * @throws BindingResolutionException
     */
    protected function parse(): ParserProductDataDTO
    {
        $config = config('parsers.dominoParse');
        $document = new Document(storage_path('app/public/file/dominoParse.xml'), true);
        $mock = Mockery::mock(ConnectToParseService::class)->makePartial();
        $mock->shouldReceive('connect')->andReturns($document);
        app()->instance(ConnectToParseService::class, $mock);

        $parsingManager = app(ParseManager::class);
        return $parsingManager->callParse($config);
    }
}
