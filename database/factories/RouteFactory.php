<?php

namespace Database\Factories;

use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'realtime_id' => $this->faker->randomNumber,
            'short_name' => $this->faker->randomNumber,
            'long_name' => $this->faker->streetName,
            'type' => Route::TYPE_BUS,
        ];
    }
}
