<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['prefix'=> 'api', 'middleware' => ['api','auth:sanctum']]);

        require base_path('routes/channels.php');
    }
}
