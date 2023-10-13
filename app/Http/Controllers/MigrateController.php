<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class MigrateController extends Controller
{
    public function index()
    {
        $exitCode = Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        return $exitCode;
    }
}
