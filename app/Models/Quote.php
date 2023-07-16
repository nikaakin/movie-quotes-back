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

    public function scopeSearch($query, string $searchQuery)
    {

        if(str_starts_with($searchQuery, '#')) {
            $searchQuery = str_replace('#', '', $searchQuery);
            return $query->where(function ($query) use ($searchQuery) {
                $query->WhereRaw('LOWER(JSON_EXTRACT(quote, "$.en")) like ?', ["%$searchQuery%"])
                ->orWhereRaw('LOWER(JSON_EXTRACT(quote, "$.ka")) like ?', ["%$searchQuery%"]);
            })->with(['user','notifications.user','notifications'=> function ($notification) {
                $notification->where('comment', '!=', 'null');
            }])->withCount(['notifications as likes'=> function ($notification) {
                $notification->where('isLike', 1);
            }, 'notifications as current_user_likes' => function ($notification) {
                $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
            }])->get();
        }

        if(str_starts_with($searchQuery, '@')) {
            $searchQuery = str_replace('@', '', $searchQuery);
            return $query->whereHas('movie', function ($q) use ($searchQuery) {
                return $q->where(function ($q) use ($searchQuery) {
                    $q->WhereRaw('LOWER(JSON_EXTRACT(title, "$.en")) like ?', ["%$searchQuery%"])
                        ->orWhereRaw('LOWER(JSON_EXTRACT(title, "$.ka")) like ?', ["%$searchQuery%"]);
                });
            })->with(['user','notifications.user', 'notifications'=> function ($notification) {
                $notification->where('comment', "!=", "null");
            }])->withCount(['notifications as likes'=> function ($notification) {
                $notification->where('isLike', 1);
            }, 'notifications as current_user_likes' => function ($notification) {
                $notification->where('isLike', 1)->Where('user_id', auth()->user()->id);
            }])->get();
        }
    }
}
