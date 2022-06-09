<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Size::create([
            'id' => '1ebd3eab-5655-4092-bd7c-f134bccac587',
            'name' =>'Маленька'
         ]);

        Size::create([
            'id' => 'e1e74edf-1431-4a90-8234-5039265d7ae6',
            'name' =>'Середня'
        ]);

        Size::create([
            'id' => 'fb739582-296f-484f-b3a4-2da8f6c7f57c',
            'name' =>'Велика'
        ]);
    }
}
