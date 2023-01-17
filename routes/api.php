<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// User
Route::prefix('user')->group(function () {
    Route::get('', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/{id}/role', [UserController::class, 'role']);
});

Route::prefix('product')->group(function () {
    Route::get('', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::get('/{id}/seller', [ProductController::class, 'seller']);
});

Route::prefix('comment')->group(function () {
    Route::get('', [CommentController::class, 'index']);
    Route::get('/{id}', [CommentController::class, 'show']);
    Route::get('/{id}/product', [CommentController::class, 'product']);
    Route::get('/{id}/user', [CommentController::class, 'user']);
});