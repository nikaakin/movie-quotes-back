<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->zjson(['is_authenticated' => true], 200);
});

Route::get('/test', function () {
    return response()->json(['message' => 'test'], 200);
});

Route::group(['controller' => AuthController::class], function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/email/verify/{id}/{hash}', 'verification')->middleware(['signed'])->name('verification.verify');
    Route::post('/forgot-password', 'forgot')->name('password.forgot');
    Route::post('/reset-password', 'reset')->name('password.reset');
    Route::get('/auth/google/redirect', 'googleRedirect')->name('google.redirect');
    Route::get('/auth/google/callback', 'googleCallback')->name('google.callback');
});
