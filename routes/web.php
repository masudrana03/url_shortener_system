<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShortenedUrlController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard1', function () {
//     return view('dashboard1');
// })->middleware(['auth', 'verified'])->name('dashboard1');

Route::get('url/{shortUrl}', [ShortenedUrlController::class, 'shortener'])->name('url.shortener');

//
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/short-url', ShortenedUrlController::class);
    Route::get('stats/{slug}', [ShortenedUrlController::class, 'stats'])->name('short-url.stats');
});







require __DIR__ . '/auth.php';
