<?php

namespace Database\Seeders;

use App\Models\ParseConfig;
use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Drivers\DominoParseDriver;
use App\Services\ParserManager\Drivers\OrigamiPizzaParseDriver;
use App\Services\ParserManager\Drivers\VdhPizzaParseDriver;
use App\Services\ParserManager\Drivers\ZharPizzaParseDriver;
use Illuminate\Database\Seeder;

class ParseConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ParseConfig::create([
            'name' => 'dominoParse',
            'parser' => DominoParseDriver::class,
            'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
            'url' => 'https://dominos.ua/uk/chornomorsk/',
        ]);
        ParseConfig::create([
            'name' => 'zharPizza',
            'parser' => ZharPizzaParseDriver::class,
            'connection' => ConnectToParseService::CONNECTION_TYPES['GUZZLE'],
            'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1656336574041&getparts=true&getoptions=true&slice=1&&size=36',
        ]);
        ParseConfig::create([
            'name' => 'vdhBar',
            'parser' => VdhPizzaParseDriver::class,
            'connection' => ConnectToParseService::CONNECTION_TYPES['GUZZLE'],
            'url' => 'https://store.tildacdn.com/api/getproductslist/?storepartuid=261323000731&recid=264435121&c=1655380264126&getparts=true&getoptions=true',
        ]);
        ParseConfig::create([
            'name' => 'origami',
            'parser' => OrigamiPizzaParseDriver::class,
            'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
            'url' => 'https://origami.od.ua/index.php?route=product/category&path=68',
        ]);
    }
}
