<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Database\Factories\Concerns\HasActivityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VehicleFactory extends Factory
{
    use HasActivityStatus;

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
}
