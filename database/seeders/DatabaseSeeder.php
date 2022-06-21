<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SizeSeeder::class);
        $this->call(FlavorSeeder::class);
        $this->call(ToppingSeeder::class);
        $this->call(HistorySeeder::class);
    }
}
