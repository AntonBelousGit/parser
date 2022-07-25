<?php

declare(strict_types=1);

namespace Tests;

use App\Services\ConnectService\ConnectService;
use App\Services\ParserManager\DTOs\ParserProductDataDTO;
use App\Services\ParserManager\ParseManager;
use DiDom\Document;
use File;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

abstract class ParseTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Mock parser driver and return parsed data
     *
     * @param array $config
     * @param string $parseName
     * @param string $type
     * @return ParserProductDataDTO
     * @throws BindingResolutionException
     */
    protected function parse(array $config, string $parseName, string $type = ''): ParserProductDataDTO
    {
        $filePath = storage_path("app/public/file/{$parseName}.xml");
        $document = $type === 'DiDom' ? new Document($filePath, true) : File::get($filePath);
        $mock = Mockery::mock(ConnectService::class)->makePartial();
        $mock->shouldReceive('connect')->andReturns($document);
        app()->instance(ConnectService::class, $mock);
        $parsingManager = app(ParseManager::class);

        return $parsingManager->callParse($config['parser'], $config['url']);
    }
}
