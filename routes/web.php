<?php

use App\Http\Controllers\PermissionsController;
use App\Models\Topic;
use App\Models\Tweet;
use App\Models\User;
use Atymic\Twitter\Twitter as TwitterContract;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

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
    return view('home', ['user' => Auth::user()]);
})->name('home');

// Route::get('/faq', function () {
//     return view('faq', ['user' => Auth::user()]);
// })->name('faq');

Route::get('/about', function () {
    return view('about', ['user' => Auth::user()]);
})->name('about');

Route::get('/capabilities', function () {
    return view('capabilities', ['user' => Auth::user()]);
})->name('capabilities');


Route::get('/anotate', function () {
    return view('anotate', ['user' => Auth::user()]);
})->middleware(['auth', 'can:anotate'])->name('anotate');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::prefix('/search')->group(function () {
    Route::get('/{media}', function ($media) {
        return view('search', ['data_id' => null, 'media' => $media]);
    });

    Route::get('/{media}/data_id={data_id}', function ($media, $data_id) {
        return view('search', ['data_id' => $data_id, 'media' => $media]);
    });

    Route::get('/{media}/author_username={author_username}', function ($media, $author_username) {
        return view('search', ['author_username' => $author_username, 'media' => $media]);
    });

    Route::get('/{media}/content={content}', function ($media, $content) {
        return view('search', ['content' => $content, 'media' => $media]);
    });

    Route::get('/{media}/declination={declination}', function ($media, $declination) {
        return view('search', ['declination' => $declination, 'media' => $media]);
    });

    Route::get('/{media}/category={category}', function ($media, $category) {
        return view('search', ['category' => $category, 'media' => $media]);
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

Route::get('/admin', function () {
    return view('admin');
})->middleware(['auth', 'can:admin'])->name('admin');

Route::middleware(['auth', 'can:admin'])->prefix('/permissions')->group(function () {
    Route::get('/', [PermissionsController::class, 'index']);
    Route::get('/edit/{user}', [PermissionsController::class, 'edit']);
    Route::post('/update/{user}', [PermissionsController::class, 'update']);
});





require __DIR__ . '/auth.php';
