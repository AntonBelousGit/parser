<?php

namespace Database\Factories;

use App\Models\Flavor;
use App\Services\ConnectToParseService\ConnectToParseService;
use App\Services\ParserManager\Drivers\DominoParseDriver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ParseConfigFactory extends Factory
{
    protected $model = Flavor::class;

    public function definition(): array
    {
        return [
            'name' => 'dominoParse',
            'parser' => DominoParseDriver::class,
            'connection' => ConnectToParseService::CONNECTION_TYPES['DIDOM'],
            'url' => 'https://dominos.ua/uk/chornomorsk/',
        ];
    }
}
