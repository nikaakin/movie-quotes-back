<?php

namespace App\Http\Controllers;

use App\Http\Requests\movies\UpdateRequest;
use App\Http\Requests\movies\StoreRequest;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        $movies = Movie::where("user_id", auth()->user()->id)->withCount('quotes')->get();

        return response()->json([
            'movies' => $movies,
        ], 200);
    }

    public function show(Movie $movie): JsonResponse
    {
        Quote::where("movie_id", $movie->id)->withCount(['notifications as like' => function ($notification) {
            $notification->where('isLike', 1);
        },'notifications'=> function ($notification) {
            $notification->where('isLike', 0);
        }])->get();
        $movie['genres'] = $movie->genres;
        $movie['quotes'] = $movie->quotes;

        return response()->json([
            'movie' => $movie,
        ], 200);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $movie = Movie::create($request->validated());
        return response()->json([
            'movie' => $movie,
        ], 201);
    }

    public function update(UpdateRequest $request, Movie $movie): JsonResponse
    {
        $movie->update($request->validated());
        return response()->json([
            'movie' => $movie,
        ], 201);
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $movie->delete();
        return response()->json([
            'message' => 'Movie deleted successfully'
        ], 204);
    }
}
