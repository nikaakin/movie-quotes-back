<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quote' => [
                'en' => FakerFactory::create('en_US')->unique()->words(10, true),
                'ka' => FakerFactory::create('ka_GE')->unique()->words(10, true),
            ],
            'image' => env('FRONTEND_URL') . '/assets/images/quote-image.png',
            'movie_id' => Movie::first()->id,
            'user_id' => User::first()->id,
        ];
    }
}
