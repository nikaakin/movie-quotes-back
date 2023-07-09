<?php

namespace App\Broadcasting;

use App\Models\User;

class NotificationChannel
{
    public function join(User $user, $id): bool
    {
        return (int) $user->id === (int) $id;
    }
}
