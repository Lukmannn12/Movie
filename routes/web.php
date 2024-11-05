<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MovieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

// routes/web.php
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// routes/web.php
Route::middleware('auth')->group(function () {
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/{id}', [MovieController::class, 'detailMovie'])->name('movies.detail');
    Route::post('/favorite/{id}', [MovieController::class, 'addFavorite'])->name('movies.favorite');
    Route::get('/favorites', [MovieController::class, 'favoriteMovies'])->name('movies.favorites');
    Route::delete('/favorite/{id}', [MovieController::class, 'removeFavorite'])->name('movies.favorite.remove');
    Route::get('/switch-language/{lang}', [MovieController::class, 'switchLanguage'])->name('switch.language');

});

