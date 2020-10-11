<?php

namespace Database\Factories\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\ActiveVehicleCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveVehicleCountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveVehicleCount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resolution' => 'hour',
            'time' => today()->startOfDay(),
            'count' => $this->faker->numberBetween(0, 3000),
        ];
    }
}
