<?php

namespace App\Http\Controllers;

use App\Http\Requests\movies\DeleteRequest;
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
            $query->with(['notifications.user', 'user','notifications'=> function ($notification) {
                $notification->where('isLike', 0);
            }])->withCount(['notifications as likes' => function ($notification) {
                $notification->where('isLike', 1);
            }, 'notifications as current_user_likes' => function ($notification) {
                $notification->where('isLike', 1)->where('user_id', auth()->user()->id);
            }]);
        }])->firstOrFail();

        return response()->json([
            'movie' => $movie,
        ], 200);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $url = $request->file('image')->store('movies', 'public');
        $data['image'] = env('APP_URL') .'/storage/'. $url;
        $movie = Movie::create($data);
        $movie->genres()->attach($data['genres']);
        return response()->json([
            'movie' => $movie,
        ], 201);
    }

    public function update(UpdateRequest $request, Movie $movie): JsonResponse
    {
        $data = $request->validated();
        if($request->file('image')) {
            $url = $request->file('image')->store('movies', 'public');
            $data['image'] = env('APP_URL') .'/storage/'. $url;
        }
        $data['user_id'] = auth()->user()->id;
        $movie->update($data);
        $movie->genres()->sync($data['genres']);
        $movie['genres']= $movie->genres;
        return response()->json([
            'movie' => $movie,
        ], 201);
    }

    public function destroy(DeleteRequest $request, Movie $movie): JsonResponse
    {
        $movie->delete();
        return response()->json([
            'message' => 'Movie deleted successfully'
        ], 204);
    }
}
