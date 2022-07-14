<?php

namespace Database\Factories;

use App\Models\Flavor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FlavorFactory extends Factory
{
    protected $model = Flavor::class;

    public function definition(): array
    {
        return [
            'id' => uniqid(),
            'name' => $this->faker->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
