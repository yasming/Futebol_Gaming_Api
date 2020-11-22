<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\RankingController;

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::resource('players', PlayerController::class)->only('store','update','index');
    Route::resource('teams', TeamController::class)->only('store','update','index');
    Route::resource('matches', MatchController::class)->only('store','update');
    Route::group(['as' => 'ranking.'], function(){
        Route::get('/ranking-teams', [RankingController::class, 'getRankingTeams'])->name('teams');
        Route::get('/ranking-players', [RankingController::class, 'getRankingPlayers'])->name('players');
    });
});

Route::post('/login', [LoginController::class,'authenticate']);
