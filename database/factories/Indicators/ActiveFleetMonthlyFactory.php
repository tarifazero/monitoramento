<?php

namespace Database\Factories\Indicators;

use App\Models\Indicators\ActiveFleetMonthly;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActiveFleetMonthlyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ActiveFleetMonthly::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'timestamp' => (new Carbon($this->faker->dateTime()))->startOfMonth(),
            'value' => $this->faker->randomNumber(),
        ];
    }
}
