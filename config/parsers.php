<?php
declare(strict_types=1);

use App\Services\ParserManager\Drivers\DominoParseDriver;
use App\Services\ParserManager\Drivers\OrigamiPizzaParseDriver;
use App\Services\ParserManager\Drivers\VdhPizzaParseDriver;
use App\Services\ParserManager\Drivers\ZharPizzaParseDriver;

/**
 * Config with parsed information
 */

return [
    'dominoParse' => [
        'parser' => DominoParseDriver::class,
        'method' => 'callConnectToParseDiDom',
        'url' => 'https://dominos.ua/uk/chornomorsk/',
        'status'=> true,
    ],
    'zharPizza' => [
        'parser' => ZharPizzaParseDriver::class,
        'method' => 'callConnectToParseGuzzle',
        'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1656336574041&getparts=true&getoptions=true&slice=1&&size=36',
        'status'=> true,
    ],
    'vdhBar' => [
        'parser' => VdhPizzaParseDriver::class,
        'method' => 'callConnectToParseGuzzle',
        'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
        'status'=> true,
    ],
    'origami' => [
        'parser' => OrigamiPizzaParseDriver::class,
        'method' => 'callConnectToParseDiDom',
        'url' => 'https://origami.od.ua/index.php?route=product/category&path=68',
        'status'=> true,
    ],
];
