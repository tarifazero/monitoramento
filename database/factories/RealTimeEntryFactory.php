<?php

namespace Database\Factories;

use App\Models\RealTimeEntry;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RealTimeEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RealTimeEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'route_real_time_id' => $this->faker->randomNumber,
            'vehicle_real_time_id' => $this->faker->randomNumber,
            'event' => 105,
            'timestamp' => $this->faker->dateTimeBetween(now()->subDay(), now()),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'speed' => $this->faker->numberBetween(0, 60),
            'cardinal_direction' => $this->faker->numberBetween(0, 360),
            'travel_direction' => $this->faker->numberBetween(1, 2),
            'distance' => $this->faker->numberBetween(0, 100),
            'created_at' => $this->faker->dateTimeBetween(now()->subDay(), now()),
        ];
    }
}
