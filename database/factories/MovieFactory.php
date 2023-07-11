<?php

namespace Database\Factories;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => [
                'en' => FakerFactory::create('en_US')->unique()->title(),
                'ka' => FakerFactory::create('ka_GE')->unique()->title(),
            ],
            'description' => [
                'en' => FakerFactory::create('en_US')->paragraph(1),
                'ka' => FakerFactory::create('ka_GE')->paragraph(1),
            ],
            'director'  => [
                'en' => FakerFactory::create('en_US')->name(),
                'ka' => FakerFactory::create('ka_GE')->name(),
            ],
            'year' => $this->faker->year(),
            'image' => env('FRONTEND_URL') . '/assets/images/movie-image.png',
            'user_id' => User::where(['email'=> 'nika@nika.com'])->first()->id,
        ];
    }
}
