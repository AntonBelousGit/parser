<?php
declare(strict_types=1);

use App\Services\ParserManager\Drivers\DominoParseDriver;
use App\Services\ParserManager\Drivers\VdhPizzaParseDriver;
use App\Services\ParserManager\Drivers\ZharPizzaParseDriver;

return [
    'dominoParse' => [
        'parser' => DominoParseDriver::class,
        'config' => [
            'address' => 'https://dominos.ua/uk/chornomorsk/',
            'attribute' => [
                'size' => \App\Models\Size::class,
                'flavor' => \App\Models\Flavor::class,
                'topping' => \App\Models\Topping::class,
            ]
        ],
    ],
    'zharPizza' => [
        'parser' => ZharPizzaParseDriver::class,
        'config' => [
            'address' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1656336574041&getparts=true&getoptions=true&slice=1&&size=36',
            'attribute' => [
                'size' => \App\Models\Size::class,
                'topping' => \App\Models\Topping::class,
            ]
        ],
    ],
    'vdhBar' => [
        'parser' => VdhPizzaParseDriver::class,
        'config' => [
            'address' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
            'attribute' => [
                'size' => \App\Models\Size::class,
                'topping' => \App\Models\Topping::class,
            ]
        ],
    ],
];
