<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class GenreFactory extends Factory
{

    public function definition(): array
    {
        return [
            'genre' => [
                'en' => $this->faker->unique()->word(),
                'ka' => $this->faker->unique()->word(),
            ],
        ];
    }
}
