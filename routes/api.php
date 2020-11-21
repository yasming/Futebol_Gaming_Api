<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;

Route::post('/login', [LoginController::class,'authenticate']);
