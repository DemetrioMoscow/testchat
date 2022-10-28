<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes([
    'middleware' => [
        'auth:sanctum',
    ]
]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return \App\Http\Resources\Api\User\UserResource::make($request->user());
});

Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
], function() {
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->name('logout');
    Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
});

Route::group([
    'prefix' => 'chat',
    'middleware' => 'auth:sanctum',
    'as' => 'chat.',
], function() {
    Route::get('init', [\App\Http\Controllers\Api\ChatController::class, 'init'])->name('init');
    Route::post('message', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage'])->name('send_message');
});
