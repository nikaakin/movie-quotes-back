<?php

namespace App\Http\Controllers;

use App\Http\Requests\quotes\DeleteRequest;
use App\Http\Requests\quotes\StoreRequest;
use App\Http\Requests\quotes\UpdateRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    public function index(int $skip): JsonResponse
    {
        $quotes = Quote::withCount(['notifications as likes' => function ($notification) {
            $notification->where('isLike', 1);
        }, 'notifications as current_user_likes' => function ($notification) {
            $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
        }])->with(['notifications.user', 'user', "movie:id,year,title", 'notifications'=> function ($notification) {
            $notification->where('comment', '!=', 'null');
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
        $quote = Quote::create($data)->withCount(['notifications as likes' => function ($notification) {
            $notification->where('isLike', 1);
        }, 'notifications as current_user_likes' => function ($notification) {
            $notification->where('isLike', 1)->where('user_id', auth()->user()->id);
        }])->with(['notifications.user', 'user', "movie:id,year,title", 'notifications'=> function ($notification) {
            $notification->where('comment', '!=', 'null');
        }])->latest()->first();
        return response()->json([
            'quote' => $quote,
        ], 201);
    }

    public function update(UpdateRequest $request, Quote $current_quote): JsonResponse
    {
        $data = $request->validated();
        if($request->file('image')) {
            $url = $request->file('image')->store('movies', 'public');
            $data['image'] = env('APP_URL') .'/storage/'. $url;
        }
        $current_quote->update($data);
        return response()->json([
            'quote' => $current_quote,
        ], 201);
    }

    public function destroy(DeleteRequest $request, Quote $current_quote): JsonResponse
    {
        $current_quote->delete();
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
