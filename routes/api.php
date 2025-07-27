<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

Route::group(['prefix' => 'auth', 'middleware' => ['api', 'auth:api']], static function () {
    Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:api']);
    Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(['auth:api']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});

Route::group(['middleware' => ['api', 'auth:api']], static function () {
    Route::group(['prefix' => 'reservations'], static function () {
        Route::group(['prefix' => '{reservation}', 'where' => ['reservation' => '[0-9]+']], static function () {
            Route::delete('/', [ReservationController::class, 'delete']);
        });
        Route::get('/get-available-times', [ReservationController::class, 'getAvailableTimes']);
        Route::get('/', [ReservationController::class, 'list']);
        Route::post('/', [ReservationController::class, 'save']);
    });
});
