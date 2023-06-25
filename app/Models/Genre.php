<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'genre' => 'array'
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class);
    }
}
