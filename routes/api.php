<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'products'
], function ($router) {
    Route::get('/', [ProductsController::class, 'index']);
    Route::post('/', [ProductsController::class, 'store']);
    Route::get('/{id}', [ProductsController::class, 'show']);
    Route::put('/{id}', [ProductsController::class, 'update']);
    Route::post('/image', [ProductsController::class, 'updateImage']);
    Route::delete('/{id}', [ProductsController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'cart'
], function ($router) {
    Route::post('/add', [CartsController::class, 'addToCart']);
    Route::get('/', [CartsController::class, 'getCart']);
    Route::put('/update/{id}', [CartsController::class, 'updateCart']);
    Route::delete('/remove/{id}', [CartsController::class, 'deleteCart']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'order'
], function ($router) {
    Route::post('/', [OrderController::class, 'generateSnapToken']);
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/snap', [OrderController::class, 'showBySnapToken']);
    Route::get('/snap/{id}', [OrderController::class, 'showBySnapTokenId']);
    Route::post('/store', [OrderController::class, 'store']);
});
