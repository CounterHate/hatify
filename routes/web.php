<?php

use App\Models\Topic;
use App\Models\Tweet;
use App\Models\User;
use Atymic\Twitter\Twitter as TwitterContract;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use function React\Promise\Stream\first;

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

Route::get('/', function () {
    return view('random', ['user' => Auth::user()]);
})->middleware(['auth'])->name('/');

Route::get('/aiCheck', function () {
    return view('aiCheck', ['user' => Auth::user()]);
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



Route::middleware(['auth'])->name('search')->prefix('/search')->group(function () {
    Route::get('/{media}', function ($media) {
        return view('search', ['data_id' => null, 'media' => $media]);
    });

    Route::get('/{media}/data_id={data_id}', function ($media, $data_id) {
        return view('search', ['data_id' => $data_id, 'media' => $media]);
    });

    Route::get('/{media}/author_username={author_username}', function ($media, $author_username) {
        return view('search', ['author_username' => $author_username, 'media' => $media]);
    });
});

Route::middleware(['auth'])->name('userTweets')->prefix('/userTweets')->group(function () {
    Route::get('/{media}', function ($media) {
        return view('userTweets', ['username' => null, 'media' => $media]);
    });
    Route::get('/{media}/{username}', function ($media, $username) {
        return view('userTweets', ['username' => $username, 'media' => $media]);
    });
});

Route::get('/tweetStats/{tweet_id}', function ($tweet_id) {

    $params = [
        'place.fields' => '',
        'tweet.fields' => 'public_metrics',
        'expansions' => '',
        TwitterContract::KEY_RESPONSE_FORMAT => TwitterContract::RESPONSE_FORMAT_JSON,
    ];


    $data = json_decode(Twitter::forApiV2()->getTweet($tweet_id, $params));
    return $data;
});





require __DIR__ . '/auth.php';
