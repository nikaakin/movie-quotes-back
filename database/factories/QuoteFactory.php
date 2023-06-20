<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quote' => [
                'en' => $this->faker->unique()->sentence(2),
                'ka' => $this->faker->unique()->sentence(2),
            ],
            'image' => env('FRONTEND_URL') . '/assets/images/quote-image.png',
            'movie_id' => Movie::first()->id,
            'user_id' => User::first()->id,
        ];
    }
}
