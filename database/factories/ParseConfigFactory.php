<?php

namespace Database\Factories;

use App\Models\ParseConfig;
use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Drivers\DominoParseDriver;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParseConfigFactory extends Factory
{
    protected $model = ParseConfig::class;

    public function definition(): array
    {
        return [
            'name' => 'dominoParse',
            'parser' => DominoParseDriver::class,
            'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
            'url' => 'https://dominos.ua/uk/chornomorsk/',
            'enable' => 1,
        ];
    }
}
