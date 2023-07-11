<?php

namespace Database\Factories;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class GenreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'genre' => [
                'en' => FakerFactory::create('en_US')->unique()->word(),
                'ka' => FakerFactory::create('ka_GE')->unique()->word(),
            ],
        ];
    }
}
