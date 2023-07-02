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
        $quotes = Quote::withCount(['notifications as likes' => function ($notification) {
            $notification->where('isLike', 1);
        }])->with(['notifications.user', 'user', "movie:id,year,title", 'notifications'=> function ($notification) {
            $notification->where('isLike', 0);
        }])->latest()->get()->skip($skip*5)->take(5)->values();
        $has_more_pages = Quote::count() > ($skip + 1) * 5;

        return response()->json([
            'quotes' => $quotes,
            'has_more_pages' => $has_more_pages,
            'current_page' => $skip,
        ], 200);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $url = $request->file('image')->store('quotes', 'public');
        $data['user_id'] = auth()->user()->id;
        $data['image'] = env('APP_URL') .'/storage/'. $url;
        $quote = Quote::create($data);
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

    public function search(): JsonResponse
    {
        $quotes = Quote::search(request()->query('search'));

        return response()->json([
            'quotes' =>  $quotes,
        ], 200);
    }
}
