<?php
declare(strict_types=1);


use App\Services\ParseDomino\CallParse\CallParseDomino;
use App\Services\ParseVdhPizza\CallParse\CallParseVdhPizza;
use App\Services\ParseZharPizza\CallParse\CallParseZharPizza;

return [
    'dominoParse' => [
        'parser' => CallParseDomino::class. '@parser',
        'config' => [
            'address' => 'https://dominos.ua/uk/chornomorsk/',
            'attribute' => ['sizes', 'flavors', 'toppings']
        ],
    ],
//    'zharPizza' => [
//        'parser' => CallParseZharPizza::class ,
//        'config' => [
//            'address' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=608315548424&recid=155887892&c=1655719066017&getparts=true&getoptions=true&slice=1&&size=36',
//
//        ],
//    ],
//    'vdhBar' => [
//        'parser' => CallParseVdhPizza::class . '@__invoke',
//        'config' => [
//            'address' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
//        ],
//    ],
];
