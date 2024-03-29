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
        $quoteQuery = Quote::search(request()->query('search'));
        $quotes = $quoteQuery->withCount(['notifications as likes' => function ($notification) {
            $notification->where('isLike', 1);
        }, 'notifications as current_user_likes' => function ($notification) {
            $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
        }])->with(['notifications.user', 'user', "movie:id,year,title", 'notifications' => function ($notification) {
            $notification->where('comment', '!=', 'null');
        }])->latest()->get()->skip($skip * 5)->take(5)->values();
        $has_more_pages = $quoteQuery->count() > ($skip + 1) * 5;

        return response()->json([
            'quotes' => $quotes,
            'has_more_pages' => $has_more_pages,
            'current_page' => $skip,
        ], 200);
    }

    public function show(int $quoteId): JsonResponse
    {
        $quote = Quote::where('id', $quoteId)->withCount(['notifications as likes' => function ($notification) {
            $notification->where('isLike', 1);
        }, 'notifications as current_user_likes' => function ($notification) {
            $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
        }])->with(['notifications.user', 'user', "movie:id,year,title", 'notifications' => function ($notification) {
            $notification->where('comment', '!=', 'null');
        }])->firstOrFail();

        return response()->json([
            'quote' => $quote,
        ], 200);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $url = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
        $data['user_id'] = auth()->user()->id;
        $data['image'] = $url;
        $quote = Quote::create($data)->withCount(['notifications as likes' => function ($notification) {
            $notification->where('isLike', 1);
        }, 'notifications as current_user_likes' => function ($notification) {
            $notification->where('isLike', 1)->where('user_id', auth()->user()->id);
        }])->with(['notifications.user', 'user', "movie:id,year,title", 'notifications' => function ($notification) {
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
            $url = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
            $data['image'] =  $url;
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

}
