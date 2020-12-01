<?php

namespace Database\Factories;

use App\Models\RealTimeEntry;
use App\Models\RealTimeFetch;
use App\Models\Route;
use App\Models\Vehicle;
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
            'route_id' => Route::factory(),
            'vehicle_id' => Vehicle::factory(),
            'timestamp' => $this->faker->dateTimeBetween(now()->subDay(), now()->subSecond()),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'speed' => $this->faker->numberBetween(0, 60),
            'travel_direction' => $this->faker->numberBetween(1, 2),
        ];
    }

    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                'event' => 98475,
                'travel_direction' => 90182,
            ];
        });
    }

    public function processed()
    {
        return $this->state(function (array $attributes) {
            return [
                'processed_at' => now(),
            ];
        });
    }
}
