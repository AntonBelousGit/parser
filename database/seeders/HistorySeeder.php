<?php

namespace Database\Seeders;

use App\Models\History;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        History::create([
            'historical_type' => 'App\Models\Attribute',
            'historical_id' => 1,
            'changed_column' => 'price',
            'changed_value_from' => 100,
            'changed_value_to' => 150
        ]);

        History::create([
            'historical_type' => 'App\Models\Attribute',
            'historical_id' => 1,
            'changed_column' => 'price',
            'changed_value_from' => 150,
            'changed_value_to' => 175
        ]);

        History::create([
            'historical_type' => 'App\Models\Attribute',
            'historical_id' => 1,
            'changed_column' => 'price',
            'changed_value_from' => 175,
            'changed_value_to' => 200
        ]);
    }
}
