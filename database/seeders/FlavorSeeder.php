<?php

namespace Database\Seeders;

use App\Models\Flavor;
use Illuminate\Database\Seeder;

class FlavorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Flavor::factory()->create();
    }
}
