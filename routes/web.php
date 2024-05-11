<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MarkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::delete('/marker/{id}', [MarkerController::class, 'destroy'])->name('marker.delete');
Route::post('/markers', [MarkerController::class, 'store'])->name('markers.store')->middleware('auth');
//Route::get('/markers', [MarkerController::class, 'show'])->name('markers.show')->middleware('auth');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
