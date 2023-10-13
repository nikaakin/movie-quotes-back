<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Database\Factories\AdminFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        collect(config('genres.genres'))->map(function ($genre) {
            Genre::create([
                'genre' => $genre,
            ]);
        });

        User::factory()->create([
            'username' => 'nika',
            'email' => 'nika@nika.com',
            'password' => env('ADMIN_PASSWORD'),
        ]);

        Admin::factory()->create();

        Movie::factory(20)->create();
        Quote::factory(20)->create();
        Notification::factory(10)->create([
            'isLike' => false
        ]);
        Notification::factory(10)->create([
            'comment' => null,
            'isLike' => true
        ]);

        Movie::all()->each(function ($movie) {
            $movie->genres()->attach(
                Genre::inRandomOrder()->first()
            );
        });

    }
}
