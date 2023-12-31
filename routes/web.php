<?php

use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LinkedinController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/auth/google/redirect', [GoogleController::class, 'handleGoogleRedirect'])->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/linkedin/redirect', [LinkedinController::class, 'handleLinkedinRedirect'])->name('linkedin.login');

Route::get('auth/linkedin/callback', [LinkedinController::class, 'handleLinkedinCallback']);


Route::get('auth/github/redirect', [GithubController::class, 'handleGithubRedirect'])->name('github.login');
Route::get('auth/github/callback', [GithubController::class, 'handleGithubCallback']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
