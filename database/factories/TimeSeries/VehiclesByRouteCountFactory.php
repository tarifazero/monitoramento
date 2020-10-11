<?php

namespace Database\Factories\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\VehiclesByRouteCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiclesByRouteCountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehiclesByRouteCount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'route_id' => Route::factory(),
            'resolution' => 'hour',
            'time' => today()->startOfDay(),
            'count' => $this->faker->numberBetween(0, 30),
        ];
    }
}
