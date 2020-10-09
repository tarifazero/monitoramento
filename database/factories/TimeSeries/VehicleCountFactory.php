<?php

namespace Database\Factories\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\VehicleCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleCountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleCount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time' => today()->hour($this->faker->numberBetween(0, 23)),
            'route_id' => Route::factory(),
            'count' => $this->faker->numberBetween(0, 30),
        ];
    }
}
