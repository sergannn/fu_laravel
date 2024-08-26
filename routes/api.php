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

Route::post('/email/verify/{id}/{bool}', [VerifyEmailController::class, '__invoke']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserCatsController;
use App\Http\Controllers\MarkerController;
Route::get('/cats', [UserCatsController::class, 'showCats']);
Route::get('/users', [UserAuthController::class, 'showUsers']);
Route::get('/markers', [MarkerController::class, 'show']);
//Route::post('/markers', 'MarkerController@store');
//Route::post('/markers', [MarkerController::class, 'storeApi']);


Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthenticatedSessionController@store');
//  Route::get('/login', [AuthenticatedSessionController::class,'store']);


Route::get('login', [UserAuthController::class, 'login']);
Route::get('logout', [UserAuthController::class, 'logout']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/markers/add', [MarkerController::class, 'storeMarker']);
});



Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('/markers/{markerId}', [MarkerController::class, 'deleteMarker']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user-markers', [MarkerController::class, 'showMarkersForUser']);
});
//require __DIR__.'/auth.php';
