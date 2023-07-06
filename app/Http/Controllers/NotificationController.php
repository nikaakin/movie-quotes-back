<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(int $skip): JsonResponse
    {
        $notifications = Notification::with(['user'])->
        latest()->get()->skip($skip)->take(10)->values();
        $has_more_pages = Notification::count() > ($skip + 10);

        return response()->json([
            'notifications' => $notifications,
            'has_more_pages' => $has_more_pages,
        ], 200);
    }

    public function toggleLike(string $quoteId): JsonResponse
    {
        $notification = Notification::where(['quote_id'=>$quoteId, 'user_id'=> auth()->user()->id, 'isLike'=> 1]);

        if ($notification->value('id')) {
            $notification->delete();
        } else {
            $notification = Notification::Create([
                'quote_id' => $quoteId,
                'user_id' => auth()->user()->id,
                'isLike' => true
            ]);
            $data = $notification;
            $data['user'] = auth()->user();

            event(new NewNotification(collect($data)));
        }
        return response()->json(['message' => 'success'], 201);
    }

    public function comment(string $quoteId): JsonResponse
    {
        $comment  = Notification::Create([
            'quote_id' => $quoteId,
            'user_id' => auth()->user()->id,
            'isLike' => false,
            'comment' => request()->input('comment')
        ]);
        $data = $comment;
        $data['user'] = auth()->user();

        event(new NewNotification(collect($data)));
        return response()->json(['message' => 'success', "comment" =>$comment], 201);
    }

}
