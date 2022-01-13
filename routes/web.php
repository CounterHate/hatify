<?php

use App\Models\Topic;
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
})->middleware(['auth'])->name('/');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
