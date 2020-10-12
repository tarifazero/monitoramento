<?php

namespace Database\Factories\Concerns;

trait HasActivityStatus
{
    public function activeInPastHour()
    {
        return $this->state(function (array $attributes) {
            $created_at = $this->faker->dateTimeBetween(now()->subHour()->startOfHour(), now()->subMinute());
            $updated_at = $this->faker->dateTimeBetween($created_at, now());

            return [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        });
    }

    public function activeInPastDay()
    {
        return $this->state(function (array $attributes) {
            $created_at = $this->faker->dateTimeBetween(now()->subDay()->startOfDay(), now()->subMinute());
            $updated_at = $this->faker->dateTimeBetween($created_at, now());

            return [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        });
    }

    public function activeInPastMonth()
    {
        return $this->state(function (array $attributes) {
            $created_at = $this->faker->dateTimeBetween(now()->subMonth()->startOfMonth(), now()->subMinute());
            $updated_at = $this->faker->dateTimeBetween($created_at, now());

            return [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        });
    }

    public function inactiveInPastHour()
    {
        return $this->state(function (array $attributes) {
            $created_at = $this->faker->dateTimeBetween(now()->subYear(), now()->subHour()->startOfHour()->subMinute());
            $updated_at = $this->faker->dateTimeBetween($created_at, now()->subHour()->startOfHour());

            return [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        });
    }

    public function inactiveInPastDay()
    {
        return $this->state(function (array $attributes) {
            $created_at = $this->faker->dateTimeBetween(now()->subYear(), now()->subDay()->startOfDay()->subMinute());
            $updated_at = $this->faker->dateTimeBetween($created_at, now()->subDay()->startOfDay());

            return [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        });
    }

    public function inactiveInPastMonth()
    {
        return $this->state(function (array $attributes) {
            $created_at = $this->faker->dateTimeBetween(now()->subYear(), now()->subMonth()->startOfMonth()->subMinute());
            $updated_at = $this->faker->dateTimeBetween($created_at, now()->subMonth()->startOfMonth());

            return [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
        });
    }
}
