<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 投稿関連のルート
Route::apiResource('posts', PostController::class);

// いいね関連のルート
Route::post('likes', [LikeController::class, 'store']);
Route::delete('likes/{id}', [LikeController::class, 'destroy']);
Route::get('posts/{post_id}/likes', [LikeController::class, 'index']);

// コメント関連のルート
Route::apiResource('comments', CommentController::class);
Route::get('posts/{post_id}/comments', [CommentController::class, 'index']);