<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'quote' => 'array'
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, string|null $searchQuery)
    {
        if($searchQuery == '' || $searchQuery == null) {
            return $query;
        }

        if(str_starts_with($searchQuery, '#')) {
            $searchQuery = str_replace('#', '', $searchQuery);
            return $query->where(function ($query) use ($searchQuery) {
                $query->WhereRaw("quote->>'en' ilike ?", ["%$searchQuery%"])
                ->orWhereRaw("quote->>'ka' ilike ?", ["%$searchQuery%"]);
            })->with(['user','notifications.user','notifications' => function ($notification) {
                $notification->where('comment', '!=', 'null');
            }])->withCount(['notifications as likes' => function ($notification) {
                $notification->where('isLike', 1);
            }, 'notifications as current_user_likes' => function ($notification) {
                $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
            }]);
        }

        if(str_starts_with($searchQuery, '@')) {
            $searchQuery = str_replace('@', '', $searchQuery);
            return $query->whereHas('movie', function ($q) use ($searchQuery) {
                return $q->where(function ($q) use ($searchQuery) {
                    $q->WhereRaw("title->>'en' ilike ?", ["%$searchQuery%"])
                        ->orWhereRaw("title->>'ka' ilike ?", ["%$searchQuery%"]);
                });
            })->with(['user','notifications.user', 'notifications' => function ($notification) {
                $notification->where('comment', "!=", "null");
            }])->withCount(['notifications as likes' => function ($notification) {
                $notification->where('isLike', 1);
            }, 'notifications as current_user_likes' => function ($notification) {
                $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
            }]);
        }
    }
}
