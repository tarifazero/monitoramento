<?php

namespace Database\Factories\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\RouteCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteCountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RouteCount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time' => today()->startOfDay(),
            'count' => $this->faker->numberBetween(0, 300),
        ];
    }
}
