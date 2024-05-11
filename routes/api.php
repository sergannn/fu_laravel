<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\MarkerController;

Route::get('/markers', [MarkerController::class, 'show']);
//Route::post('/markers', 'MarkerController@store');
Route::post('/markers', [MarkerController::class, 'storeApi']);
