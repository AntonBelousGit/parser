<?php
declare(strict_types=1);

use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Drivers\DominoParseDriver;
use App\Services\ParserManager\Drivers\OrigamiPizzaParseDriver;
use App\Services\ParserManager\Drivers\VdhPizzaParseDriver;
use App\Services\ParserManager\Drivers\ZharPizzaParseDriver;

/**
 * Config with parsed information
 */

return [
    'dominoParse' => [
        'enable'=> true,
        'parser' => DominoParseDriver::class,
        'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
        'url' => 'https://dominos.ua/uk/chornomorsk/',
    ],
    'zharPizza' => [
        'enable'=> true,
        'parser' => ZharPizzaParseDriver::class,
        'connection' => ConnectToParseService::CONNECTION_TYPES['GUZZLE'],
        'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1656336574041&getparts=true&getoptions=true&slice=1&&size=36',
    ],
    'vdhBar' => [
        'enable'=> true,
        'parser' => VdhPizzaParseDriver::class,
        'connection' => ConnectToParseService::CONNECTION_TYPES['GUZZLE'],
        'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
    ],
    'origami' => [
        'enable'=> true,
        'parser' => OrigamiPizzaParseDriver::class,
        'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
        'url' => 'https://origami.od.ua/index.php?route=product/category&path=68',
    ],
];

