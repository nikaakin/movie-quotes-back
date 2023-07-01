<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'director' => 'array'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id'
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function scopeSearch($query, string $searchQuery)
    {
        return $query->where(function ($query) use ($searchQuery) {
            $query->WhereRaw('LOWER(JSON_EXTRACT(title, "$.en")) like ?', ["%$searchQuery%"])
                ->orWhereRaw('LOWER(JSON_EXTRACT(title, "$.ka")) like ?', ["%$searchQuery%"]);
        });
    }

}
