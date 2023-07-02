<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function toggleLike(string $quoteId)
    {
        $notification = Notification::where(['quote_id'=>$quoteId, 'user_id'=> auth()->user()->id, 'isLike'=> 1]);

        if ($notification->value('id')) {
            $notification->delete();
        } else {
            Notification::Create([
                'quote_id' => $quoteId,
                'user_id' => auth()->user()->id,
                'isLike' => true
            ]);
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

        return response()->json(['message' => 'success', "comment" =>$comment], 201);
    }

}
