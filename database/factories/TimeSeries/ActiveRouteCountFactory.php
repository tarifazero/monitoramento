<?php

namespace Database\Factories\TimeSeries;

use App\Models\Route;
use App\Models\TimeSeries\ActiveRouteCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveRouteCountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveRouteCount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resolution' => 'day',
            'time' => today()->startOfDay(),
            'count' => $this->faker->numberBetween(0, 300),
        ];
    }
}
