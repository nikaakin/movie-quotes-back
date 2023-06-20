<?php

namespace App\Http\Controllers;

use App\Http\Requests\quotes\StoreRequest;
use App\Http\Requests\quotes\UpdateRequest;
use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function index(int $skip): JsonResponse
    {
        $quotes = Quote::with('notifications.user')->skip($skip)->take(10)->get()->sortByDesc('created_at')->values();
        foreach ($quotes as $quote) {
            $likes = 0;
            foreach ($quote->notifications as $key => $notification) {
                if($notification->isLike === 1) {
                    $likes++;
                    unset($quote->notifications[$key]);
                }
            }
            $quote->likes = $likes;
        }

        return response()->json([
            'quotes' => $quotes,
        ], 200);
    }

    public function quotesOfMovie(int $skip, Movie $movie): JsonResponse
    {
        $quotes = $movie->quotes()->skip($skip)->take(10)->get();

        return response()->json([
            'quotes' => $quotes,
        ], 200);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $quote = Quote::create($request->validated());
        return response()->json([
            'quote' => $quote,
        ], 201);
    }

    public function update(UpdateRequest $request, Quote $quote): JsonResponse
    {
        $quote->update($request->validated());
        return response()->json([
            'quote' => $quote,
        ], 201);
    }

    public function destroy(Quote $quote): JsonResponse
    {
        $quote->delete();
        return response()->json([
            'message' => 'Quote deleted successfully'
        ], 204);
    }
}
