<?php

namespace App\Http\Controllers;

use App\Http\Requests\movies\UpdateRequest;
use App\Http\Requests\movies\StoreRequest;
use App\Models\Movie;
use App\Models\User;
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
        }])->firstWhere($movie);

        return response()->json([
            'movies' => $movie,
        ], 200);
    }

    public function moviesOfUser(User $user): JsonResponse
    {

        $movies = $user->with(['movies'=>function ($movie) {
            $movie->with(['genres','quotes'=>function ($quote) {
                $quote->withCount(['notifications' => function ($notification) {
                    $notification->where('isLike', 1);
                }])->with(['notifications.user', 'notifications'=> function ($notification) {
                    $notification->where('isLike', 0);
                }]);
            }]);
        }])->firstWhere(['id' => $user->id])->movies;

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
