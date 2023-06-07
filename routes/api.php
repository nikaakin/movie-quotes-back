<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
    return $request->user();
});


Route::group(['controller' => AuthController::class], function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::get('/email/verify/{id}/{hash}', 'verification')->middleware(['signed'])->name('verification.verify');
    Route::get('/auth/google/redirect', 'googleRedirect');
    Route::get('/auth/google/callback', 'googleCallback');
});
