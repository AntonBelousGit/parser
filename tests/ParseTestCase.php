<?php

declare(strict_types=1);

namespace Tests;

use App\Services\ConnectionService\ConnectionService;
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
        $mock = Mockery::mock(ConnectionService::class)->makePartial();
        $mock->shouldReceive('getHtml')->andReturns($document);
        app()->instance(ConnectionService::class, $mock);
        $parsingManager = app(ParseManager::class);

        return $parsingManager->parse($config['driver'], $config['url']);
    }

    /**
     * Mock parser driver and return parsed data
     *
     * @param array $config
     * @param string $parseNameFirst
     * @param string $parseNameSecond
     * @param string $type
     * @return ParserProductDataDTO
     * @throws BindingResolutionException
     */
    protected function parseTwo(array $config, string $parseNameFirst, string $parseNameSecond, string $type = ''): ParserProductDataDTO
    {
        $filePathFirst = storage_path("app/public/file/{$parseNameFirst}.xml");
        $filePathSecond = storage_path("app/public/file/{$parseNameSecond}.xml");
        $documentFirst = $type === 'DiDom' ? new Document($filePathFirst, true) : File::get($filePathFirst);
        $documentSecond = $type === 'DiDom' ? new Document($filePathSecond, true) : File::get($filePathSecond);
        $mock = Mockery::mock(ConnectionService::class)->makePartial();
        $mock->shouldReceive('getHtml')->andReturns($documentFirst, $documentSecond);
        app()->instance(ConnectionService::class, $mock);
        $parsingManager = app(ParseManager::class);

        return $parsingManager->parse($config['driver'], $config['url']);
    }
}
