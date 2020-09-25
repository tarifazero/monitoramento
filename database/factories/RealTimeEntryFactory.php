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
            'route_json_id' => $this->faker->randomNumber,
            'vehicle_json_id' => $this->faker->randomNumber,
            'created_at' => $this->faker->dateTimeBetween(now()->subDay(), now()),
        ];
    }
}
