<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function toggleLike(string $quoteId)
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
            $data['notification'] = $notification;
            $data['sender'] = auth()->user();

            event(new NewNotification($data));
        }
        return response()->json(['message' => 'success'], 201);
    }

    public function comment(string $quoteId)
    {
        $comment  = Notification::Create([
            'quote_id' => $quoteId,
            'user_id' => auth()->user()->id,
            'isLike' => false,
            'comment' => request()->input('comment')
        ]);
        $data['notification'] = $comment;
        $data['sender'] = auth()->user();

        event(new NewNotification($data));
        return response()->json(['message' => 'success', "comment" =>$comment], 201);
    }

}
