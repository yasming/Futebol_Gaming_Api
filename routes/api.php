<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\MatchController;

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::resource('players', PlayerController::class)->only('store','update','index');
    Route::resource('teams', TeamController::class)->only('store','update','index');
    Route::resource('matches', MatchController::class)->only('store','update');
});

Route::post('/login', [LoginController::class,'authenticate']);
