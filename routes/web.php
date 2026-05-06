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

    // report
    Route::post('/models/{model}/report',[ReportController::class,'store']);

    // verify request (user)
    Route::get('/verify',[VerifyController::class,'index']);
    Route::post('/verify-request',[VerifyController::class,'store']);
});

Route::get('/models/{model}/download',[InteractionController::class,'download']);
/*
|--------------------------------------------------------------------------
| ADMIN + MODERATOR PANEL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function(){

    // panel utama (admin & moderator bisa akses)
    Route::get('/panel',[AdminController::class,'index']);

    // MODEL moderation
    Route::delete('/admin/delete-model/{id}',[AdminController::class,'deleteModel']);

    // VERIFY (moderator + admin)
    Route::post('/verify/{id}/approve',[VerifyController::class,'approve']);
    Route::post('/verify/{id}/reject',[VerifyController::class,'reject']);

});


/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:admin'])->group(function(){

    // user management
    Route::post('/admin/promote/{id}',[AdminController::class,'promote']);
    Route::delete('/admin/delete-user/{id}',[AdminController::class,'deleteUser']);

    // category
    Route::post('/admin/category',[AdminController::class,'storeCategory']);
    Route::delete('/admin/category/{id}',[AdminController::class,'deleteCategory']);

});