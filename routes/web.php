<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UploadPageController;
use App\Http\Controllers\VerifyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [ModelController::class, 'index']);
Route::get('/creators/{user:username}', [ProfileController::class, 'creator']);
Route::get('/models/{model}', [ModelController::class, 'show']);

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register');
Route::view('/terms', 'legal.terms')->name('terms');

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth-actions');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth-actions');
Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| AUTH USER
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    // upload & model
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

    // verify request (user)
    Route::get('/verify', [VerifyController::class, 'index']);
    Route::post('/verify-request', [VerifyController::class, 'store'])->middleware('throttle:verification');
});

Route::get('/models/{model}/download', [InteractionController::class, 'download'])->middleware('throttle:downloads');

/*
|--------------------------------------------------------------------------
| ADMIN + MODERATOR PANEL (Hanya Admin & Moderator yang Bisa Masuk)
|--------------------------------------------------------------------------
*/

// PERBAIKAN: Bungkus dengan auth DAN validasi role agar user biasa otomatis tertendang (403)
Route::middleware(['auth', 'role:admin,moderator'])->group(function () {

    // panel utama (hanya admin & moderator yang bisa akses via URL)
    Route::get('/panel', [AdminController::class, 'index']);
    Route::get('/admin/status', [AdminController::class, 'status']);

    // MODEL moderation
    Route::delete('/admin/delete-model/{id}', [AdminController::class, 'deleteModel'])->middleware('throttle:admin-actions');
    Route::post('/admin/reports/{report}/reviewed', [AdminController::class, 'reviewReport'])->middleware('throttle:admin-actions');
    Route::post('/admin/reports/{report}/resolve', [AdminController::class, 'resolveReport'])->middleware('throttle:admin-actions');

    // VERIFY (moderator + admin)
    Route::post('/verify/{id}/approve', [VerifyController::class, 'approve'])->middleware('throttle:admin-actions');
    Route::post('/verify/{id}/reject', [VerifyController::class, 'reject'])->middleware('throttle:admin-actions');

    // category
    Route::post('/admin/category', [AdminController::class, 'storeCategory'])->middleware('throttle:admin-actions');
    Route::delete('/admin/category/{id}', [AdminController::class, 'deleteCategory'])->middleware('throttle:admin-actions');

});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY (Khusus Admin, Moderator pun Tidak Bisa)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    // user management
    Route::post('/admin/promote/{id}', [AdminController::class, 'promote'])->middleware('throttle:admin-actions');
    Route::post('/admin/demote/{id}', [AdminController::class, 'demote'])->middleware('throttle:admin-actions');
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])->middleware('throttle:admin-actions');

});
