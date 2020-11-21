<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PlayerController;

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::resource('players', PlayerController::class)->only('store','update','index');
    Route::resource('teams', PlayerController::class)->only('store','update','index');
});

Route::post('/login', [LoginController::class,'authenticate']);
