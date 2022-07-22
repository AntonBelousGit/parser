<?php

declare(strict_types=1);

namespace ParserManagerTests;

use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\ParseManager;
use DiDom\Document;
use File;
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
    public function testParserDominoParse()
    {
        $response = $this->parse('dominoParse', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
        $this->assertNotNull($response->attributes->flavor[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testZharPizzaParse()
    {
        $response = $this->parse('zharPizza');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testVdhBarParse()
    {
        $response = $this->parse('vdhBar');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testOrigamiParse()
    {
        $response = $this->parse('origami', 'DiDom');
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->products[0]);
        $this->assertNotNull($response->attributes->size[0]);
        $this->assertNotNull($response->attributes->topping[0]);
    }

    /**
     * @param string $parseName
     * @param string $type
     * @return ParserProductDataDTO
     * @throws BindingResolutionException
     */
    protected function parse(string $parseName, string $type = ''): ParserProductDataDTO
    {
        $config = config("parsers.{$parseName}");
        if ($type === 'DiDom') {
            $document = new Document(storage_path("app/public/file/{$parseName}.xml"), true);
        } else {
            $document = File::get(storage_path("app/public/file/{$parseName}.xml"));
        }
        $mock = Mockery::mock(ConnectToParseService::class)->makePartial();
        $mock->shouldReceive('connect')->andReturns($document);
        app()->instance(ConnectToParseService::class, $mock);

        $parsingManager = app(ParseManager::class);
        return $parsingManager->callParse($config['parser'], $config['url']);
    }
}
