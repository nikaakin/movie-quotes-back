<?php

namespace Database\Factories;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'isLike' => $this->faker->boolean(),
            'comment' => $this->faker->paragraph(1),
            'user_id' =>  User::where(['email'=> 'nika@nika.com'])->first()->id,
            'quote_id'=> Quote::first()->id,
        ];
    }
}
