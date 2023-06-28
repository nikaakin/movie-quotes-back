<?php

namespace App\Http\Controllers;

use App\Http\Requests\movies\UpdateRequest;
use App\Http\Requests\movies\StoreRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        $movies = Movie::where("user_id", auth()->user()->id)->withCount('quotes')->get();

        return response()->json([
            'movies' => $movies,
        ], 200);
    }

    public function show(int $movieId): JsonResponse
    {
        $movie = Movie::where('id', $movieId)->with(['genres','quotes' => function ($query) {
            $query->withCount(['notifications as likes' => function ($notification) {
                $notification->where('isLike', 1);
            },'notifications as comments'=> function ($notification) {
                $notification->where('isLike', 0);
            }]);
        }])->first();

        return response()->json([
            'movie' => $movie,
        ], 200);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $url = $request->file('image')->store('movies', 'public');
        $data['image'] = env('APP_URL') .'/storage/'. $url;
        $movie = Movie::create($data);
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
