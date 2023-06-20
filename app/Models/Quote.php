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

    public function users()
    {
        return $this->hasManyThrough(User::class, Notification::class);
    }
}
