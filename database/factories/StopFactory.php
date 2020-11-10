<?php

namespace Database\Factories;

use App\Models\GtfsFetch;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Factories\Factory;

class StopFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stop::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'gtfs_fetch_id' => GtfsFetch::factory():
            'gtfs_id' => $this->faker->unique()->randomNumber,
            'name' => $this->faker->word,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'location_type' => $this->faker->numberBetween(0, 4),
            'parent_station' => null,
        ];
    }
}
