<?php

namespace Database\Factories;

use App\Models\Route;
use Database\Factories\Concerns\HasActivityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RouteFactory extends Factory
{
    use HasActivityStatus;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'real_time_id' => $this->faker->unique()->randomNumber,
            'gtfs_id' => $this->faker->unique()->randomNumber,
            'short_name' => $this->faker->randomNumber,
            'long_name' => $this->faker->streetName,
            'type' => Route::TYPE_BUS,
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subWeek(), now()),
            ];
        });
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subYear(), now()->subWeek()),
            ];
        });
    }

    public function realTimeOnly()
    {
        return $this->state(function (array $attributes) {
            return [
                'gtfs_id' => null,
            ];
        });
    }
}
