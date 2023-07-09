<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        $notifications = Notification::whereHas('quote', function ($quote) {
            $quote->where('user_id', auth()->user()->id);
        })->with(['user'])->latest()->get();

        return response()->json([
            'notifications' => $notifications,
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
            $data['to'] = $data->quote->user_id;

            event(new NewNotification(collect($data)));
        }
        return response()->json(['message' => 'success',], 201);
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
        $data['to'] = $comment->quote->user_id;

        event(new NewNotification(collect($data)));
        return response()->json(['message' => 'success', "comment" =>$comment], 201);
    }

    public function seen(Notification $notification): JsonResponse
    {
        $notification->update(['seen'=> true]);
        return response()->json(['message' => 'success'], 201);
    }

    public function seenAll(): JsonResponse
    {
        Notification::where('user_id', auth()->user()->id)->update(['seen'=> true]);
        return response()->json('success', 201);
    }

}
