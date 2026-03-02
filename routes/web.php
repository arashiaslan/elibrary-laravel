<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/book/{slug}', [BookController::class, 'show'])->name('book.show');
Route::post('/book/{book}/borrow', [BookController::class, 'borrow'])
    ->middleware(['auth', 'verified'])
    ->name('book.borrow');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::group(['auth', 'verified'], function() {
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::post('/history/{borrowing}/return', [HistoryController::class, 'returnBook'])->name('history.return');
});