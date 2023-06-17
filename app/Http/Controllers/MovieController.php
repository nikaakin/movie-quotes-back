<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\UpdateRequest;
use App\Http\Requests\movies\StoreRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function index(int $skip): JsonResponse
    {
        $movies = Movie::skip($skip)->take(10)->get();

        return response()->json([
            'movies' => $movies,
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
