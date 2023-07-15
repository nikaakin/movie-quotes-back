<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
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

    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::get('/email/verify/{id}/{hash}', 'verification')->middleware(['signed'])->name('verification.verify');
    Route::post('/forgot-password', 'forgot')->name('password.forgot');
    Route::post('/reset-password', 'reset')->name('password.reset');
    Route::get('/auth/google/redirect', 'googleRedirect')->name('google.redirect');
    Route::get('/auth/google/callback', 'googleCallback')->name('google.callback');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/logout', 'logout')->name('logout');
        Route::patch('/update', 'update')->name('update');
        Route::get('/user', 'isAuthenticated')->name('user.auth');
    });
});

Route::group(['middleware' =>'auth:sanctum'], function () {
    Route::group(['prefix' => 'movies','controller' => MovieController::class], function () {
        Route::get('/', 'index')->name('movies.index');
        Route::get('/{movieId}', 'show')->name('movies.show');
        Route::post('/store', 'store')->name('movies.store');
        Route::post('/update/{movie}', 'update')->name('movies.update');
        Route::delete('/destroy/{movie}', 'destroy')->name('movies.destroy');
    });

    Route::group(['controller' => QuoteController::class,'prefix' => 'quotes'], function () {
        Route::get('/search', 'search')->name('search');
        Route::post('/store', 'store')->name('quotes.store');
        Route::post('/update/{quote}', 'update')->name('quotes.update');
        Route::delete('/destroy/{quote}', 'destroy')->name('quotes.destroy');
        Route::get('/{skip}', 'index')->name('quotes.index');
    });

    Route::group(['controller' => NotificationController::class, 'prefix' => 'notifications'], function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::patch('/like/{quoteId}', 'toggleLike')->name('notifications.toggleLike');
        Route::patch('/comment/{quoteId}', 'comment')->name('notifications.comment');
        Route::patch('/seen', 'seenAll')->name('notifications.seenAll');
        Route::patch('/seen/{notification}', 'seen')->name('notifications.seen');
    });
});

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
