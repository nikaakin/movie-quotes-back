<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
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



Route::group(['controller' => AuthController::class], function () {
    Route::get('/user', 'isAuthenticated')->middleware('auth:sanctum');
    Route::post('/register', 'register')->name('register');
    Route::patch('/update', 'update')->name('update');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/email/verify/{id}/{hash}', 'verification')->middleware(['signed'])->name('verification.verify');
    Route::post('/forgot-password', 'forgot')->name('password.forgot');
    Route::post('/reset-password', 'reset')->name('password.reset');
    Route::get('/auth/google/redirect', 'googleRedirect')->name('google.redirect');
    Route::get('/auth/google/callback', 'googleCallback')->name('google.callback');
});

Route::group([ 'prefix' => 'movies'], function () {

    Route::group(["middleware" => "auth:sanctum",'controller' => MovieController::class], function () {
        Route::get('/', 'index')->name('movies.index');
        Route::get('/{movieId}', 'show')->name('movies.show');
        Route::post('/store', 'store')->name('movies.store');
        Route::patch('/update/{movie}', 'update')->name('movies.update');
        Route::delete('/destroy/{movie}', 'destroy')->name('movies.destroy');
    });
});

Route::group(['controller' => QuoteController::class], function () {
    Route::group(['prefix' => 'quotes'], function () {
        Route::get('/{akip}', 'index')->name('quotes.index');
        Route::group([ "middleware" => "auth:sanctum"], function () {
            Route::post('/store', 'store')->name('quotes.store');
            Route::patch('/update/{quote}', 'update')->name('quotes.update');
            Route::delete('/destroy/{quote}', 'destroy')->name('quotes.destroy');
        });
    });
});

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
