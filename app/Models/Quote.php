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
        $firstFilter = collect([]);
        $secondFilter = collect([]);

        if(str_starts_with($searchQuery, '@') || !str_contains($searchQuery, '#')) {
            $searchQuery = str_replace('@', '', $searchQuery);
            $firstFilter =  $query->where(function ($query) use ($searchQuery) {
                $query->WhereRaw('LOWER(JSON_EXTRACT(quote, "$.en")) like ?', ["%$searchQuery%"])
                    ->orWhereRaw('LOWER(JSON_EXTRACT(quote, "$.ka")) like ?', ["%$searchQuery%"]);
            })->get();
        }

        if(str_starts_with($searchQuery, '#') || !str_contains($searchQuery, '@')) {
            $searchQuery = str_replace('#', '', $searchQuery);
            $filteredmovies =  Movie::search($searchQuery)->with('quotes')->get();
            $filteredmovies->each(function ($movie) use (&$secondFilter) {
                $secondFilter->push(...$movie->quotes);
            });
        }

        return $firstFilter->merge($secondFilter)->unique('id')->values();
    }
}
