<?php

namespace Database\Factories;

use App\Models\GtfsFetch;
use App\Models\Stop;
use App\Models\StopTime;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class StopTimeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StopTime::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'gtfs_fetch_id' => GtfsFetch::factory(),
            'trip_id' => Trip::factory(),
            'stop_id' => Stop::factory(),
            'arrival_time' => $this->faker->time,
            'departure_time' => $this->faker->time,
            'stop_sequence' => $this->faker->randomDigit,
            'location_type' => $this->faker->randomElement([0, 1]),
        ];
    }
}
