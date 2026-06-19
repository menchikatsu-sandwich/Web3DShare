<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadPageController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [ModelController::class, 'index']);
Route::get('/models', [ModelController::class, 'index']);
Route::get('/creators/{user:username}', [ProfileController::class, 'creator']);
Route::get('/models/{model}/download', [InteractionController::class, 'download']);
Route::get('/models/{model}', [ModelController::class, 'show']);
Route::get('/terms', fn () => response()->json([
    'title' => 'Web3DShare Terms',
    'url' => url('/terms'),
]));

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
    Route::get('/me', [ProfileController::class, 'show']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile', [ProfileController::class, 'update']);

    // upload and model
    Route::get('/upload', [UploadPageController::class, 'index']);
    Route::post('/models', [ModelController::class, 'store']);
    Route::patch('/models/{model}', [ModelController::class, 'update']);
    Route::delete('/models/{model}', [ModelController::class, 'destroy']);

    // interaction
    Route::post('/models/{model}/star', [InteractionController::class, 'star']);
    Route::post('/models/{model}/comment', [InteractionController::class, 'comment']);
    Route::delete('/comments/{comment}', [InteractionController::class, 'deleteComment']);

    // report
    Route::post('/models/{model}/report', [ReportController::class, 'store']);

    // verify
    Route::get('/verify', [VerifyController::class, 'index']);
    Route::post('/verify-request', [VerifyController::class, 'store']);

    Route::middleware('role:admin,moderator')->group(function () {
        Route::get('/panel', [AdminController::class, 'index']);
        Route::get('/admin/status', [AdminController::class, 'status']);

        Route::delete('/admin/delete-model/{id}', [AdminController::class, 'deleteModel']);
        Route::post('/admin/reports/{report}/reviewed', [AdminController::class, 'reviewReport']);
        Route::post('/admin/reports/{report}/resolve', [AdminController::class, 'resolveReport']);

        Route::post('/verify/{id}/approve', [VerifyController::class, 'approve']);
        Route::post('/verify/{id}/reject', [VerifyController::class, 'reject']);

        Route::post('/admin/category', [AdminController::class, 'storeCategory']);
        Route::delete('/admin/category/{id}', [AdminController::class, 'deleteCategory']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/admin/promote/{id}', [AdminController::class, 'promote']);
        Route::post('/admin/demote/{id}', [AdminController::class, 'demote']);
        Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);
    });
});
