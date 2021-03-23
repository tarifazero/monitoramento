<?php

namespace Database\Factories\Indicators;

use App\Models\Indicators\ActiveFleetHourly;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveFleetHourlyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveFleetHourly::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'timestamp' => (new Carbon($this->faker->dateTime()))->startOfHour(),
            'value' => $this->faker->randomNumber(),
        ];
    }
}
