<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerifyController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/models', [ModelController::class, 'index']);
Route::get('/models/{model}', [ModelController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


/*
|--------------------------------------------------------------------------
| AUTH USER
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // model
    Route::post('/models', [ModelController::class, 'store']);
    Route::delete('/models/{model}', [ModelController::class, 'destroy']);

    // interaction
    Route::post('/models/{model}/star', [InteractionController::class, 'star']);
    Route::post('/models/{model}/comment', [InteractionController::class, 'comment']);
    Route::get('/models/{model}/download', [InteractionController::class, 'download']);

    // report
    Route::post('/models/{model}/report', [ReportController::class, 'store']);

    // verify
    Route::post('/verify-request', [VerifyController::class, 'store']);
});