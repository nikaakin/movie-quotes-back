<?php

namespace App\Http\Controllers;

use App\Http\Requests\movies\UpdateRequest;
use App\Http\Requests\movies\StoreRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function index(Movie $movie): JsonResponse
    {
        $movie = $movie->with(['quotes'=>function ($quote) {
            $quote->withCount(['notifications' => function ($notification) {
                $notification->where('isLike', 1);
            }])->with(['notifications.user', 'notifications'=> function ($notification) {
                $notification->where('isLike', 0);
            }]);
        }])->get();

        return response()->json([
            'movies' => $movie,
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
