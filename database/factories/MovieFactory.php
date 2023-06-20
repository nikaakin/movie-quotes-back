<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovieFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => [
                'en' => $this->faker->unique()->sentence(1),
                'ka' => $this->faker->unique()->sentence(1),
            ],
            'description' => [
                'en' => $this->faker->paragraph(1),
                'ka' => $this->faker->paragraph(1),
            ],
            'director'  => [
                'en' => $this->faker->name(),
                'ka' => $this->faker->name(),
            ],
            'year' => $this->faker->year(),
            'image' => env('FRONTEND_URL') . '/assets/images/movie-image.png',
            'user_id' => User::where(['email'=> 'nika@nika.com'])->first()->id,
        ];
    }
}
