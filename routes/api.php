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
Route::get('/models/{model}/download', [InteractionController::class, 'download'])->middleware('throttle:downloads');
Route::get('/models/{model}', [ModelController::class, 'show']);
Route::get('/terms', fn () => response()->json([
    'title' => 'Web3DShare Terms',
    'url' => url('/terms'),
]));

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth-actions');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth-actions');


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
    Route::post('/models', [ModelController::class, 'store'])->middleware('throttle:uploads');
    Route::patch('/models/{model}', [ModelController::class, 'update']);
    Route::delete('/models/{model}', [ModelController::class, 'destroy']);

    // interaction
    Route::post('/models/{model}/star', [InteractionController::class, 'star'])->middleware('throttle:interactions');
    Route::post('/models/{model}/comment', [InteractionController::class, 'comment'])->middleware('throttle:comments');
    Route::delete('/comments/{comment}', [InteractionController::class, 'deleteComment'])->middleware('throttle:interactions');

    // report
    Route::post('/models/{model}/report', [ReportController::class, 'store'])->middleware('throttle:reports');

    // verify
    Route::get('/verify', [VerifyController::class, 'index']);
    Route::post('/verify-request', [VerifyController::class, 'store'])->middleware('throttle:verification');

    Route::middleware('role:admin,moderator')->group(function () {
        Route::get('/panel', [AdminController::class, 'index']);
        Route::get('/admin/status', [AdminController::class, 'status']);

        Route::delete('/admin/delete-model/{id}', [AdminController::class, 'deleteModel'])->middleware('throttle:admin-actions');
        Route::post('/admin/reports/{report}/reviewed', [AdminController::class, 'reviewReport'])->middleware('throttle:admin-actions');
        Route::post('/admin/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->middleware('throttle:admin-actions');

        Route::post('/verify/{id}/approve', [VerifyController::class, 'approve'])->middleware('throttle:admin-actions');
        Route::post('/verify/{id}/reject', [VerifyController::class, 'reject'])->middleware('throttle:admin-actions');

        Route::post('/admin/category', [AdminController::class, 'storeCategory'])->middleware('throttle:admin-actions');
        Route::delete('/admin/category/{id}', [AdminController::class, 'deleteCategory'])->middleware('throttle:admin-actions');
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/admin/promote/{id}', [AdminController::class, 'promote'])->middleware('throttle:admin-actions');
        Route::post('/admin/demote/{id}', [AdminController::class, 'demote'])->middleware('throttle:admin-actions');
        Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->middleware('throttle:admin-actions');
    });
});
