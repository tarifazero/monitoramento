<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'real_time_id' => $this->faker->unique()->randomNumber,
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subDay(), now()),
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subYear(), now()->subDay()),
            ];
        });
    }
}
