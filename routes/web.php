<?php

use App\Models\Topic;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/index', function () {
    return view('index', ['user' => Auth::user()]);
})->middleware(['auth'])->name('/index');

Route::get('/search', function () {
    return view('search', ['user' => Auth::user()]);
})->middleware(['auth'])->name('/search');

Route::get('/', function () {
    return view('random', ['user' => Auth::user()]);
})->middleware(['auth'])->name('/');

Route::get('/verify', function () {
    return view('verify', ['user' => Auth::user()]);
})->middleware(['auth'])->name('/verify');

Route::get('/stats', function () {
    return view('stats', ['users' => User::all()]);
})->middleware(['auth'])->name('/stats');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');



Route::middleware(['auth'])->name('similar')->prefix('/similar')->group(function() {
    Route::get('/', function() {
        return view('similar', ['tweet_id' => null]);
    });

    Route::get('/{tweet_id}', function($tweet_id) {
        return view('similar', ['tweet_id' => $tweet_id]);
    });
});

Route::middleware(['auth'])->name('userTweets')->prefix('/userTweets')->group(function () {
    Route::get('/', function() {
        return view('userTweets', ['username' => null]);
    });
    Route::get('/{username}', function($username) {
        return view('userTweets', ['username' => $username]);
    });
});





require __DIR__ . '/auth.php';
