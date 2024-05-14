<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\MarkerController;

Route::get('/markers', [MarkerController::class, 'show']);
//Route::post('/markers', 'MarkerController@store');
Route::post('/markers', [MarkerController::class, 'storeApi']);

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

use App\Http\Controllers\UserAuthController;

Route::get('login', [UserAuthController::class, 'login']);


