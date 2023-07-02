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
}
