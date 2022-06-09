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
        Flavor::create([
            'id' => '8cce4b72-3386-415c-b983-70711ea235e7',
            'name' => 'Стандарт',
            'code' => 'USA'
        ]);

        Flavor::create([
            'id' => '154f73b6-5b9d-4168-ab13-85e8003fda56',
            'name' => 'Тонке',
            'code' => 'ITAL'
        ]);

        Flavor::create([
            'id' => '924937c7-1637-4162-aac0-8284f71173a7',
            'name' => 'Борт Філадельфія',
            'code' => 'CHEESE'
        ]);

        Flavor::create([
            'id' => '6a78ca88-800b-4c32-9057-76e56252f8b4',
            'name' => 'Борт Хот-Дог',
            'code' => 'HOTDOG'
        ]);
    }
}
