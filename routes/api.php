<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;

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

// Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/who', [AuthController::class, 'whoIsLogged']);
    });
});

// Routes protected by Auth
Route::middleware('check.login')->group(function () {

    // User
    Route::prefix('users')->group(function () {
        Route::get('', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::get('/{id}/role', [UserController::class, 'role']);
        Route::get('/{id}/purchases', [UserController::class, 'purchases']);
        Route::get('/{id}/comments', [UserController::class, 'comments']);
    });
    
    // Category
    Route::prefix('categories')->group(function () {
        Route::get('', [CategoryController::class, 'index']);
    });
    
    // Product
    Route::prefix('products')->group(function () {
        Route::post('/create', [ProductController::class, 'store']);
        Route::get('', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::get('/{id}/category', [ProductController::class, 'category']);
        Route::get('/{id}/seller', [ProductController::class, 'seller']);
        Route::get('/{id}/purchases', [ProductController::class, 'purchases']);
        Route::get('/{id}/comments', [ProductController::class, 'comments']);
    });
    
    // Comment
    Route::prefix('comments')->group(function () {
        Route::get('', [CommentController::class, 'index']);
        Route::get('/{id}', [CommentController::class, 'show']);
        Route::get('/{id}/product', [CommentController::class, 'product']);
        Route::get('/{id}/user', [CommentController::class, 'user']);
    });
    
    // Purchase
    Route::prefix('purchases')->group(function () {
        Route::get('', [PurchaseController::class, 'index']);
        Route::get('/{id}', [PurchaseController::class, 'show']);
        Route::get('/{id}/product', [PurchaseController::class, 'product']);
        Route::get('/{id}/user', [PurchaseController::class, 'user']);
    });

    // Chat
    Route::prefix('chats')->group(function () {
        Route::get('', [ChatController::class, 'index']);
        Route::get('/{id}', [ChatController::class, 'show']);
        Route::post('/create', [ChatController::class, 'store']);
    });

    // Message
    Route::prefix('messages')->group(function () {
        Route::get('', [MessageController::class, 'index']);
        Route::post('', [MessageController::class, 'store']);
        Route::get('/{id}', [MessageController::class, 'show']);
    });
    
});