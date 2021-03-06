<?php

namespace Database\Factories;

use App\Models\GtfsFetch;
use App\Models\Route;
use App\Models\Service;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'gtfs_fetch_id' => GtfsFetch::factory(),
            'route_id' => Route::factory(),
            'service_id' => Service::factory(),
            'gtfs_id' => $this->faker->unique()->randomNumber,
            'headsign' => $this->faker->word,
            'direction_id' => $this->faker->randomElement([0, 1]),
        ];
    }
}
