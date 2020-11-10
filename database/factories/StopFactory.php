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
            'gtfs_fetch_id' => GtfsFetch::factory(),
            'gtfs_id' => $this->faker->unique()->randomNumber,
            'name' => $this->faker->word,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'location_type' => $this->faker->randomElement([0, 1]),
            'parent_station' => null,
        ];
    }

    public function child()
    {
        return $this->state(function (array $attributes) {
            return [
                'location_type' => $this->faker->randomElement([0, 2, 3, 4]),
                'parent_station' => Stop::factory(),
            ];
        });
    }
}
