<?php

namespace Database\Factories\Concerns;

trait HasActivityStatus
{
    public function activeInPastHour()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subHour()->startOfHour(), now()),
            ];
        });
    }

    public function activeInPastDay()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subDay()->startOfDay(), now()),
            ];
        });
    }

    public function activeInPastMonth()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subMonth()->startOfMonth(), now()),
            ];
        });
    }

    public function inactiveInPastHour()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subYear(), now()->subHour()->startOfHour()),
            ];
        });
    }

    public function inactiveInPastDay()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subYear(), now()->subDay()->startOfDay()),
            ];
        });
    }

    public function inactiveInPastMonth()
    {
        return $this->state(function (array $attributes) {
            return [
                'updated_at' => $this->faker->dateTimeBetween(now()->subYear(), now()->subMonth()->startOfMonth()),
            ];
        });
    }
}
