<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;


    protected $fillable = [
        'username',
        'email',
        'password',
        'remember_token',
        'google_id',
        'image'
    ];


    protected $hidden = [
        'password',
        'remember_token',
        "email_verified_at",
        "created_at",
        "updated_at",
        "google_id",
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

}
