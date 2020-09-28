<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\RouteVehicle;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteVehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RouteVehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'route_id' => Route::factory(),
            'vehicle_id' => Vehicle::factory(),
            'created_at' => today()->hour($this->faker->numberBetween(0, 23)),
        ];
    }
}
