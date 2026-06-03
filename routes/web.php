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

Route::get('/', [ModelController::class,'index']);
Route::get('/creators/{user:username}', [ProfileController::class, 'creator']);
Route::get('/models/{model}', [ModelController::class,'show']);

Route::view('/login','auth.login')->name('login');
Route::view('/register','auth.register');

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/logout',[AuthController::class,'logout']);

/*
|--------------------------------------------------------------------------
| AUTH USER
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

    // profile
    Route::get('/profile',[ProfileController::class,'show']);
    Route::post('/profile',[ProfileController::class,'update']);

    // upload & model
    Route::get('/upload',[UploadPageController::class,'index']);
    Route::post('/models',[ModelController::class,'store']);
    Route::delete('/models/{model}',[ModelController::class,'destroy']);

    // interaction
    Route::post('/models/{model}/star',[InteractionController::class,'star']);
    Route::post('/models/{model}/comment',[InteractionController::class,'comment']);
    Route::delete('/comments/{comment}', [InteractionController::class, 'deleteComment']);

    // report
    Route::post('/models/{model}/report',[ReportController::class,'store']);

    // verify request (user)
    Route::get('/verify',[VerifyController::class,'index']);
    Route::post('/verify-request',[VerifyController::class,'store']);
});

Route::get('/models/{model}/download',[InteractionController::class,'download']);

/*
|--------------------------------------------------------------------------
| ADMIN + MODERATOR PANEL (Hanya Admin & Moderator yang Bisa Masuk)
|--------------------------------------------------------------------------
*/

// PERBAIKAN: Bungkus dengan auth DAN validasi role agar user biasa otomatis tertendang (403)
Route::middleware(['auth', 'role:admin,moderator'])->group(function(){

    // panel utama (hanya admin & moderator yang bisa akses via URL)
    Route::get('/panel', [AdminController::class, 'index']);
    Route::get('/admin/status', [AdminController::class, 'status']);

    // MODEL moderation
    Route::delete('/admin/delete-model/{id}', [AdminController::class, 'deleteModel']);
    Route::post('/admin/reports/{report}/reviewed', [AdminController::class, 'reviewReport']);
    Route::post('/admin/reports/{report}/resolve', [AdminController::class, 'resolveReport']);

    // VERIFY (moderator + admin)
    Route::post('/verify/{id}/approve', [VerifyController::class, 'approve']);
    Route::post('/verify/{id}/reject', [VerifyController::class, 'reject']);

     // category
    Route::post('/admin/category', [AdminController::class, 'storeCategory']);
    Route::delete('/admin/category/{id}', [AdminController::class, 'deleteCategory']);

});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY (Khusus Admin, Moderator pun Tidak Bisa)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function(){

    // user management
    Route::post('/admin/promote/{id}', [AdminController::class, 'promote']);
    Route::post('/admin/demote/{id}', [AdminController::class, 'demote']);
    Route::delete('/admin/delete-user/{id}', [AdminController::class, 'deleteUser']);

   

});
